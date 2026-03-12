<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $table = 'branches';
    
    protected $fillable = [
        'name', 'code', 'address', 'city', 'province', 
        'mobile', 'email', 'status'
    ];

    protected $casts = [
        'status' => 'boolean',
        'created_at' => 'datetime',
    ];

    // Relationships
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function classes()
    {
        return $this->hasMany(Classes::class);
    }

    public function devices()
    {
        return $this->hasMany(Device::class);
    }

    public function announcements()
    {
        return $this->hasMany(Announcement::class, 'branch_id');
    }
}