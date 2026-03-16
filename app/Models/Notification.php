<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'notifications';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'is_read',
        'read_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    /**
     * Indicates if the model should be timestamped.
     * The SQL schema uses created_at but does not explicitly show updated_at.
     */
    public $timestamps = true;

    /**
     * Get the user that owns the notification.
     * This links to the 'users' table via user_id.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}