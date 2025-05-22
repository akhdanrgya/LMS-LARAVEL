<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    public function students()
    {
        return $this->belongsToMany(User::class)
        ->withPivot('completed');
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
}
