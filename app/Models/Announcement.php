<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Announcement extends Model
{
    use HasFactory;

    protected $table = 'announcements';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'created_by_user_id',
        'target_type',
        'class_id',
        'title',
        'message',
        'publish_date',
        'expire_date'
    ];

    protected $casts = [
        'publish_date' => 'date',
        'expire_date' => 'date',
    ];

    /**
     * Get the user who created the announcement.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by_user_id', 'id');
    }

    /**
     * Get the class associated with the announcement.
     */
    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id', 'id');
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'id', 'id');
    }
}