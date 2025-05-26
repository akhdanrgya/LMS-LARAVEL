<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Menampilkan halaman form login.
     * Pastikan view 'auth.login' ini adalah file Blade login lo.
     */
    public function showLoginForm()
    {
        // Arahkan ke view login lo
        // Asumsi file Blade login lo ada di resources/views/auth/login.blade.php
        return view('auth.login'); // Sesuaikan dengan path view login lo
    }

    /**
     * Menangani proses login.
     */
    public function login(Request $request)
    {
        // 1. Validasi Input
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. Coba Lakukan Otentikasi
        if (Auth::attempt($credentials, $request->boolean('remember'))) { // $request->boolean('remember') buat checkbox "Remember Me" kalo ada
            $request->session()->regenerate(); // Regenerate session ID biar aman

            // 3. Redirect Sesuai Role Setelah Login Sukses
            $user = Auth::user();
            if ($user->role === 'admin') {
                return redirect()->intended(route('admin.dashboard')); // intended() ngarahin ke halaman yg dituju sebelum login, atau ke fallback
            } elseif ($user->role === 'mentor') {
                return redirect()->intended(route('mentor.dashboard'));
            } elseif ($user->role === 'student') {
                return redirect()->intended(route('student.dashboard'));
            }
            
            return redirect()->intended('/'); // Fallback
        }

        // 4. Kalo Gagal Login
        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.', // Pesan error umum
        ])->onlyInput('email'); // Balik ke form login, cuma bawa input email (password jangan)
    }

    /**
     * Menangani proses logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login'); // Redirect ke halaman utama setelah logout
    }
}