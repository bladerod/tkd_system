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
        // Validate the request
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Attempt to log the user in
        if (Auth::attempt(['username' => $request->username, 'password' => $request->password], $request->filled('remember'))) {
            
            // Check if the authenticated user is an admin
            if (Auth::user()->role !== 'admin') {
                // Log the non-admin user out immediately
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                // Return an error message
                throw ValidationException::withMessages([
                    'username' => ['Access denied. Only administrators are allowed to log in.'],
                ]);
            }

            // Authentication passed and user IS an admin
            $request->session()->regenerate();
            
            // Update last login timestamp
            $user = Auth::user();
            User::where('id', $user->id)->update([
                'last_login_at' => Carbon::now()
            ]);
            
            // Redirect to dashboard with success message
            return redirect()->intended('/dashboard')
                ->with('success', 'Welcome back, ' . $user->fname . '!');
        }

        // Authentication failed (wrong username or password)
        throw ValidationException::withMessages([
            'username' => [trans('auth.failed')],
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