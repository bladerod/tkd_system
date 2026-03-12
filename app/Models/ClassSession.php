<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassSession extends Model
{
    protected $fillable = [
        'class_id', 
        'session_date', 
        'start_time', 
        'end_time',
        'instructor_id', 
        'session_status', 
        'notes'
    ];

    protected $casts = [
        'session_date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    // Relationships
    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }

    public function instructor()
    {
        return $this->belongsTo(Instructor::class);
    }

    public function attendanceLogs()
    {
        return $this->hasMany(AttendanceLog::class);
    }

    // Scopes
    public function scopeToday($query)
    {
        return $query->whereDate('session_date', today());
    }

    public function scopeScheduled($query)
    {
        return $query->where('session_status', 'scheduled');
    }
}