<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Attendance extends Model
{
    protected $fillable = [
        'user_id',
        'date',
        'course_id', // Ku dar halkan sxb
        'check_in',
        'check_out',
        'status',
    ];

    /**
     * Xiriirka u dhexeeya Attendance iyo Course
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    // KU DAR TAN HOOSTA
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id'); // haddii column‑ku yahay user_id
    }
}
