<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Event;
use App\Models\Student;


class AdminController extends Controller
{
    public function dashboard()
    {
        // Fetch students with their associated school
        $students = Student::with('school')->get();

        // Extract unique school names
        $schoolNames = $students->pluck('school.name')->unique()->values();

        // Count students per school
        $studentCounts = $students->groupBy('school_id')->map->count()->values();

        return view('admin.dashboard', compact('schoolNames', 'studentCounts'));
    }

    public function profile()
    {
        $user = Auth::user(); // Get the authenticated user
        return view('admin.profile', compact('user')); // Pass to the view
    }
    public function editProfile()
    {
        return view('admin.profile', ['user' => auth()->user()]);
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . auth()->id(),
            'current_password' => 'required_with:password|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = auth()->user();

        // Check if the current password matches
        if ($request->filled('current_password') && !Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Incorrect Password, Please Try Again!']);
        }

        // Update the user details
        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }

    public function createEvent()
    {
        return view('admin.events.create');
    }

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
}
