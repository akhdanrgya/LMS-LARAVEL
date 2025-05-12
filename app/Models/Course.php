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
    public function students()
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * Relasi satu ke banyak dengan Materials
     */
    public function materials()
    {
        return $this->hasMany(Material::class);
    }
}
