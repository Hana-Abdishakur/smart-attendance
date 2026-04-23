<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;

class AttendanceSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'amira@gmail.com')->first();

        if ($user) {
            for ($i = 1; $i <= 30; $i++) {
                // Waxaan samaynaynaa fursad (Random) ah
                $chance = rand(1, 10);

                if ($chance <= 7) {
                    $status = 'Present';
                    $check_in = '07:' . rand(30, 59) . ':00';
                } elseif ($chance <= 9) {
                    $status = 'Late';
                    $check_in = '08:' . rand(15, 45) . ':00';
                } else {
                    $status = 'Absent';
                    $check_in = null; // Maadaama uusan imaan
                }

                Attendance::create([
                    'user_id' => $user->id,
                    'date' => Carbon::now()->subDays($i)->toDateString(),
                    'check_in' => $check_in,
                    'status' => $status,
                ]);
            }
        }
    }
}
