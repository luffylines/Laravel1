<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Verification Code</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            color: #2563eb;
            margin-bottom: 30px;
        }
        .verification-code {
            background: #f8f9fa;
            border: 2px solid #2563eb;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
        }
        .code {
            font-size: 32px;
            font-weight: bold;
            color: #2563eb;
            letter-spacing: 5px;
            margin: 10px 0;
        }
        .warning {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 5px;
            padding: 15px;
            margin: 20px 0;
            color: #856404;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔐 Login Verification Required</h1>
        </div>
        
        <p>Hello {{ $user->firstname }} {{ $user->lastname }},</p>
        
        <p>We received a login attempt for your account. To complete your login, please use the verification code below:</p>
        
        <div class="verification-code">
            <p><strong>Your Verification Code:</strong></p>
            <div class="code">{{ $verificationCode }}</div>
            <p><small>This code will expire in 10 minutes</small></p>
        </div>
        
        <div class="warning">
            <strong>⚠️ Security Notice:</strong> If you didn't attempt to log in, please ignore this email and consider changing your password for security.
        </div>
        
        <p>For your security, this verification code:</p>
        <ul>
            <li>Can only be used once</li>
            <li>Will expire in 10 minutes</li>
            <li>Is case-sensitive</li>
        </ul>
        
        <p>If you're having trouble logging in, please contact our support team.</p>
        
        <div class="footer">
            <p>Best regards,<br>
            <strong>Room Rental System Team</strong></p>
            <p><em>This is an automated security email. Please do not reply to this message.</em></p>
        </div>
    </div>
</body>
</html>
