<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\UserController;

// Route untuk ambil data user yang sedang login
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Route untuk ngatur courses (admin atau authenticated user bisa akses)
Route::middleware('auth:sanctum')->group(function () {
    // Melihat semua course
    Route::get('courses', [CourseController::class, 'index']);
    // Melihat course berdasarkan ID
    Route::get('courses/{id}', [CourseController::class, 'show']);
    // Menyimpan course baru (admin)
    Route::post('courses', [CourseController::class, 'store']);
    // Update course (admin)
    Route::put('courses/{id}', [CourseController::class, 'update']);
    // Hapus course (admin)
    Route::delete('courses/{id}', [CourseController::class, 'destroy']);
    
    // Route untuk ngatur materi di dalam course
    Route::get('courses/{courseId}/materials', [MaterialController::class, 'index']);
    Route::post('courses/{courseId}/materials', [MaterialController::class, 'store']);
    Route::get('courses/{courseId}/materials/{materialId}', [MaterialController::class, 'show']);
    Route::put('courses/{courseId}/materials/{materialId}', [MaterialController::class, 'update']);
    Route::delete('courses/{courseId}/materials/{materialId}', [MaterialController::class, 'destroy']);

    // Enroll user ke course
    Route::post('users/{userId}/enroll/{courseId}', [UserController::class, 'enrollToCourse']);
    
    // Melihat semua course yang diikuti oleh user
    Route::get('users/{userId}/courses', [UserController::class, 'courses']);
});
