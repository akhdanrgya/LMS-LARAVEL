<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CourseRating;

class Course extends Model
{
    use HasFactory;

    // Field yang bisa diisi mass-assignment
    protected $fillable = [
        'name',
        'description',
        'cover_photo',
    ];

    /**
     * Relasi banyak ke banyak dengan User (Students)
     */
    // app/Models/Course.php
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    // app/Models/Course.php
    public function students()
    {
        return $this->belongsToMany(User::class, 'enrollments')
            ->withPivot('completed')
            ->withTimestamps();
    }
    /**
     * Relasi satu ke banyak dengan Materials
     */
    public function materials()
    {
        return $this->hasMany(Material::class);
    }

    public function mentor()
    {
        return $this->belongsTo(User::class, 'mentor_id');
    }

    // Tambahkan accessor untuk cover photo URL
    public function ratings()
    {
        return $this->hasMany(CourseRating::class);
    }

    // Accessor untuk average rating
    public function getAverageRatingAttribute()
    {
        return $this->ratings()->avg('rating') ?? 0;
    }

    // Accessor untuk format duration
    public function getFormattedDurationAttribute()
    {
        $hours = floor($this->duration_minutes / 60);
        $minutes = $this->duration_minutes % 60;

        return $hours > 0
            ? sprintf('%dh %02dm', $hours, $minutes)
            : sprintf('%dm', $minutes);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function getMaterialsCountAttribute()
    {
        return $this->materials_count ?? 0;
    }
}
