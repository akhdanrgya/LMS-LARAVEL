<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\AnswerOption; // Untuk menyimpan pilihan jawaban
use Illuminate\Http\Request;  // Nanti kita ganti dengan StoreQuestionRequest
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule; // Untuk validasi tipe soal

class QuestionController extends Controller
{
    /**
     * Helper untuk otorisasi mentor pemilik course & quiz.
     */
    private function authorizeMentor(Course $course, Quiz $quiz = null)
    {
        if ($course->mentor_id !== Auth::id()) {
            abort(403, 'ANDA TIDAK BERHAK MENGELOLA KONTEN UNTUK COURSE INI.');
        }
        if ($quiz && $quiz->course_id !== $course->id) {
            abort(404, 'Quiz tidak ditemukan pada course ini.');
        }
    }

    /**
     * Menampilkan daftar pertanyaan untuk sebuah quiz DAN form untuk menambah pertanyaan baru.
     */
    public function index(Course $course, Quiz $quiz)
    {
        $this->authorizeMentor($course, $quiz);
        $questions = $quiz->questions()->with('answerOptions')->orderBy('id', 'asc')->get(); // Ambil juga pilihan jawabannya
        
        // View 'mentor.questions.index' akan berisi daftar soal dan form tambah soal
        return view('mentor.questions.index', compact('course', 'quiz', 'questions'));
    }

    /**
     * Method create() mungkin tidak kita pakai jika form ada di halaman index.
     * Tapi kita buat saja kerangkanya jika mau dipisah.
     */
    public function create(Course $course, Quiz $quiz)
    {
        $this->authorizeMentor($course, $quiz);
        // return view('mentor.questions.create', compact('course', 'quiz'));
        // Untuk sekarang, kita arahkan saja ke halaman index dimana form create berada
        return redirect()->route('mentor.courses.quizzes.questions.index', [$course->slug, $quiz->id]);
    }

    /**
     * Menyimpan pertanyaan baru ke database.
     */
    public function store(Request $request, Course $course, Quiz $quiz) // Ganti Request dengan StoreQuestionRequest nanti
    {
        $this->authorizeMentor($course, $quiz);

        $validatedData = $request->validate([
            'question_text' => 'required|string',
            'question_type' => ['required', Rule::in(['multiple_choice', 'single_choice', 'essay'])],
            'points' => 'required|integer|min:1',
            // Validasi untuk Answer Options (Pilihan Jawaban)
            'options' => 'required_if:question_type,multiple_choice,single_choice|array|min:2', // Minimal 2 pilihan jika PG/Single
            'options.*.text' => 'required_if:question_type,multiple_choice,single_choice|string|max:255',
            'options.*.is_correct' => 'nullable|boolean',
            // Pastikan minimal satu is_correct true untuk single_choice/multiple_choice
            // Ini validasi yang lebih kompleks, bisa di FormRequest atau custom validation rule
        ]);
        
        // Validasi tambahan: pastikan ada is_correct jika PG atau Single Choice
        if (in_array($validatedData['question_type'], ['multiple_choice', 'single_choice'])) {
            $correctAnswersCount = 0;
            foreach ($request->options ?? [] as $option) {
                if (isset($option['is_correct']) && $option['is_correct']) {
                    $correctAnswersCount++;
                }
            }
            if ($correctAnswersCount === 0) {
                return back()->withInput()->with('error', 'Untuk tipe soal pilihan, minimal harus ada satu jawaban yang benar.');
            }
            if ($validatedData['question_type'] === 'single_choice' && $correctAnswersCount > 1) {
                 return back()->withInput()->with('error', 'Untuk tipe soal Pilihan Tunggal, hanya boleh ada satu jawaban benar.');
            }
        }


        $question = $quiz->questions()->create([
            'question_text' => $validatedData['question_text'],
            'question_type' => $validatedData['question_type'],
            'points' => $validatedData['points'],
        ]);

        if (in_array($question->question_type, ['multiple_choice', 'single_choice']) && isset($validatedData['options'])) {
            foreach ($validatedData['options'] as $optionData) {
                if (!empty($optionData['text'])) { // Hanya simpan jika teks pilihan ada isinya
                    $question->answerOptions()->create([
                        'option_text' => $optionData['text'],
                        'is_correct' => isset($optionData['is_correct']) ? (bool)$optionData['is_correct'] : false,
                    ]);
                }
            }
        }

        return redirect()->route('mentor.courses.quizzes.questions.index', [$course->slug, $quiz->id])
                         ->with('success', 'Pertanyaan berhasil ditambahkan ke quiz!');
    }

    // Method edit, update, destroy akan kita detailkan nanti
    // ...
}