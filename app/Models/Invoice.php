<?php
// app/Models/Invoice.php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo, HasMany;

class Invoice extends Model {
    protected $fillable = ['student_id', 'subscription_id', 'invoice_no', 'billing_period_start', 'billing_period_end', 'amount', 'discount', 'penalty', 'total_due', 'due_date', 'status'];

    public function student(): BelongsTo { return $this->belongsTo(Student::class); }
    public function subscription(): BelongsTo { return $this->belongsTo(StudentSubscription::class); }
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
