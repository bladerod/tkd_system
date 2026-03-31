<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Instructor extends Model
{
    protected $table = 'instructors';

    protected $fillable = [
        'user_id',
        'fname',
        'lname',
        'email',
        'username',
        'contact',
        'password',
        'rank_belt',
        'certification_level',
        'specialization',
        'bio',
        'status',
        'active_flag',
        'photo'
    ];

    protected $hidden = [
        'password',
    ];

    public function user()
{
    return $this->belongsTo(User::class);
}
}
