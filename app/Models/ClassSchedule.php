<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClassSchedule extends Model
{
    protected $table = 'class_schedules';
    public $timestamps = false; // Schema does not include standard timestamps

    protected $fillable = [
        'class_id', 
        'day_of_week', 
        'start_time', 
        'end_time', 
        'instructor_user_id', 
        'room'
    ];

    public function class(): BelongsTo
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_user_id');
    }
}