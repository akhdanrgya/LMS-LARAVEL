<?php

namespace App\Http\Requests\Mentor;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Pastikan mentor hanya bisa update course miliknya ATAU admin (tapi ini khusus mentor)
        $course = $this->route('course'); // Ambil course dari route model binding
        return Auth::check() && Auth::user()->role === 'mentor' && $course->mentor_id == Auth::id();
    }

    public function rules(): array
    {
        $courseId = $this->route('course')->id; // Ambil ID course dari route
        return [
            'title' => ['required', 'string', 'max:255', Rule::unique('courses')->ignore($courseId)],
            'description' => 'required|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ];
    }
}