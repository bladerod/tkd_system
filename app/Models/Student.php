<?php
// app/Models/Student.php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo, HasMany, BelongsToMany;

class Student extends Model {
    protected $fillable = ['branch_id', 'student_code', 'first_name', 'last_name', 'middle_name', 'birthdate', 'gender', 'photo_url', 'current_belt', 'join_date', 'status', 'medical_notes', 'allergies', 'emergency_contact_name', 'emergency_contact_mobile', 'primary_parent_id'];

    public function branch(): BelongsTo { return $this->belongsTo(Branch::class); }
    public function primaryParent(): BelongsTo { return $this->belongsTo(ParentModel::class, 'primary_parent_id'); }
    public function parents() { return $this->belongsToMany(ParentModel::class, 'parent_students', 'student_id', 'parent_id')
        ->withPivot('relationship', 'is_primary'); }
    public function classes() { return $this->belongsToMany(Classes::class, 'class_students', 'student_id', 'class_id')
        ->withPivot('start_date', 'end_date', 'status'); }
    public function subscriptions(): HasMany { return $this->hasMany(StudentSubscription::class); }
    public function attendanceLogs(): HasMany { return $this->hasMany(AttendanceLog::class); }
    public function faceProfile(): HasOne { return $this->hasOne(FaceProfile::class); }
    public function evaluations(): HasMany { return $this->hasMany(StudentEvaluation::class); }
    public function skillProgress(): HasMany { return $this->hasMany(StudentSkillProgress::class); }
    public function examResults(): HasMany { return $this->hasMany(BeltExamResult::class); }
    public function competitionEntries(): HasMany { return $this->hasMany(CompetitionEntry::class); }
    public function certificates(): HasMany { return $this->hasMany(Certificate::class); }
    public function invoices(): HasMany { return $this->hasMany(Invoice::class); }
}
