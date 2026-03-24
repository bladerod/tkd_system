<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Classes extends Model
{
    use HasFactory;

    protected $table = 'classes';

    protected $fillable = [
        'branch_id',
        'class_name',
        'age_group',
        'level',
        'max_students',
        'primary_instructor_id',
        'assistant_instructor_id',
        'status'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function primaryInstructor(): BelongsTo
    {
        return $this->belongsTo(Instructor::class, 'primary_instructor_id');
    }

    public function assistantInstructor(): BelongsTo
    {
        return $this->belongsTo(Instructor::class, 'assistant_instructor_id');
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(ClassSchedule::class, 'class_id');
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(ClassSession::class, 'class_id');
    }

    public function students(): HasMany
    {
        return $this->hasMany(ClassStudent::class, 'class_id');
    }

    // Accessor for student count
    public function getStudentCountAttribute(): int
    {
        return $this->students()->where('status', 'active')->count();
    }

    // Accessor for class status badge
    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'active' => '<span class="px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">Active</span>',
            'inactive' => '<span class="px-2 py-1 text-xs font-semibold text-yellow-700 bg-yellow-100 rounded-full">Inactive</span>',
            'cancelled' => '<span class="px-2 py-1 text-xs font-semibold text-red-700 bg-red-100 rounded-full">Cancelled</span>',
            default => '<span class="px-2 py-1 text-xs font-semibold text-gray-700 bg-gray-100 rounded-full">Unknown</span>'
        };
    }
}