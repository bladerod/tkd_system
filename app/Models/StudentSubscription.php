<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentSubscription extends Model
{
    protected $table = 'student_subscriptions';
    public $timestamps = false;

    protected $fillable = [
        'student_id', 'plan_id', 'start_date', 'end_date', 'status'
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}