<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Carbon\Carbon;
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
    $request->validate([
        'event_id' => 'required|exists:events,id',
    ]);

    $eventId = $request->event_id;

    $attendanceLogs = AttendanceLog::where('event_id', $eventId)
        ->with(['student' => function ($query) {
            $query->select('id', 'name'); // Select only necessary fields
        }])
        ->select('id', 'student_id', 'event_id', 'attended_at', 'status') // Ensure attended_at is selected
        ->get();

    // Format the attended_at date for each log
    $attendanceLogs->each(function ($log) {
        // Make sure attended_at is in a valid format
        $log->attended_at = Carbon::parse($log->attended_at)->toDateTimeString(); // Converts to a proper datetime format
    });

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
