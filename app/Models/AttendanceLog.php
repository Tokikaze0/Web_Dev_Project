<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceLog extends Model
{
    use HasFactory;

    // If the table name doesn't follow Laravel's pluralization convention
    protected $table = 'attendancelogs';

    // Mass-assignable attributes
    protected $fillable = [
        'student_id',
        'event_id',
        'attended_at'
    ];

    // If the timestamp fields are not named `created_at` and `updated_at`
    const CREATED_AT = 'attended_at'; // Use `attended_at` for created timestamp
    const UPDATED_AT = null; // Assuming you don't need updated_at

    // Relationships (optional, depending on your structure)
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
