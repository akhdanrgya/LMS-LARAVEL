<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\MaterialController;

Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard'); // view home/dashboard
    Route::get('/courses', [DashboardController::class, 'courses'])->name('dashboard.courses'); // list courses view
    Route::get('/task', [DashboardController::class, 'task'])->name('dashboard.task'); // task page
    Route::get('/forum', [DashboardController::class, 'forum'])->name('dashboard.forum'); // forum page
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout'); // logout action

    // Mentor routes to view pages
    Route::middleware('role:mentor')->group(function () {
        Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.create'); // create course form
        Route::get('/courses/{course}', [CourseController::class, 'show'])->name('courses.show'); // course detail
        Route::get('/courses/{course}/materials', [MaterialController::class, 'index'])->name('materials.index'); // list materials
        Route::get('/courses/{course}/materials/create', [MaterialController::class, 'create'])->name('materials.create'); // create material form
    });
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login'); // login page
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register'); // register page
});

