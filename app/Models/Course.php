<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // Opsional, bisa buat helper slug jika tidak di controller

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'mentor_id',
        'title',
        'slug',
        'description',
        'thumbnail_path',
    ];

    /**
     * Boot a new Eloquent model instance.
     * Otomatis bikin slug kalo title diisi dan slug kosong (opsional, bisa juga di controller)
     */
    // protected static function boot()
    // {
    //     parent::boot();
    //     static::creating(function ($course) {
    //         if (empty($course->slug)) {
    //             $course->slug = Str::slug($course->title) . '-' . uniqid();
    //         }
    //     });
    //     static::updating(function ($course) {
    //         if ($course->isDirty('title') && empty($course->slug)) { // Atau jika mau slug selalu update saat title update
    //             $course->slug = Str::slug($course->title) . '-' . $course->id; // pastikan unik
    //         }
    //     });
    // }


    /**
     * Relasi one-to-many (inverse) ke User (Mentor yang mengajar course ini).
     */
    public function mentor()
    {
        return $this->belongsTo(User::class, 'mentor_id');
    }

    /**
     * Relasi one-to-many ke Material.
     */
    public function materials()
    {
        return $this->hasMany(Material::class, 'course_id')->orderBy('order_sequence', 'asc'); // Urutkan materi
    }

    /**
     * Relasi one-to-many ke Quiz.
     */
    public function quizzes()
    {
        return $this->hasMany(Quiz::class, 'course_id');
    }

    /**
     * Relasi one-to-many ke Enrollment.
     */
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'course_id');
    }

    /**
     * Relasi many-to-many ke User (Student yang terdaftar di course ini).
     */
    public function students()
    {
        return $this->belongsToMany(User::class, 'enrollments', 'course_id', 'student_id')
                    ->withTimestamps()
                    ->withPivot('completion_status', 'completed_at');
    }

    /**
     * Relasi one-to-many ke CourseRating.
     */
    public function ratings()
    {
        return $this->hasMany(CourseRating::class, 'course_id');
    }

    /**
     * Accessor untuk rata-rata rating (opsional, tapi berguna).
     */
    public function getAverageRatingAttribute()
    {
        return $this->ratings()->avg('rating');
    }

    /**
     * Mendapatkan path URL untuk thumbnail.
     */
    public function getThumbnailUrlAttribute()
    {
        if ($this->thumbnail_path) {
            return asset('storage/' . $this->thumbnail_path); // Pastikan sudah php artisan storage:link
        }
        return asset('images/default_course_thumbnail.png'); // Sediakan gambar default
    }

    /**
     * Untuk Route Model Binding menggunakan slug daripada ID.
     * Pastikan kolom 'slug' di tabel 'courses' itu unik.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}