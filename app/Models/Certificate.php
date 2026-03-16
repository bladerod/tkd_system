<?php
// app/Models/Certificate.php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Certificate extends Model {
    protected $fillable = ['student_id', 'certificate_type', 'title', 'description', 'issued_date', 'issued_by_user_id', 'qr_code_value', 'verification_url', 'pdf_path'];

    public function student(): BelongsTo { return $this->belongsTo(Student::class); }
    public function issuedBy(): BelongsTo { return $this->belongsTo(User::class, 'issued_by_user_id'); }
}
