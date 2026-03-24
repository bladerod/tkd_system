<?php
// app/Models/Branch.php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Branch extends Model {

    protected $table = 'branches';
    protected $primaryKey = 'id';
    public $timestamps = true;

   protected $fillable = [
    'name','code','address','city','province',
    'mobile','email','status'
];

    /**
     * Get the users for the branch.
     */
    public function users()
    {
        return $this->hasMany(User::class, 'branch_id', 'id');
    }
    public function students(): HasMany { return $this->hasMany(Student::class); }
    public function classes(): HasMany { return $this->hasMany(Classes::class); }
    public function devices(): HasMany { return $this->hasMany(Device::class); }
}
