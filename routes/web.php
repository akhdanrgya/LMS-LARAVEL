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
// use App\Http\Controllers\Admin\CourseManagementController as AdminCourseManagementController; // Nanti kalo ada

// Controller Mentor
use App\Http\Controllers\Mentor\DashboardController as MentorDashboardController; // Kalo mau ada dashboard mentor khusus
use App\Http\Controllers\Mentor\CourseController as MentorCourseController;
// use App\Http\Controllers\Mentor\MaterialController as MentorMaterialController; // Nanti kalo ada

// Controller Student
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\MyCoursesController as StudentMyCoursesController;
use App\Http\Controllers\Student\EnrollmentController as StudentEnrollmentController;
// use App\Http\Controllers\Student\QuizAttemptController as StudentQuizAttemptController; // Nanti kalo ada


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman Utama (Welcome/Landing Page)
Route::get('/', function () {
    return view('welcome'); // Atau view landing page lo
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

// Rute Publik untuk Melihat Course
// Route::get('/courses', [CoursePageController::class, 'index'])->name('courses.index');
// Route::get('/courses/{course:slug}', [CoursePageController::class, 'show'])->name('courses.show'); // Pake slug buat SEO friendly

// ------------------------- ADMIN ROUTES -------------------------
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // User Management oleh Admin
    Route::resource('users', AdminUserController::class)->except(['show']); 
    // method show() di-exclude karena biasanya gak perlu halaman detail khusus user buat admin

});

// ------------------------- MENTOR ROUTES -------------------------
Route::middleware(['auth', 'role:mentor'])->prefix('mentor')->name('mentor.')->group(function () {
    // Kalo mau ada dashboard khusus mentor, bisa di sini:
    // Route::get('/dashboard', [MentorDashboardController::class, 'index'])->name('dashboard'); // Pastikan MentorDashboardController ada method index

    // Resourceful route untuk Course milik Mentor (ini udah dari kode lo, bagus!)
    // Route::resource('courses', MentorCourseController::class);
    
    // Contoh route buat kelola materi (Nanti kalo udah ada MentorMaterialController)
    // Route::resource('courses/{course}/materials', MentorMaterialController::class);
});

// ------------------------- STUDENT ROUTES -------------------------
Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    // Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard'); // Pastikan StudentDashboardController ada method index
    
    // Halaman buat liat course yang udah di-enroll student
    // Route::get('/my-courses', [StudentMyCoursesController::class, 'index'])->name('my-courses.index');
    
    // Proses enroll ke course
    // Route::post('/enroll/{course}', [StudentEnrollmentController::class, 'store'])->name('courses.enroll');
    
    // Contoh route buat ngerjain quiz (Nanti kalo udah ada StudentQuizAttemptController)
    // Route::get('/courses/{course}/quiz/{quiz}/attempt', [StudentQuizAttemptController::class, 'create'])->name('quiz.attempt');
    // Route::post('/courses/{course}/quiz/{quiz}/attempt', [StudentQuizAttemptController::class, 'store']);
});