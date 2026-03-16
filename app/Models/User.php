<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'branch_id',
        'role',
        'fname',
        'lname',
        'username',
        'email',
        'mobile',
        'password',
        'photo_url',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'last_login_at' => 'datetime',
    ];

    /**
     * Get the branch that owns the user.
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id'); // Fixed: changed 'branch_id' to 'id'
    }

    /**
     * Get the parent record associated with the user.
     */
    public function parent()
    {
        return $this->hasOne(Parents::class, 'user_id', 'id');
    }

    /**
     * Get the user's full name.
     */
    public function getFullNameAttribute()
    {
        return $this->fname . ' ' . $this->lname;
    }

    /**
     * Check if user has a specific role.
     */
    public function hasRole($role)
    {
        return $this->role === $role;
    }

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

<<<<<<< HEAD


=======
    public function branch(): BelongsTo { return $this->belongsTo(Branch::class); }
    public function parent(): HasOne { return $this->hasOne(ParentModel::class); }
>>>>>>> 988bf1c3a61171e4a6b56431c3c5e0d03b1c21f8
    public function instructor(): HasOne { return $this->hasOne(Instructor::class); }
    public function notifications(): HasMany { return $this->hasMany(Notification::class); }
    public function chatParticipants() { return $this->belongsToMany(ChatThread::class, 'chat_participants', 'user_id', 'thread_id'); }
    public function sentMessages(): HasMany { return $this->hasMany(ChatMessage::class, 'sender_user_id'); }
}
