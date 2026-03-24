<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class parentview extends Model
{
    use HasFactory;
    protected $table = 'parentviews';
    protected $fillable = ['id','fname', 'mobile','status'];
}
