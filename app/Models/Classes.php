<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{

    protected $table = "classes";
    protected $primaryKey = 'classes_id';
    public $timestamps = false;

    protected $fillable = [
        "classes_id",
        "branch_id",
        "class_name",
        "age_group",
        "level",
        "max_students",
        "primary_instructor_id",
        "assistant_instructor_id",
        "created_at",
        "status",
    ] ;

    protected $casts = [
        "created_at"=> "datetime",
    ];

}
