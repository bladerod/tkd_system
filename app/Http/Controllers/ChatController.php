<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatThread;
use App\Models\ChatMessage;

class ChatController extends Controller
{
    public function index()
    {
        $threads = auth()->user()->threads()
            ->with(['messages.sender'])
            ->get();

        return view('chat', compact('threads'));
    }

    public function show($id)
    {
        $threads = auth()->user()->threads()
            ->with(['messages.sender'])
            ->get();

        $thread = ChatThread::with([
            'messages.sender',
            'participants'
        ])->findOrFail($id);

        return view('chat', compact('threads', 'thread'));
    }

    public function send(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string'
        ]);

        ChatMessage::create([
            'thread_id' => $id,
            'sender_user_id' => auth()->id(),
            'message' => $request->message,
            'sent_at' => now()
        ]);

        return redirect()->route('chat.show', $id);
    }
}
