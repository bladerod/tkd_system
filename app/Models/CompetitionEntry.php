<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompetitionEntry extends Model
{
    use HasFactory;

    protected $table = 'competition_entries';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'competition_id',
        'student_id',
        'instructor_id',
        'category',
        'division',
        'result',
        'medal',
        'remarks'
    ];

    protected $casts = [
        'result' => 'string',
        'medal' => 'string'
    ];

    public function competition()
    {
        return $this->belongsTo(Competition::class, 'competition_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function instructor()
    {
        return $this->belongsTo(Instructor::class, 'instructor_id');
    }
}