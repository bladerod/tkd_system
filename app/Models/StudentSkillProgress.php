<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentSkillProgress extends Model
{
    protected $table = 'student_skill_progress';
    public $timestamps = false;

    protected $fillable = [
        'student_id', 'skill_name', 'proficiency_level', 'last_assessed'
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}