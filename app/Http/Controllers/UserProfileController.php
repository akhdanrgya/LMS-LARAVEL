<?php

namespace App\Http\Controllers; // Pastikan namespace ini, bukan App\Http\Controllers\Student atau Mentor

use App\Models\User;
use Illuminate\Http\Request; // Mungkin gak kepake di method show()
// use Illuminate\Support\Facades\Auth; // Mungkin gak kepake di method show()

class UserProfileController extends Controller
{
    /**
     * Menampilkan halaman profil publik seorang user (mentor atau student).
     */
    public function show(User $user) // Route model binding akan inject User model berdasarkan ID/slug di URL
    {
        // Eager load data yang dibutuhkan berdasarkan role user yang DILIHAT
        if ($user->role === 'mentor') {
            $user->loadMissing(['mentorProfile', 'taughtCourses' => function ($query) {
                // Contoh: hanya course yang published dan ambil beberapa saja
                // Sesuaikan ini dengan logika 'is_published' jika ada di model Course
                // $query->where('status', 'published')->orderBy('created_at', 'desc')->take(12);
                $query->orderBy('created_at', 'desc')->take(12); // Versi simpel dulu
            }]);
        } elseif ($user->role === 'student') {
            $user->loadMissing('studentProfile'); 
            // Untuk student, kita mungkin mau nampilin course yang dia ikuti secara publik?
            // Atau cukup info dasar & level dari studentProfile?
            // Untuk sekarang, kita fokus ke data dari studentProfile dulu.
            // $user->loadMissing(['studentProfile', 'enrolledCourses' => function($query) {
            //     $query->take(5); // Contoh nampilin beberapa course yang di-enroll
            // }]);
        }
        // Admin profile tidak ada model terpisah, infonya dari User model saja.
        // Jika role tidak dikenal atau tidak mau ditampilkan, bisa kasih 404.
        if (!in_array($user->role, ['mentor', 'student', 'admin'])) {
             abort(404, 'Profil user tidak dapat ditampilkan.');
        }


        return view('profiles.show', compact('user')); // Kita pake satu view generik: profiles.show
    }
}