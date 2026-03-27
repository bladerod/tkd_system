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
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        // Generate a plain text token for Flutter to save
        $token = $user->createToken('flutter-mobile-app')->plainTextToken;

        $name = trim($user->fname . ' ' . $user->lname);


        if (empty(trim($name)) && $user->instructor) {
        $instructor = $user->instructor;
        $name = trim($instructor->fname . ' ' . $instructor->lname);
}

       return response()->json([
    'success' => true,
    'token' => $token,
    'user' => [
        'id' => $user->id,
        'name' => $name, 
        'email' => $user->email,
        'role' => $user->role,
    ]
]);
    }
}