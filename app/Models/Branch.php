<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $table = 'branches';
    protected $primaryKey = 'id';
    public $timestamps = true;
    
    protected $fillable = [
        'name',
        'code',
        'address',
        'city',
        'mobile',
        'email',
        'status'
    ];

    /**
     * Get the users for the branch.
     */
    public function users()
    {
        return $this->hasMany(User::class, 'branch_id', 'id');
    }
}