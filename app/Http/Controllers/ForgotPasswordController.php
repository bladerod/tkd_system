<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;
use App\Mail\ResetPasswordEmail;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $token = Str::random(64);

        // Delete any old tokens for this email
        DB::table('password_resets')->where('email', $request->email)->delete();

        // Insert new token
        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        // Send the email (using the same Mail setup from your announcements!)
        Mail::to($request->email)->send(new ResetPasswordEmail($token, $request->email));

        return back()->with('success', 'We have emailed your password reset link!');
    }

    public function showResetForm($token, Request $request)
    {
        return view('auth.reset-password', ['token' => $token, 'email' => $request->email]);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:6|confirmed',
            'token' => 'required'
        ]);

        // Verify the token exists and matches the email
        $resetRecord = DB::table('password_resets')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$resetRecord) {
            return back()->withErrors(['email' => 'Invalid token or email.']);
        }

        // Update the user's password
        User::where('email', $request->email)->update([
            'password' => Hash::make($request->password)
        ]);

        // Delete the used token
        DB::table('password_resets')->where('email', $request->email)->delete();

        return redirect('/')->with('success', 'Your password has been reset! You can now log in.');
    }
}