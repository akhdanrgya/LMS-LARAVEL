<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\Course; // Untuk mengambil data course parent
use App\Models\Quiz;
use Illuminate\Http\Request; // Nanti bisa ganti dengan FormRequest
use Illuminate\Support\Facades\Auth;

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
        $quizzes = $course->quizzes()->latest()->paginate(10); // Ambil quiz milik course ini
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
    public function store(Request $request, Course $course) // Nanti ganti Request dengan StoreQuizRequest
    {
        $this->authorizeMentor($course);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration_minutes' => 'nullable|integer|min:1',
            // 'material_id' => 'nullable|exists:materials,id', // Jika quiz bisa dikaitkan ke materi tertentu
        ]);

        $quiz = $course->quizzes()->create([
            'title' => $request->title,
            'description' => $request->description,
            'duration_minutes' => $request->duration_minutes,
            // 'material_id' => $request->material_id, // Jika ada
        ]);

        // Setelah quiz dibuat, biasanya redirect ke halaman untuk nambah pertanyaan di quiz itu
        // atau ke daftar quiz course ini. Untuk sekarang, ke daftar quiz dulu.
        return redirect()->route('mentor.courses.quizzes.index', $course->slug)
                         ->with('success', 'Quiz "' . $quiz->title . '" berhasil dibuat! Sekarang tambahkan pertanyaan.');
    }

    // Method show(Course $course, Quiz $quiz), edit(...), update(...), destroy(...) akan kita detailkan nanti
    // ...
}