<?php
// app/Models/Student.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

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
    public function parents()
    {
        return $this->belongsToMany(
            Parents::class,
            'parent_students',
            'student_id',
            'parent_id'
        )->withPivot('relationship', 'is_primary');
    }

    /**
     * Get the branch that owns the student.
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'branch_id');
    }
}