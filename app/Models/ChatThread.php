<?php
// app/Models/ChatThread.php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatThread extends Model {
    protected $fillable = ['type', 'class_id'];

    public function participants() { return $this->belongsToMany(User::class, 'chat_participants', 'thread_id', 'user_id'); }
    public function messages()
    {
        return $this->hasMany(ChatMessage::class,'thread_id');
    }
    public function class(): BelongsTo { return $this->belongsTo(Classes::class); }
}
