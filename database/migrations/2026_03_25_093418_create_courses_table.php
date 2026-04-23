<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('name'); 
            $table->string('code')->unique();
            
            // 1. Ku dar Teacher-ka (Foreign Key)
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
            
            // 2. Ku dar Jadwalka (Schedule)
            $table->string('class_day')->nullable(); // Monday, Tuesday, etc.
            $table->time('start_time'); // Tusaale: 08:30:00
            $table->time('end_time');   // Tusaale: 10:30:00 (Kani waa kan Auto-Absent u baahan yahay)
            
            // 3. Grace Period (Daqiiqadaha loo oggol yahay ka dib Present)
            $table->integer('grace_period')->default(15);
            
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};