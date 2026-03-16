<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentEvaluation extends Model
{
    protected $table = 'student_evaluations';
    public $timestamps = false;

    protected $fillable = [
        'student_id', 'evaluator_user_id', 'evaluation_date', 'comments', 'overall_rating'
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function evaluator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'evaluator_user_id');
    }
}