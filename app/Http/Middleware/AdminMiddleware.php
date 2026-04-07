<?php
// app/Http/Middleware/AdminMiddleware.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     * Allow ONLY admin and staff to access the system
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to access this page');
        }

        $user = Auth::user();
        
        // Allow both admin and staff roles
        if (!in_array($user->role, ['admin', 'staff'])) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Access denied. Admin or staff privileges required.');
        }

        // Check if user account is active
        if (!$user->status) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Your account is deactivated. Please contact administrator.');
        }

        return $next($request);
    }
}