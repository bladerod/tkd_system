<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VwUser extends Model
{
    use HasFactory;

    protected $table = 'vwuser';
    protected $primaryKey = 'user_id';
    public $timestamps = false; // Views typically don't have timestamps
    
    protected $fillable = [
        'user_id',
        'branch_name',
        'role',
        'full_name',
        'email',
        'mobile_no',
        'password',
        'photo_url',
        'last_login_at',
        'created_at',
        'updated_at'
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'last_login_at' => 'datetime',
    ];
}