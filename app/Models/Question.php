<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'question_text',
        'question_type', // 'multiple_choice', 'single_choice', 'essay'
        'points',
    ];

    protected $casts = [
        'points' => 'integer',
    ];

    /**
     * Relasi one-to-many (inverse) ke Quiz.
     */
    public function quiz()
    {
        return $this->belongsTo(Quiz::class, 'quiz_id');
    }

    /**
     * Relasi one-to-many ke AnswerOption (jika tipe soal PG/Single Choice).
     */
    public function answerOptions()
    {
        return $this->hasMany(AnswerOption::class, 'question_id');
    }

    /**
     * Relasi one-to-many ke StudentAnswer.
     */
    public function studentAnswers()
    {
        return $this->hasMany(StudentAnswer::class, 'question_id');
    }
}