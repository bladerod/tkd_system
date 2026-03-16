<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model; 

class BeltExam extends Model
{
    use HasFactory;

    protected $table = 'belt_exams'; 

    protected $fillable = [
        'exam_date', 
        'belt_level', 
        'class_id', 
        'chief_instructor_id', 
        'status'
    ];

    public $timestamps = false; 

    
}