<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Cek apakah user terautentikasi
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = auth()->user();
        
        // Pengecualian khusus untuk user dengan nama 'admin'
        if ($user->name === 'admin') {
            return $next($request);
        }

        // Cek apakah user punya salah satu role yang diizinkan
        if (!in_array(strtolower($user->role), array_map('strtolower', $roles))) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        return $next($request);
    }
}