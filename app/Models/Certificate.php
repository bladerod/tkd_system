<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    protected $table = 'certificates';

    protected $fillable = [
        'student_id',
        'template_id',
        'certificate_type',
        'title',
        'issued_date',
        'data',
        'qr_code_value'
    ];

    protected $casts = [
        'data' => 'array'
    ];

    public function template()
    {
        return $this->belongsTo(CertificateTemplate::class, 'template_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
