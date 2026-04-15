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
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function students()
    {
        return $this->belongsToMany(Student::class,'parent_students');
    }
    public function children()
{
    return $this->hasMany(Student::class, 'primary_parent_id');
}

// public function billing()
// {
//     return $this->hasMany(Billing::class, 'parent_id');
// }

// public function payments()
// {
//     return $this->hasMany(Payment::class, 'parent_id');
// }
}
