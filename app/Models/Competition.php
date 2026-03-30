<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Competition extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location',
        'date',
        'organizer',
        'level',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    /**
     * Get the entries for the competition.
     */
    public function entries()
    {
        return $this->hasMany(CompetitionEntry::class);
    }
}