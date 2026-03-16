<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FaceProfile extends Model
{
    protected $table = 'face_profiles';
    public $timestamps = false;

    protected $fillable = [
        'student_id', 'face_data', 'last_updated'
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}