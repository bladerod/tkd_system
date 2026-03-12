<?php
// app/Models/AttendanceLog.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceLog extends Model
{
    use HasFactory;

    protected $table = 'attendance_logs';
    public $timestamps = false;

    protected $fillable = [
        'student_id',
        'class_session_id',
        'checkin_time',
        'checkout_time',
        'method',
        'confidence_score',
        'recorded_by_user_id',
        'device_id',
        'status'
    ];

    protected $casts = [
        'checkin_time' => 'datetime',
        'checkout_time' => 'datetime',
        'status' => 'boolean'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    // public function classSession()
    // {
    //     return $this->belongsTo(ClassSession::class, 'class_session_id');
    // }

    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by_user_id');
    }

    // public function device()
    // {
    //     return $this->belongsTo(Device::class, 'device_id');
    // }

    public function getDurationAttribute()
    {
        if ($this->checkout_time) {
            return $this->checkin_time->diffInMinutes($this->checkout_time);
        }
        return null;
    }

    
}