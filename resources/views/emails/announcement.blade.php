<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }}</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Base styles for email clients */
        .container {
            max-width: 580px;
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
                    
                    <!-- Main Content -->
                    <tr>
                        <td style="padding: 24px 32px 32px 32px;">
                            <h2 style="margin: 0 0 16px 0; font-size: 24px; font-weight: 600; color: #1e1e2a; letter-spacing: -0.2px;">
                                {{ $title }}
                            </h2>
                            
                            <div style="font-size: 15px; color: #3a3a4a; margin-bottom: 28px;">
                                {!! nl2br(e($messageBody)) !!}
                            </div>
                            
                            <div style="margin-top: 32px; padding-top: 20px; border-top: 1px solid #ffffff;">
                                <p style="margin: 0 0 4px 0; font-size: 14px; color: #5a5a6e;">
                                    Best regards,
                                </p>
                                <p style="margin: 0; font-size: 14px; font-weight: 500; color: #1C1C1D;">
                                    TrainNova Academy Team
                                </p>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #ffffff; padding: 20px 32px; border-top: 1px solid #eeeeee;">
                            <p style="margin: 0; font-size: 12px; color: #8a8a9a; text-align: center;">
                                &copy; {{ date('Y') }} 
                                TrainNova Academy. All rights reserved.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>