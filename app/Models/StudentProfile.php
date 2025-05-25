<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'profile_picture_path',
        'total_score',
        'level',
    ];

    /**
     * Relasi one-to-one (inverse) ke User.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}