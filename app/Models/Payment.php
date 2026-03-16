<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $table = 'payments';
    public $timestamps = false; // Schema uses 'payment_date' instead

    protected $fillable = [
        'invoice_id', 
        'amount', 
        'payment_method', 
        'transaction_reference', 
        'payment_date', 
        'status'
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }
}