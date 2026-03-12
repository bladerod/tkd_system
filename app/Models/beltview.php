<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class beltview extends Model
{
    use HasFactory;
    protected $table = 'belt_levels';
    protected $fillable = ['name', 'rank_order','color_code'];
}
