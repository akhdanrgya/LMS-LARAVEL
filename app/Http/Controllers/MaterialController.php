<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Course;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    /**
     * Menampilkan semua materi untuk course tertentu.
     *
     * @param  int  $courseId
     * @return \Illuminate\Http\Response
     */
    public function index($courseId)
    {
        $course = Course::findOrFail($courseId);
        $materials = $course->materials;
        return response()->json($materials);
    }

    /**
     * Menyimpan materi baru untuk course tertentu.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $courseId
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $courseId)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file_path' => 'nullable|file',
        ]);

        $course = Course::findOrFail($courseId);

        $material = $course->materials()->create($validated);

        return response()->json($material, 201);
    }

    /**
     * Menampilkan materi tertentu.
     *
     * @param  int  $courseId
     * @param  int  $materialId
     * @return \Illuminate\Http\Response
     */
    public function show($courseId, $materialId)
    {
        $course = Course::findOrFail($courseId);
        $material = $course->materials()->findOrFail($materialId);
        return response()->json($material);
    }

    /**
     * Update materi untuk course tertentu.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $courseId
     * @param  int  $materialId
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $courseId, $materialId)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file_path' => 'nullable|file',
        ]);

        $course = Course::findOrFail($courseId);
        $material = $course->materials()->findOrFail($materialId);

        $material->update($validated);

        return response()->json($material);
    }

    /**
     * Menghapus materi dari course tertentu.
     *
     * @param  int  $courseId
     * @param  int  $materialId
     * @return \Illuminate\Http\Response
     */
    public function destroy($courseId, $materialId)
    {
        $course = Course::findOrFail($courseId);
        $material = $course->materials()->findOrFail($materialId);
        $material->delete();

        return response()->json(['message' => 'Material deleted successfully']);
    }
}
