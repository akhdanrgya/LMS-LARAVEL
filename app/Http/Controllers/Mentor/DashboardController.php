<?php

namespace App\Http\Controllers\Mentor; // Pastikan namespace-nya bener

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Buat dapetin user mentor yang lagi login

// Mungkin butuh model Course buat ngitung jumlah course
// use App\Models\Course; 

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard utama untuk Mentor.
     */
    public function index()
    {
        $mentor = Auth::user(); // Ambil data mentor yang lagi login

        // Contoh ngambil data buat statistik sederhana:
        $totalCoursesByMentor = $mentor->taughtCourses()->count(); // Menggunakan relasi 'taughtCourses' di model User
        
        // Lo bisa tambahin data lain yang relevan buat mentor, misalnya:
        // - Jumlah total student yang enroll di semua course-nya
        // - Notifikasi baru (kalo ada sistem notifikasi)
        // - Course yang paling baru dibuat/diupdate

        return view('mentor.dashboard', [
            'mentorName' => $mentor->name,
            'totalCourses' => $totalCoursesByMentor,
            // kirim data lain ke view di sini
        ]);
    }
}