<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MyCoursesController extends Controller
{
    public function index()
    {
        $student = Auth::user();
        // Ambil course yang di-enroll student, pake relasi enrolledCourses yang udah kita buat di model User
        $enrolledCourses = $student->enrolledCourses()->with('mentor')->latest('enrollments.created_at')->paginate(10);
        // 'enrollments.created_at' buat ngurutin berdasarkan kapan dia enroll

        return view('student.my-courses.index', compact('enrolledCourses'));
    }
}