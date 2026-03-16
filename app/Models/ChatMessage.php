<?php
// app/Models/ChatMessage.php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatMessage extends Model {
    protected $fillable = ['thread_id', 'sender_user_id', 'message', 'attachment_url', 'sent_at'];
    protected $casts = ['sent_at' => 'datetime'];
    
    public function thread(): BelongsTo { return $this->belongsTo(ChatThread::class); }
    public function sender(): BelongsTo { return $this->belongsTo(User::class, 'sender_user_id'); }
}