<?php
// app/Models/Branch.php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Branch extends Model {
    protected $fillable = ['name', 'code', 'address', 'city', 'province', 'mobile', 'email', 'status'];

    public function users(): HasMany { return $this->hasMany(User::class); }
    public function students(): HasMany { return $this->hasMany(Student::class); }
    public function classes(): HasMany { return $this->hasMany(Classes::class); }
    public function devices(): HasMany { return $this->hasMany(Device::class); }
}
