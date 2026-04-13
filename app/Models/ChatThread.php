<?php
// app/Models/ChatThread.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatThread extends Model
{
    protected $table = 'chat_threads';
    public $timestamps = false;

    protected $fillable = [
        'type',
        'class_id',
        'name',
        'created_at'
    ];

    public function messages()
{
    return $this->hasMany(ChatMessage::class, 'thread_id')
                ->orderBy('sent_at', 'asc');
}

    public function participants()
    {
        return $this->belongsToMany(User::class, 'chat_participants', 'thread_id', 'user_id');
    }
}
