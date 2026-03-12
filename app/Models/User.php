<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'branch_id', 
        'role', 
        'username', 
        'fname', 
        'lname', 
        'email', 
        'mobile', 
        'password', 
        'photo_url', 
        'status'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
    ];

    // Relationships
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function instructor()
    {
        return $this->hasOne(Instructor::class);
    }

    public function parent()
    {
        return $this->hasOne(Parents::class);
    }

    // public function notifications()
    // {
    //     return $this->hasMany(Notification::class);
    // }

    // public function chatMessages()
    // {
    //     return $this->hasMany(ChatMessage::class, 'sender_user_id');
    // }

    // public function chatThreads()
    // {
    //     return $this->belongsToMany(ChatThread::class, 'chat_participants', 'user_id', 'thread_id');
    // }

    public function announcements()
    {
        return $this->hasMany(Announcement::class, 'created_by_user_id');
    }

    // public function auditLogs()
    // {
    //     return $this->hasMany(AuditLog::class);
    // }

    // public function certificates()
    // {
    //     return $this->hasMany(Certificate::class, 'issued_by_user_id');
    // }
}