<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnrollmentController extends Controller
{
    /**
     * Proses student enroll ke sebuah course.
     * Course $course otomatis di-inject karena route model binding {course}
     */
    public function store(Request $request, Course $course)
    {
        $student = Auth::user();

        // Cek apakah student adalah student (double check, meskipun route udah diprotect middleware)
        if ($student->role !== 'student') {
            return redirect()->back()->with('error', 'Hanya student yang bisa mendaftar course.');
        }

        // Cek apakah student sudah enroll sebelumnya untuk mencegah duplikasi
        $alreadyEnrolled = Enrollment::where('student_id', $student->id)
                                     ->where('course_id', $course->id)
                                     ->exists();
        if ($alreadyEnrolled) {
            return redirect()->route('courses.show', $course->slug)
                             ->with('info', 'Anda sudah terdaftar di course ini.');
        }

        // Buat record enrollment baru
        Enrollment::create([
            'student_id' => $student->id,
            'course_id' => $course->id,
            // 'enrolled_at' dan 'completion_status' sudah ada defaultnya di migrasi
        ]);

        return redirect()->route('courses.show', $course->slug)
                         ->with('success', 'Selamat! Anda berhasil mendaftar ke course "' . $course->title . '".');
    }
}