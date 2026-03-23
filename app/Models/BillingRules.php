<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillingRules extends Model
{
    protected $table = 'billing_rules';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $fillable = [
        'monthly_fee',
        'enrollment_fees',
        'uniform_fees',
        'belt_promotion_fees',
        'competition_fees',
        'billing_cycle',
        'due_date_rule',
        'grace_period',
        'late_fees_type',
        'late_fee_amount',
        'allow_partial_payment',
        'auto_mark_overdue',
        'auto_generate_monthly_invoice',

    ];
    protected $casts = [
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
    ];

    // public function display()
    // {
    //     return $this->hasMany(a,b);
    // }
}
