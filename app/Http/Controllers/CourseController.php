<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    // Tampilkan semua course (API)
    public function indexApi()
    {
        $courses = Course::select('id', 'name', 'description', 'cover_photo', 'created_at', 'updated_at')->get();
        return response()->json($courses);
    }

    public function userCourses()
    {
        $courses = auth()->user()->courses()->latest()->get();
        return view('dashboard.index', [
            'courses' => $courses,
            'useAlternateCard' => true
        ]);
    }

    // Tampilkan semua course tanpa enrollment (untuk tampilan umum)
    public function fetchAllCourses()
    {
        $courses = Course::with(['author:id,name', 'materials'])
            ->withCount('materials')
            ->latest()
            ->get();

        return view('courses.index', [
            'courses' => $courses
        ]);
    }

    // Tampilkan detail course berdasarkan model binding
    public function show(Course $course)
    {
        $course->load([
            'author:id,name',
            'materials' => function ($query) {
                $query->orderBy('order', 'asc');
            },
            'enrollments' => function ($query) {
                $query->where('user_id', auth()->id())->first();
            }
        ]);

        // Hitung total durasi course
        $totalDuration = $course->materials->sum('duration_minutes');
        $formattedDuration = floor($totalDuration / 60) . 'h ' . ($totalDuration % 60) . 'm';

        return view('courses.show', [
            'course' => $course,
            'formattedDuration' => $formattedDuration,
            'userEnrollment' => $course->enrollments->first(),
            'materialsCount' => $course->materials->count()
        ]);
    }


    // Store course baru dengan validasi dan upload cover photo
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'cover_photo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('cover_photo')) {
            $path = $request->file('cover_photo')->store('course_covers', 'public');
            $validated['cover_photo'] = $path;
        }

        $course = new Course();
        $course->name = $validated['name'];
        $course->description = $validated['description'];
        $course->cover_photo = $validated['cover_photo'] ?? null;
        $course->author_id = auth()->id();
        $course->save();

        return response()->json($course->only(['id', 'name', 'description', 'cover_photo', 'created_at', 'updated_at']), 201);
    }

    // Update course, termasuk ganti cover photo (hapus yang lama)
    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'cover_photo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('cover_photo')) {
            if ($course->cover_photo) {
                Storage::disk('public')->delete($course->cover_photo);
            }
            $path = $request->file('cover_photo')->store('course_covers', 'public');
            $validated['cover_photo'] = $path;
        }

        $course->update($validated);

        return response()->json($course->only(['id', 'name', 'description', 'cover_photo', 'created_at', 'updated_at']));
    }

    // Hapus course sekaligus hapus cover photo kalau ada
    public function destroy(Course $course)
    {
        try {
            if ($course->cover_photo) {
                Storage::disk('public')->delete($course->cover_photo);
            }
            $course->delete();
            return response()->json(['message' => 'Course deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete course'], 500);
        }
    }

    public function create()
    {
        return view('courses.create');
    }

    public function enroll(Course $course)
{
    if(!auth()->user()->enrollments()->where('course_id', $course->id)->exists()) {
        auth()->user()->enrollments()->create([
            'course_id' => $course->id,
            'enrolled_at' => now()
        ]);
    }

    return redirect()->route('courses.show', $course);
}
}
