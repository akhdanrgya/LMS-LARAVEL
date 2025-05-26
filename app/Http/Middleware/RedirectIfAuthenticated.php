<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // MODIFIKASI DI SINI
                $user = Auth::guard($guard)->user();
                if ($user->role === 'admin') {
                    return redirect(route('admin.dashboard'));
                } elseif ($user->role === 'mentor') {
                    return redirect(route('mentor.dashboard'));
                } elseif ($user->role === 'student') {
                    return redirect(route('student.dashboard'));
                }
                // Fallback jika tidak ada role spesifik atau route dashboard belum ada (seharusnya tidak terjadi jika route dashboard ada)
                return redirect('/'); // RouteServiceProvider::HOME biasanya /home, ini mungkin perlu diubah atau dihapus jika /home tidak ada lagi
                                                              // Atau redirect ke '/' saja jika aman
            }
        }

        return $next($request);
    }
}