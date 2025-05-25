<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'title',
        'content_type',
        'content',
        'order_sequence', // sebelumnya 'order' di migrasi, saya ganti jadi 'order_sequence' biar lebih jelas & gak bentrok keyword SQL
    ];

    /**
     * Relasi one-to-many (inverse) ke Course.
     */
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    /**
     * Jika sebuah materi bisa langsung berupa quiz (Quiz punya material_id).
     * Relasi one-to-one ke Quiz.
     */
    public function linkedQuiz() // Nama bisa disesuaikan
    {
        return $this->hasOne(Quiz::class, 'material_id');
    }
}