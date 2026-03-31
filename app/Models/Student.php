<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $table = 'students';

    // Disable the updated_at timestamp since it's missing from your schema
    const UPDATED_AT = null;

    protected $fillable = [
        'branch_id', 'student_code', 'first_name', 'last_name', 'middle_name',
        'birthdate', 'gender', 'photo_url', 'current_belt', 'join_date',
        'status', 'medical_notes', 'allergies', 'emergency_contact_name',
        'emergency_contact_mobile', 'primary_parent_id'
    ];
}
