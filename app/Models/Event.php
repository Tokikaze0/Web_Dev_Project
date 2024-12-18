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
        'name', 'date', 'location' // Adjust the attributes based on your database
    ];

    // Relationship with AttendanceLog
    public function attendanceLogs()
    {
        return $this->hasMany(AttendanceLog::class);
    }
}
