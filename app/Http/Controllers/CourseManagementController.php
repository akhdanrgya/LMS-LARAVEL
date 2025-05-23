<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CourseManagementController extends Controller
{
    public function index(){
        return view('admin.coursemanagement');
    }
}
