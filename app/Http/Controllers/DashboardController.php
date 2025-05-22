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
        // Authorization check
        if (auth()->id() !== $user->id) {
            abort(403, 'Unauthorized action.');
        }
    
        $courses = $user->courses()
                    ->withCount('materials')
                    ->latest()
                    ->get();
    
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
