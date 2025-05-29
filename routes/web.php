<?php

use Illuminate\Support\Facades\Route;

// Controller untuk Otentikasi
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;

// Controller Publik & Home
use App\Http\Controllers\HomeController;         // Untuk halaman /home generik
use App\Http\Controllers\CoursePageController;  // Untuk halaman publik daftar & detail course

// Controller Admin
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\CourseManagementController as AdminCourseManagementController;

// Controller Mentor
use App\Http\Controllers\Mentor\DashboardController as MentorDashboardController; // Kalo mau ada dashboard mentor khusus
use App\Http\Controllers\Mentor\CourseController as MentorCourseController;
use App\Http\Controllers\Mentor\MaterialController as MentorMaterialController; // Nanti kalo ada

// Controller Student
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\MyCoursesController as StudentMyCoursesController;
use App\Http\Controllers\Student\EnrollmentController as StudentEnrollmentController;
use App\Http\Controllers\Student\OverviewController as StudentOverviewController;
// use App\Http\Controllers\Student\QuizAttemptController as StudentQuizAttemptController; // Nanti kalo ada


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    // return view('welcome'); // Jika masih ada landing page sederhana sebelum login
    return redirect()->route('login'); // Atau langsung redirect ke login
})->name('welcome');

// Rute Otentikasi (Login, Register, Logout)
// Ini udah oke dari kode lo, gue tambahin name buat POST biar konsisten (opsional)
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.submit'); // Opsional name

    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.submit'); // Opsional name
});
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Halaman Home Umum (Setelah Login jika tidak ada redirect spesifik role)
// Route::get('/home', [HomeController::class, 'index'])->name('home')->middleware('auth');

// RUTE-RUTE YANG MEMBUTUHKAN OTENTIKASI (SEMUA ROLE BISA AKSES AWALNYA, NANTI DIHANDLE ROLE DI DALAM)
Route::middleware(['auth'])->group(function () {
    // Halaman "All Courses" untuk semua user yang sudah login
    Route::get('/courses', [CoursePageController::class, 'index'])->name('courses.index');
    Route::get('/courses/{course:slug}', [CoursePageController::class, 'show'])->name('courses.show');

    // Halaman /home sekarang tidak ada, redirect dihandle oleh LoginController & RegisterController
    // Dan oleh RedirectIfAuthenticated middleware
});

// ------------------------- ADMIN ROUTES -------------------------
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('users', AdminUserController::class)->except(['show']);

    // Course Management oleh Admin
    Route::get('/courses', [AdminCourseManagementController::class, 'index'])->name('courses.index');
    Route::get('/courses/{course}/edit', [AdminCourseManagementController::class, 'edit'])->name('courses.edit');
    Route::put('/courses/{course}', [AdminCourseManagementController::class, 'update'])->name('courses.update');
    Route::delete('/courses/{course}', [AdminCourseManagementController::class, 'destroy'])->name('courses.destroy');
    // Opsional: Route buat toggle status
    // Route::patch('/courses-management/{course}/status/{newStatus}', [AdminCourseManagementController::class, 'toggleStatus'])->name('courses.manage.toggleStatus');
});

// ------------------------- MENTOR ROUTES -------------------------
Route::middleware(['auth', 'role:mentor'])->prefix('mentor')->name('mentor.')->group(function () {

    // 1. Dashboard Mentor
    Route::get('/dashboard', [MentorDashboardController::class, 'index'])->name('dashboard');

    // 2. Resourceful route untuk Course milik Mentor
    Route::resource('courses', MentorCourseController::class);

    // 3. Resourceful route untuk Materi di dalam Course (NESTED)
    // INI YANG PERLU LO TAMBAHKAN:
    Route::resource('courses.materials', MentorMaterialController::class)
        ->except(['show']); // Kita exclude 'show' karena mungkin detail materi langsung di-view atau edit

    Route::resource('courses.quizzes', App\Http\Controllers\Mentor\QuizController::class)
        ->except(['show']); // Method show bisa kita custom nanti buat nampilin detail quiz + daftar pertanyaan

    Route::resource('courses.quizzes.questions', App\Http\Controllers\Mentor\QuestionController::class)
        ->except(['show']) // Kita mungkin tidak pakai show() terpisah untuk question
        ->scoped(); // Penting untuk nested model binding yang benar (Question harus milik Quiz, Quiz harus milik Course)

});

// ------------------------- STUDENT ROUTES -------------------------
Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard'); // Pastikan StudentDashboardController ada method index
    Route::get('/overview', [StudentOverviewController::class, 'index'])->name('overview');

    // Halaman buat liat course yang udah di-enroll student
    Route::get('/my-courses', [StudentMyCoursesController::class, 'index'])->name('my-courses.index');

    // Proses enroll ke course
    Route::post('/enroll/{course}', [StudentEnrollmentController::class, 'store'])->name('courses.enroll');

    // Contoh route buat ngerjain quiz (Nanti kalo udah ada StudentQuizAttemptController)
    // Route::get('/courses/{course}/quiz/{quiz}/attempt', [StudentQuizAttemptController::class, 'create'])->name('quiz.attempt');
    // Route::post('/courses/{course}/quiz/{quiz}/attempt', [StudentQuizAttemptController::class, 'store']);
});