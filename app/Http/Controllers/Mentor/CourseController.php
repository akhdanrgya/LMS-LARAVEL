<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str; // Buat bikin slug
use Illuminate\Support\Facades\Storage; // Buat handle file

class CourseController extends Controller
{
    // Kita akan pasang middleware di Routes nanti, biar lebih terpusat.

    /**
     * Menampilkan daftar course milik mentor yang login.
     */
    public function index()
    {
        $mentor = Auth::user();
        $courses = $mentor->taughtCourses()->latest()->paginate(10); // Menggunakan relasi dari User model

        // Nanti, view ini akan menampilkan tabel berisi daftar course mentor
        // dengan tombol edit, hapus, dan mungkin link ke materi/quiz
        return view('mentor.courses.index', compact('courses'));
    }

    /**
     * Menampilkan form untuk membuat course baru.
     */
    public function create()
    {
        // View ini berisi form dengan field: title, description, thumbnail (opsional)
        return view('mentor.courses.create');
    }

    /**
     * Menyimpan course baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255|unique:courses,title', // Pastikan title unik
            'description' => 'required|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi file gambar
        ]);

        $mentor = Auth::user();
        $thumbnailPath = null;

        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('course_thumbnails', 'public');
            // 'course_thumbnails' adalah folder di dalam storage/app/public/
            // Pastikan sudah menjalankan `php artisan storage:link`
        }

        $mentor->taughtCourses()->create([ // Menggunakan relasi untuk create
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . uniqid(), // Atau cara lain untuk slug unik
            'description' => $request->description,
            'thumbnail_path' => $thumbnailPath,
        ]);

        return redirect()->route('mentor.courses.index')
                         ->with('success', 'Course baru berhasil dibuat!');
    }

    /**
     * Menampilkan form untuk mengedit course.
     * Course $course otomatis di-inject (Route Model Binding).
     */
    public function edit(Course $course)
    {
        // Authorization: Pastikan mentor hanya bisa edit course miliknya
        // Ini bisa pake Policy atau cek manual
        if ($course->mentor_id !== Auth::id()) {
            abort(403, 'Anda tidak diizinkan mengedit course ini.');
        }

        // View ini mirip form create, tapi field-nya udah keisi data course yang mau diedit
        return view('mentor.courses.edit', compact('course'));
    }

    /**
     * Update course yang ada di database.
     */
    public function update(Request $request, Course $course)
    {
        // Authorization
        if ($course->mentor_id !== Auth::id()) {
            abort(403, 'Anda tidak diizinkan mengupdate course ini.');
        }

        $request->validate([
            'title' => ['required', 'string', 'max:255', Rule::unique('courses')->ignore($course->id)], // Unik kecuali untuk dirinya sendiri
            'description' => 'required|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $updateData = [
            'title' => $request->title,
            'description' => $request->description,
        ];

        // Update slug jika title berubah
        if ($course->title !== $request->title) {
            $updateData['slug'] = Str::slug($request->title) . '-' . $course->id; // Pastikan unik
        }

        if ($request->hasFile('thumbnail')) {
            // Hapus thumbnail lama jika ada
            if ($course->thumbnail_path) {
                Storage::disk('public')->delete($course->thumbnail_path);
            }
            // Simpan thumbnail baru
            $updateData['thumbnail_path'] = $request->file('thumbnail')->store('course_thumbnails', 'public');
        }

        $course->update($updateData);

        return redirect()->route('mentor.courses.index')
                         ->with('success', 'Course berhasil diupdate!');
    }

    /**
     * Menghapus course dari database.
     */
    public function destroy(Course $course)
    {
        // Authorization
        if ($course->mentor_id !== Auth::id()) {
            abort(403, 'Anda tidak diizinkan menghapus course ini.');
        }

        // Hapus thumbnail jika ada
        if ($course->thumbnail_path) {
            Storage::disk('public')->delete($course->thumbnail_path);
        }

        // Hapus juga relasi lain yang terkait (materi, quiz, enrollment)
        // Jika onDelete('cascade') sudah di-set di migrasi untuk relasi-relasi ini,
        // maka akan otomatis terhapus. Jika tidak, perlu dihapus manual di sini.
        // Contoh: $course->materials()->delete(); $course->quizzes()->delete(); dll.
        // Di migrasi kita, materials dan quizzes sudah cascade. Enrollments juga.

        $course->delete();

        return redirect()->route('mentor.courses.index')
                         ->with('success', 'Course berhasil dihapus!');
    }
}