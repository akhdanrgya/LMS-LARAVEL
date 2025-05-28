<?php

namespace App\Http\Requests\Mentor;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;

class StoreQuizRequest extends FormRequest
{
    public function authorize(): bool
    {
        $course = $this->route('course');
        return Auth::check() && Auth::user()->role === 'mentor' && $course && $course->mentor_id == Auth::id();
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration_minutes' => 'nullable|integer|min:1',
            // 'material_id' => 'nullable|exists:materials,id', // Jika quiz bisa dikaitkan ke materi tertentu
        ];
    }
}