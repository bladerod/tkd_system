<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BeltLevel extends Model
{
    protected $table = 'belt_levels';
    public $timestamps = false;

    protected $fillable = ['name', 'rank_order', 'color_code'];
}