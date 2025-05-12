<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Course;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Menampilkan semua user.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    /**
     * Menampilkan user tertentu.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    /**
     * Enroll user ke course.
     *
     * @param  int  $userId
     * @param  int  $courseId
     * @return \Illuminate\Http\Response
     */
    public function enrollToCourse($userId, $courseId)
    {
        $user = User::findOrFail($userId);
        $course = Course::findOrFail($courseId);

        // Enroll user ke course
        $user->courses()->attach($course);

        return response()->json(['message' => 'User enrolled to course successfully']);
    }

    /**
     * Mengambil semua course yang diikuti oleh user.
     *
     * @param  int  $userId
     * @return \Illuminate\Http\Response
     */
    public function courses($userId)
    {
        $user = User::findOrFail($userId);
        $courses = $user->courses;
        return response()->json($courses);
    }
}
