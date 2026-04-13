<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    protected $table = 'chat_messages';
    public $timestamps = false;

    protected $fillable = [
        'thread_id',
        'sender_user_id',
        'message',
        'attachment_url',
        'sent_at',
        'is_seen' // ✅ IMPORTANT
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'is_seen' => 'boolean'
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_user_id');
    }

    public function thread()
    {
        return $this->belongsTo(ChatThread::class, 'thread_id');
    }
}
