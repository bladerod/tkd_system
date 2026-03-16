<?php
// app/Models/Parents.php

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
        // FIX 4: Ang primary key ng `users` table ay `id`, hindi `user_id`
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Get the students associated with the parent through parent_students table.
     */
    public function students()
    {
        return $this->belongsToMany(
            Student::class,
            'parent_students', // pivot table
            'parent_id',        // foreign key on pivot table
            'student_id'        // related key on pivot table
        )->withPivot('relationship', 'is_primary');
    }
}