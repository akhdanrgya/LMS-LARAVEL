<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\Course;      // Untuk mengambil data course parent
use App\Models\Material;
use Illuminate\Http\Request; // Nanti kita bisa ganti dengan FormRequest
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // Untuk handle file upload

class MaterialController extends Controller
{
    /**
     * Middleware untuk memastikan hanya mentor pemilik course yang bisa akses.
     * Bisa juga diterapkan di level route group atau dengan Policies.
     */
    private function authorizeMentor(Course $course)
    {
        if ($course->mentor_id !== Auth::id()) {
            abort(403, 'ANDA TIDAK BERHAK MENGAKSES MATERI UNTUK COURSE INI.');
        }
    }

    /**
     * Menampilkan daftar materi untuk sebuah course.
     */
    public function index(Course $course) // Course otomatis di-inject dari route
    {
        $this->authorizeMentor($course);

        $materials = $course->materials()->orderBy('order_sequence')->paginate(15);
        return view('mentor.materials.index', compact('course', 'materials'));
    }

    /**
     * Menampilkan form untuk membuat materi baru di sebuah course.
     */
    public function create(Course $course)
    {
        $this->authorizeMentor($course);
        // Kirim data course ke view biar tau materi ini buat course mana
        return view('mentor.materials.create', compact('course'));
    }

    /**
     * Menyimpan materi baru ke database.
     */
    public function store(Request $request, Course $course) // Nanti ganti Request dengan StoreMaterialRequest
    {
        $this->authorizeMentor($course);

        $request->validate([
            'title' => 'required|string|max:255',
            'content_type' => 'required|in:text,video_link,file_path',
            'content_text' => 'required_if:content_type,text|nullable|string',
            'content_video_link' => 'required_if:content_type,video_link|nullable|url',
            'content_file' => 'required_if:content_type,file_path|nullable|file|mimes:pdf,doc,docx,ppt,pptx,zip,jpg,png|max:10240', // Max 10MB
            'order_sequence' => 'nullable|integer',
        ]);

        $contentData = null;
        $filePath = null;

        if ($request->content_type === 'text') {
            $contentData = $request->content_text;
        } elseif ($request->content_type === 'video_link') {
            $contentData = $request->content_video_link;
        } elseif ($request->content_type === 'file_path' && $request->hasFile('content_file')) {
            // Simpan file ke storage/app/public/course_materials/{course_id}/namafile.ext
            $filePath = $request->file('content_file')->store('course_materials/' . $course->id, 'public');
            $contentData = $filePath; // Simpan path filenya di kolom content
        }

        if ($contentData === null && $request->content_type !== 'file_path') { // Validasi tambahan jika content kosong padahal bukan file upload
             return back()->withInput()->with('error', 'Konten tidak boleh kosong untuk tipe yang dipilih.');
        }
        
        $order = $request->order_sequence ?? ($course->materials()->count() + 1);

        $course->materials()->create([
            'title' => $request->title,
            'content_type' => $request->content_type,
            'content' => $contentData,
            'order_sequence' => $order,
        ]);

        return redirect()->route('mentor.courses.materials.index', $course->slug)
                         ->with('success', 'Materi baru berhasil ditambahkan!');
    }

    // Method show(Course $course, Material $material), edit(...), update(...), destroy(...) akan kita detailkan nanti
    // ...
}