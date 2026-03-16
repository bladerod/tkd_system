<?php
// app/Models/Invoice.php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
<<<<<<< HEAD
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
=======
use Illuminate\Database\Eloquent\Relations\BelongsTo, HasMany;
>>>>>>> 988bf1c3a61171e4a6b56431c3c5e0d03b1c21f8

class Invoice extends Model {
    protected $fillable = ['student_id', 'subscription_id', 'invoice_no', 'billing_period_start', 'billing_period_end', 'amount', 'discount', 'penalty', 'total_due', 'due_date', 'status'];

    public function student(): BelongsTo { return $this->belongsTo(Student::class); }
    public function subscription(): BelongsTo { return $this->belongsTo(StudentSubscription::class); }
    public function payments(): HasMany { return $this->hasMany(Payment::class); }
}
