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
        // 1. Validate the request
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // 2. Attempt login with status = 1 (Active)
        $credentials = [
            'email' => $request->email, 
            'password' => $request->password,
            'status' => 1 // Only users with status 1 can log in
        ];

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            
            // 3. Keep your existing Admin-only check
            if (Auth::user()->role !== 'admin') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                throw ValidationException::withMessages([
                    'email' => ['Access denied. Only administrators are allowed to log in.'],
                ]);
            }

            // 4. Handle successful login logic
            $request->session()->regenerate();
            
            $user = Auth::user();
            User::where('id', $user->id)->update([
                'last_login_at' => Carbon::now()
            ]);
            
            return redirect()->intended('/dashboard')
                ->with('success', 'Welcome back, ' . $user->fname . '!');
        }

        // 5. Failed authentication
        // If the status is 0, this error will also trigger
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