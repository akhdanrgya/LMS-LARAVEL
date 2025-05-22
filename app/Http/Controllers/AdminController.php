<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Course;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.index');
    }
    public function updateRole(Request $request)
    {
        $validated = $request->validate([
            'role' => 'required|in:admin,mentor,student'
        ]);
        
        auth()->user()->update([
            'role' => $validated['role']
        ]);
        
        return back()->with('success', 'Role berhasil diubah');
    }
    
}