<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    // Connect to your database view
    protected $table = 'student_overview';

    // Make sure Eloquent doesn't expect timestamps if the view doesn't have them
    public $timestamps = false;

    protected $fillable = [
        'id',
        'student_name',
        'current_belt',
        'status',
        'parent_name',
        'balance',
        'attendance',
    ];
}
