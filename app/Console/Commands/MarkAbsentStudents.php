<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Course;
use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;

class MarkAbsentStudents extends Command
{
    protected $signature = 'app:mark-absent-students';
    protected $description = 'Wuxuu Absent u qoraa ardayda aan is xaadirin markay xiisadu dhamaato';

    public function handle()
    {
        // 1. Taariikhaha la eegayo (Maanta, Shalay, iyo Doraad)
        // Tan waxay caawinaysaa haddii shalay nidaamku damnaa inuu hadda soo qabto
        $datesToCheck = [
            now()->toDateString(),            // Maanta
            now()->subDay()->toDateString(),  // Shalay
        ];

        $nowTime = now()->toTimeString();

        foreach ($datesToCheck as $date) {
            $dayName = Carbon::parse($date)->format('l');
            
            // 2. Soo qaado koorsooyinka maalintaas la dhigto
            // Haddii ay tahay taariikhdii shalay, waqtiga (end_time) ma eegayno waayo horay ayay u dhammaatay
            $query = Course::where('class_day', $dayName);
            
            if ($date == now()->toDateString()) {
                $query->where('end_time', '<=', $nowTime);
            }

            $finishedCourses = $query->get();

            if ($finishedCourses->isEmpty()) {
                $this->info("Ma jiraan koorsooyin dhammaaday oo la dhigto: $dayName ($date)");
                continue;
            }

            foreach ($finishedCourses as $course) {
                // 3. Hel ardayda maaddadan u diiwaangashan
                // Hubi in Course model-ka uu leeyahay 'users' relationship
                $students = $course->users; 

                if ($students->isEmpty()) {
                    continue;
                }

                foreach ($students as $student) {
                    
                    // 4. Hubi haddii ardaygu uu horey u lahaa record (Present/Late/Absent)
                    $exists = Attendance::where('user_id', $student->id)
                        ->where('course_id', $course->id)
                        ->where('date', $date)
                        ->exists();

                    // 5. Haddii uusan record lahayn, hadda u qor "Absent"
                    if (!$exists) {
                        Attendance::create([
                            'user_id'   => $student->id,
                            'course_id' => $course->id,
                            'date'      => $date,
                            'status'    => 'Absent',
                            'check_in'  => null,
                        ]);
                        $this->info("Absent: {$student->name} - {$course->name} ($date)");
                    }
                }
            }
        }

        $this->info('Nidaamka Auto-Absent waa la dhammaystiryay!');
    }
}