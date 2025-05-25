<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User; // Untuk dropdown pilih mentor jika diperlukan
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CourseManagementController extends Controller
{
    /**
     * Menampilkan daftar semua course di sistem untuk Admin.
     */
    public function index(Request $request)
    {
        // Ambil semua course, bisa ditambah filter atau search nantinya
        $query = Course::with('mentor')->latest(); // Eager load mentor

        // Contoh filter sederhana (bisa dikembangin)
        if ($request->has('search') && $request->search != '') {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhereHas('mentor', function($q) use ($request) {
                      $q->where('name', 'like', '%' . $request->search . '%');
                  });
        }

        $courses = $query->paginate(15);
        
        return view('admin.courses.index', compact('courses'));
    }

    /**
     * Menampilkan form untuk Admin mengedit detail course.
     */
    public function edit(Course $course) // Route Model Binding
    {
        $mentors = User::where('role', 'mentor')->orderBy('name')->get(); // Ambil daftar mentor buat dropdown
        return view('admin.courses.edit', compact('course', 'mentors'));
    }

    /**
     * Update detail course oleh Admin.
     */
    public function update(Request $request, Course $course)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255', Rule::unique('courses')->ignore($course->id)],
            'description' => ['required', 'string'],
            'mentor_id' => ['required', 'exists:users,id'], // Pastikan mentor_id valid dan ada di tabel users
            'thumbnail' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            // Tambahkan validasi untuk field lain jika ada (misal status: 'published', 'pending', 'rejected')
        ]);

        $updateData = [
            'title' => $request->title,
            'description' => $request->description,
            'mentor_id' => $request->mentor_id,
            // 'status' => $request->status, // Jika ada field status
        ];

        // Update slug jika title berubah
        if ($course->title !== $request->title) {
            $updateData['slug'] = Str::slug($request->title) . '-' . $course->id; // Pastikan unik
        }

        if ($request->hasFile('thumbnail')) {
            if ($course->thumbnail_path) {
                Storage::disk('public')->delete($course->thumbnail_path);
            }
            $updateData['thumbnail_path'] = $request->file('thumbnail')->store('course_thumbnails', 'public');
        }

        $course->update($updateData);

        return redirect()->route('admin.courses.index')
                         ->with('success', 'Course "' . $course->title . '" berhasil diupdate oleh Admin.');
    }

    /**
     * Menghapus course dari sistem oleh Admin.
     */
    public function destroy(Course $course)
    {
        $courseTitle = $course->title;

        if ($course->thumbnail_path) {
            Storage::disk('public')->delete($course->thumbnail_path);
        }
        
        // Relasi (materials, quizzes, enrollments, ratings) akan terhapus otomatis jika
        // foreign key di tabel-tabel tersebut ke 'courses' di-set onDelete('cascade')
        $course->delete();

        return redirect()->route('admin.courses.index')
                         ->with('success', 'Course "' . $courseTitle . '" berhasil dihapus dari sistem.');
    }

    // Opsional: Method buat ganti status course (misal publish/unpublish)
    // public function toggleStatus(Course $course, $newStatus)
    // {
    //     $course->update(['status' => $newStatus]); // Asumsi ada kolom 'status' di tabel 'courses'
    //     return redirect()->back()->with('success', 'Status course berhasil diubah.');
    // }
}