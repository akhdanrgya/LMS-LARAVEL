<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_quiz_attempt_id',
        'question_id',
        'answer_option_id', // Nullable, diisi jika jawaban berupa pilihan ganda/single
        'answer_text',      // Nullable, diisi jika jawaban berupa essay
        'is_correct',       // Nullable, diisi setelah penilaian (terutama essay)
        'points_awarded',   // Nullable, poin yang didapat untuk jawaban ini
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_correct' => 'boolean',
        'points_awarded' => 'integer',
    ];

    /**
     * Relasi one-to-many (inverse) ke StudentQuizAttempt.
     */
    public function attempt() // atau studentQuizAttempt
    {
        return $this->belongsTo(StudentQuizAttempt::class, 'student_quiz_attempt_id');
    }

    /**
     * Relasi one-to-many (inverse) ke Question.
     */
    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }

    /**
     * Relasi one-to-many (inverse) ke AnswerOption (Pilihan jawaban yang dipilih student, jika ada).
     */
    public function chosenOption() // atau answerOption
    {
        return $this->belongsTo(AnswerOption::class, 'answer_option_id');
    }
}