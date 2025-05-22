<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    /// app/Models/Enrollment.php
    protected $fillable = [
        'user_id',
        'course_id',
        'completed'
    ];
}
