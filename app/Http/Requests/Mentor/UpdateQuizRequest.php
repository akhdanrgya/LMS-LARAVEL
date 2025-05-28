<?php

namespace App\Http\Requests\Mentor;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Course; // Untuk otorisasi
use App\Models\Quiz;   // Untuk otorisasi

class UpdateQuizRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Ambil course dan quiz dari parameter route
        $course = $this->route('course'); 
        $quiz = $this->route('quiz');     

        // Pastikan user adalah mentor, pemilik course, dan quiz ini milik course tersebut
        return Auth::check() && 
               Auth::user()->role === 'mentor' &&
               $course instanceof Course && // Pastikan $course adalah instance dari Course
               $quiz instanceof Quiz &&     // Pastikan $quiz adalah instance dari Quiz
               $course->mentor_id == Auth::id() &&
               $quiz->course_id == $course->id;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        // $quizId = $this->route('quiz')->id; // Ambil ID quiz yang sedang diedit

        return [
            'title' => [
                'required', 
                'string', 
                'max:255',
                // Rule::unique('quizzes', 'title')->ignore($quizId), // Uncomment jika judul quiz harus unik per sistem & diabaikan untuk diri sendiri
            ],
            'description' => 'nullable|string',
            'duration_minutes' => 'nullable|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            // 'title.unique' => 'Judul quiz ini sudah pernah ada.',
        ];
    }
}