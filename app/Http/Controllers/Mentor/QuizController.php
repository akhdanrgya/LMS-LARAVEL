<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Quiz;
use Illuminate\Support\Facades\Auth;
// Import Form Requests
use App\Http\Requests\Mentor\StoreQuizRequest;
use App\Http\Requests\Mentor\UpdateQuizRequest;

class QuizController extends Controller
{
    /**
     * Helper untuk otorisasi mentor pemilik course.
     */
    private function authorizeMentor(Course $course)
    {
        if ($course->mentor_id !== Auth::id()) {
            abort(403, 'ANDA TIDAK BERHAK MENGELOLA QUIZ UNTUK COURSE INI.');
        }
    }

    /**
     * Menampilkan daftar quiz untuk sebuah course.
     */
    public function index(Course $course)
    {
        $this->authorizeMentor($course);
        $quizzes = $course->quizzes()->withCount('questions')->latest()->paginate(10); // Tambah withCount buat jumlah soal
        return view('mentor.quizzes.index', compact('course', 'quizzes'));
    }

    /**
     * Menampilkan form untuk membuat quiz baru di sebuah course.
     */
    public function create(Course $course)
    {
        $this->authorizeMentor($course);
        return view('mentor.quizzes.create', compact('course'));
    }

    /**
     * Menyimpan quiz baru ke database.
     */
    public function store(StoreQuizRequest $request, Course $course)
    {
        // Otorisasi sudah dihandle oleh StoreQuizRequest->authorize()
        $validatedData = $request->validated();
        $quiz = $course->quizzes()->create($validatedData);

        return redirect()->route('mentor.courses.quizzes.index', $course->slug)
                         ->with('success', 'Quiz "' . $quiz->title . '" berhasil dibuat! Selanjutnya, tambahkan pertanyaan untuk quiz ini.');
    }

    /**
     * Menampilkan form untuk mengedit quiz.
     */
    public function edit(Course $course, Quiz $quiz)
    {
        $this->authorizeMentor($course);
        if ($quiz->course_id !== $course->id) {
            abort(404, 'Quiz tidak ditemukan pada course ini.');
        }
        return view('mentor.quizzes.edit', compact('course', 'quiz'));
    }

    /**
     * Update quiz yang ada di database.
     */
    public function update(UpdateQuizRequest $request, Course $course, Quiz $quiz)
    {
        // Otorisasi sudah dihandle oleh UpdateQuizRequest->authorize()
        $validatedData = $request->validated();
        $quiz->update($validatedData);

        return redirect()->route('mentor.courses.quizzes.index', $course->slug)
                         ->with('success', 'Quiz "' . $quiz->title . '" berhasil diupdate.');
    }

    /**
     * Menghapus quiz dari database.
     */
    public function destroy(Course $course, Quiz $quiz)
    {
        // Otorisasi dasar
        $this->authorizeMentor($course);
        if ($quiz->course_id !== $course->id) {
            abort(404, 'Quiz tidak ditemukan pada course ini untuk dihapus.');
        }

        $quizTitle = $quiz->title;

        // Menghapus quiz.
        // Pertanyaan (questions) dan percobaan quiz oleh student (student_quiz_attempts)
        // akan otomatis terhapus jika foreign key di tabel-tabel tersebut
        // ke 'quizzes' sudah di-set onDelete('cascade') pada saat migrasi.
        // (Di migrasi kita, ini sudah di-set cascade, jadi aman!)
        $quiz->delete();

        return redirect()->route('mentor.courses.quizzes.index', $course->slug)
                         ->with('success', 'Quiz "' . $quizTitle . '" beserta semua pertanyaannya berhasil dihapus.');
    }
}