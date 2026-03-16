<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>TKD Chat</title>

@vite(['resources/css/app.css'])
@vite(['resources/css/chat.css'])
@vite(['resources/css/dashboard.css'])

</head>

<body>

@include("includes.navbar")

<div class="separate">

@include('includes.sidebar')

<div class="main-content">

<div class="chat-container">

<!-- ================= CHAT LIST ================= -->
<div class="chat-list">

<h2 class="chat-title">Chat Module</h2>

@if($threads->count())

@foreach($threads as $t)

<a href="{{ route('chat.show',$t->id) }}" class="chat-item">

<div class="chat-avatar"></div>

<div class="chat-info">

<h4>{{ ucfirst($t->type) }} Chat</h4>

<p class="last-message">

@if($t->messages->last())

{{ $t->messages->last()->sender->name }} :
{{ $t->messages->last()->message }}

@else

No messages yet

@endif

</p>

</div>

</a>

@endforeach

@else

<p class="no-chat">No chats available</p>

@endif

</div>


<!-- ================= CHAT WINDOW ================= -->
<div class="chat-window">

@if(isset($thread))

<div class="chat-header">

<div class="chat-user">

<div class="chat-avatar large"></div>

<div>

<h3>{{ ucfirst($thread->type) }} Chat</h3>

<span class="participants">
{{ $thread->participants->count() }} Participants
</span>

</div>

</div>

</div>


<!-- ================= MESSAGES ================= -->

<div class="chat-messages">

@if($thread->messages->count())

@foreach($thread->messages as $msg)

<div class="message {{ $msg->sender_user_id == auth()->id() ? 'own' : 'other' }}">

<div class="message-box">

<span class="sender">

{{ $msg->sender->name }}

</span>

<p>{{ $msg->message }}</p>

</div>

</div>

@endforeach

@else

<p class="no-message">Start the conversation</p>

@endif

</div>


<!-- ================= MESSAGE INPUT ================= -->

<form action="{{ route('chat.send',$thread->id) }}" method="POST">

@csrf

<div class="chat-input">

<input
type="text"
name="message"
placeholder="Type a message..."
required
>

<button type="submit">

<i class="fa-solid fa-paper-plane"></i>
Send

</button>

</div>

</form>

@else

<div class="no-thread">

Select a chat to start messaging

</div>

@endif

</div>

</div>

</div>

</div>

<script src="//unpkg.com/alpinejs" defer></script>

</body>
</html>
