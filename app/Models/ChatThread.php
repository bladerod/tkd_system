<?php
// app/Models/ChatThread.php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
<<<<<<< HEAD
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

=======
use Illuminate\Database\Eloquent\Relations\HasMany, BelongsTo;
>>>>>>> 988bf1c3a61171e4a6b56431c3c5e0d03b1c21f8

class ChatThread extends Model {
    protected $fillable = ['type', 'class_id'];
    
    public function participants() { return $this->belongsToMany(User::class, 'chat_participants', 'thread_id', 'user_id'); }
    public function messages(): HasMany { return $this->hasMany(ChatMessage::class, 'thread_id')->orderBy('sent_at'); }
    public function class(): BelongsTo { return $this->belongsTo(Classes::class); }
}