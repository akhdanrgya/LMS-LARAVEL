<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\AnswerOption;
use App\Models\StudentQuizAttempt;
use App\Models\StudentAnswer;
use App\Models\Enrollment; // Untuk cek enrollment
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // Untuk transaction jika perlu

class QuizAttemptController extends Controller
{
    /**
     * Memulai percobaan quiz oleh student.
     * Membuat record StudentQuizAttempt dan menampilkan halaman pengerjaan quiz.
     */
    public function startAttempt(Course $course, Quiz $quiz)
    {
        $student = Auth::user();

        // 1. Cek apakah student terdaftar di course ini
        $isEnrolled = Enrollment::where('student_id', $student->id)
                                ->where('course_id', $course->id)
                                ->exists();
        if (!$isEnrolled) {
            return redirect()->route('courses.show', $course->slug)
                             ->with('error', 'Anda harus terdaftar di course ini untuk mengerjakan quiz.');
        }

        // 2. Cek apakah student sudah pernah submit quiz ini (jika ada batasan percobaan)
        // Untuk sekarang, kita izinkan multiple attempts, tapi bisa ditambah logic di sini.
        // Misalnya, cek apakah ada attempt sebelumnya yang belum selesai, atau batasi jumlah attempt.

        // 3. Buat record percobaan quiz baru
        $attempt = StudentQuizAttempt::create([
            'student_id' => $student->id,
            'quiz_id' => $quiz->id,
            'started_at' => now(),
            // score dan submitted_at diisi nanti pas submit
        ]);

        // 4. Ambil semua pertanyaan dan pilihan jawabannya untuk quiz ini
        $questions = $quiz->questions()->with('answerOptions')->get(); // Eager load pilihan jawaban

        return view('student.quizzes.attempt', compact('course', 'quiz', 'attempt', 'questions'));
    }

    /**
     * Menyimpan jawaban student dan menyelesaikan percobaan quiz.
     */
    public function submitAttempt(Request $request, Course $course, Quiz $quiz, StudentQuizAttempt $attempt)
    {
        $student = Auth::user();

        if ($attempt->student_id !== $student->id || $attempt->quiz_id !== $quiz->id || $attempt->submitted_at !== null) {
            return redirect()->route('student.dashboard')->with('error', 'Percobaan quiz tidak valid atau sudah disubmit.');
        }

        $submittedAnswers = $request->input('answers', []);
        $totalScoreFromThisQuiz = 0; // Ganti nama variabel biar lebih jelas
        $totalPointsPossible = 0;

        DB::beginTransaction();
        try {
            foreach ($quiz->questions as $question) {
                $totalPointsPossible += $question->points;
                $studentAnswerData = [
                    'student_quiz_attempt_id' => $attempt->id,
                    'question_id' => $question->id,
                    'is_correct' => null, // Default null, diisi untuk PG/Single
                    'points_awarded' => 0, // Default 0
                ]; // Sama seperti sebelumnya

                if (isset($submittedAnswers[$question->id])) {
                    $answerValue = $submittedAnswers[$question->id];
                    // ... (logika scoring single_choice, multiple_choice, essay sama seperti sebelumnya) ...
                    // Pastikan $totalScoreFromThisQuiz diakumulasi dengan benar di sini
                    // Contoh untuk single_choice:
                    if ($question->question_type === 'single_choice') {
                        $studentAnswerData['answer_option_id'] = $answerValue;
                        $chosenOption = AnswerOption::find($answerValue); // Pastikan AnswerOption di-import atau pake FQCN
                        if ($chosenOption && $chosenOption->is_correct) {
                            $studentAnswerData['is_correct'] = true;
                            $studentAnswerData['points_awarded'] = $question->points;
                            $totalScoreFromThisQuiz += $question->points; // Akumulasi skor dari quiz ini
                        } else {
                            $studentAnswerData['is_correct'] = false;
                        }
                    } 
                    // Lakukan hal serupa untuk multiple_choice
                    elseif ($question->question_type === 'multiple_choice') {
                        $correctOptions = $question->answerOptions()->where('is_correct', true)->pluck('id')->toArray();
                        $chosenOptionIds = is_array($answerValue) ? $answerValue : [];
                        sort($correctOptions);
                        sort($chosenOptionIds);
                        if (empty(array_diff($correctOptions, $chosenOptionIds)) && empty(array_diff($chosenOptionIds, $correctOptions))) {
                            $studentAnswerData['is_correct'] = true;
                            $studentAnswerData['points_awarded'] = $question->points;
                            $totalScoreFromThisQuiz += $question->points;
                            $studentAnswerData['answer_text'] = json_encode($chosenOptionIds); 
                        } else {
                            $studentAnswerData['is_correct'] = false;
                            $studentAnswerData['answer_text'] = json_encode($chosenOptionIds);
                        }
                    }
                    // ... (akhir logika scoring) ...
                }
                StudentAnswer::create($studentAnswerData);
            }

            // Update attempt dengan skor dan waktu submit
            $attempt->score = $totalScoreFromThisQuiz;
            $attempt->submitted_at = now();
            $attempt->save();

            // --- BAGIAN BARU: UPDATE TOTAL_SCORE DI STUDENT_PROFILE ---
            $studentProfile = $student->studentProfile; // Ambil profil student lewat relasi
            
            if ($studentProfile) {
                // Tambahkan skor dari quiz ini ke total_score yang sudah ada
                $studentProfile->total_score += $totalScoreFromThisQuiz;
                // Di sini lo bisa tambahin logic buat update level student berdasarkan total_score baru
                // $studentProfile->level = hitungLevelBaru($studentProfile->total_score);
                $studentProfile->save();
            } else {
                // Kalo student belum punya profil (seharusnya udah dibuat pas register),
                // Lo bisa bikin profil baru di sini atau log error.
                // Untuk sekarang, kita asumsikan profil udah ada.
                // StudentProfile::create([
                //     'student_id' => $student->id,
                //     'total_score' => $totalScoreFromThisQuiz,
                //     // 'level' => hitungLevelBaru($totalScoreFromThisQuiz),
                // ]);
                \Illuminate\Support\Facades\Log::warning('Student profile not found for user ID: ' . $student->id . ' during quiz submission.');
            }
            // --- AKHIR BAGIAN BARU ---

            DB::commit();

            return redirect()->route('student.quiz.attempt.result', $attempt->id)
                ->with('success', 'Quiz "' . $quiz->title . '" berhasil disubmit!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Error submitting quiz for attempt ID ' . $attempt->id . ': ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat submit quiz. Silakan coba lagi.');
        }
    }

    public function showResult(StudentQuizAttempt $attempt) // Route Model Binding untuk $attempt
    {
        $student = Auth::user();

        // 1. Otorisasi: Pastikan attempt ini milik student yang login
        // dan sudah disubmit.
        if ($attempt->student_id !== $student->id || $attempt->submitted_at === null) {
            return redirect()->route('student.dashboard')->with('error', 'Hasil quiz tidak ditemukan atau belum selesai dikerjakan.');
        }

        // 2. Eager load relasi yang dibutuhkan untuk nampilin detail
        $attempt->load([
            'quiz.course', // Ambil quiz dan course-nya
            'quiz.questions.answerOptions', // Ambil pertanyaan quiz & semua pilihan jawabannya
            'answers.question.answerOptions', // Ambil jawaban student, pertanyaan terkait, & semua pilihan jawabannya
        ]);
        
        // Atau bisa juga:
        // $quiz = $attempt->quiz->load(['questions.answerOptions', 'course']);
        // $studentAnswers = $attempt->answers()->with(['question.answerOptions'])->get()->keyBy('question_id');

        return view('student.quizzes.result', compact('attempt'));
    }
}