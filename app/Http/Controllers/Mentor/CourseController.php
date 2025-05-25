<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request; // Hapus ini jika tidak ada Request biasa
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
// Import Form Requests:
use App\Http\Requests\Mentor\StoreCourseRequest;
use App\Http\Requests\Mentor\UpdateCourseRequest;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Auth::user()->taughtCourses()->latest()->paginate(10);
        return view('mentor.courses.index', compact('courses'));
    }

    public function create()
    {
        return view('mentor.courses.create');
    }

    public function store(StoreCourseRequest $request) // Ganti Request jadi StoreCourseRequest
    {
        $validatedData = $request->validated(); // Ambil data yang sudah divalidasi
        $thumbnailPath = null;

        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('course_thumbnails', 'public');
        }

        Auth::user()->taughtCourses()->create([
            'title' => $validatedData['title'],
            'slug' => Str::slug($validatedData['title']) . '-' . uniqid(),
            'description' => $validatedData['description'],
            'thumbnail_path' => $thumbnailPath,
        ]);

        return redirect()->route('mentor.courses.index')
                         ->with('success', 'Course baru berhasil dibuat!');
    }

    public function edit(Course $course) // Route model binding otomatis inject course
    {
        // Otorisasi tambahan, meskipun udah ada di UpdateCourseRequest authorize()
        if ($course->mentor_id !== Auth::id()) {
            abort(403, 'Anda tidak diizinkan mengedit course ini.');
        }
        return view('mentor.courses.edit', compact('course'));
    }

    public function update(UpdateCourseRequest $request, Course $course) // Ganti Request jadi UpdateCourseRequest
    {
        // Otorisasi sudah dihandle oleh UpdateCourseRequest->authorize()
        
        $validatedData = $request->validated();
        $updateData = [
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
        ];

        if ($course->title !== $validatedData['title']) {
            $updateData['slug'] = Str::slug($validatedData['title']) . '-' . $course->id;
        }

        if ($request->hasFile('thumbnail')) {
            if ($course->thumbnail_path) {
                Storage::disk('public')->delete($course->thumbnail_path);
            }
            $updateData['thumbnail_path'] = $request->file('thumbnail')->store('course_thumbnails', 'public');
        }

        $course->update($updateData);

        return redirect()->route('mentor.courses.index')
                         ->with('success', 'Course berhasil diupdate!');
    }

    public function destroy(Course $course)
    {
        if ($course->mentor_id !== Auth::id()) {
            abort(403, 'Anda tidak diizinkan menghapus course ini.');
        }

        if ($course->thumbnail_path) {
            Storage::disk('public')->delete($course->thumbnail_path);
        }
        // Materi, quiz, enrollment, rating akan terhapus otomatis jika relasi onDelete('cascade')
        $course->delete();

        return redirect()->route('mentor.courses.index')
                         ->with('success', 'Course berhasil dihapus!');
    }
}