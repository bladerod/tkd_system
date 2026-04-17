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

                <!-- ✅ MODAL -->
                <div id="chatModal" class="chat-modal">
                    <div class="chat-modal-content">

                        <button onclick="closeModal()" class="close-btn me-4 mt-2">
                            <i class="fa-solid fa-xmark"></i>
                        </button>

                        <h3 class="text-xl font-bold mb-1">Create Chat</h3>

                        <form action="{{ route('chat.create') }}" method="POST" id="chatForm" class="mt-5">
                            @csrf

                            <select name="type" class="input mb-2">
                                <option value="private" selected>Private Chat</option>
                                <option value="class">Class</option>
                                <option value="parent-staff">Parent - Staff</option>
                            </select>

                            <!-- 🔍 SEARCH -->
                            <input type="text" id="userSearch" placeholder="Search users..." class="input"
                                onkeyup="filterUsers()">

                            <!-- 👥 SELECTED USERS (chips) -->
                            <div id="selectedUsers" class="selected-users"></div>

                            <!-- 👤 USER LIST -->
                            <div class="user-list" id="userList">
                                @foreach($users as $user)
                                    @if($user->id != auth()->id())
                                        <div class="user-item"
                                            onclick="selectUser({{ $user->id }}, '{{ $user->fname }} {{ $user->lname }}')"
                                            data-name="{{ strtolower($user->fname . ' ' . $user->lname) }}"
                                            data-username="{{ strtolower($user->username) }}">

                                            <div class="avatar-wrapper">
                                                <div class="avatar">
                                                    @if ($user->photo_url)
                                                        <img class="user-profile" src="{{ asset('storage/'.$user->photo_url) }}" alt="">
                                                    @else
                                                        <img class="user-profile" src="{{ asset('storage/assets/profile.jpg') }}" alt="">
                                                    @endif
                                                </div>

                                                @if($user->isOnline())
                                                    <span class="online-dot"></span>
                                                @endif
                                            </div>

                                            <div>
                                                <div class="user-name">
                                                    {{ $user->fname }} {{ $user->lname }}
                                                </div>

                                                <small class="user-username">
                                                    @if($user->isOnline())
                                                        <span class="online-text">Active now</span>
                                                    @else
                                                        Last active {{ $user->last_seen?->diffForHumans() ?? 'offline' }}
                                                    @endif
                                                </small>
                                            </div>

                                        </div>
                                    @endif
                                @endforeach
                            </div>
                            <!-- hidden inputs -->
                            <div id="hiddenInputs"></div>

                            <button type="submit" class="create-btn" id="createBtn" disabled>
                                <span id="btnText">Start Chat</span>
                                <span id="btnLoader" class="loader" style="display:none;"></span>
                            </button>
                        </form>

                    </div>
                </div>

                <!-- ================= CHAT LIST ================= -->
                <div class="chat-list">

                    <div class="" style="display:flex; justify-content:space-between; align-items:center;">
                        <h2 class="chat-title font-bold text-lg mt-4">Chat Module</h2>

                        <!-- ✅ FIX BUTTON -->
                        <button type="button" onclick="openModal()" class="new-chat-btn" title="Create Chat">
                            <i class="fa fa-plus"></i>
                        </button>

                    </div>
                    <input class="rounded-xl " type="text" id="chatSearch" placeholder="Search chats..." class="input"
                        onkeyup="filterChats()">
                    @if(isset($threads) && $threads->count())
                        @foreach($threads as $t)
                            <a href="{{ route('chat.show', $t->id) }}" class="chat-item"
                                data-name="{{ strtolower($t->name ?? $t->type) }}">
                                <div class="chat-avatar">
                                    @php
                                        // Find the first participant in this thread who is NOT the logged-in user
                                        $otherUser = $t->participants->where('id', '!=', auth()->id())->first();
                                    @endphp
                                    @if ($otherUser && $otherUser->photo_url)
                                        <img class="avatar-profile" src="{{ asset('storage/'.$otherUser->photo_url) }}" alt="">
                                    @else
                                        <img class="avatar-profile" src="{{ asset('storage/assets/profile.jpg') }}" alt="">
                                    @endif
                                </div>
                                <div class="chat-info">
                                    <h4>
                                        {{ $t->name ?? ucfirst(str_replace('-', ' ', $t->type)) }}
                                    </h4>

                                    <p class="last-message">
                                        @if($t->messages->last())
                                            {{ $t->messages->last()->sender->name }} :
                                            {{ \Illuminate\Support\Str::limit($t->messages->last()->message, 10) }}
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
                            <h3>{{ ucfirst($thread->type) }} Chat</h3>
                            <p style="font-size:12px; color:gray;">{{ $thread->participants()->count() }} participants</p>
                        </div>

                        <div class="chat-messages">
                            @if($thread->messages->count())
                                @foreach($thread->messages as $msg)
                                    <div class="message {{ $msg->sender_user_id == auth()->id() ? 'own' : 'other' }}">
                                        <div class="message-bubble">{{ $msg->message }}</div>
                                        <div id="typingIndicator" style="font-size:12px; color:gray; padding-left:10px;"></div>
                                        @if($msg->sender_user_id == auth()->id())
                                            <span class="message-status">
                                                {{ $msg->is_seen ? 'Seen ✓✓' : 'Delivered ✓' }}
                                            </span>
                                        @endif
                                    </div>
                                @endforeach
                            @else
                                <div class="no-message">
                                    👋 Start the conversation
                                    <div id="typingIndicator" style="font-size:12px; color:gray; padding-left:10px;"></div>
                                </div>
                            @endif
                        </div>

                        <form action="{{ route('chat.send', $thread->id) }}" method="POST" class="chat-input">
                            @csrf

                            <input type="text" name="message" placeholder="Type a message..." required>

                            <button type="submit" class="send-btn">Send</button>
                        </form>

                    @else
                        <div class="flex h-screen items-center justify-center">
                            <p class="text-white font-bold">Select a chat</p>
                        </div>
                    @endif

                </div>

            </div>
        </div>
    </div>
    <script src="//unpkg.com/alpinejs" defer></script>
    @vite(['resources/js/navbarDrop.js'])
    <!-- ================= JS FIX ================= -->
    <script>
        function openModal() {
            document.getElementById('chatModal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('chatModal').style.display = 'none';
        }

        /* ✅ Close when clicking outside */
        window.onclick = function (event) {
            const modal = document.getElementById('chatModal');
            if (event.target === modal) {
                closeModal();
            }
        }

        /* ✅ ESC key close */
        document.addEventListener('keydown', function (e) {
            if (e.key === "Escape") {
                closeModal();
            }
        });
    </script>
    <script>
        function filterUsers() {
            const input = document.getElementById("userSearch").value.toLowerCase();
            const users = document.querySelectorAll(".user-item");

            users.forEach(user => {
                const name = user.getAttribute("data-name");
                const username = user.getAttribute("data-username");

                if (name.includes(input) || username.includes(input)) {
                    user.style.display = "block";
                } else {
                    user.style.display = "none";
                }
            });
        }
    </script>
    <script>
        let selected = [];

        // ✅ FIXED (NO RECURSION)
        function updateButtonState() {
            const btn = document.getElementById("createBtn");
            btn.disabled = selected.length === 0;
        }

        function selectUser(id, name) {
            if (selected.includes(id)) return;

            selected.push(id);

            // add chip
            const chip = document.createElement("div");
            chip.className = "user-chip";
            chip.id = "chip-" + id;
            chip.innerHTML = `
        ${name}
        <span onclick="removeUser(${id})">✖</span>
    `;

            document.getElementById("selectedUsers").appendChild(chip);

            // hidden input
            const input = document.createElement("input");
            input.type = "hidden";
            input.name = "participants[]";
            input.value = id;
            input.id = "input-" + id;

            document.getElementById("hiddenInputs").appendChild(input);

            updateButtonState(); // ✅ AFTER add
        }

        function removeUser(id) {
            selected = selected.filter(u => u !== id);

            document.getElementById("chip-" + id).remove();
            document.getElementById("input-" + id).remove();

            updateButtonState(); // ✅ AFTER remove
        }

        // ✅ KEEP ONLY ONE
        function filterUsers() {
            const input = document.getElementById("userSearch").value.toLowerCase();
            const users = document.querySelectorAll(".user-item");

            users.forEach(user => {
                const name = user.dataset.name;
                const username = user.dataset.username;

                user.style.display =
                    name.includes(input) || username.includes(input)
                        ? "flex"
                        : "none";
            });
        }

        // loading state
        document.getElementById("chatForm").addEventListener("submit", function () {
            const btn = document.getElementById("createBtn");
            const text = document.getElementById("btnText");
            const loader = document.getElementById("btnLoader");

            btn.disabled = true;
            text.style.display = "none";
            loader.style.display = "inline-block";
        });

        let typingTimer;

        document.querySelector('input[name="message"]').addEventListener('input', () => {

            clearTimeout(typingTimer);

            axios.post('/typing', {
                thread_id: "{{ $thread->id ?? '' }}"
            });

            typingTimer = setTimeout(() => { }, 2000);
        });

        Echo.channel('chat.' + threadId)
            .listen('.typing', (e) => {

                const typingBox = document.getElementById('typingIndicator');

                typingBox.innerHTML = `${e.user} is typing...`;

                setTimeout(() => {
                    typingBox.innerHTML = '';
                }, 2000);
            });
    </script>
</body>

</html>
