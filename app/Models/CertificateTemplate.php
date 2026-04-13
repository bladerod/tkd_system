<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CertificateTemplate extends Model
{
    protected $table = 'certificate_templates';

    protected $fillable = [
        'name',
        'type',
        'layout',
        'is_active'
    ];

    protected $casts = [
        'layout' => 'array'
    ];
}
