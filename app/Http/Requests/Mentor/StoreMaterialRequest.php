<?php

namespace App\Http\Requests\Mentor;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Course; // Import Course

class StoreMaterialRequest extends FormRequest
{
    public function authorize(): bool
    {
        $course = $this->route('course'); // Ambil Course dari route parameter
        // Pastikan user adalah mentor dan pemilik course
        return Auth::check() && Auth::user()->role === 'mentor' && $course && $course->mentor_id == Auth::id();
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'content_type' => 'required|in:text,video_link,file_path',
            'content_text' => 'required_if:content_type,text|nullable|string',
            'content_video_link' => 'required_if:content_type,video_link|nullable|url',
            'content_file' => 'required_if:content_type,file_path|nullable|file|mimes:pdf,doc,docx,ppt,pptx,zip,txt,jpg,jpeg,png,mp4,mov|max:20480', // Max 20MB, tambahin tipe file
            'order_sequence' => 'nullable|integer|min:0',
        ];
    }

    public function messages(): array // Pesan error custom (opsional)
    {
        return [
            'content_text.required_if' => 'Kolom konten teks wajib diisi jika tipe konten adalah Teks.',
            'content_video_link.required_if' => 'Kolom link video wajib diisi jika tipe konten adalah Link Video.',
            'content_file.required_if' => 'File wajib diunggah jika tipe konten adalah File.',
        ];
    }
}