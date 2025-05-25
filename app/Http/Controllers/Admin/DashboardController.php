<?php

namespace App\Http\Controllers\Admin; // Pastikan namespace-nya bener

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User; // Untuk ngambil data user
use App\Models\Course; // Untuk ngambil data course

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard utama untuk Admin.
     */
    public function index()
    {
        // Contoh ngambil data buat statistik sederhana:
        $totalUsers = User::count();
        $totalMentors = User::where('role', 'mentor')->count();
        $totalStudents = User::where('role', 'student')->count();
        $totalCourses = Course::count();

        // Kirim data ini ke view
        return view('admin.dashboard', [
            'totalUsers' => $totalUsers,
            'totalMentors' => $totalMentors,
            'totalStudents' => $totalStudents,
            'totalCourses' => $totalCourses,
        ]);
    }
}