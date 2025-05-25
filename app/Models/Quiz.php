<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'material_id', // Opsional, jika quiz terkait langsung dengan satu materi
        'title',
        'description',
        'duration_minutes',
    ];

    /**
     * Relasi one-to-many (inverse) ke Course.
     */
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    /**
     * Relasi one-to-one (inverse) ke Material (jika quiz ini adalah bagian dari materi).
     */
    public function material() // Relasi ke material tempat quiz ini terkait (jika ada)
    {
        return $this->belongsTo(Material::class, 'material_id');
    }

    /**
     * Relasi one-to-many ke Question.
     */
    public function questions()
    {
        return $this->hasMany(Question::class, 'quiz_id');
    }

    /**
     * Relasi one-to-many ke StudentQuizAttempt.
     */
    public function attempts() // atau studentAttempts
    {
        return $this->hasMany(StudentQuizAttempt::class, 'quiz_id');
    }
}