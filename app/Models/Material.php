<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'file_path',
        'course_id',
        'order',
    ];

    /**
     * Relasi banyak ke satu dengan Course
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
