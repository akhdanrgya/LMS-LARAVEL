<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $student = Auth::user();
        
        // Ambil data yang relevan buat dashboard student
        // Contoh: Jumlah course yang sudah di-enroll
        $enrolledCoursesCount = $student->enrollments()->count(); 
        // atau bisa juga $student->enrolledCourses()->count(); jika relasi enrolledCourses sudah benar

        // Contoh: Course yang terakhir diakses (ini butuh tracking tambahan, untuk sekarang kita skip dulu)
        // $lastAccessedCourse = ... ; 

        return view('student.dashboard', [
            'studentName' => $student->name,
            'enrolledCoursesCount' => $enrolledCoursesCount,
            // 'lastAccessedCourse' => $lastAccessedCourse,
        ]);
    }
}