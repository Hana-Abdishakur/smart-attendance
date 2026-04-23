<?php

namespace App\Http\Controllers;

use App\Mail\AdminNotification;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\Attendance;
use App\Models\Course;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Display the General Admin Dashboard with Analytics.
     */
    public function index()
    {
        $today = now()->toDateString();
        $dayName = now()->format('l'); 

        // --- TIER 1: SYSTEM OVERVIEW (TOTAL COUNTS) ---
        $totalStudents = User::where('role', 'student')->count();
        $totalTeachers = User::where('role', 'teacher')->count();
        $totalCourses  = Course::count();

        // --- TIER 1.5: FINANCE & REVENUE ANALYTICS ---
// Wadarta lacagta la xaqiijiyey (Completed)
$totalRevenue = DB::table('payments')->where('status', 'completed')->sum('amount');

// Inta lacag ee sugeysa in la Approve-gareeyo
$pendingPayments = DB::table('payments')->where('status', 'pending')->count();

// Inta arday ee bixisay lacagta (Unique students)
$paidStudentsCount = DB::table('payments')->where('status', 'completed')->distinct('user_id')->count();

        // --- TIER 2: TODAY'S LIVE ATTENDANCE STATUS ---
        $todayAttendances = Attendance::whereDate('date', $today)->get();
        
        $todayPresent = $todayAttendances->whereIn('status', ['Present', 'Late'])->count();
        $todayLate    = $todayAttendances->where('status', 'Late')->count();

        // Logic to calculate expected absentees based on total students
        $hasCoursesToday = Course::where('class_day', $dayName)->exists();
        $todayAbsent = (!$hasCoursesToday) ? 0 : max(0, $totalStudents - $todayPresent);

        // --- TIER 3: TODAY'S CLASS SCHEDULE ---
        $todayClasses = Course::where('class_day', $dayName)
            ->with('teacher')
            ->orderBy('start_time')
            ->get();

        // --- TIER 4: MONTHLY ATTENDANCE HEALTH RATE (%) ---
        $monthlyStats = Attendance::whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->selectRaw("COUNT(*) as total, SUM(CASE WHEN status IN ('Present', 'Late') THEN 1 ELSE 0 END) as attended")
            ->first();

        $avgRate = ($monthlyStats->total > 0) ? ($monthlyStats->attended / $monthlyStats->total) * 100 : 0;

        // --- TIER 5: WEEKLY ATTENDANCE TREND (LINE CHART DATA) ---
        $weeklyData = [];
        $days = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $days[] = $date->format('D'); 
            $weeklyData[] = Attendance::whereDate('date', $date->toDateString())
                ->whereIn('status', ['Present', 'Late'])
                ->count();
        }

        // --- TIER 6: LIVE RECENT ATTENDANCE FEED ---
        $recentAttendances = Attendance::with(['user', 'course'])
            ->latest()
            ->take(10)
            ->get();

        // --- TIER 7: LOW ATTENDANCE ALERTS (AT-RISK STUDENTS) ---
        $lowAttendanceStudents = User::where('role', 'student')
            ->withCount(['attendances as absent_count' => function($query) {
                $query->whereMonth('date', now()->month)
                      ->where('status', 'Absent');
            }])
            ->get()
            ->filter(fn($student) => $student->absent_count > 5)
            ->map(function($student) {
                $stats = Attendance::whereMonth('date', now()->month)
                    ->where('user_id', $student->id)
                    ->selectRaw("COUNT(*) as total, SUM(CASE WHEN status IN ('Present', 'Late') THEN 1 ELSE 0 END) as attended")
                    ->first();
                
                $student->attendance_rate = ($stats->total > 0) ? round(($stats->attended / $stats->total) * 100, 1) : 0;
                return $student;
            })
            ->sortBy('attendance_rate')
            ->take(5);

        return view('admin.dashboard', compact(
            'totalStudents', 'totalTeachers', 'totalCourses', 
            'todayPresent', 'todayLate', 'todayAbsent',     
            'avgRate', 'recentAttendances', 'days', 'weeklyData', 
            'lowAttendanceStudents', 'todayClasses',
            'totalRevenue', 'pendingPayments', 'paidStudentsCount'
        ));
    }

    /**
     * Manage and Search Students.
     */
    public function students(Request $request) 
    {
        $search = $request->get('search');
        $students = User::where('role', 'student')
            ->with('attendances') 
            ->when($search, function ($query) use ($search) {
                return $query->where('name', 'LIKE', "%{$search}%")
                             ->orWhere('email', 'LIKE', "%{$search}%");
            })
            ->latest()
            ->paginate(10);

        return view('admin.students.index', compact('students'));
    }

    /**
     * Show detailed profile and history of a specific student.
     */
    public function show($id)
    {
        $student = User::where('role', 'student')->findOrFail($id);
        $attendances = Attendance::where('user_id', $id)->with('course')->orderBy('date', 'desc')->get();
        return view('admin.students.show', compact('student', 'attendances'));
    }

    /**
     * Remove a student from the system.
     */
    public function destroyStudent($id)
    {
        $student = User::where('role', 'student')->findOrFail($id);
        $student->delete();
        return redirect()->back()->with('success', 'Student record deleted successfully.');
    }

    /**
     * Notification Services: Send targeted emails to students.
     */
    public function sendEmailToStudent($id)
    {
        $user = User::findOrFail($id);
        $details = [
            'title' => 'Message from SmartAttend Admin',
            'body' => 'Please note that your attendance for this month is below the threshold.'
        ];

        Mail::to($user->email)->send(new \App\Mail\AdminNotification($details));
        return back()->with('success', 'Email successfully sent to ' . $user->name);
    }   

    public function sendAppreciation($id) {
        $student = User::findOrFail($id);
        $details = [
            'name' => $student->name,
            'subject' => 'Congratulations: Excellent Attendance Record!',
            'message' => 'We noticed your attendance rate is outstanding. Keep up the great work!'
        ];

        Mail::to($student->email)->send(new \App\Mail\AdminNotification($details));
        return back()->with('success', 'Appreciation email sent to ' . $student->name);
    }

    public function sendWarning($id) {
        $student = User::findOrFail($id);
        $details = [
            'name' => $student->name,
            'message' => 'Your attendance is critically low. Please visit the administration office immediately.'
        ];

        Mail::to($student->email)->send(new \App\Mail\AdminNotification($details));
        return back()->with('success', 'Warning email sent to ' . $student->name);
    }

    /**
     * Teacher Management (CRUD Operations).
     */
    public function teachers()
    {
        $teachers = User::where('role', 'teacher')
            ->withCount('courses') 
            ->latest()
            ->paginate(10);

        return view('admin.teachers.index', compact('teachers'));
    }

    public function editTeacher($id)
    {
        $teacher = User::where('role', 'teacher')->findOrFail($id);
        return response()->json($teacher); // Used for AJAX Modal population
    }

    public function storeTeacher(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Secure hashing
            'role' => 'teacher',
        ]);

        return redirect()->back()->with('success', 'New teacher registered successfully.');
    }

    public function updateTeacher(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|min:6',
        ]);

        $teacher = User::where('role', 'teacher')->findOrFail($id);
        $teacher->name = $request->name;
        $teacher->email = $request->email;
        
        if ($request->filled('password')) {
            $teacher->password = Hash::make($request->password);
        }

        $teacher->save();
        return redirect()->back()->with('success', 'Teacher records updated successfully.');
    }

    public function destroyTeacher($id)
    {
        $teacher = User::where('role', 'teacher')->findOrFail($id);
        $teacher->delete();
        return redirect()->back()->with('success', 'Teacher deleted successfully.');
    }

    /**
     * Course Management.
     */
    public function courses()
    {
        $courses = Course::with('teacher')->get();
        $teachers = User::where('role', 'teacher')->get(); 
        return view('admin.courses.index', compact('courses', 'teachers'));
    }

    public function storeCourse(Request $request)
    {
        $request->validate([
            'course_name' => 'required',
            'course_code' => 'required|unique:courses',
            'teacher_id' => 'required|exists:users,id',
            'class_day' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        Course::create($request->all());
        return redirect()->back()->with('success', 'New course created successfully.');
    }

    /**
 * Liiska dhamaan lacagaha soo dhacay
 */
public function payments(Request $request)
{
    $search = $request->get('search');

    $payments = DB::table('payments')
        ->join('users', 'payments.user_id', '=', 'users.id')
        ->select('payments.*', 'users.name as student_name', 'users.email as student_email')
        ->when($search, function ($query) use ($search) {
            return $query->where('users.name', 'LIKE', "%{$search}%")
                         ->orWhere('payments.transaction_id', 'LIKE', "%{$search}%");
        })
        ->orderBy('payments.created_at', 'desc')
        ->paginate(15);

    return view('admin.payments.index', compact('payments'));
}

/**
 * Ansixinta lacagta ardayga
 */
public function approvePayment($id)
{
    DB::table('payments')->where('id', $id)->update([
        'status' => 'completed',
        'updated_at' => now()
    ]);

    return back()->with('success', 'Lacagta ardayga si guul leh ayaad u xaqiijisay!');
}

    /**
     *Returns dynamic view for Admin Analytics Dashboard
     */
    public function reports(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $students = User::where('role', 'student')->with(['attendances' => function($query) use ($month, $year) {
            $query->whereMonth('date', $month)->whereYear('date', $year);
        }])->get();

        $reportData = $students->map(function($student) {
            return [
                'name' => $student->name,
                'email' => $student->email,
                'present' => $student->attendances->where('status', 'Present')->count(),
                'late' => $student->attendances->where('status', 'Late')->count(),
                'absent' => $student->attendances->where('status', 'Absent')->count(),
            ];
        });

        $totalPresent = $reportData->sum('present');
        $totalLate = $reportData->sum('late');
        $totalAbsent = $reportData->sum('absent');
        $totalRecords = $totalPresent + $totalLate + $totalAbsent;
        $avgRate = $totalRecords > 0 ? round((($totalPresent + $totalLate) / $totalRecords) * 100, 1) : 0;

        return view('admin.reports', compact('reportData', 'avgRate', 'totalPresent', 'totalLate', 'totalAbsent'));
    }

    /**
     * Generate General PDF Report.
     */
    public function generatePdf(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $students = User::where('role', 'student')->with(['attendances' => function($query) use ($month, $year) {
            $query->whereMonth('date', $month)->whereYear('date', $year);
        }])->get();

        $reportData = $students->map(function($student) {
            return [
                'name' => $student->name,
                'email' => $student->email,
                'present' => $student->attendances->where('status', 'Present')->count(),
                'late' => $student->attendances->where('status', 'Late')->count(),
                'absent' => $student->attendances->where('status', 'Absent')->count(),
            ];
        });

        $totalPresent = $reportData->sum('present');
        $totalLate = $reportData->sum('late');
        $totalAbsent = $reportData->sum('absent');
        $totalRecords = $totalPresent + $totalLate + $totalAbsent;
        $avgRate = $totalRecords > 0 ? round((($totalPresent + $totalLate) / $totalRecords) * 100, 1) : 0;

        $pdf = Pdf::loadView('admin.pdf_general', compact(
            'reportData', 'avgRate', 'totalPresent', 'totalLate', 'totalAbsent'
        ));

        return $pdf->download("General_Attendance_Report.pdf");
    }

    /**
     * Export Individual Student Report with QR Code Verification.
     */
    public function downloadStudentReport($id)
    {
        $student = User::where('role', 'student')->with('attendances.course')->findOrFail($id);
        
        // QR Code Generation Logic for Verification
        $ngrokBaseUrl = "https://9473-41-221-81-38.ngrok-free.app";
        $path = route('admin.students.show', $student->id, false);
        $fullUrl = $ngrokBaseUrl . $path;
        $qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . urlencode($fullUrl);

        $present = $student->attendances->where('status', 'Present')->count();
        $late = $student->attendances->where('status', 'Late')->count();
        $absent = $student->attendances->where('status', 'Absent')->count();
        $total = $student->attendances->count();
        $performance = $total > 0 ? round((($present + $late) / $total) * 100, 1) : 0;

        $attendances = $student->attendances()->orderBy('date', 'desc')->get();

        $pdf = Pdf::loadView('admin.pdf_template', compact(
            'student', 'present', 'late', 'absent', 'performance', 'attendances', 'qrCodeUrl'
        ));
        
        return $pdf->download("Official_Report_{$student->name}.pdf");
    }

    /**
     * System Settings and Configurations.
     */
    public function settings()
    {
        return view('admin.settings');
    }
}