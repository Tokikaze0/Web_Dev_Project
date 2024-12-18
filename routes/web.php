<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RepresentativeController;
use App\Http\Controllers\StudentController;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\AttendanceLog;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login-form');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/register', [AuthController::class, 'showSignupForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin Routes
Route::group(['middleware' => ['auth', 'role:admin']], function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
});

// Representative Routes
Route::group(['middleware' => ['auth', 'role:representative']], function () {
    Route::get('/representative/dashboard', [RepresentativeController::class, 'index'])->name('representative.dashboard');
});

// Student Routes
Route::group(['middleware' => ['auth', 'role:student']], function () {
    Route::get('/student/dashboard', [StudentController::class, 'index'])->name('student.dashboard');
});

Route::post('/check-rfid', function (Request $request) {
    $student = Student::where('rfid', $request->rfid)->first();

    if ($student) {
        return response()->json([
            'exists' => true,
            'student_id' => $student->id
        ]);
    }

    return response()->json(['exists' => false]);
});

Route::post('/save-attendance', function (Request $request) {
    AttendanceLog::create([
        'student_id' => $request->student_id,
        'event_id' => $request->event_id,
        'attended_at' => now(),
    ]);
    return response()->json(['status' => 'success']);
});