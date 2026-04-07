<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function parent(): HasOne { return $this->hasOne(ParentModel::class); }
    public function instructor(): HasOne { return $this->hasOne(Instructor::class); }
    public function notifications(): HasMany { return $this->hasMany(Notification::class); }
    public function chatParticipants() { return $this->belongsToMany(ChatThread::class, 'chat_participants', 'user_id', 'thread_id'); }
    public function sentMessages(): HasMany { return $this->hasMany(ChatMessage::class, 'sender_user_id'); }

    public function announcements()
    {
        return $this->hasMany(Announcement::class, 'created_by_user_id');
    }

    /**
     * Check if user is an admin.
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is a staff.
     */
    public function isStaff()
    {
        return $this->role === 'staff';
    }


     // Check if user has any role (admin or staff only)
    public function hasSystemAccess(): bool
    {
        return in_array($this->role, ['admin', 'staff']);
    }

    // Get role permissions from database
    public function getPermissionsAttribute()
    {
        return RolePermission::where('role', $this->role)->get();
    }

    // Check specific permission
    public function canView($module): bool
    {
        $permission = RolePermission::where('role', $this->role)
            ->where('module', $module)
            ->first();
        
        return $permission ? (bool) $permission->can_view : false;
    }

    public function canCreate($module): bool
    {
        $permission = RolePermission::where('role', $this->role)
            ->where('module', $module)
            ->first();
        
        return $permission ? (bool) $permission->can_create : false;
    }

    public function canEdit($module): bool
    {
        $permission = RolePermission::where('role', $this->role)
            ->where('module', $module)
            ->first();
        
        return $permission ? (bool) $permission->can_edit : false;
    }

    public function canDelete($module): bool
    {
        $permission = RolePermission::where('role', $this->role)
            ->where('module', $module)
            ->first();
        
        return $permission ? (bool) $permission->can_delete : false;
    }
    
}
