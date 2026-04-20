<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $table = 'payments';
    public $timestamps = true;
    protected $fillable = [
        'invoice_id', 
        'amount', 
        'payment_method', 
        'transaction_reference', 
        'paid_at', 
        'status'
    ];
    protected $casts = [
        'paid_at' => 'timestamp'
    ];
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }
}