<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany; // KAN GELI
class Course extends Model
{
        protected $fillable = [
            'name',
            'code',
            'teacher_id',
            'class_day',
            'start_time',
            'end_time',
            'grace_period',
            'description'
        ]; 
    /**
     * Koorso kasta waxay ka tirsan tahay hal macallin
     */
    public function teacher(): BelongsTo
    {
        // Hubi in 'teacher_id' ay ku jirto table-kaaga courses
        return $this->belongsTo(User::class, 'teacher_id');
    }
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'course_user');
    }
}