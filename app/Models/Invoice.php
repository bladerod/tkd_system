<?php
// app/Models/Invoice.php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model {
    protected $table = "invoices";
    protected $fillable = ['student_id', 'subscription_id', 'invoice_no', 'billing_period_start', 'billing_period_end', 'amount', 'discount', 'penalty', 'total_due', 'due_date', 'status'];

    public function student(): BelongsTo { return $this->belongsTo(Student::class, 'student_id', 'id'); }
    public function parent(): BelongsTo { return $this->belongsTo(Parents::class); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function subscription(): BelongsTo { return $this->belongsTo(StudentSubscription::class); }
    public function payments()
    {
        return $this->hasMany(Payment::class, 'invoice_id', 'id');
    }
}
