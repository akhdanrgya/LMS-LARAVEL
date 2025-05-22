<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Course;

class DashboardController extends Controller
{
    public function index()
    {
        $courses = auth()->user()->courses()
                    ->withCount('materials')
                    ->latest()
                    ->get();
        
        return view('dashboard.index', compact('courses'));
    }

    public function courses(User $user)
    {
        $courses = Course::where('id', $user->id)->get();
        return view('dashboard.courses', compact('courses', 'user'));
    }

    public function task()
    {
        return view('dashboard.task');
    }
    public function forum()
    {
        return view('dashboard.forum');
    }
}
