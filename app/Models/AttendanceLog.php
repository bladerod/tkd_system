<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceLog extends Model
{
    protected $table = 'attendance_logs';
    
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
    ];

    // Relationships
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function classSession()
    {
        return $this->belongsTo(ClassSession::class);
    }

    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by_user_id');
    }

    public function device()
    {
        return $this->belongsTo(Device::class, 'device_id');
    }

    // Scopes
    public function scopeToday($query)
    {
        return $query->whereDate('checkin_time', today());
    }

    public function scopeByDate($query, $date)
    {
        return $query->whereDate('checkin_time', $date);
    }

    public function scopePresent($query)
    {
        return $query->where('status', 'present');
    }
}