<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $user->createToken('flutter-mobile-app')->plainTextToken;
        $name  = trim($user->fname . ' ' . $user->lname);

        if (empty(trim($name)) && $user->instructor) {
            $instructor = $user->instructor;
            $name = trim($instructor->fname . ' ' . $instructor->lname);
        }

        // Kung student, i-insert sa active_logins
        if ($user->role === 'student') {
            $student = \DB::table('students')->where('user_id', $user->id)->first();
            if ($student) {
                $now = now();
                \DB::table('active_logins')->updateOrInsert(
                    ['student_id' => $student->id],
                    [
                        'login_type'   => 'manual',
                        'logged_in_at' => $now,
                        'expires_at'   => $now->copy()->endOfDay(),
                    ]
                );
            }
        }

        return response()->json([
            'success' => true,
            'token'   => $token,
            'user'    => [
                'id'    => $user->id,
                'name'  => $name,
                'email' => $user->email,
                'role'  => $user->role,
            ]
        ]);
    }
}