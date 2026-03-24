<?php
// app/Models/Branding.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Branding extends Model
{
    use HasFactory;

    protected $fillable = [
        'logo_path',
        'logo_filename',
        'certificate_header_text',
        'certificate_signature_name',
        'signature_position',
        'official_seal_path',
        'official_seal_filename',
        'primary_color',
        'secondary_color',
        'accent_color',
        'favicon_path'
    ];

    // Get the first/branding settings (assuming only one record)
    public static function getBranding()
    {
        return self::first() ?? new self();
    }

    // Helper method to get logo URL
    public function getLogoUrlAttribute()
    {
        return $this->logo_path ? Storage::url($this->logo_path) : null;
    }

    // Helper method to get seal URL
    public function getSealUrlAttribute()
    {
        return $this->official_seal_path ? Storage::url($this->official_seal_path) : null;
    }
}