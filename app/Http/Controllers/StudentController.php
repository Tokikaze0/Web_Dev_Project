<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        return view('student.dashboard'); // Create a Blade view for student dashboard
    }

    // Additional student-specific methods...
}

