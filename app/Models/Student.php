<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'branch_id', 'student_code', 'first_name', 'last_name', 'middle_name',
        'birthdate', 'gender', 'photo_url', 'current_belt', 'join_date',
        'status', 'medical_notes', 'allergies', 'emergency_contact_name',
        'emergency_contact_mobile', 'primary_parent_id'
    ];

    protected $casts = [
        'birthdate' => 'date',
        'join_date' => 'date',
    ];

    // Relationships
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function primaryParent()
    {
        return $this->belongsTo(Parents::class, 'primary_parent_id');
    }

    public function parents()
    {
        return $this->belongsToMany(Parents::class, 'parent_students', 'student_id', 'parent_id')
                    ->withPivot('relationship', 'is_primary');
    }

    public function classes()
    {
        return $this->belongsToMany(Classes::class, 'class_students', 'student_id', 'class_id')
                    ->withPivot('start_date', 'end_date', 'status');
    }

    public function attendanceLogs()
    {
        return $this->hasMany(AttendanceLog::class);
    }

    // public function faceProfiles()
    // {
    //     return $this->hasMany(FaceProfile::class);
    // }

    // public function subscriptions()
    // {
    //     return $this->hasMany(StudentSubscription::class);
    // }

    // public function invoices()
    // {
    //     return $this->hasMany(Invoice::class);
    // }

    // public function evaluations()
    // {
    //     return $this->hasMany(StudentEvaluation::class);
    // }

    // public function skillProgress()
    // {
    //     return $this->hasMany(StudentSkillProgress::class);
    // }

    // public function beltExamResults()
    // {
    //     return $this->hasMany(BeltExamResult::class);
    // }

    public function competitionEntries()
    {
        return $this->hasMany(CompetitionEntry::class);
    }

    // public function certificates()
    // {
    //     return $this->hasMany(Certificate::class);
    // }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByBelt($query, $belt)
    {
        return $query->where('current_belt', $belt);
    }
}