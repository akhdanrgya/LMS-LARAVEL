<?php

namespace App\Http\Controllers;
use App\Models\Course;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{


    public function enroll(Course $course)
    {
        $user = auth()->user();

        if ($user->enrolledCourses()->where('course_id', $course->id)->exists()) {
            return redirect()->back()->with('info', 'You are already enrolled in this course.');
        }

        $user->enrolledCourses()->attach($course->id);

        return redirect()->route('courses.show', $course->id)->with('success', 'Enrolled successfully.');
    }
}