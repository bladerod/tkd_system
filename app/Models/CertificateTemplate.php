<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CertificateTemplate extends Model
{
    use HasFactory;

    protected $table = 'certificate_templates';

    protected $fillable = [
        'name',
        'type',        // Promotion | Dan | Competition | Participation
        'belt_level',  // White | Yellow | Green | Blue | Red | Black | null
        'status',      // active | draft
        'layout',      // JSON string — canvas object positions
    ];

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    // Always decode layout to array when reading
    public function getLayoutAttribute($value)
    {
        if (is_string($value)) {
            return json_decode($value, true) ?? ['objects' => []];
        }
        return $value ?? ['objects' => []];
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByBelt($query, string $belt)
    {
        return $query->where('belt_level', $belt);
    }
}
