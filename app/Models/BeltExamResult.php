<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeltExamResult extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     * * @var string
     */
    protected $table = 'belt_exam_results';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'exam_id',
        'student_id',
        'score',
        'result',
        'remarks',
        'approved_by',
    ];

    /**
     * Indicates if the model should be timestamped.
     * Your SQL schema does not have created_at/updated_at for this table.
     */
    public $timestamps = false;

    /**
     * Get the exam that this result belongs to.
     */
    public function exam()
    {
        return $this->belongsTo(BeltExam::class, 'exam_id');
    }

    /**
     * Get the student who took the exam.
     */
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    /**
     * Get the user who approved this result.
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}