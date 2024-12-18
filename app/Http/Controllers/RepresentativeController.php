<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class RepresentativeController extends Controller
{
    public function index()
    {
        $events = Event::where('school_id', auth()->user()->school_id)->get();
        // Pass events to the view
        return view('representative.r_dashboard', compact('events'));
    }

    // Additional representative-specific methods...
}

