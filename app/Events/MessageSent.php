<?php

namespace App\Events;

use App\Models\ChatMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public ChatMessage $message;

    public function __construct(ChatMessage $message)
    {
        $this->message = $message->load('sender');
    }

    public function broadcastOn(): Channel
    {
        return new Channel('chat.' . $this->message->thread_id);
    }

    public function broadcastAs(): string
    {
        return 'MessageSent';
    }

    public function broadcastWith(): array
    {
        return [
            'message' => [
                'id'             => $this->message->id,
                'thread_id'      => $this->message->thread_id,
                'sender_user_id' => $this->message->sender_user_id,
                'message'        => $this->message->message,
                'sent_at'        => $this->message->sent_at,
                'sender'         => [
                    'id'       => $this->message->sender->id,
                    'username' => $this->message->sender->username,
                    'name'     => $this->message->sender->name,
                ],
            ],
        ];
    }
}
