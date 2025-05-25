<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MentorProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'bio',
        'expertise',
        'experience_years',
        'profile_picture_path',
        'linkedin_url',
        'website_url',
    ];

    /**
     * Relasi one-to-one (inverse) ke User.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}