<?php
// app/Http/Controllers/LoginController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class LoginController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        return view('login');
    }

    /**
     * Handle a login request.
     */
    public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|string',
        'password' => 'required|string',
    ]);

    if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->filled('remember'))) {
        
        if (Auth::user()->role !== 'admin') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw ValidationException::withMessages([
                'email' => ['Access denied. Only administrators are allowed to log in.'],
            ]);
        }

        $request->session()->regenerate();
        
        $user = Auth::user();
        User::where('id', $user->id)->update([
            'last_login_at' => Carbon::now()
        ]);
        
        return redirect()->intended('/dashboard')
            ->with('success', 'Welcome back, ' . $user->name . '!');
    }

    throw ValidationException::withMessages([
        'email' => [trans('auth.failed')],
    ]);
}

    /**
     * Log the user out.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/')
            ->with('success', 'You have been logged out successfully.');
    }
}