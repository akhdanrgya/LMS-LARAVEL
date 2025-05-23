<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MentorController extends Controller
{
    public function index(){
        return view('mentor.index');
    }

    public function managecourse(){
        return view('mentor.managecourse');
    }
    public function managematerial(){
        return view('mentor.managematerial');
    }
}
