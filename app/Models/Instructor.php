<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instructor extends Model
{
    use HasFactory;

    protected $table = 'instructors';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'rank_belt',
        'certification_level',
        'specialization',
        'hire_date',
        'bio',
        'active_flag'
    ];

    protected $casts = [
        'hire_date' => 'date',
        'active_flag' => 'boolean'
    ];

    /**
     * Get the user associated with the instructor.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the classes taught by this instructor.
     */
    public function classes()
    {
        return $this->hasMany(Classes::class, 'primary_instructor_id');
    }

    /**
     * Get the classes where this instructor is assistant.
     */
    public function assistantClasses()
    {
        return $this->hasMany(Classes::class, 'assistant_instructor_id');
    }

    /**
     * Get the class sessions taught by this instructor.
     */
    // public function classSessions()
    // {
    //     return $this->hasMany(ClassSession::class, 'instructor_id');
    // }

    /**
     * Get the competition entries for this instructor.
     */
    public function competitionEntries()
    {
        return $this->hasMany(CompetitionEntry::class, 'instructor_id');
    }

    /**
     * Get the student evaluations done by this instructor.
     */
    // public function studentEvaluations()
    // {
    //     return $this->hasMany(StudentEvaluation::class, 'instructor_id');
    // }

    /**
     * Get the belt exams where this instructor is chief.
     */
    // public function beltExams()
    // {
    //     return $this->hasMany(BeltExam::class, 'chief_instructor_id');
    // }

    /**
     * Get the student skill progress records checked by this instructor.
     */
    // public function studentSkillProgress()
    // {
    //     return $this->hasMany(StudentSkillProgress::class, 'instructor_id');
    // }

    /**
     * Get the full name of the instructor.
     */
    public function getFullNameAttribute()
    {
        if ($this->user) {
            return $this->user->fname . ' ' . $this->user->lname;
        }
        return 'Unknown Instructor';
    }

    /**
     * Scope a query to only include active instructors.
     */
    public function scopeActive($query)
    {
        return $query->where('active_flag', 1);
    }

    /**
     * Scope a query to only include inactive instructors.
     */
    public function scopeInactive($query)
    {
        return $query->where('active_flag', 0);
    }

    /**
     * Get the instructor's belt level with color.
     */
    public function getBeltDisplayAttribute()
    {
        return $this->rank_belt ?? 'No Belt Assigned';
    }

    /**
     * Check if instructor is active.
     */
    public function getIsActiveAttribute()
    {
        return $this->active_flag == 1;
    }
}