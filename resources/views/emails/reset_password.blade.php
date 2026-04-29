<!DOCTYPE html>
<html>
<head>
    <title>Reset Your Password</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .container {
            max-width: 560px;
            margin: 0 auto;
            background-color: #ffffff;
        }
        .button {
            display: inline-block;
            padding: 12px 28px;
            background-color: #1C1C1D;
            color: #ffffff;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            font-size: 15px;
        }
        @media only screen and (max-width: 600px) {
            .container {
                width: 100% !important;
            }
            .button {
                display: block !important;
                text-align: center !important;
            }
        }
    </style>
</head>
<body style="margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #ffffff; line-height: 1.5; color: #1e1e2a;">
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #ffffff; padding: 40px 20px;">
        <tr>
            <td align="center">
                <table class="container" width="560" cellpadding="0" cellspacing="0" border="0" style="max-width: 560px; width: 100%; background-color: #ffffff; border-radius: 16px; overflow: hidden;">
                    <!-- Header Accent -->
                    
                    
                    <!-- Logo / Brand -->
                    <tr>
                        <td style="padding: 32px 32px 0 32px;">
                            <div style="font-size: 20px; font-weight: 600; color: #1C1C1D; letter-spacing: -0.3px;">
                                TrainNova Academy
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Main Content -->
                    <tr>
                        <td style="padding: 24px 32px 32px 32px;">
                            <h2 style="margin: 0 0 12px 0; font-size: 24px; font-weight: 600; color: #1e1e2a; letter-spacing: -0.2px;">
                                Reset Your Password
                            </h2>
                            
                            <p style="margin: 0 0 20px 0; font-size: 15px; color: #5a5a6e;">
                                We received a request to reset the password for your TrainNova account.
                            </p>
                            
                            <div style="padding: 20px; margin: 24px 0; text-align: center;">
                                
                                <a href="
                                {{-- {{ route('password.reset', ['token' => $token, 'email' => $email]) }} --}}
                                 " 
                                   class="button" 
                                   style="display: inline-block; padding: 12px 28px; background-color: #1C1C1D; color: #ffffff; text-decoration: none; border-radius: 8px; font-weight: 500; font-size: 15px;">
                                   Reset Password
                                </a>
                            </div>
                            
                            <p style="margin: 0 0 12px 0; font-size: 14px; color: #8a8a9a;">
                                This password reset link will expire in 60 minutes.
                            </p>
                            
                            <div style="margin-top: 28px; padding: 16px 0 0 0; border-top: 1px solid #f0f0f0;">
                                <p style="margin: 0 0 8px 0; font-size: 13px; color: #8a8a9a;">
                                    If you did not request a password reset, please ignore this email. Your password will remain unchanged. If you need any more help logging in to your TrainNova account, you can contact us.
                                </p>
                                <p style="margin: 16px 0 0 0; font-size: 13px; color: #8a8a9a;">
                                    For security reasons, do not share this email with anyone.
                                </p>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #ffffff; padding: 20px 32px; border-top: 1px solid #eeeeee;">
                            <p style="margin: 0 0 6px 0; font-size: 12px; color: #8a8a9a; text-align: center;">
                                TrainNova Academy
                            </p>
                            <p style="margin: 0; font-size: 11px; color: #aaaaaa; text-align: center;">
                                &copy; {{ date('Y') }} TrainNova Academy. All rights reserved.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>