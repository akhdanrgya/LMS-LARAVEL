<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'course_id',
        'enrolled_at',
        'completion_status', // 'in_progress', 'completed'
        'completed_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'enrolled_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Relasi one-to-many (inverse) ke User (Student yang melakukan enrollment).
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Relasi one-to-many (inverse) ke Course (Course yang di-enroll).
     */
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}