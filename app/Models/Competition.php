<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Competition extends Model
{
    use HasFactory;

    protected $table = 'competitions';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'location',
        'date',
        'organizer',
        'level'
    ];

    protected $casts = [
        'date' => 'date',
        'level' => 'string'
    ];

    public function entries()
    {
        return $this->hasMany(CompetitionEntry::class, 'competition_id');
    }

    public function getTotalParticipantsAttribute()
    {
        return $this->entries()->count();
    }

    public function getMedalCountsAttribute()
    {
        $medals = [
            'gold' => $this->entries()->where('medal', 'gold')->count(),
            'silver' => $this->entries()->where('medal', 'silver')->count(),
            'bronze' => $this->entries()->where('medal', 'bronze')->count(),
        ];
        return $medals;
    }

    public function getMedalSummaryAttribute()
    {
        $medals = $this->medal_counts;
        $parts = [];
        if ($medals['gold'] > 0) $parts[] = $medals['gold'] . ' Gold';
        if ($medals['silver'] > 0) $parts[] = $medals['silver'] . ' Silver';
        if ($medals['bronze'] > 0) $parts[] = $medals['bronze'] . ' Bronze';
        return implode(', ', $parts) ?: 'No medals';
    }

    public function getResultsSummaryAttribute()
    {
        $results = $this->entries()
            ->select('result', DB::raw('count(*) as total'))
            ->groupBy('result')
            ->pluck('total', 'result')
            ->toArray();
        
        if (empty($results)) {
            return 'No results yet';
        }
        
        $summary = [];
        if (isset($results['win'])) $summary[] = $results['win'] . ' Wins';
        if (isset($results['loss'])) $summary[] = $results['loss'] . ' Losses';
        if (isset($results['draw'])) $summary[] = $results['draw'] . ' Draws';
        if (isset($results['pending'])) $summary[] = $results['pending'] . ' Pending';
        
        return implode(', ', $summary);
    }
}