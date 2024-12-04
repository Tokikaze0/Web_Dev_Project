<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.dashboard'); // Create a Blade view for admin dashboard
    }

    // Additional admin-specific methods...
}

