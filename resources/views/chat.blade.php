<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TKD - Billing Rules</title>
    @vite(['resources/css/app.css'])
     @vite(['resources/css/chat.css'])
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    @vite(['resources/css/dashboard.css'])
    @vite(['resources/css/attendance.css'])
<title>Chat</title>
</head>
<body>

@include("includes.navbar")
<div class="separate">


@include('includes.sidebar')

<div class="main-content">

    <div class="chat-container">

        <!-- Chat List -->
        <div class="chat-list">
            <h2 class="text-[#1C1C1D] font-medium text-xl">Chat Module</h2>

            <div class="chat-item active">
                <div class="avatar parent"></div>
                <div class="chat-info">
                    <h4 >Parent Chats</h4>
                    <p>You : Hi</p>
                </div>
            </div>

            <div class="chat-item">
                <div class="avatar student"></div>
                <div class="chat-info">
                    <h4>Student Chats</h4>
                    <p>You : Hi</p>
                </div>
            </div>

            <div class="chat-item">
                <div class="avatar class"></div>
                <div class="chat-info">
                    <h4>Class Groups</h4>
                    <p>You : Hi</p>
                </div>
            </div>

            <div class="chat-item">
                <div class="avatar coach"></div>
                <div class="chat-info">
                    <h4>Coach Groups</h4>
                    <p>You : Hi</p>
                </div>
            </div>

        </div>

        <!-- Chat Window -->
        <div class="chat-window">

            <div class="chat-header">
                <div class="chat-user">
                    <div class="avatar parent"></div>
                    <div>
                        <h3>Parent Chats</h3>
                        <span>8 Participants</span>
                    </div>
                </div>
            </div>

            <!-- Messages -->
             <div class="chat-messages">
            </div>

            <div class="chat-input">
                <button class="icon-btn"><i class="fa-solid fa-link"></i></button>
                <button class="icon-btn"><i class="fa-solid fa-microphone"></i></button>
                <input type="text" placeholder="Type a message...">
                <button class="send-btn ">Send</button>
            </div>

        </div>

    </div>

</div>
</div>
</body>
    <script src="//unpkg.com/alpinejs" defer></script>
    @vite(['resources/js/dashboard.js'])
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    @vite(['resources/css/app.css'])
</html>
