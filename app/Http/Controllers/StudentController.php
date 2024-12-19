<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Student;

class StudentController extends Controller
{
    // Display the student dashboard (for student role users)
    public function index()
    {
        return view('students.dashboard'); // Create a Blade view for student dashboard
    }

    // Admin-specific: Display the list of students for the admin's school
    // Admin-specific: Display the list of students for the admin's school
    public function adminIndex()
    {
        $students = Student::where('school_id', auth()->user()->school_id) // Only filter by school_id
            ->get();

        return view('admin.students.index', compact('students'));
    }

    // Admin-specific: Show the form for creating a new student
    // Show the form for creating a new student
    public function create()
    {
        return view('admin.students.create');
    }

    // Store a new student
    public function store(Request $request)
    {
        // Validate the input data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:students',
            'rfid' => 'required|string|unique:students',
        ]);

        // Debug: Check if validation passed
        // if ($validated) {
        //     dd('Validation passed');
        // }

        // Get the currently logged-in user
        $user = auth()->user();

        // Include the school_id from the logged-in user in the student data
        $validated['school_id'] = $user->school_id;

        // Create the student record with the validated data, including school_id
        $student = Student::create($validated);

        // Check if the student is saved
        // if ($student) {
        //     dd('Student saved:', $student);
        // } else {
        //     dd('Student not saved');
        // }

        return redirect()->route('admin.students.index')->with('success', 'Student added successfully.');
    }


    // Show the form for editing an existing student
    public function edit($id)
    {
        $student = Student::findOrFail($id);
        return view('admin.students.edit', compact('student'));
    }

    // Update the student
    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:students,email,' . $student->id,
            'rfid' => 'required|string|unique:students,rfid,' . $student->id,
        ]);

        $student->update($validated);

        return redirect()->route('admin.students.index')->with('success', 'Student updated successfully.');
    }

    // Delete a student
    public function destroy($id)
    {
        $student = Student::findOrFail($id);
        $student->delete();

        return redirect()->route('admin.students.index')->with('success', 'Student deleted successfully.');
    }
}
