<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    protected $fillable = [
        'student_id',
        'certificate_type',
        'title',
        'description',
        'issued_date',
        'issued_by_user_id',
        'qr_code_value',
        'verification_url',
        'pdf_path'
    ];

    public $timestamps = false;

    public function student()
{
    return $this->belongsTo(Student::class, 'student_id');
}

    public function issuer()
    {
        return $this->belongsTo(User::class, 'issued_by_user_id');
    }
}
