<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $middleware = [
        // Middleware global bawaan Laravel biasanya ada di sini,
        // seperti HandleCors, PreventRequestsDuringMaintenance, ValidatePostSize, dll.
        // Kalo lo emang bikin dari awal banget dan ngehapus semua,
        // pastiin middleware global yang penting tetep ada atau sengaja dihilangkan.
        // Contoh yang mungkin lo butuhin (sesuaikan dengan versi Laravel lo):
        \App\Http\Middleware\TrustProxies::class,
        \Illuminate\Http\Middleware\HandleCors::class, // Jika pakai CORS
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class, // Opsional, ada di beberapa versi
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            // 'auth:sanctum', // Kalo pake Sanctum buat API auth, dulunya 'throttle:api' dan EnsureFrontendRequestsAreStateful
            'throttle:api', // Atau 'throttle:60,1' jika EnsureFrontendRequestsAreStateful tidak dipakai lagi di versi Laravel lo
             \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class, // Ini biasanya buat SPA yg pake Sanctum cookies
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array<string, class-string|string>
     */
    protected $routeMiddleware = [ // Di Laravel 10 ke atas, ini namanya $middlewareAliases
        // 'auth' => \App\Http\Middleware\Authenticate::class,
        // 'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        // 'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        // 'can' => \Illuminate\Auth\Middleware\Authorize::class,
        // 'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        // 'precognitive' => \Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests::class,
        // 'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        // 'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        // 'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        // INI YANG DIGANTI:
        'role' => \App\Http\Middleware\CheckRole::class,
    ];
}