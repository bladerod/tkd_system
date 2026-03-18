<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table = 'students';

    protected $fillable = [
        'branch_id',
        'student_code',
        'first_name',
        'last_name',
        'middle_name',
        'birthdate',
        'gender',
        'photo_url',
        'current_belt',
        'join_date',
        'status',
        'primary_parent_id'
    ];

    public function parent()
    {
        return $this->belongsTo(ParentModel::class,'primary_parent_id');
    }

    public function classes()
    {
        return $this->belongsToMany(ClassModel::class,'class_students','student_id','class_id');
    }

    public function attendanceLogs()
    {
        return $this->hasMany(AttendanceLog::class);
    }

    public function invoices()
    {
        return $this->hasMany(Payment::class);
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    public function competitions()
    {
        return $this->hasMany(CompetitionEntry::class);
    }

    public function skillProgress()
    {
        return $this->hasMany(StudentSkillProgress::class);
    }
}
