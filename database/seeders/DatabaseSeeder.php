<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Attendance;
use App\Models\Course;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin123'),
            'role' => 'admin',
        ]);

        // 2. SAMEE 5 MACALLIN
        $teachersData = [
            ['name' => 'Prof. Mohamed Ali', 'email' => 'mohamed@gmail.com'],
            ['name' => 'Dr. Fatuma Abdi', 'email' => 'fatuma@gmail.com'],
            ['name' => 'Eng. Hassan Barre', 'email' => 'hassan@gmail.com'],
            ['name' => 'Ms. Aisha Omar', 'email' => 'aisha@gmail.com'],
            ['name' => 'Mr. Liban Yusuf', 'email' => 'liban@gmail.com'],
        ];

        $teachers = [];
        foreach ($teachersData as $t) {
            $teachers[] = User::create([
                'name' => $t['name'],
                'email' => $t['email'],
                'password' => bcrypt('teacher123'),
                'role' => 'teacher',
            ]);
        }

        // 3. SAMEE 5 KOORSO
        $courses = [
            ['name' => 'Advanced PHP Laravel', 'code' => 'CS301', 'teacher_id' => $teachers[0]->id, 'class_day' => 'Monday', 'start_time' => '08:30:00', 'end_time' => '10:30:00', 'grace_period' => 15],
            ['name' => 'Java Programming', 'code' => 'CS202', 'teacher_id' => $teachers[1]->id, 'class_day' => 'Tuesday', 'start_time' => '19:30:00', 'end_time' => '21:30:00', 'grace_period' => 15],
            ['name' => 'Database Systems', 'code' => 'CS105', 'teacher_id' => $teachers[2]->id, 'class_day' => 'Wednesday', 'start_time' => '13:00:00', 'end_time' => '15:00:00', 'grace_period' => 15],
            ['name' => 'Computer Networking', 'code' => 'CS404', 'teacher_id' => $teachers[3]->id, 'class_day' => 'Thursday', 'start_time' => '09:00:00', 'end_time' => '11:00:00', 'grace_period' => 15],
            ['name' => 'Artificial Intelligence', 'code' => 'CS501', 'teacher_id' => $teachers[4]->id, 'class_day' => 'Friday', 'start_time' => '11:00:00', 'end_time' => '13:00:00', 'grace_period' => 15],
        ];

        foreach ($courses as $c) {
            Course::create($c);
        }

        // 4. Samee Amira (Student)
        $amira = User::create([
            'name' => 'Amira Abdi',
            'email' => 'amira@example.com',
            'password' => bcrypt('password'),
            'role' => 'student',
        ]);

        // 5. Samee 20 Arday oo kale
        User::factory(20)->create(['role' => 'student']);

        // 6. Enrollment
        $allCourses = Course::all();
        $allStudents = User::where('role', 'student')->get();

        foreach ($allStudents as $student) {
            foreach ($allCourses as $course) {
                DB::table('enrollments')->insert([
                    'user_id' => $student->id,
                    'course_id' => $course->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // 7. Attendance-ka (HAGAAJINTA DHAMMAAN ARDAYDA)
        foreach ($allCourses as $course) {
        // Waxaan u samaynaynaa xog 30-kii maalmood ee u dambeeyay
        for ($i = 0; $i < 30; $i++) {
        $date = now()->subDays($i);

        // Kaliya qor attendance haddii ay tahay maalinta maadadaas la dhigto
        if ($date->format('l') !== $course->class_day) {
            continue; 
        }

        // Halkan waxaan ku dhex wareegaynaa dhammaan ardayda (Amira + 20-ka kale)
        foreach ($allStudents as $student) {
            $chance = rand(1, 100);

            if ($chance <= 75) { 
                $status = 'Present';
                $checkIn = $course->start_time; 
            } elseif ($chance <= 90) { 
                $status = 'Late';
                // Random daqiiqo u dhexaysa 16 ilaa 40 (si uu Late u noqdo)
                $lateMinutes = rand(16, 40);
                $checkIn = Carbon::parse($course->start_time)->addMinutes($lateMinutes)->toTimeString();
            } else { 
                $status = 'Absent';
                $checkIn = null;
            }

            Attendance::create([
                'user_id' => $student->id,
                'course_id' => $course->id,
                'date' => $date->toDateString(),
                'check_in' => $checkIn,
                'status' => $status,
                'created_at' => $date, // Muhiim si Weekly Trend-ku u shaqeeyo
            ]);
        }
      }
        }
    }
}