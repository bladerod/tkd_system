<?php
// app/Models/ClubProfile.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClubProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'club_name',
        'club_acronym',
        'founded_year',
        'club_description',
        'email',
        'contact_number',
        'club_address',
        'logo_url',
        'website_url',
        'facebook_url',
        'instagram_url',
        'tax_id'
    ];

    // Get the first/club profile (assuming only one record)
    public static function getClubProfile()
    {
        return self::first() ?? new self();
    }
}