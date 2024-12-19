<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\AttendanceLog;

class RepresentativeController extends Controller
{
    public function index()
    {
        $events = Event::where('school_id', auth()->user()->school_id)->get();
        // Pass events to the view
        return view('representative.r_dashboard', compact('events'));
    }

    // Additional representative-specific methods...
    public function getAttendanceLogs(Request $request)
    {
        $eventId = $request->event_id;
        $attendanceLogs = AttendanceLog::where('event_id', $eventId)
            ->with('student') // Assuming there's a relationship with the Student model
            ->get();

        return response()->json($attendanceLogs);
    }

    public function saveAttendance(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'event_id' => 'required|exists:events,id',
        ]);

        $attendance = AttendanceLog::create([
            'student_id' => $validated['student_id'],
            'event_id' => $validated['event_id'],
            'logged_at' => now(), // Optional, defaults to current timestamp
        ]);

        return response()->json(['status' => 'success', 'attendance' => $attendance]);
    }
}
