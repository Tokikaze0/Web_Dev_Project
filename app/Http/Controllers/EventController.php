<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\AttendanceLog;
use Illuminate\Http\Request;
use Carbon\Carbon; // Import Carbon class

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

    // Fetch students who attended the event
    public function getEventStudents($eventId)
    {
        // Fetch attendance logs for the given event
        $students = AttendanceLog::where('event_id', $eventId)
            ->join('students', 'attendance_logs.student_id', '=', 'students.id')
            ->select('students.name', 'attendance_logs.attended_at')
            ->get();

        // Fetch the event details
        $event = Event::find($eventId);

        // Process attendance status for each student
        $students = $students->map(function ($student) use ($event) {
            $attendedAt = Carbon::parse($student->attended_at);
            $status = 'absent'; // Default status is absent

            if ($attendedAt->lte(Carbon::parse($event->end_time))) {
                // If attendance is on or before the event's end time, mark as on-time
                $status = 'on-time';
            } elseif ($attendedAt->between(Carbon::parse($event->start_time), Carbon::parse($event->end_time))) {
                // If attendance is within the event start and end time, mark as late
                $status = 'late';
            }

            return [
                'name' => $student->name,
                'attended_at' => $student->attended_at,
                'status' => $status,
            ];
        });

        // Return the students with their status as JSON
        return response()->json(['students' => $students]);
    }
}
