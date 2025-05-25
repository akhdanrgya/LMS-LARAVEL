<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentQuizAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'quiz_id',
        'score',
        'started_at',
        'submitted_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'started_at' => 'datetime',
        'submitted_at' => 'datetime',
        'score' => 'integer',
    ];

    /**
     * Relasi one-to-many (inverse) ke User (Student yang mengerjakan quiz).
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Relasi one-to-many (inverse) ke Quiz (Quiz yang dikerjakan).
     */
    public function quiz()
    {
        return $this->belongsTo(Quiz::class, 'quiz_id');
    }

    /**
     * Relasi one-to-many ke StudentAnswer (Jawaban-jawaban untuk attempt ini).
     */
    public function answers() // atau studentAnswers
    {
        return $this->hasMany(StudentAnswer::class, 'student_quiz_attempt_id');
    }
}