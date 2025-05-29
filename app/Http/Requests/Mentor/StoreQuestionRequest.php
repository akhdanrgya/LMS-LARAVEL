<?php

namespace App\Http\Requests\Mentor;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use App\Models\Quiz;
use Illuminate\Validation\Rule;

class StoreQuestionRequest extends FormRequest
{
    public function authorize(): bool
    {
        $course = $this->route('course');
        $quiz = $this->route('quiz');
        return Auth::check() && Auth::user()->role === 'mentor' &&
               $course instanceof Course && $quiz instanceof Quiz &&
               $course->mentor_id == Auth::id() &&
               $quiz->course_id == $course->id;
    }

    public function rules(): array
    {
        $rules = [
            'question_text' => 'required|string',
            'question_type' => ['required', Rule::in(['multiple_choice', 'single_choice', 'essay'])], // <-- ada array di sini
            'points' => 'required|integer|min:1',
            'options' => 'nullable|array', 
        ];
    
        if (in_array($this->input('question_type'), ['multiple_choice', 'single_choice'])) {
            $rules['options'] = 'required|array|min:2'; // <-- array lagi
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

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (in_array($this->input('question_type'), ['multiple_choice', 'single_choice'])) {
                $options = $this->input('options', []);
                $correctAnswersCount = 0;
                $hasEmptyOptionText = false;

                foreach ($options as $option) {
                    if (empty($option['text'])) {
                        $hasEmptyOptionText = true; // Tidak boleh ada option text kosong
                    }
                    if (!empty($option['is_correct'])) {
                        $correctAnswersCount++;
                    }
                }
                
                if ($hasEmptyOptionText && count($options) > 0) { // Hanya error jika ada options tapi teksnya kosong
                     $validator->errors()->add('options', 'Teks pilihan jawaban tidak boleh ada yang kosong.');
                }

                if ($correctAnswersCount === 0 && count($options) > 0 && !$hasEmptyOptionText) { // Hanya error jika ada opsi valid tapi tak ada yg benar
                    $validator->errors()->add('options', 'Minimal harus ada satu jawaban yang benar untuk tipe soal ini.');
                }

                if ($this->input('question_type') === 'single_choice' && $correctAnswersCount > 1) {
                    $validator->errors()->add('options', 'Untuk tipe soal Pilihan Tunggal, hanya boleh ada satu jawaban benar.');
                }
            }
        });
    }
}