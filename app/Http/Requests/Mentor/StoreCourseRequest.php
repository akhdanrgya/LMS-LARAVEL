<?php

namespace App\Http\Requests\Mentor;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->role === 'mentor'; // Hanya mentor yang boleh
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255|unique:courses,title',
            'description' => 'required|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048', // Tambahin webp kalo mau
        ];
    }
}