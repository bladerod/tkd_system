<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $table = "discount";
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'type',
        'value',
        'applicable_to',
        'valid_from',
        'valid_to',
        'status',
    ];
}
