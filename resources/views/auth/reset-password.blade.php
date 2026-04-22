@php
    $branding = \App\Models\Branding::first();
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @if(isset($branding) && $branding->logo_path)
        <link rel="icon" href="{{ Storage::url($branding->logo_path) }}">
    @endif
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="../assets/img/logo.png">
    <title>TrainNova | Reset Password</title>
    @vite(['resources/css/app.css', 'resources/css/login.css'])
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 flex items-center justify-center min-h-screen font-sans antialiased">
    <div class="reset-card w-full max-w-md mx-4">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <!-- Subtle accent bar -->
            <div class="h-1 bg-gradient-to-r from-gray-700 to-gray-900"></div>
            
            <div class="p-8 md:p-10">
                <!-- Logo / Brand -->
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-semibold text-gray-800 tracking-tight">Create new password</h2>
                    <p class="text-gray-500 text-sm mt-2">Your new password must be different from previously used passwords.</p>
                </div>

                <form action="{{ route('password.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <input type="hidden" name="email" value="{{ $email }}">

                    <!-- New Password Field -->
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-gray-700 mb-1">New password</label>
                        <input type="password" name="password" required 
                               class="input-field w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-400 focus:border-transparent transition"
                               placeholder="••••••••">
                        @error('password')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Confirm Password Field -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Confirm password</label>
                        <input type="password" name="password_confirmation" required 
                               class="input-field w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-400 focus:border-transparent transition"
                               placeholder="••••••••">
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                            class="submit-btn w-full bg-gray-900 hover:bg-gray-800 text-white font-medium py-2.5 rounded-lg transition shadow-sm">
                        Reset password
                    </button>
                </form>

                <!-- Back to Login Link -->
                <div class="text-center mt-6">
                    <a href="{{ route('login') }}" class="text-sm text-gray-500 hover:text-gray-800 transition">
                        ← Back to login
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Footer note -->
        <p class="text-center text-gray-400 text-xs mt-6">
            TrainNova — Secure password reset
        </p>
    </div>

    @vite(['resources/js/app.js', 'resources/js/login.js'])
</body>
</html>