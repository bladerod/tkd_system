<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Plans extends Model
{
    protected $table = 'plans';
    public $timestamps = true;
    protected $primaryKey = 'id';
    protected $fillable = [
        'class_id',
        'plan_name',
        'description',
        'session_type',   
        'sessions_count',  
        'expiry_value',    
        'expiry_unit',     
        'session_per_week',
        'monthly_price',
        'unlimitted_flag',
        'billing_cycle',
        'active_flag',
        'created_at',
        'updated_at',
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    public function subscriptions(): HasMany { return $this->hasMany(StudentSubscription::class); }
    public function relatedClass(): BelongsTo 
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }
}
