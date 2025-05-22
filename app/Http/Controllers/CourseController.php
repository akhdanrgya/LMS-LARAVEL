<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    // Tampilkan semua course (API)
    public function index()
    {
        $courses = Course::select('id', 'name', 'description', 'cover_photo', 'created_at', 'updated_at')->get();
        return response()->json($courses);
    }

    // Tampilkan detail course berdasarkan model binding
    public function show(Course $course)
    {
        return response()->json($course->only(['id', 'name', 'description', 'cover_photo', 'created_at', 'updated_at']));
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
        $course->mentor_id = auth()->id();
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
}
