<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="../assets/img/logo.png">
    <title>TKD | Login</title>
    @vite(['resources/css/app.css', 'resources/css/login.css'])
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-md w-96">
        <!-- Logo or Title -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-[#1C1C1D]">TKD</h1>
        </div>

        <form action="{{ route('login.submit') }}" method="POST" id="loginForm">
            @csrf
            
            <!-- Username Field -->
            <div class="mb-4">
                <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640">
                            <path fill="currentColor" d="M240 192C240 147.8 275.8 112 320 112C364.2 112 400 147.8 400 192C400 236.2 364.2 272 320 272C275.8 272 240 236.2 240 192zM448 192C448 121.3 390.7 64 320 64C249.3 64 192 121.3 192 192C192 262.7 249.3 320 320 320C390.7 320 448 262.7 448 192zM144 544C144 473.3 201.3 416 272 416L368 416C438.7 416 496 473.3 496 544L496 552C496 565.3 506.7 576 520 576C533.3 576 544 565.3 544 552L544 544C544 446.8 465.2 368 368 368L272 368C174.8 368 96 446.8 96 544L96 552C96 565.3 106.7 576 120 576C133.3 576 144 565.3 144 552L144 544z"/>
                        </svg>
                    </span>
                    <input type="text" 
                           name="username" 
                           id="username" 
                           value="{{ old('username') }}"
                           placeholder="Enter your username"
                           class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#1C1C1D] focus:border-transparent @error('username') border-red-500 @enderror"
                           required
                           autofocus>
                </div>
               
            </div>
            
            <!-- Password Field -->
            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </span>
                    <input type="password" 
                           name="password" 
                           id="password" 
                           placeholder="Enter your password"
                           class="w-full pl-10 pr-10 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#1C1C1D] focus:border-transparent @error('password') border-red-500 @enderror"
                           required>
                    <button type="button" 
                            id="togglePassword" 
                            class="absolute inset-y-0 right-0 flex items-center pr-3 focus:outline-none">
                        <svg id="eyeIcon" class="h-5 w-5 text-gray-400 hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </div>
                @error('username')
                    <p class="text-red-500 text-xs mt-1">Invalid username and/or password.</p>
                @enderror
            </div>
            
            <button type="submit" 
                    class="w-full bg-[#1C1C1D] text-white py-2 px-4 rounded-md hover:bg-[#535353] transition duration-200 font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#1C1C1D] flex items-center justify-center"
                    id="loginButton">
                <span class="flex items-center justify-center" id="buttonContent">
                    <span id="buttonText">Login</span>
                    <span id="buttonSpinner" class="hidden">
                        <div class="spinner"></div>
                    </span>
                </span>
            </button>
        </form>
    </div>

    @vite(['resources/js/app.js', 'resources/js/login.js'])
</body>
</html>