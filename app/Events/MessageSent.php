<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use SerializesModels;

    public $message;

    public function __construct($message)
    {
        $this->message = $message->load('sender');
    }

    public function broadcastOn()
    {
        return new Channel('chat.' . $this->message->thread_id);
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->message->id,
            'message' => $this->message->message,
            'sender' => [
                'id' => $this->message->sender->id,
                'fname' => $this->message->sender->fname,
                'lname' => $this->message->sender->lname,
            ],
            'sent_at' => $this->message->sent_at,
        ];
    }
}
