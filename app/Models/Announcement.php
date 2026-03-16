<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = [
        'created_by_user_id', 
        'target_type', 
        'class_id', 
        'belt_level', 
        'branch_id', 
        'channel', 
        'title', 
        'message', 
        'publish_date', 
        'expire_date'
    ];

    protected $casts = [
        'publish_date' => 'date',
        'expire_date' => 'date',
    ];

    // Relationships
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function class()
    {
        return $this->belongsTo(Classes::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('publish_date', '<=', now())
                     ->where(function($q) {
                         $q->whereNull('expire_date')
                           ->orWhere('expire_date', '>=', now());
                     });
    }

    public function scopeForUser($query, User $user)
    {
        return $query->where(function($q) use ($user) {
            $q->where('target_type', 'all')
              ->orWhere(function($q2) use ($user) {
                  $q2->where('target_type', 'branch')
                     ->where('branch_id', $user->branch_id);
              });
        });
    }
}