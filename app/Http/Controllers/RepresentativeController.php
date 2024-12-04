<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RepresentativeController extends Controller
{
    public function index()
    {
        return view('representative.r_dashboard'); // Create a Blade view for representative dashboard
    }

    // Additional representative-specific methods...
}

