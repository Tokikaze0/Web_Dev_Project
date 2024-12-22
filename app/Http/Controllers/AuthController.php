<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    // Show Login Form
    public function showLoginForm()
    {
        return view('auth.login'); // Create a Blade view for login
    }

    // Process Login
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($request->only('username', 'password'))) {
            $user = Auth::user();

            // Redirect based on role
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role === 'representative') {
                return redirect()->route('representative.dashboard');
            } elseif ($user->role === 'student') {
                return redirect()->route('students.student_dashboard');
            }
        }

        return back()->withErrors(['message' => 'Invalid credentials.']);
    }

    // Show Signup Form
    public function showSignupForm()
    {
        return view('auth.registration'); // Create a Blade view for signup
    }

    // Process Signup
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:user',
            'email' => 'required|email|max:255|unique:user',
            'password' => 'required|confirmed|min:8',
            'role' => 'required|in:student,representative',
        ]);
        

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('login')->with('success', 'Registration successful! Please log in.');
    }

    // Logout
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login-form');
    }
}

