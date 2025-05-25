<?php

// use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\AuthController;
// use App\Http\Controllers\DashboardController;
// use App\Http\Controllers\CourseController;
// use App\Http\Controllers\MaterialController;
// use App\Http\Controllers\AdminController;
// use App\Http\Controllers\MentorController;
// use App\Http\Controllers\UserManagementController;
// use App\Http\Controllers\CourseManagementController;


// Route::middleware('auth')->group(function () {
    //     Route::post('/logout', [AuthController::class, 'logout'])->name('logout'); // logout action
    
    
    //     Route::middleware('role:mentor,student')->group(function () {
        //         Route::get('/', [DashboardController::class, 'index'])->name('dashboard'); // view home/dashboard
        //         Route::get('/courses', [CourseController::class, 'fetchAllCourses'])->name('courses.index');
        //         Route::get('/{user:name}/courses', [DashboardController::class, 'courses'])->name('dashboard.courses'); // list courses view
        //         Route::get('/task', [DashboardController::class, 'task'])->name('dashboard.task'); // task page
        //         Route::get('/forum', [DashboardController::class, 'forum'])->name('dashboard.forum'); // forum page
        
        //         Route::middleware('role:mentor')->group(function () {
            //             Route::get('/courses/create', [CourseController::class, 'viewCreate'])->name('courses.create'); // create course form
            //             Route::get('/mentor', [MentorController::class, 'index'])->name('mentor.index'); // create course form
            //             Route::get('/courses/{course}/materials/create', [MaterialController::class, 'create'])->name('materials.create'); // create material form
            //             Route::get('/managecourse', [MentorController::class, 'managecourse'])->name(name: 'mentor.managecourse'); // create material form
            //             Route::get('/managematerial', [MentorController::class, 'managematerial'])->name(name: 'mentor.managematerial'); // create material form
            //             Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');
            //         });
            
            //         Route::get('/mycourses', [CourseController::class, 'userCourses'])->name('dashboard.index');
//         Route::get('/courses/{course:name}', [CourseController::class, 'show'])->name('courses.show'); // course detail
//         Route::post('/courses/{course}/enroll', [CourseController::class, 'enroll'])->name('courses.enroll');
//         Route::get('/courses/{course}/materials', [MaterialController::class, 'index'])->name('materials.index'); // list materials

//     });

//     // Mentor routes to view pages

//     // admin route
//     Route::middleware('role:admin')->group(function () {
    //         Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    //         Route::get('/usermanagement', [UserManagementController::class, 'index'])->name('admin.usermanagement');
    //         Route::get('/coursemanagement', [CourseManagementController::class, 'index'])->name('admin.coursemanagement');
    
    //         Route::post('/admin/update-role', [AdminController::class, 'updateRole'])
    //             ->name('admin.update-role');
    //     });
    // });
    
    // Route::middleware('guest')->group(function () {
        //     Route::get('/login', [AuthController::class, 'showLogin'])->name('login'); // login page
        //     Route::get('/register', [AuthController::class, 'showRegister'])->name('register'); // register page
        
        //     Route::post('/login', [AuthController::class, 'login'])->name('login.post');
        //     Route::post('/register', [AuthController::class, 'register'])->name('register.post');
        // });
        
        
use App\Http\Controllers\Mentor\CourseController as MentorCourseController;
use App\Http\Controllers\Auth\RegisterController; // Import RegisterController
use App\Http\Controllers\Auth\LoginController;   // Import LoginController

// Route untuk menampilkan form
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register')->middleware('guest'); // Cuma tamu yang bisa akses
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');    // Cuma tamu yang bisa akses

Route::post('/register', [RegisterController::class, 'register'])->middleware('guest');
Route::post('/login', [LoginController::class, 'login'])->middleware('guest');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth'); // Cuma yang udah login bisa logout



Route::middleware(['auth', 'role:mentor'])->prefix('mentor')->name('mentor.')->group(function () {
    
    // Dashboard Mentor (misal)
    // Route::get('/dashboard', [MentorDashboardController::class, 'index'])->name('dashboard');

    // Resourceful route untuk Course milik Mentor
    // Ini otomatis bikin route buat index, create, store, show, edit, update, destroy
    Route::resource('courses', MentorCourseController::class);
    
    // Kalo ada route spesifik lain buat mentor, tambahin di sini
    // Misalnya buat kelola materi atau quiz di dalam course:
    // Route::get('/courses/{course}/materials', [MentorMaterialController::class, 'index'])->name('courses.materials.index');

});