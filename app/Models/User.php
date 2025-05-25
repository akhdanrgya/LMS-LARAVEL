<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail; // Jika pakai verifikasi email
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // Jika pakai Sanctum buat API

class User extends Authenticatable // implements MustVerifyEmail (jika perlu)
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Pastikan 'role' ada di sini biar bisa diisi massal
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed', // Otomatis hash pas di-set (Laravel 9+)
                                // Kalo Laravel lama, hashing di controller/mutator
        // 'role' => 'string', // Enum udah dihandle di DB, di sini bisa string
    ];

    // RELASI-RELASI PENTING UNTUK USER:

    /**
     * Relasi one-to-one ke StudentProfile (jika user adalah student).
     */
    public function studentProfile()
    {
        return $this->hasOne(StudentProfile::class, 'student_id'); // foreign key di StudentProfile adalah student_id
    }

    /**
     * Relasi one-to-one ke MentorProfile (jika user adalah mentor).
     */
    public function mentorProfile()
    {
        return $this->hasOne(MentorProfile::class, 'user_id'); // foreign key di MentorProfile adalah user_id
    }

    /**
     * Relasi one-to-many ke Course (jika user adalah mentor, ini course yang diajar).
     */
    public function taughtCourses() // Nama method bisa courses() aja, tapi taughtCourses lebih jelas
    {
        return $this->hasMany(Course::class, 'mentor_id');
    }

    /**
     * Relasi one-to-many ke Enrollment (jika user adalah student).
     */
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'student_id');
    }

    /**
     * Relasi many-to-many ke Course melalui Enrollment (course yang diikuti student).
     */
    public function enrolledCourses()
    {
        return $this->belongsToMany(Course::class, 'enrollments', 'student_id', 'course_id')
                    ->withTimestamps() // jika mau ambil created_at/updated_at dari pivot table (enrollments)
                    ->withPivot('completion_status', 'completed_at'); // ambil kolom tambahan dari pivot table
    }

    /**
     * Relasi one-to-many ke StudentQuizAttempt (jika user adalah student).
     */
    public function quizAttempts()
    {
        return $this->hasMany(StudentQuizAttempt::class, 'student_id');
    }

    /**
     * Relasi one-to-many ke CourseRating (jika user adalah student).
     */
    public function courseRatings()
    {
        return $this->hasMany(CourseRating::class, 'student_id');
    }

    // Helper methods buat cek role (opsional, tapi ngebantu)
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isMentor(): bool
    {
        return $this->role === 'mentor';
    }

    public function isStudent(): bool
    {
        return $this->role === 'student';
    }
}