<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RepresentativeController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\EventController;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\AttendanceLog;

/*
|----------------------------------------------------------------------
| Web Routes
|----------------------------------------------------------------------
*/

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login-form');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/register', [AuthController::class, 'showSignupForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin Routes
Route::group(['middleware' => ['auth', 'role:admin']], function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::resource('/admin/students', StudentController::class)->except(['show']);
    Route::post('/admin/students/{student}/toggle-role', [StudentController::class, 'toggleRole'])->name('admin.students.toggleRole');
    Route::resource('/admin/events', EventController::class)->except(['show']);
    Route::get('/admin/profile', [AdminController::class, 'editProfile'])->name('admin.profile');
    Route::post('/admin/profile', [AdminController::class, 'updateProfile'])->name('admin.profile.update');
});

// Representative Routes
Route::group(['middleware' => ['auth', 'role:representative']], function () {
    Route::get('/representative/dashboard', [RepresentativeController::class, 'index'])->name('representative.dashboard');
});

// Student Routes
Route::group(['middleware' => ['auth', 'role:student']], function () {
    Route::get('/student/dashboard', [StudentController::class, 'index'])->name('student.dashboard');
});

// RFID Check and Attendance
Route::post('/check-rfid', function (Request $request) {
    $student = Student::where('rfid', $request->rfid)->first();

    if ($student) {
        return response()->json([
            'exists' => true,
            'student_id' => $student->id,
            'student_name' => $student->name, // Include the student's name
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

// Admin Students Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin/students')->group(function () {
    Route::get('/', [StudentController::class, 'adminIndex'])->name('admin.students.index');
    Route::get('/create', [StudentController::class, 'create'])->name('admin.students.create');
    Route::post('/', [StudentController::class, 'store'])->name('admin.students.store');
    Route::get('/{id}/edit', [StudentController::class, 'edit'])->name('admin.students.edit');
    Route::put('/{id}', [StudentController::class, 'update'])->name('admin.students.update');
    Route::delete('/{id}', [StudentController::class, 'destroy'])->name('admin.students.destroy');
    Route::post('/{id}/promote', [StudentController::class, 'promote'])->name('admin.students.promote');
    Route::post('/{id}/demote', [StudentController::class, 'demote'])->name('admin.students.demote');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin/events')->group(function () {
    Route::get('/', [EventController::class, 'index'])->name('admin.events.index');
    Route::get('/create', [EventController::class, 'create'])->name('admin.events.create');
    Route::post('/', [EventController::class, 'storeEvent'])->name('admin.events.store');
    Route::get('/{id}/edit', [EventController::class, 'edit'])->name('admin.events.edit');
    Route::put('/{id}', [EventController::class, 'update'])->name('admin.events.update');
    Route::delete('/{id}', [EventController::class, 'destroy'])->name('admin.events.destroy');
});

