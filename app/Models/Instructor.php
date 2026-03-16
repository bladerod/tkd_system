<?php
// app/Models/Instructor.php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo, HasMany;

class Instructor extends Model {
    protected $fillable = ['user_id', 'rank_belt', 'certification_level', 'specialization', 'hire_date', 'bio', 'active_flag'];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function primaryClasses(): HasMany { return $this->hasMany(Classes::class, 'primary_instructor_id'); }
    public function assistantClasses(): HasMany { return $this->hasMany(Classes::class, 'assistant_instructor_id'); }
    public function evaluations(): HasMany { return $this->hasMany(StudentEvaluation::class); }
    public function skillChecks(): HasMany { return $this->hasMany(StudentSkillProgress::class); }
    public function examApprovals(): HasMany { return $this->hasMany(BeltExamResult::class, 'approved_by'); }
    public function competitionEntries(): HasMany { return $this->hasMany(CompetitionEntry::class); }
}
