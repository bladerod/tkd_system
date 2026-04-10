<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\ChatThread;
use App\Models\ChatMessage;

class ChatController extends Controller
{public function index()
{
    $users = User::all();

    $threads = auth()->user()->threads()
        ->with(['messages.sender'])
        ->get();

    return view('chat', [
        'threads' => $threads,
        'thread' => null,
        'users' => $users // ✅ ADD THIS
    ]);
}

    public function show($id)
    {
        $users = User::all();
        $threads = auth()->user()->threads()
            ->with(['messages.sender'])
            ->get();

        $thread = ChatThread::with(['messages.sender', 'participants'])
            ->findOrFail($id);

        // ✅ mark as seen (optimized)
        ChatMessage::where('thread_id', $id)
            ->where('sender_user_id', '!=', auth()->id())
            ->where('is_seen', false)
            ->update(['is_seen' => true]);

        return view('chat', compact('threads', 'thread', 'users'));
    }

    public function send(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string'
        ]);

        $message = ChatMessage::create([
    'thread_id' => $id,
    'sender_user_id' => auth()->id(),
    'message' => $request->message,
    'sent_at' => now(),
    'is_seen' => false
]);

broadcast(new MessageSent($message));

        return redirect()->route('chat.show', $id);
    }

    public function create(Request $request)
{
    $request->validate([
        'participants' => 'required|array|min:1'
    ]);

    // include self
    $participants = $request->participants;
    $participants[] = auth()->id();

    // create thread
    $thread = ChatThread::create([
        'type' => count($participants) > 2 ? 'group' : 'private'
    ]);

    // attach users
    $thread->participants()->attach($participants);

    return redirect()->route('chat.show', $thread->id);
}
}
