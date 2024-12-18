<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Event;
use App\Models\Student;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\StudentsImport;


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

    public function import(Request $request)
    {
        // Validate the CSV file
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048', // Add your validation rules
        ]);

        // Handle CSV file import
        $path = $request->file('csv_file')->getRealPath();

        try {
            // Use the Excel package to import CSV data
            Excel::import(new StudentsImport, $path);

            return redirect()->route('admin.students.index')->with('success', 'Students imported successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.students.index')->with('error', 'Failed to import students.');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:students,email',
            'rfid' => 'required|string|unique:students,rfid',
            'school_id' => 'required|exists:schools,id',
        ]);

        Student::create([
            'name' => $request->name,
            'email' => $request->email ?? $request->name . '@gmail.com',
            'rfid' => $request->rfid,
            'school_id' => $request->school_id,
        ]);

        return redirect()->route('admin.students.index')->with('success', 'Student created successfully!');
    }
}
