<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClassStudent extends Model
{
    use HasFactory;

    protected $table = 'class_students';
    public $timestamps = false;

    protected $fillable = [
        'class_id',
        'student_id',
        'start_date',
        'end_date',
        'status'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function class(): BelongsTo
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    // Get status badge
    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'active' => '<span class="px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">Active</span>',
            'completed' => '<span class="px-2 py-1 text-xs font-semibold text-blue-700 bg-blue-100 rounded-full">Completed</span>',
            'dropped' => '<span class="px-2 py-1 text-xs font-semibold text-red-700 bg-red-100 rounded-full">Dropped</span>',
            default => '<span class="px-2 py-1 text-xs font-semibold text-gray-700 bg-gray-100 rounded-full">Unknown</span>'
        };
    }
}