<?php

namespace App\Http\Controllers\Student; // Pastikan namespace-nya

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Enrollment;

class OverviewController extends Controller
{
    public function index()
    {
        $student = Auth::user();
        $studentProfile = $student->studentProfile; // Asumsi relasi studentProfile ada di model User

        // Statistik dari gambar & improvement
        $coursesInProgressCount = $student->enrollments()->where('completion_status', 'in_progress')->count();
        $coursesCompletedCount = $student->enrollments()->where('completion_status', 'completed')->count();
        
        $hoursLearning = "N/A"; // Placeholder, atau implementasi estimasi
        $totalQuizScore = $studentProfile->total_score ?? 0;

        $currentLevel = $studentProfile->level ?? 1;
        $pointsForNextLevel = 500; // Contoh
        $currentLevelExp = $totalQuizScore % $pointsForNextLevel;
        $expToNextLevel = $pointsForNextLevel - $currentLevelExp;

        // Untuk bagian "MY COURSES" (ambil 3 course yang sedang berjalan)
        $myRecentInProgressCourses = $student->enrolledCourses()
                                        ->where('enrollments.completion_status', 'in_progress')
                                        ->with('mentor') // Eager load mentor
                                        ->orderBy('enrollments.updated_at', 'desc')
                                        ->take(3)
                                        ->get();
        
        // Menyiapkan data progress untuk tiap course (contoh simpel)
        foreach ($myRecentInProgressCourses as $course) {
            // Untuk demo, kita kasih nilai acak atau default:
            $course->progress_percentage = rand(20, 80); 
            if ($course->pivot && $course->pivot->completion_status === 'completed') {
                 $course->progress_percentage = 100;
            }
        }

        // Mengirim data ke view 'student.overview'
        return view('student.overview', [
            'studentName' => $student->name,
            'coursesInProgressCount' => $coursesInProgressCount,
            'coursesCompletedCount' => $coursesCompletedCount,
            'hoursLearning' => $hoursLearning,
            'totalQuizScore' => $totalQuizScore,
            'currentLevel' => $currentLevel,
            'currentLevelExp' => $currentLevelExp,
            'pointsForNextLevel' => $pointsForNextLevel,
            'expToNextLevel' => $expToNextLevel,
            'myRecentInProgressCourses' => $myRecentInProgressCourses,
        ]);
    }
}