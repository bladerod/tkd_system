<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatThread;
use App\Models\ChatMessage;

class ChatController extends Controller
{

    public function index()
    {

        $threads = ChatThread::with(['messages.sender'])->get();

        $thread = $threads->first();

        return view('chat', compact('threads','thread'));

    }

    public function show($id)
    {

        $threads = ChatThread::with(['messages.sender'])->get();

        $thread = ChatThread::with(['messages.sender'])
                    ->findOrFail($id);

        return view('chat', compact('threads','thread'));

    }

    public function send(Request $request,$id)
    {

        $request->validate([
            'message'=>'required'
        ]);

        ChatMessage::create([
    'thread_id' => $id,
    'sender_user_id' => auth()->id(),
    'message' => $request->message,
    'sent_at' => now()
]);

        return redirect()->route('chat.show',$id);

    }

}