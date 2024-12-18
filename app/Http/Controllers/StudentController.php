<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class StudentController extends Controller
{
    // Display the student dashboard (for student role users)
    public function index()
    {
        return view('student.dashboard'); // Create a Blade view for student dashboard
    }

    // Admin-specific: Display the list of students for the admin's school
    public function adminIndex()
    {
        $students = User::where('school_id', auth()->user()->school_id)
            ->where('role', 'student') // Filter only students
            ->get();

        return view('admin.students.index', compact('students')); // Admin-specific student list view
    }

    // Admin-specific: Show the form for creating a new student
    public function create()
    {
        return view('admin.students.create'); // Admin-specific student creation view
    }

    // Admin-specific: Store a new student in the database
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'rfid' => 'required|string|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'rfid' => $validated['rfid'],
            'password' => bcrypt($validated['password']),
            'school_id' => auth()->user()->school_id,
            'role' => 'student', // Default role as student
        ]);

        return redirect()->route('admin.students.index')->with('success', 'Student added successfully.');
    }

    // Admin-specific: Show the form for editing an existing student
    public function edit($id)
    {
        $student = User::where('school_id', auth()->user()->school_id)
            ->where('role', 'student')
            ->findOrFail($id);

        return view('admin.students.edit', compact('student'));
    }

    // Admin-specific: Update an existing student in the database
    public function update(Request $request, $id)
    {
        $student = User::where('school_id', auth()->user()->school_id)
            ->where('role', 'student')
            ->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $student->id,
            'rfid' => 'required|string|unique:users,rfid,' . $student->id,
        ]);

        $student->update($validated);

        return redirect()->route('admin.students.index')->with('success', 'Student updated successfully.');
    }

    // Admin-specific: Delete a student from the database
    public function destroy($id)
    {
        $student = User::where('school_id', auth()->user()->school_id)
            ->where('role', 'student')
            ->findOrFail($id);

        $student->delete();

        return redirect()->route('admin.students.index')->with('success', 'Student deleted successfully.');
    }

    // Admin-specific: Promote a student to a representative
    public function promote($id)
    {
        $student = User::where('school_id', auth()->user()->school_id)
            ->where('role', 'student')
            ->findOrFail($id);

        $student->update(['role' => 'representative']);

        return redirect()->route('admin.students.index')->with('success', 'Student promoted to representative.');
    }

    // Admin-specific: Demote a representative back to a student
    public function demote($id)
    {
        $student = User::where('school_id', auth()->user()->school_id)
            ->where('role', 'representative')
            ->findOrFail($id);

        $student->update(['role' => 'student']);

        return redirect()->route('admin.students.index')->with('success', 'Representative demoted to student.');
    }
}
