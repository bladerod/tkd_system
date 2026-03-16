<?php
// app/Models/Plan.php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model {
    protected $fillable = ['plan_name', 'description', 'sessions_per_week', 'monthly_price', 'unlimited_flag', 'billing_cycle', 'active_flag'];

    public function subscriptions(): HasMany { return $this->hasMany(StudentSubscription::class); }
}
