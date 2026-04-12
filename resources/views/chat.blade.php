<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @vite(['resources/js/app.js'])
    <title>TrainNova | Chat</title>
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
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
                <div id="chatModal" class="chat-modal">
    <div class="chat-modal-content">
        <h3>Create Chat</h3>

        <form action="{{ route('chat.create') }}" method="POST">
            @csrf

            <input type="text" name="name" placeholder="Group name (optional)" class="input">

            <div class="user-list">
                @foreach($users as $user)
                    @if($user->id != auth()->id())
                        <label class="user-item">
                            <input type="checkbox" name="participants[]" value="{{ $user->id }}">
                            {{ $user->name }}
                        </label>
                    @endif
                @endforeach
            </div>

            <button type="submit" class="create-btn">Create Chat</button>
        </form>

        <button onclick="closeModal()" class="close-btn">✖</button>
    </div>
</div>
                <!-- ================= CHAT LIST ================= -->
                <div class="chat-list">

                    <div class="mt-5" style="display:flex; justify-content:space-between; align-items:center;">
    <h2 class="chat-title font-bold text-lg">Chat Module</h2>
    <button onclick="openModal()" class="new-chat-btn">
        <i class="fa fa-plus"></i>
    </button>
</div>
                    @if(isset($threads) && $threads->count())
                        @foreach($threads as $t)
                            <a href="{{ route('chat.show', $t->id) }}" class="chat-item">
                                <div class="chat-avatar">

                                </div>
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

        @if($msg->sender_user_id != auth()->id())
            <span class="sender-name">{{ $msg->sender->username }}</span>
        @endif

        <div class="message-bubble">
            {{ $msg->message }}
        </div>

        <span class="message-time">
            {{ $msg->sent_at->format('h:i A') }}
        </span>



    </div>
@endforeach
                            @else
                                <p class="no-message">Start the conversation</p>
                            @endif
                        </div>
                        <!-- ================= MESSAGE INPUT ================= -->
                        <form action="{{ route('chat.send', $thread->id) }}" method="POST">
                            @csrf
                            <div class="chat-input">
                                <input type="text" name="message" placeholder="Type a message..." required>
                                <button type="submit" class="send-btn">
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
    @vite(['resources/js/user.js', 'resources/js/navbarDrop.js'])

   <script>
document.addEventListener("DOMContentLoaded", function () {

    const threadId = "{{ $thread->id ?? null }}";

    console.log("Thread ID:", threadId);

    if (threadId) {
        Echo.channel('chat.' + threadId)
            .listen('.MessageSent', (e) => { // 👈 NOTE THE DOT

                console.log("EVENT RECEIVED:", e); // 👈 DEBUG

                const msg = e.message;

                const container = document.querySelector('.chat-messages');

                const isOwn = msg.sender_user_id == {{ auth()->id() }} ? 'own' : 'other';

                const html = `
                    <div class="message ${isOwn}">
                        ${isOwn === 'other' ? `<span class="sender-name">${msg.sender.username}</span>` : ''}
                        <div class="message-bubble">${msg.message}</div>
                        <span class="message-time">
                            ${new Date(msg.sent_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}
                        </span>
                    </div>
                `;

                container.insertAdjacentHTML('beforeend', html);
                container.scrollTop = container.scrollHeight;
            });
    }
});
</script>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const chatBox = document.querySelector(".chat-messages");
        if (chatBox) {
            chatBox.scrollTop = chatBox.scrollHeight;
        }
    });
</script>

<script>
function openModal() {
    document.getElementById('chatModal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('chatModal').style.display = 'none';
}
</script>
</body>
</html>
