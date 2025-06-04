<?php

namespace App\Http\Requests\Mentor;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use App\Models\Quiz;
use App\Models\Question;
use Illuminate\Validation\Rule;

class UpdateQuestionRequest extends FormRequest
{
    public function authorize(): bool
    {
        $course = $this->route('course');
        $quiz = $this->route('quiz');
        $question = $this->route('question'); // Ambil question dari route

        return Auth::check() && Auth::user()->role === 'mentor' &&
               $course instanceof Course && 
               $quiz instanceof Quiz &&
               $question instanceof Question &&
               $course->mentor_id == Auth::id() &&
               $quiz->course_id == $course->id &&
               $question->quiz_id == $quiz->id; // Pastikan question ini milik quiz tersebut
    }

    public function rules(): array
    {
        $rules = [
            'question_text' => 'required|string',
            'question_type' => ['required', Rule::in(['multiple_choice', 'single_choice', 'essay'])],
            'points' => 'required|integer|min:1',
            'options' => 'nullable|array',
        ];

        // Validasi options sama seperti di StoreQuestionRequest
        if (in_array($this->input('question_type'), ['multiple_choice', 'single_choice'])) {
            $rules['options'] = 'required|array|min:2';
            $rules['options.*.text'] = 'required|string|max:255';
            $rules['options.*.is_correct'] = 'nullable|boolean';
        }
        return $rules;
    }

    public function messages(): array
    {
        return [
            'options.required' => 'Pilihan jawaban wajib diisi untuk tipe soal ini.',
            'options.min' => 'Minimal harus ada :min pilihan jawaban.',
            'options.*.text.required' => 'Teks pilihan jawaban tidak boleh kosong.',
        ];
    }
    
    public function withValidator($validator)
    {
        // Validasi custom buat pastikan ada jawaban benar, sama seperti di StoreQuestionRequest
        $validator->after(function ($validator) {
            if (in_array($this->input('question_type'), ['multiple_choice', 'single_choice'])) {
                $options = $this->input('options', []);
                $correctAnswersCount = 0;
                $hasEmptyOptionText = false;

                foreach ($options as $option) {
                    if (empty($option['text'])) {
                        $hasEmptyOptionText = true;
                    }
                    if (!empty($option['is_correct'])) {
                        $correctAnswersCount++;
                    }
                }
                
                if ($hasEmptyOptionText && count($options) > 0) {
                     $validator->errors()->add('options', 'Teks pilihan jawaban tidak boleh ada yang kosong.');
                }

                if ($correctAnswersCount === 0 && count($options) > 0 && !$hasEmptyOptionText) {
                    $validator->errors()->add('options', 'Minimal harus ada satu jawaban yang benar untuk tipe soal ini.');
                }

                if ($this->input('question_type') === 'single_choice' && $correctAnswersCount > 1) {
                    $validator->errors()->add('options', 'Untuk tipe soal Pilihan Tunggal, hanya boleh ada satu jawaban benar.');
                }
            }
        });
    }
}