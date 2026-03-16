<?php
// app/Models/Branch.php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Branch extends Model {
    protected $fillable = ['name', 'code', 'address', 'city', 'province', 'mobile', 'email', 'status'];

<<<<<<< HEAD
=======
    protected $table = 'branches';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'name',
        'code',
        'address',
        'city',
        'mobile',
        'email',
        'status'
    ];

    /**
     * Get the users for the branch.
     */
    public function users()
    {
        return $this->hasMany(User::class, 'branch_id', 'id');
    }
>>>>>>> 988bf1c3a61171e4a6b56431c3c5e0d03b1c21f8
    public function users(): HasMany { return $this->hasMany(User::class); }
    public function students(): HasMany { return $this->hasMany(Student::class); }
    public function classes(): HasMany { return $this->hasMany(Classes::class); }
    public function devices(): HasMany { return $this->hasMany(Device::class); }
}
