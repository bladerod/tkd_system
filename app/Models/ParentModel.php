<?php
// app/Models/ParentModel.php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo, HasMany;

class ParentModel extends Model {
    protected $table = 'parents';
    protected $fillable = ['user_id', 'emergency_contact', 'relationship_note', 'address', 'id_verified_flag'];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function students() { return $this->belongsToMany(Student::class, 'parent_students', 'parent_id', 'student_id')
        ->withPivot('relationship', 'is_primary'); }
    public function primaryStudents(): HasMany { return $this->hasMany(Student::class, 'primary_parent_id'); }
}
