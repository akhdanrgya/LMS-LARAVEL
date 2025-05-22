<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\UserController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum', 'role:mentor'])->group(function () {
    // Courses CRUD - RESTful style
    Route::apiResource('courses', CourseController::class);

    // Materials nested under courses
    Route::apiResource('courses.materials', MaterialController::class);
});

// Student routes for enrollment and courses accessed
Route::middleware('auth:sanctum')->group(function () {
    Route::post('users/{user}/enroll/{course}', [UserController::class, 'enrollToCourse']);
    Route::get('users/{user}/courses', [UserController::class, 'courses']);
});
