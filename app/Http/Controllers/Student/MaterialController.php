<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Material;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // Untuk link download file

class MaterialController extends Controller
{
    /**
     * Menampilkan detail satu materi untuk student yang terdaftar.
     */
    public function show(Course $course, Material $material)
    {
        $student = Auth::user();

        // 1. Cek apakah student terdaftar di course ini
        $isEnrolled = Enrollment::where('student_id', $student->id)
                                ->where('course_id', $course->id)
                                ->exists();
        
        // 2. Cek apakah materi ini milik course yang benar
        if (!$isEnrolled || $material->course_id !== $course->id) {
            // Kalo gak enroll atau materi bukan punya course itu, redirect atau kasih error
            return redirect()->route('courses.show', $course->slug)
                             ->with('error', 'Anda tidak berhak mengakses materi ini atau materi tidak ditemukan.');
        }

        return view('student.materials.show', compact('course', 'material'));
    }
}