<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RepresentativeController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentShowController;
use App\Http\Controllers\EventController;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Event;
use App\Models\AttendanceLog;

/*
|----------------------------------------------------------------------
| Web Routes
|----------------------------------------------------------------------
*/
// Route for the root URL ('/') to show the login page
Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login-form');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/register', [AuthController::class, 'showSignupForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin Routes
Route::group(['middleware' => ['auth', 'role:admin']], function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::resource('/admin/students1', StudentController::class)->except(['show']);
    Route::post('/admin/students/{student}/toggle-role', [StudentController::class, 'toggleRole'])->name('admin.students.toggleRole');
    Route::resource('/admin/events', EventController::class)->except(['show']);
    Route::get('/admin/profile', [AdminController::class, 'editProfile'])->name('admin.profile');
    Route::post('/admin/profile', [AdminController::class, 'updateProfile'])->name('admin.profile.update');
    Route::post('/students/import', [AdminController::class, 'import'])->name('admin.students.import');
});

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

// Representative Routes
Route::group(['middleware' => ['auth', 'role:representative']], function () {
    Route::get('/representative/dashboard', [RepresentativeController::class, 'index'])->name('representative.dashboard');
    Route::post('/get-attendance-logs', [RepresentativeController::class, 'getAttendanceLogs'])->name('getAttendanceLogs');
});

// Student Routes
Route::group(['middleware' => ['auth', 'role:student']], function () {
    Route::get('/student/dashboard', [StudentShowController::class, 'index'])->name('students.student_dashboard');
    Route::get('/student/events', [StudentShowController::class, 'studentEvents'])->name('students.events');
});

// RFID Check and Attendance
Route::post('/check-rfid', function (Request $request) {
    $request->validate([
        'rfid' => 'required',
        'event_id' => 'required|exists:events,id',
    ]);

    $student = Student::where('rfid', $request->rfid)->first();
    if (!$student) {
        return response()->json(['exists' => false]);
    }

    $event = Event::find($request->event_id);
    if (!$event) {
        return response()->json(['error' => 'Event not found.']);
    }

    $currentTime = now();

    $attendanceStatus = 'on_time';
    if ($currentTime > $event->end_time) {
        $attendanceStatus = 'late';
    }

    return response()->json([
        'exists' => true,
        'student_id' => $student->id,
        'student_name' => $student->name,
        'attendance_status' => $attendanceStatus,
    ]);
});



Route::post('/save-attendance', function (Request $request) {
    $request->validate([
        'student_id' => 'required|exists:students,id',
        'event_id' => 'required|exists:events,id',
    ]);

    $student = Student::find($request->student_id);
    $event = Event::find($request->event_id);
    if (!$student || !$event) {
        return response()->json(['status' => 'error', 'message' => 'Invalid student or event.']);
    }

    $currentTime = now();
    $attendanceStatus = 'on_time';

    if ($currentTime > $event->end_time) {
        $attendanceStatus = 'late';
    }

    // Check if attendance already exists for this student and event
    $existingAttendance = AttendanceLog::where('student_id', $student->id)
        ->where('event_id', $event->id)
        ->first();

    if ($existingAttendance) {
        return response()->json(['status' => 'error', 'message' => 'Attendance already recorded.']);
    }

    AttendanceLog::create([
        'student_id' => $student->id,
        'event_id' => $event->id,
        'attended_at' => $currentTime,
        'status' => $attendanceStatus,
    ]);

    return response()->json(['status' => 'success']);
});


Route::get('/attendance-logs/{event_id}/{student_id}', function ($event_id, $student_id) {
    // Validate if event and student exist
    $event = Event::find($event_id);
    $student = Student::find($student_id);

    if (!$event || !$student) {
        return response()->json(['error' => 'Event or Student not found'], 404);
    }

    // Fetch attendance logs
    $logs = AttendanceLog::where('event_id', $event_id)
        ->where('student_id', $student_id)
        ->join('students', 'attendance_logs.student_id', '=', 'students.id')
        ->select('students.name', 'attendance_logs.attended_at', 'attendance_logs.status')
        ->orderBy('attendance_logs.attended_at', 'asc')
        ->get();

    return response()->json(['logs' => $logs]);
});

Route::middleware(['auth', 'role:admin'])->prefix('admin/events')->group(function () {
    Route::get('/', [EventController::class, 'index'])->name('admin.events.index');
    Route::get('/create', [EventController::class, 'create'])->name('admin.events.create');
    Route::post('/', [EventController::class, 'storeEvent'])->name('admin.events.store');
    Route::get('/{id}/edit', [EventController::class, 'edit'])->name('admin.events.edit');
    Route::put('/{id}', [EventController::class, 'update'])->name('admin.events.update');
    Route::delete('/{id}', [EventController::class, 'destroy'])->name('admin.events.destroy');
    Route::get('/admin/events/{event}/students', [EventController::class, 'getEventStudents']);

});
