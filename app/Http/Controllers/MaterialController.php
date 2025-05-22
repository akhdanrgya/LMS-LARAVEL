<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Course;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    // Tampil semua materi buat course tertentu
    public function index($courseId)
    {
        $course = Course::findOrFail($courseId);
        $materials = $course->materials()->select('id', 'course_id', 'content', 'created_at', 'updated_at')->get();
        return response()->json($materials);
    }

    // Form buat tambah materi baru
    public function create(Course $course)
    {
        return view('materials.create', compact('course'));
    }

    // Simpan materi baru
    public function store(Request $request, Course $course)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        $material = new Material();
        $material->content = $request->content;
        $material->course_id = $course->id;
        $material->save();

        return redirect()->route('courses.show', $course->id)->with('success', 'Material added successfully.');
    }

    // Tampil materi tertentu
    public function show($courseId, $materialId)
    {
        $course = Course::findOrFail($courseId);
        $material = $course->materials()->select('id', 'course_id', 'content', 'created_at', 'updated_at')->findOrFail($materialId);
        return response()->json($material);
    }

    // Update materi tertentu
    public function update(Request $request, $courseId, $materialId)
    {
        $validated = $request->validate([
            'content' => 'required|string',
        ]);

        $course = Course::findOrFail($courseId);
        $material = $course->materials()->findOrFail($materialId);
        $material->update($validated);

        return response()->json($material);
    }

    // Hapus materi
    public function destroy($courseId, $materialId)
    {
        $course = Course::findOrFail($courseId);
        $material = $course->materials()->findOrFail($materialId);
        $material->delete();

        return response()->json(['message' => 'Material deleted successfully']);
    }
}
