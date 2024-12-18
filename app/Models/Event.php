<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    // The table associated with the model.
    protected $table = 'events'; // If your table name is not the default

    // Mass assignable attributes
    protected $fillable = [
        'name',
        'date',
        'location',
        'start_time',
        'end_time', // Add start_time and end_time to the fillable fields
        'school_id',
    ];

    // Relationship with AttendanceLog
    public function attendanceLogs()
    {
        return $this->hasMany(AttendanceLog::class);
    }

    public function index()
    {
        $events = Event::all(); // Replace with actual logic
        return view('admin.events.index', compact('events'));
    }
}
