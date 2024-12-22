<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Event;
use App\Models\AttendanceLog;
use Illuminate\Http\Request;

class StudentShowController extends Controller
{
    /**
     * Display the student's dashboard with events and attendance logs.
     *
     * @return \Illuminate\View\View
     */
    // StudentShowController.php
    public function index()
    {
        // Get the events for the student (filter based on the student's school)
        $events = Event::where('school_id', auth()->user()->school_id)->get();

        // Get attendance logs for the student
        $attendanceLogs = AttendanceLog::where('student_id', auth()->user()->id)
            ->with('event')  // eager load event details
            ->get();

        // Pass both events and attendance logs to the view
        return view('students.student_dashboard', compact('events', 'attendanceLogs'));
    }


    /**
     * Display the list of all events and attendance logs for students.
     *
     * @return \Illuminate\View\View
     */
    public function studentEvents()
    {
        // Retrieve all events (you can adjust this based on your requirements)
        $events = Event::all();  // Retrieve all events in the system, or apply filters

        // Retrieve attendance logs for all students and eager load related event and student data
        $attendanceLogs = AttendanceLog::with('event', 'student')->get();

        // Pass both events and attendance logs to the view
        return view('students.events', compact('events', 'attendanceLogs'));
    }
}
