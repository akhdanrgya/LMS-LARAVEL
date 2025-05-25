<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\StudentProfile;
use App\Models\MentorProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password; // Untuk validasi password yang lebih kuat
use Illuminate\Validation\Rule; // Untuk validasi enum role

class RegisterController extends Controller
{
    /**
     * Menampilkan halaman form registrasi.
     * Pastikan view 'auth.register' ini adalah file Blade register lo.
     */
    public function showRegistrationForm()
    {
        // Lo udah punya view-nya, jadi kita arahkan ke sana
        // Asumsi file Blade register lo ada di resources/views/auth/register.blade.php
        // Kalo beda, tinggal ganti 'auth.register' nya
        return view('auth.register'); // Sesuaikan dengan path view register lo
    }

    /**
     * Menangani proses registrasi user baru.
     */
    public function register(Request $request)
    {
        // 1. Validasi Input dari Form Register Lo
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::min(8)], // 'confirmed' buat ngecek password_confirmation
            'role' => ['required', 'string', Rule::in(['student', 'mentor'])], // Validasi role
        ]);

        // 2. Buat User Baru
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'email_verified_at' => now(), // Atau kirim email verifikasi dulu
        ]);

        // 3. Buat Profil Sesuai Role
        if ($user->role === 'student') {
            StudentProfile::create([
                'student_id' => $user->id,
                // Field lain di StudentProfile bisa diisi default atau dari form tambahan
            ]);
        } elseif ($user->role === 'mentor') {
            MentorProfile::create([
                'user_id' => $user->id,
                // Field lain di MentorProfile bisa diisi default atau dari form tambahan
            ]);
        }

        // 4. Login User yang Baru Didaftar
        Auth::login($user);

        // 5. Redirect ke Halaman yang Sesuai Setelah Register
        // Ini bisa ke dashboard masing-masing role atau halaman home umum
        if ($user->role === 'admin') { // Walaupun di form cuma student/mentor, jaga-jaga
            return redirect()->route('admin.dashboard'); // Asumsi ada route name ini
        } elseif ($user->role === 'mentor') {
            return redirect()->route('mentor.dashboard'); // Asumsi ada route name ini
        } elseif ($user->role === 'student') {
            return redirect()->route('student.dashboard'); // Asumsi ada route name ini
        }
        
        return redirect('/'); // Fallback ke /home
    }
}