<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Mail\PaymentConfirmed;
use App\Mail\NewPaymentNotification;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Models\Attendance;
use App\Models\Course;
use Carbon\Carbon;
use Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class StudentController extends Controller
{
    // Define the timezone to ensure accurate time recording
    protected $tz = 'Africa/Mogadishu'; 

    public function index()
    {
        $user = auth()->user();
        $startOfMonth = now($this->tz)->startOfMonth();

        // 1. Fetch Today's Courses
        $courses = Course::where('class_day', now($this->tz)->format('l'))
            ->orderBy('start_time', 'asc')
            ->get();

        // 2. Fetch Weekly Schedule
        $weeklySchedule = Course::orderByRaw("FIELD(class_day, 'Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday')")
            ->orderBy('start_time', 'asc')
            ->get();

        // 3. Fetch Today's Attendance Status
        $todayAttendances = Attendance::where('user_id', $user->id)
            ->whereDate('date', now($this->tz)->toDateString())
            ->get()
            ->keyBy('course_id');

        // --- ATTENDANCE STATISTICS LOGIC ---
        $totalPossible = Attendance::where('user_id', $user->id)
            ->where('date', '>=', $startOfMonth)
            ->count();

        $monthlyCount = Attendance::where('user_id', $user->id)
            ->where('date', '>=', $startOfMonth)
            ->whereIn('status', ['Present', 'Late'])
            ->count();

        $attendanceRate = ($totalPossible > 0) ? round(($monthlyCount / $totalPossible) * 100) : 0;

        $attendances = Attendance::where('user_id', $user->id)
            ->with('course')
            ->latest('date')
            ->take(5)
            ->get();

        return view('student.dashboard', compact(
            'courses', 'weeklySchedule', 'todayAttendances', 
            'attendances', 'monthlyCount', 'attendanceRate'
        ));
    }

    public function checkIn(Request $request)
    {
        $user = Auth::user();
        $now = Carbon::now($this->tz); 
        $today = $now->toDateString();
        
        // 1. Retrieve QR Code Data
        $qr_data = $request->qr_token;

        if (!$qr_data) {
            return response()->json(['success' => false, 'message' => 'No QR Code data found!']);
        }

        // --- REQUIREMENT #9: PAYMENT VERIFICATION ---
        // Check if the student has a 'completed' payment record
        $hasPaid = \DB::table('payments')->where('user_id', $user->id)
                       ->where('status', 'completed')
                       ->exists();

        if (!$hasPaid) {
            return response()->json([
                'success' => false, 
                'message' => 'Please clear your registration fees before marking attendance.'
            ]);
        }

        // 2. Find Course by ID or Name
        $course = Course::where('id', $qr_data)->orWhere('name', 'LIKE', '%' . $qr_data . '%')->first();

        if (!$course) {
            return response()->json(['success' => false, 'message' => 'This course is not found in the system.']);
        }

        // 3. Check for Duplicate Attendance
        $alreadyExists = Attendance::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->whereDate('date', $today)
            ->first();

        if ($alreadyExists) {
            return response()->json(['success' => false, 'message' => 'You have already checked into this course today.']);
        }

        // 4. Smart Late Logic Calculation
        $startTimeStr = $course->start_time ?? $now->format('H:i:s');
        $startTime = Carbon::parse($startTimeStr);
        $lateThreshold = $startTime->copy()->addMinutes($course->grace_period ?? 15);
        $status = $now->format('H:i:s') > $lateThreshold->format('H:i:s') ? 'Late' : 'Present';

        // 5. Store Attendance Record
        $attendance = Attendance::create([
            'user_id'   => $user->id,
            'course_id' => $course->id,
            'date'      => $today,
            'check_in'  => $now->toTimeString(),
            'status'    => $status,
        ]);

        // --- REQUIREMENT #7: EMAIL CONFIRMATION SERVICE ---
        try {
            \Mail::to($user->email)->send(new \App\Mail\AttendanceSuccess($course, $status));
        } catch (\Exception $e) {
            // Log error if mail server fails but maintain successful check-in
            \Log::error("Email service error: " . $e->getMessage());
        }

        return response()->json([
            'success' => true, 
            'message' => "Successfully checked in to " . $course->name . " ($status). A confirmation email has been sent."
        ]);
    }

   public function processPayment(Request $request)
{
    $request->validate([
        'payment_method' => 'required',
        'transaction_id' => 'required_if:payment_method,mobile_money',
    ]);

    $user = auth()->user();
    $isMobile = $request->payment_method == 'mobile_money';
    $finalAmount = $isMobile ? 190000 : 50;
    $currency = $isMobile ? 'UGX' : 'USD';
    $t_id = $request->transaction_id ?? 'CARD-REF-' . strtoupper(Str::random(8));

    // 1. Keydi xogta (Status-ka ka dhig 'pending' si Admin-ku u hubiyo)
    $paymentId = \DB::table('payments')->insertGetId([
        'user_id' => $user->id,
        'phone_number' => $request->phone_number,
        'amount' => $finalAmount,
        'currency' => $currency, 
        'payment_method' => $request->payment_method,
        'transaction_id' => $t_id,
        'status' => 'pending', // Waa inuu Pending ahaado marka hore
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $paymentData = \DB::table('payments')->where('id', $paymentId)->first();

    // 2. Diyaarinta PDF-ka (Wixii loo diri lahaa Admin-ka)
    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.pdf_payment_receipt', [
        'paymentData' => $paymentData,
        'student' => $user
    ])->output();

    // 3. Email-ka Admin-ka (Isagoo PDF-ka wada)
    $adminEmail = 'hayatinashakur@gmail.com'; 
    
    try {
        // Admin-ka u dir Email-ka rasiidka wada
        \Illuminate\Support\Facades\Mail::to($adminEmail)->send(new \App\Mail\NewPaymentNotification($paymentData, $pdf));
        
        // Ardayga u dir Email-ka xaqiijinta (Confirmation)
        \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\PaymentConfirmed($user->name, $t_id));
    } catch (\Exception $e) {
        \Log::error("Mail Error: " . $e->getMessage());
    }

    // 4. Redirect to Dashboard
    return redirect()->route('student.dashboard')->with('success', 'Lacagta waa la gudbiyey! Fadlan sug inta Admin-ku ka xaqiijinayo.');
}
    public function showReceipt($transaction_id)
{
    $payment = DB::table('payments')
        ->where('transaction_id', $transaction_id)
        ->where('user_id', auth()->id())
        ->first();

    if (!$payment) {
        return redirect()->route('student.dashboard')->with('error', 'Receipt-ka lama helin.');
    }

    return view('student.receipt', compact('payment'));
}
    public function reports(Request $request)
    {
        $query = Attendance::where('user_id', auth()->id())->with('course');

        // Apply Date Range Filter
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }

        $allAttendances = $query->orderBy('date', 'desc')->paginate(10);
        return view('student.reports', compact('allAttendances'));
    }

    public function downloadPDF(Request $request)
    {
        $query = Attendance::where('user_id', auth()->id())->with('course');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }

        $attendances = $query->latest('date')->get();
        
        // Generate PDF Report (Requirement #8)
        $pdf = Pdf::loadView('student.pdf_report', compact('attendances'));
        
        return $pdf->download('Attendance_Report_' . now($this->tz)->format('Y-m-d') . '.pdf');
    }
}