<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\AnswerOption;
use Illuminate\Support\Facades\Auth;
// Ganti Request dengan FormRequest yang sesuai
use App\Http\Requests\Mentor\StoreQuestionRequest;
use App\Http\Requests\Mentor\UpdateQuestionRequest; // <--- TAMBAH INI
use Illuminate\Support\Str; // <--- INI YANG BENER

class QuestionController extends Controller
{
    private function authorizeMentor(Course $course, Quiz $quiz = null, Question $question = null)
    {
        if ($course->mentor_id !== Auth::id()) {
            abort(403, 'ANDA TIDAK BERHAK MENGELOLA KONTEN UNTUK COURSE INI.');
        }
        if ($quiz && $quiz->course_id !== $course->id) {
            abort(404, 'Quiz tidak ditemukan pada course ini.');
        }
        if ($question && $question->quiz_id !== $quiz->id) {
            abort(404, 'Pertanyaan tidak ditemukan pada quiz ini.');
        }
    }

    public function index(Course $course, Quiz $quiz)
    {
        $this->authorizeMentor($course, $quiz);
        $questions = $quiz->questions()->with('answerOptions')->orderBy('id', 'asc')->get();
        return view('mentor.questions.index', compact('course', 'quiz', 'questions'));
    }

    // create() method bisa di-skip jika form ada di index

    public function store(StoreQuestionRequest $request, Course $course, Quiz $quiz)
    {
        // Otorisasi sudah di FormRequest
        $validatedData = $request->validated();

        $question = $quiz->questions()->create([
            'question_text' => $validatedData['question_text'],
            'question_type' => $validatedData['question_type'],
            'points' => $validatedData['points'],
        ]);

        if (in_array($question->question_type, ['multiple_choice', 'single_choice']) && isset($validatedData['options'])) {
            foreach ($validatedData['options'] as $optionData) {
                if (!empty($optionData['text'])) {
                    $question->answerOptions()->create([
                        'option_text' => $optionData['text'],
                        'is_correct' => isset($optionData['is_correct']) ? (bool)$optionData['is_correct'] : false,
                    ]);
                }
            }
        }
        return redirect()->route('mentor.courses.quizzes.questions.index', [$course->slug, $quiz->id])
                         ->with('success', 'Pertanyaan berhasil ditambahkan!');
    }

    /**
     * Menampilkan form untuk mengedit pertanyaan.
     */
    public function edit(Course $course, Quiz $quiz, Question $question)
    {
        $this->authorizeMentor($course, $quiz, $question); // Otorisasi lengkap
        
        // Eager load answer options untuk dikirim ke view
        $question->load('answerOptions'); 
        
        // View 'mentor.questions.edit' akan berisi form mirip create tapi sudah terisi
        return view('mentor.questions.edit', compact('course', 'quiz', 'question'));
    }

    /**
     * Update pertanyaan yang ada di database.
     */
    public function update(UpdateQuestionRequest $request, Course $course, Quiz $quiz, Question $question)
    {
        // Otorisasi sudah dihandle oleh UpdateQuestionRequest->authorize()
        $validatedData = $request->validated();

        $question->update([
            'question_text' => $validatedData['question_text'],
            'question_type' => $validatedData['question_type'],
            'points' => $validatedData['points'],
        ]);

        // Update Answer Options: hapus yang lama, buat yang baru. Ini cara paling simpel.
        // Cara yang lebih advance bisa update yang ada, hapus yang gak ada, tambah yang baru.
        if (in_array($question->question_type, ['multiple_choice', 'single_choice'])) {
            $question->answerOptions()->delete(); // Hapus semua pilihan jawaban lama
            if (isset($validatedData['options'])) {
                foreach ($validatedData['options'] as $optionData) {
                    if (!empty($optionData['text'])) {
                        $question->answerOptions()->create([
                            'option_text' => $optionData['text'],
                            'is_correct' => isset($optionData['is_correct']) ? (bool)$optionData['is_correct'] : false,
                        ]);
                    }
                }
            }
        } elseif ($question->question_type === 'essay') {
            // Jika tipe diubah jadi essay, hapus semua pilihan jawaban yang mungkin ada sebelumnya
            $question->answerOptions()->delete();
        }


        return redirect()->route('mentor.courses.quizzes.questions.index', [$course->slug, $quiz->id])
                         ->with('success', 'Pertanyaan berhasil diupdate.');
    }

    /**
     * Menghapus pertanyaan dari database.
     */
    public function destroy(Course $course, Quiz $quiz, Question $question)
    {
        $this->authorizeMentor($course, $quiz, $question); // Otorisasi lengkap

        // Menghapus pertanyaan.
        // Pilihan jawaban (answerOptions) akan otomatis terhapus jika foreign key
        // di tabel 'answer_options' ke 'questions' sudah di-set onDelete('cascade').
        // (Di migrasi kita, ini sudah di-set cascade, jadi aman!)
        $questionTitle = Str::limit($question->question_text, 30); // Ambil potongan teks buat pesan
        $question->delete();

        return redirect()->route('mentor.courses.quizzes.questions.index', [$course->slug, $quiz->id])
                         ->with('success', 'Pertanyaan ("' . $questionTitle . '...") berhasil dihapus.');
    }
}