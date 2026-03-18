<?php
// app/Models/Classes.php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Classes extends Model {
    protected $table = 'classes';
    protected $fillable = ['branch_id', 'class_name', 'age_group', 'level', 'max_students', 'primary_instructor_id', 'assistant_instructor_id', 'status'];

   public function instructor()
    {
        return $this->belongsTo(Instructor::class,'primary_instructor_id');
    }

    public function students()
    {
        return $this->belongsToMany(Student::class,'class_students');
    }
}
