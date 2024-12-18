<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class RepresentativeController extends Controller
{
    public function index()
    {
        // Fetch all events from the database
        $events = Event::all();

        // Pass events to the view
        return view('representative.r_dashboard', compact('events'));
    }

    // Additional representative-specific methods...
}

