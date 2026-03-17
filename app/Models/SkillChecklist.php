<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SkillChecklist extends Model
{
    protected $table = "skill_checklist";
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        "id",
        "belt_level",
        "skill_name",
        "description",
    ];
}
