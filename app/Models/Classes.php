<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{

    protected $table = "classes";
    protected $primaryKey = 'classes_id';
    public $timestamps = false;

    protected $fillable = [
        "classes_id",
        "branch_id",
        "class_name",
        "age_group",
        "level",
        "max_students",
        "primary_instructor_id",
        "assistant_instructor_id",
        "created_at",
        "status",
    ] ;

    protected $casts = [
        "created_at"=> "datetime",
    ];

     public function branch(): BelongsTo { return $this->belongsTo(Branch::class); }
    public function primaryInstructor(): BelongsTo { return $this->belongsTo(Instructor::class, 'primary_instructor_id'); }
    public function assistantInstructor(): BelongsTo { return $this->belongsTo(Instructor::class, 'assistant_instructor_id'); }
    public function students() { return $this->belongsToMany(Student::class, 'class_students', 'class_id', 'student_id')
        ->withPivot('start_date', 'end_date', 'status'); }
    public function schedules(): HasMany { return $this->hasMany(ClassSchedule::class); }
    public function sessions(): HasMany { return $this->hasMany(ClassSession::class); }
    public function evaluations(): HasMany { return $this->hasMany(StudentEvaluation::class, 'class_id'); }
    public function exams(): HasMany { return $this->hasMany(BeltExam::class, 'class_id'); }
    public function announcements(): HasMany { return $this->hasMany(Announcement::class, 'class_id'); }
}
