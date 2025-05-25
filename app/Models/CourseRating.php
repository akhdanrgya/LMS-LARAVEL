<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseRating extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'course_id',
        'rating', // 1-5 bintang
        'review_text',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'rating' => 'integer',
    ];

    /**
     * Relasi one-to-many (inverse) ke User (Student yang memberi rating).
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Relasi one-to-many (inverse) ke Course (Course yang diberi rating).
     */
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}