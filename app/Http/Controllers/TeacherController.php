<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Attendance;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TeacherController extends Controller
{
    /**
     * Tani waa meesha Error-ku ka jiro (Index Method)
     * Waxay soo bandhigaysaa Dashboard-ka Macallinka
     */
    public function index()
{
    $today = now()->format('l'); // Tusaale: Monday
    $courses = Course::where('teacher_id', auth()->id())
                     ->where('class_day', $today) // Kaliya kuwa maanta
                     ->get();

    // Waxaan soo qaadaynaa kaliya koorsooyinka uu leeyahay macallinka hadda Login-ka ah
    $courses = Course::where('teacher_id', auth()->id())->get();

    // Waxaan kaloo soo qaadaynaa inta arday ee maanta xaadirtay koorsooyinkiisa
    $todayAttendanceCount = Attendance::whereIn('course_id', $courses->pluck('id'))
        ->whereDate('date', now())
        ->count();

    return view('teacher.dashboard', [
    'courses' => $courses,
    'todayTotal' => $todayAttendanceCount // Halkan ayaan u beddelnay $todayTotal
]);
}

    /**
     * Bogga Projector-ka ee QR Code-ka weyn leh
     */
    public function showProjector($course_id)
{
    $course = Course::findOrFail($course_id);
    
    // 1. QR-ka geli KALIYA Magaca Maadada ama ID-ga (Si uu StudentController-ka u helo)
    $qrCode = QrCode::size(400)
        ->color(79, 70, 229)
        ->margin(2)
        ->generate($course->name); // Halkan URL-ka ka saar, geli magaca maadada

    // 2. Inta kale iska daa sidooda
    $recentAttendance = Attendance::where('course_id', $course->id)
        ->whereDate('date', now()->toDateString())
        ->with('user')
        ->latest()
        ->take(5)
        ->get();

    return view('teacher.projector', compact('qrCode', 'course', 'recentAttendance'));
}
}