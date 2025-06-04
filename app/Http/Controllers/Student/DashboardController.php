<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Quiz; // Untuk timeline quiz
use App\Models\StudentQuizAttempt; // Untuk status quiz

class DashboardController extends Controller
{
    public function index()
    {
        $student = Auth::user();
        $studentId = $student->id;

        // --- Data untuk "AE Status" ---
        $enrolledCoursesCount = Enrollment::where('student_id', $studentId)->count();
        
        // Untuk "Quizzes": Total quiz di course yang di-enroll & yang sudah dikerjakan
        // Ini bisa jadi query yang lebih kompleks, kita buat versi simpel dulu
        $enrolledCourseIds = Enrollment::where('student_id', $studentId)->pluck('course_id');
        $totalQuizzesInEnrolledCourses = Quiz::whereIn('course_id', $enrolledCourseIds)->count();
        $completedQuizzesCount = StudentQuizAttempt::where('student_id', $studentId)
                                      ->whereNotNull('submitted_at')
                                      ->distinct('quiz_id') // Hanya hitung quiz unik yang sudah disubmit
                                      ->count('quiz_id');
        
        // "Learning Hours" - ini perlu sistem tracking sendiri, untuk sekarang kita kasih placeholder
        $learningHours = "N/A"; // atau angka statis dulu

        // --- Data untuk "TIMELINE" ---
        // Ambil quiz yang akan datang atau sedang open dari course yang di-enroll student
        // Ini juga bisa kompleks (cek due date, status open, dll.). Kita buat contoh simpel.
        $timelineQuizzes = Quiz::whereIn('course_id', $enrolledCourseIds)
                            // ->where('due_date', '>=', now()) // Contoh filter due date
                            ->orderBy('created_at', 'desc') // atau 'due_date'
                            ->take(3) // Ambil beberapa saja buat timeline
                            ->get();
        
        // "TUGAS" (Assignments) adalah fitur baru, belum ada modelnya. Jadi kita skip dulu di data.

        // --- Data untuk "ENROLL COURSES" (My Courses) ---
        $myEnrolledCourses = $student->enrolledCourses() // Pake relasi ini
                                ->with('mentor')
                                ->withCount('materials', 'quizzes')
                                ->orderBy('enrollments.enrolled_at', 'desc') // atau 'enrollments.enrolled_at' JIKA kolomnya enrolled_at
                                // PENTING: Langsung sebut nama tabel pivot dan kolomnya
                                ->take(3)
                                ->get();
        // Alternatif jika relasi sudah ada di model User:
        // $myEnrolledCourses = $student->enrolledCourses()
        //                           ->with('mentor')
        //                           ->withCount('materials', 'quizzes')
        //                           ->orderBy('enrollments.created_at', 'desc') // Order by enrollment date
        //                           ->take(3)->get();


        // --- Data untuk "COURSES" (General Listing / All Courses) ---
        // Bisa ambil dari CoursePageController atau query serupa
        $allAvailableCourses = Course::with('mentor')
                                ->withCount('materials', 'quizzes') // Contoh
                                ->orderBy('created_at', 'desc')
                                ->take(4) // Ambil beberapa saja buat dashboard
                                ->get();

        return view('student.dashboard', compact(
            'student',
            'enrolledCoursesCount',
            'totalQuizzesInEnrolledCourses',
            'completedQuizzesCount',
            'learningHours',
            'timelineQuizzes',
            'myEnrolledCourses',
            'allAvailableCourses'
        ));
    }
}