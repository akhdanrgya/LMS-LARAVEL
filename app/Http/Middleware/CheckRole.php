<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Pastiin ini ada
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $role  // Ini parameter buat role yang diizinkan
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // 1. Cek dulu user udah login apa belum
        if (!Auth::check()) {
            // Kalo belum login, redirect ke halaman login
            return redirect()->route('login'); // Asumsi lo punya route name 'login'
        }

        // 2. Ambil user yang lagi login
        $user = Auth::user();

        // 3. Cek apakah role user sesuai dengan role yang dibutuhkan
        if ($user->role !== $role) {
            // Kalo gak sesuai, kasih halaman error 403 (Forbidden / Gak Punya Akses)
            // Lo bisa juga redirect ke halaman lain kalo mau, misalnya ke dashboard utama
            // dengan pesan error. Tapi abort(403) itu standar.
            abort(403, 'ANDA TIDAK PUNYA AKSES UNTUK HALAMAN INI.');
        }

        // Kalo semua aman, lanjutin requestnya
        return $next($request);
    }
}