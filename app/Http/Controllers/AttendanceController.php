<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Course; // Lagu daray
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    protected string $tz = 'Africa/Mogadishu';

    /**
     * Dashboard-ka ardayga: Maado kasta iyo xogteeda
     */
    public function studentDashboard()
    {
        $user = Auth::user();
        $now = Carbon::now($this->tz);
        $today = $now->toDateString();

        // 1. Soo qaado dhamaan koorsooyinka ardaygu dhigto (Enrollments)
        // Haddii aadan weli haysan Enrollment model, waxaan isticmaalaynaa Course::all() tijaabo ahaan
        $courses = Course::all(); 

        // 2. Attendance-ka maanta ee maado kasta
        $todayAttendances = Attendance::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->get()
            ->keyBy('course_id'); // Waxay noo sahlaysaa inaan Blade-ka ku xirno ID-ga maadada

        // 3. Stats-ka guud ee bishan
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth   = $now->copy()->endOfMonth();

        $presentDaysCount = Attendance::where('user_id', $user->id)
            ->whereBetween('date', [$startOfMonth->toDateString(), $endOfMonth->toDateString()])
            ->whereIn('status', ['Present', 'Late'])
            ->count();

        $totalDaysSoFar = $now->day; 
        $percentage = $totalDaysSoFar > 0 ? round(($presentDaysCount / $totalDaysSoFar) * 100) : 0;

        // 5-ta record ee u dambeeyay (oo wata magaca maadada)
        $recentAttendances = Attendance::with('course')->where('user_id', $user->id)
            ->orderBy('date', 'desc')
            ->orderBy('check_in', 'desc')
            ->take(5)
            ->get();

        return view('student.dashboard', compact('user', 'courses', 'todayAttendances', 'presentDaysCount', 'percentage', 'recentAttendances', 'now'));
    }

    /**
     * Check-in (Imaanshaha - QR Scan version)
     */
    public function checkIn(Request $request)
    {
        $user = Auth::user();
        $now = Carbon::now($this->tz);
        $today = $now->toDateString();
        
        // MUHIIM: QR Code-ka waxaa ku dhex jira course_id
        $course_id = $request->course_id; 

        if (!$course_id) {
            return response()->json(['success' => false, 'message' => 'Maadada lama aqoonsan!']);
        }

        // 1. Hubi in uusan horay u xaadirin MAADADAN GAARKA AH maanta
        $alreadyExists = Attendance::where('user_id', $user->id)
            ->where('course_id', $course_id) // Halkan ayaa lagu xalliyay wreerkii
            ->whereDate('date', $today)
            ->first();

        if ($alreadyExists) {
            return response()->json(['success' => false, 'message' => 'Horay ayaad u xaadirtay maadadan maanta!']);
        }

        // 2. Threshold: 8:15 subaxnimo (Waqtiga waa la beddeli karaa)
        $threshold = Carbon::createFromTime(8, 15, 0, $this->tz);
        $status = $now->greaterThanOrEqualTo($threshold) ? 'Late' : 'Present';

        // 3. Create Attendance
        Attendance::create([
            'user_id'   => $user->id,
            'course_id' => $course_id,
            'date'      => $today,
            'check_in'  => $now->toTimeString(),
            'status'    => $status,
        ]);

        return response()->json(['success' => true, 'message' => "Waad ku mahadsantahay, status-kaagu waa: $status"]);
    }



    /**
     * Check-out (Bixitaanka)
     */
    public function checkOut(Request $request)
    {
        $user = Auth::user();

        $today = Carbon::today($this->tz)->toDateString();

        $attendance = Attendance::where('user_id', $user->id)
            ->where('user_id', $user->id)
            ->whereDate('date', $today)
            ->whereNull('check_out')
            ->first();

        if (!$attendance) {
            return back()->with('error', 'Ma jiro Check-in furan oo aad hadda ka bixi karto.');
        }

        $attendance->update([
            'check_out' => Carbon::now($this->tz)->toTimeString(),
        ]);

        return back()->with('success', 'Waad ku mahadsantahay, si nabad ah u bax!');
    }
}
