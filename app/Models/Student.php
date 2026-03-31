<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Student extends Model
{
    use HasFactory;

    // Connect to your database view
    protected $table = 'students';

    // Make sure Eloquent doesn't expect timestamps if the view doesn't have them
    public $timestamps = false;
    public $primaryKey = 'id';
    protected $fillable = [
        'branch_id',
        'first_name',
        'last_name',
        'birthdate',
        'gender',
        'photo_url',
        'email',
        'password',
        'current_belt',
        'join_date',
        'medical_notes',
        'allergies',
        'emergency_contact_name',
        'emergency_contact_mobile',
        'primary_parent_id',
    ];
}
