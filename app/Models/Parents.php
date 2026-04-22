<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Parents extends Model
{
    use HasFactory;

    protected $table = "parents";

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        "user_id",
        "emergency_contact",
        "relationship_note",
        "address",
        "id_verified_flag",
        "created_at"
    ];

    protected $casts = [
        "created_at" => "datetime",
        "id_verified_flag" => "boolean"
    ];

    /**
     * Get the user associated with the parent.
     */
public function students() {
    return $this->hasMany(Student::class, 'primary_parent_id', 'user_id');
}

public function user()
{
    return $this->belongsTo(\App\Models\User::class);
}

}
