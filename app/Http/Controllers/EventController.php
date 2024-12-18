<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class EventController extends Controller
{
    // Ensure all routes in this controller require authentication
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Display the list of events for the admin's school
    public function index()
    {
        $events = Event::where('school_id', auth()->user()->school_id)->get();
        return view('admin.events.index', compact('events'));
    }

    // Show the form for creating a new event
    public function create()
    {
        return view('admin.events.create');
    }

    // Store a new event in the database
    public function storeEvent(Request $request)
    {
        // Validate the input data
        $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i', // Ensure valid start time format
            'end_time' => 'required|date_format:H:i', // Ensure valid end time format
            'location' => 'nullable|string|max:255',
        ]);

        // Store the event in the database with the school_id of the logged-in admin
        Event::create([
            'name' => $request->name,
            'date' => $request->date, // Assuming 'date' is stored in the table
            'start_time' => $request->start_time, // Store start_time
            'end_time' => $request->end_time, // Store end_time
            'location' => $request->location, // Assuming 'location' is stored in the table
            'school_id' => auth()->user()->school_id, // Associating the event with the admin's school
        ]);

        // Redirect with success message
        return redirect()->route('admin.events.index')->with('success', 'Event created successfully!');
    }

    // Show the form for editing an event
    public function edit($id)
    {
        // Fetch the event by ID
        $event = Event::findOrFail($id);

        // Return the edit view with the event data
        return view('admin.events.edit', compact('event'));
    }

    // Update the event in the database
    public function update(Request $request, $id)
    {
        // Validate the request data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'location' => 'required|string|max:255',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        // Find the event and update it
        $event = Event::findOrFail($id);
        $event->update($validated);

        // Redirect back to the events list with a success message
        return redirect()->route('admin.events.index')->with('success', 'Event updated successfully.');
    }

    // Delete an event from the database
    public function destroy($id)
    {
        $event = Event::where('school_id', auth()->user()->school_id)->findOrFail($id);

        $event->delete();

        return redirect()->route('admin.events.index')->with('success', 'Event deleted successfully.');
    }
}
