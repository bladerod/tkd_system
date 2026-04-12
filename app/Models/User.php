<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

// ✅ ADD THESE (IMPORTANT - prevents class not found errors)
use App\Models\Branch;
use App\Models\ParentModel;
use App\Models\Instructor;
use App\Models\Notification;
use App\Models\ChatThread;
use App\Models\ChatMessage;
use App\Models\Announcement;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'branch_id',
        'role',
        'fname',
        'lname',
        'email',
        'username',
        'mobile',
        'password',
        'photo_url',
        'status',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
    ];

    // Relationships
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    // public function instructor()
    // {
    //     return $this->hasOne(Instructor::class);
    // }

    // public function parent()
    // {
    //     return $this->hasOne(Parents::class);
    // }

    public function parent(): HasOne
    {
        return $this->hasOne(ParentModel::class);
    }

    public function instructor(): HasOne
    {
        return $this->hasOne(Instructor::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    public function chatParticipants(): BelongsToMany
    {
        return $this->belongsToMany(ChatThread::class, 'chat_participants', 'user_id', 'thread_id');
    }

    public function sentMessages(): HasMany
    {
        return $this->hasMany(ChatMessage::class, 'sender_user_id');
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

    public function announcements(): HasMany
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

    /**
     * Check if user is an admin.
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is an instructor.
     */
    public function isInstructor()
    {
        return $this->role === 'instructor';
    }

    /**
     * Check if user is a staff.
     */
    public function isStaff()
    {
        return $this->role === 'staff';
    }

    /**
     * Check if user is a parent.
     */
    public function isParent()
    {
        return $this->role === 'parent';
    }

    // ⚠️ Duplicate of chatParticipants (kept but clarified)
    // public function threads(): BelongsToMany
    // {
    //     return $this->belongsToMany(ChatThread::class, 'chat_participants', 'user_id', 'thread_id');
    // }
}
