<?php
// app/Models/Student.php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
<<<<<<< HEAD
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
=======
use Illuminate\Database\Eloquent\Relations\BelongsTo, HasMany, BelongsToMany;
>>>>>>> 988bf1c3a61171e4a6b56431c3c5e0d03b1c21f8

class Student extends Model {
    protected $fillable = ['branch_id', 'student_code', 'first_name', 'last_name', 'middle_name', 'birthdate', 'gender', 'photo_url', 'current_belt', 'join_date', 'status', 'medical_notes', 'allergies', 'emergency_contact_name', 'emergency_contact_mobile', 'primary_parent_id'];

<<<<<<< HEAD
    protected $table = 'students';
    protected $primaryKey = 'student_id';
    public $timestamps = true;

    protected $fillable = [
        'branch_id',
        'student_code',
        'fname',
        'mname',
        'lname',
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

    protected $casts = [
        'birthdate' => 'date',
        'join_date' => 'datetime',
        'created_at' => 'datetime'
    ];

    /**
     * Get the parents associated with the student.
     */
    // public function parents()
    // {
    //     return $this->belongsToMany(
    //         Parents::class,
    //         'parent_students',
    //         'student_id',
    //         'parent_id'
    //     )->withPivot('relationship', 'is_primary');
    // }

    /**
     * Get the branch that owns the student.
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'branch_id');
    }
    // public function branch(): BelongsTo { return $this->belongsTo(Branch::class); }
=======
    public function branch(): BelongsTo { return $this->belongsTo(Branch::class); }
>>>>>>> 988bf1c3a61171e4a6b56431c3c5e0d03b1c21f8
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
<<<<<<< HEAD
    
=======
>>>>>>> 988bf1c3a61171e4a6b56431c3c5e0d03b1c21f8
