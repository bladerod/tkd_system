<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasApiTokens, Notifiable;

    protected $table = 'students';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'branch_id',
        'student_code',
        'first_name',
        'last_name', 
        'birthdate',
        'gender',
        'photo_url',
        'current_belt', 
        'join_date',
        'status',
        'medical_notes',
        'allergies', 
        'emergency_contact_name',
        'emergency_contact_mobile',
        'primary_parent_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function parent()
    {
        return $this->belongsTo(ParentModel::class,'primary_parent_id');
    }

    public function invoices(): HasMany
    {
        // This links students.id to invoices.student_id
        return $this->hasMany(Invoice::class, 'student_id', 'id');
    }

    public function classes(): HasMany
    {
        // This links 'students.id' to 'class_students.student_id'
        return $this->hasMany(ClassStudent::class, 'student_id', 'id');
    }

    public function attendanceLogs(): HasMany
    {
        // Links the students.id to attendance_logs.student_id
        return $this->hasMany(AttendanceLog::class, 'student_id', 'id');
    }

    /**
     * Get the belt level associated with the student.
     */
    public function currentBelt(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(BeltLevel::class, 'current_belt', 'id');
    }


    public function parent(): HasOne { return $this->hasOne(ParentModel::class); }
    public function primaryParent(): HasOne 
    { 
        return $this->hasOne(ParentModel::class, 'user_id', 'id'); 
    }
    public function instructor(): HasOne { return $this->hasOne(Instructor::class); }
    public function notifications(): HasMany { return $this->hasMany(Notification::class); }
    public function chatParticipants() { return $this->belongsToMany(ChatThread::class, 'chat_participants', 'user_id', 'thread_id'); }
    public function sentMessages(): HasMany { return $this->hasMany(ChatMessage::class, 'sender_user_id'); }

    public function chatMessages()
    {
        return $this->hasMany(ChatMessage::class, 'sender_user_id');
    }

    public function chatThreads()
    {
        return $this->belongsToMany(ChatThread::class, 'chat_participants', 'user_id', 'thread_id');
    }

    public function announcements()
    {
        return $this->belongsToMany(ClassModel::class,'class_students','student_id','class_id');
    }

    // public function auditLogs()
    // {
    //     return $this->hasMany(AuditLog::class);
    // }

    public function certificates()
    {
        return $this->hasMany(Certificate::class, 'issued_by_user_id');
    }
}
