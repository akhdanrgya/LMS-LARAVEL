<?php

namespace App\Http\Controllers;

use App\Models\Course;    // Import Model Course
use Illuminate\Http\Request; // Import Request untuk handle input, misal search
use Illuminate\Support\Facades\Auth; // Opsional, jika ada logic yg butuh status login di controller ini
use App\Models\Enrollment; 

class CoursePageController extends Controller
{
    /**
     * Menampilkan halaman daftar semua course yang tersedia (publik).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Course::with('mentor') // Eager load data mentor biar gak N+1 query
                        // ->where('status', 'published') // AKTIFKAN INI NANTI KALO UDAH ADA KOLOM STATUS DI COURSE
                        ->latest(); // Urutkan dari yang paling baru dibuat

        // Contoh fitur search sederhana berdasarkan judul course
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        
        $courses = $query->paginate(12); // Tampilkan 12 course per halaman (bisa disesuaikan)
        
        // Kirim data courses ke view 'courses.index'
        return view('courses.index', compact('courses'));
    }

    /**
     * Menampilkan halaman detail untuk satu course (publik).
     * Laravel otomatis melakukan Route Model Binding menggunakan slug karena
     * di Model Course kita sudah definisikan getRouteKeyName() return 'slug'.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\View\View
     */
    public function show(Course $course) // Route model binding otomatis pake slug
    {
        $student = Auth::user();
        $isEnrolled = false;

        // Cek apakah student (jika rolenya student) sudah terdaftar di course ini
        if ($student && $student->role === 'student') {
            $isEnrolled = Enrollment::where('student_id', $student->id)
                                    ->where('course_id', $course->id)
                                    ->exists();
        }

        // Eager load relasi yang mungkin dibutuhkan di view
        $course->load(['mentor', 'materials', 'quizzes.questions']); // Load quizzes, dan hitung juga jumlah soalnya

        return view('courses.show', compact('course', 'isEnrolled'));
    }
}