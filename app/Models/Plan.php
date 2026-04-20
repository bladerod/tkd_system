<?php
// app/Models/Plan.php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model {
    protected $table = 'plans';
    public $timestamps = true;
    protected $primaryKey = 'id';
    protected $fillable = [
        'plan_name',
        'description',
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
}
