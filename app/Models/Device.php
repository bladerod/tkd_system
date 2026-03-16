<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $fillable = [
        'branch_id', 
        'device_name', 
        'device_type', 
        'api_key', 
        'last_seen', 
        'status'
    ];

    protected $casts = [
        'last_seen' => 'datetime',
    ];

    protected $hidden = [
        'api_key',
    ];

    // Relationships
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function attendanceLogs()
    {
        return $this->hasMany(AttendanceLog::class, 'device_id');
    }

    // Scopes
    public function scopeOnline($query)
    {
        return $query->where('status', 'online');
    }
}