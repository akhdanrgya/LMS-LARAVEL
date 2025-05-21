<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.index');
    }

    public function courses()
    {
        return view('dashboard.courses');
    }
    public function task()
    {
        return view('dashboard.task');
    }
    public function forum()
    {
        return view('dashboard.forum');
    }
}
