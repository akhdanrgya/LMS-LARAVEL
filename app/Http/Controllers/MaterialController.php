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
    // app/Http/Controllers/MaterialController.php
    public function create(Course $course)
    {
        return view('materials.create', compact('course'));
    }

    public function store(Request $request, Course $course)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $material = new Material();
        $material->title = $request->title;
        $material->content = $request->content;
        $material->course_id = $course->id;
        $material->save();

        return redirect()->route('courses.show', $course->id)->with('success', 'Material added successfully.');
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
