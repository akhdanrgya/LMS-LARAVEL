<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\UserController;

// ini auth login registernya di sini
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// mentor only yak
Route::middleware(['auth:sanctum', 'role:mentor'])->group(function () {
    // Route buat course
    Route::get('courses', [CourseController::class, 'index']);
    Route::post('courses', [CourseController::class, 'store']);
    Route::put('courses/{id}', [CourseController::class, 'update']);
    Route::delete('courses/{id}', [CourseController::class, 'destroy']);
    
    // Route untuk ngatur materi di dalam course
    Route::get('courses/{courseId}/materials', [MaterialController::class, 'index']);
    Route::post('courses/{courseId}/materials', [MaterialController::class, 'store']);
    Route::get('courses/{courseId}/materials/{materialId}', [MaterialController::class, 'show']);
    Route::put('courses/{courseId}/materials/{materialId}', [MaterialController::class, 'update']);
    Route::delete('courses/{courseId}/materials/{materialId}', [MaterialController::class, 'destroy']);
});

// ini buat kita enrole course
Route::middleware('auth:sanctum')->post('users/{userId}/enroll/{courseId}', [UserController::class, 'enrollToCourse']);

// liat course apa aja si user
Route::middleware('auth:sanctum')->get('users/{userId}/courses', [UserController::class, 'courses']);
