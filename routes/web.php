<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Route;

// 1. PUBLIC ROUTES
Route::get('/', function () {
    return view('welcome');
});

// 2. DASHBOARD REDIRECT (Logic-ga u kala dirista Roles-ka)
Route::get('/dashboard', function () {
    $role = auth()->user()->role;
    if ($role === 'admin') return redirect()->route('admin.dashboard');
    if ($role === 'teacher') return redirect()->route('teacher.dashboard');
    return redirect()->route('student.dashboard');
})->middleware(['auth'])->name('dashboard');


// 3. STUDENT ROUTES (Prefix: /student)
Route::prefix('student')->middleware(['auth', 'role:student'])->group(function () {
    
    // 1. Bogga Lacag-bixinta (Kani waa inuu furnaadaa si ardaygu lacagta u bixiyo)
    Route::get('/checkout', function() { return view('student.checkout'); })->name('student.checkout');
    Route::post('/pay', [StudentController::class, 'processPayment'])->name('student.pay');
    Route::get('/receipt/{transaction_id}', [StudentController::class, 'showReceipt'])->name('student.receipt');

    // 2. Routes-ka u baahan in LACAGTA LA BIXIYO (Middleware ayaa ilaalinya)
    Route::middleware(['check.payment'])->group(function () {
        Route::get('/dashboard', [StudentController::class, 'index'])->name('student.dashboard');
        Route::post('/checkin', [StudentController::class, 'checkIn'])->name('student.checkin');
        Route::get('/reports', [StudentController::class, 'reports'])->name('student.reports');
        Route::get('/reports/pdf', [StudentController::class, 'downloadPDF'])->name('student.reports.pdf');
    });
    
});


// 4. TEACHER ROUTES (Prefix: /teacher)
Route::prefix('teacher')->middleware(['auth', 'role:teacher'])->group(function () {
    Route::get('/dashboard', [TeacherController::class, 'index'])->name('teacher.dashboard');
    Route::get('/projector/{course_id}', [TeacherController::class, 'showProjector'])->name('teacher.projector');
    // Waxaad halkan ku dari kartaa: attendance logs, manual marking, etc.
});


// 5. ADMIN ROUTES (Prefix: /admin)
Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    
    // 📊 Dashboard
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    // 🎓 Maamulka Ardayda (Student Management)
    Route::get('/students', [AdminController::class, 'students'])->name('admin.students.index');
    Route::post('/students/store', [AdminController::class, 'storeTeacher'])->name('admin.students.store'); // Isku function ayaad u isticmaashay macallimiinta
    Route::get('/students/{id}', [AdminController::class, 'show'])->name('admin.students.show');
    Route::put('/students/{id}', [AdminController::class, 'updateStudent'])->name('admin.students.update'); // Update
    Route::delete('/students/{id}', [AdminController::class, 'destroyStudent'])->name('admin.students.destroy'); // Delete
    Route::get('/students/{id}/report', [AdminController::class, 'downloadStudentReport'])->name('admin.student.report');
    Route::get('/admin/students/{id}/warning', [AdminController::class, 'sendWarning'])->name('admin.students.warning');
    Route::get('/admin/students/{id}/appreciate', [AdminController::class, 'sendAppreciation'])->name('admin.students.appreciate');
    
    // 👨‍🏫 Maamulka Macallimiinta (Teacher Management)
    Route::get('/teachers', [AdminController::class, 'teachers'])->name('admin.teachers');
    Route::post('/teachers/store', [AdminController::class, 'storeTeacher'])->name('admin.teachers.store');
    Route::delete('/teachers/{id}', [AdminController::class, 'destroyTeacher'])->name('admin.teachers.destroy');
    Route::put('/teachers/{id}', [AdminController::class, 'updateTeacher'])->name('admin.teachers.update');

    // 📚 Maamulka Koorsooyinka (Course Management)
    Route::get('/courses', [AdminController::class, 'courses'])->name('admin.courses');
    Route::post('/courses/store', [AdminController::class, 'storeCourse'])->name('admin.courses.store');
    Route::delete('/courses/{id}', [AdminController::class, 'destroyCourse'])->name('admin.courses.destroy'); // Delete course

    // 💰 Maamulka Lacagaha (Payment Management)
Route::get('/payments', [AdminController::class, 'payments'])->name('admin.payments');
Route::post('/payments/approve/{id}', [AdminController::class, 'approvePayment'])->name('admin.approve.payment');

    // 📈 Warbixinnada (Reports)
    Route::get('/reports', [AdminController::class, 'reports'])->name('admin.reports');
    Route::get('/reports/export-all', [AdminController::class, 'generatePdf'])->name('admin.export.pdf');

    // ⚙️ Settings
    Route::get('/settings', [AdminController::class, 'settings'])->name('admin.settings');
    Route::post('/settings/update', [AdminController::class, 'updateSettings'])->name('admin.settings.update'); // Inaad kaydiso settings-ka
});

// 6. COMMON PROFILE ROUTES
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';