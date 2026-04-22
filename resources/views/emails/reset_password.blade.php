<!DOCTYPE html>
<html>
<body>
    <h2>Reset Your Password</h2>
    <p>You are receiving this email because we received a password reset request for your account.</p>
    <p>
        <a href="{{ route('password.reset', ['token' => $token, 'email' => $email]) }}" 
           style="display:inline-block; padding:10px 20px; background-color:#1C1C1D; color:#fff; text-decoration:none; border-radius:5px;">
           Reset Password
        </a>
    </p>
    <p>If you did not request a password reset, no further action is required.</p>
</body>
</html>