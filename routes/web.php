<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\MaterialController;


Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/courses', [DashboardController::class, 'courses'])->name('dashboard.courses');
    Route::get('/task', [DashboardController::class, 'task'])->name('dashboard.task');
    Route::get('/forum', [DashboardController::class, 'forum'])->name('dashboard.forum');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});


Route::middleware(['auth', 'role:mentor'])->group(function () {
    Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.create');
    Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');

    Route::get('/courses/{course}/materials/create', [MaterialController::class, 'create'])->name('materials.create');
    Route::post('/courses/{course}/materials', [MaterialController::class, 'store'])->name('materials.store');
});

Route::middleware(['auth', 'role:student'])->group(function () {
    Route::post('/courses/{course}/enroll', [EnrollmentController::class, 'enroll'])->name('courses.enroll');
});
