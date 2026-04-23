<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendanceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function student_can_check_in_once_per_day()
    {
        Carbon::setTestNow(Carbon::create(2026, 3, 6, 7, 30));

        $student = User::factory()->create(['role' => 'student']);

        $this->actingAs($student)
            ->post(route('student.checkin'))
            ->assertRedirect()
            ->assertSessionHas('success', 'Waad ku mahadsantahay Check-in-ka. Status-kaagu waa: Present');

        $this->assertDatabaseHas('attendances', [
            'user_id' => $student->id,
            'date' => Carbon::today()->toDateString(),
            'status' => 'Present',
        ]);

        // second attempt should fail with error message
        $this->actingAs($student)
            ->post(route('student.checkin'))
            ->assertRedirect()
            ->assertSessionHas('error', 'Horay ayaad Check-in u samashay maanta!');
    }

    /** @test */
    public function late_check_in_records_late_status()
    {
        Carbon::setTestNow(Carbon::create(2026, 3, 6, 9, 0));

        $student = User::factory()->create(['role' => 'student']);

        $this->actingAs($student)
            ->post(route('student.checkin'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('attendances', [
            'user_id' => $student->id,
            'status' => 'Late',
        ]);
    }

    /** @test */
    public function admin_can_view_dashboard_but_student_cannot()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $student = User::factory()->create(['role' => 'student']);

        $this->actingAs($admin)
            ->get('/admin/dashboard')
            ->assertStatus(200);

        $this->actingAs($student)
            ->get('/admin/dashboard')
            ->assertStatus(403); // assuming role middleware returns 403
    }
}
