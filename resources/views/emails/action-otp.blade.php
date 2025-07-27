<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Action Verification Required</title>
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
            color: #e74c3c;
            margin-bottom: 30px;
        }
        .verification-code {
            background: #f8f9fa;
            border: 2px solid #e74c3c;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
        }
        .code {
            font-size: 32px;
            font-weight: bold;
            color: #e74c3c;
            letter-spacing: 5px;
            margin: 10px 0;
        }
        .action-info {
            background: #e8f5e8;
            border: 1px solid #4caf50;
            border-radius: 5px;
            padding: 15px;
            margin: 20px 0;
            color: #2d5a2d;
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
        .action-title {
            color: #e74c3c;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔐 Action Verification Required</h1>
        </div>
        
        <p>Hello {{ $user->firstname }} {{ $user->lastname }},</p>
        
        <div class="action-info">
            <div class="action-title">Action Being Performed:</div>
            <p><strong>{{ ucfirst(str_replace('_', ' ', $actionPurpose)) }}</strong></p>
            <p>For your security, we need to verify this action using your email.</p>
        </div>
        
        <p>To complete this action, please use the verification code below:</p>
        
        <div class="verification-code">
            <p><strong>Your Verification Code:</strong></p>
            <div class="code">{{ $otpCode }}</div>
            <p><small>This code will expire in 5 minutes</small></p>
        </div>
        
        <div class="warning">
            <strong>⚠️ Security Notice:</strong> 
            <ul>
                <li>If you didn't initiate this action, please ignore this email</li>
                <li>Never share this verification code with anyone</li>
                <li>Our support team will never ask for this code</li>
            </ul>
        </div>
        
        <p><strong>What happens next?</strong></p>
        <ul>
            <li>Enter this code on the verification page</li>
            <li>Your action will be processed immediately</li>
            <li>This code can only be used once</li>
        </ul>
        
        <p><strong>Code Details:</strong></p>
        <ul>
            <li>✅ Valid for: 5 minutes</li>
            <li>🔒 Single use only</li>
            <li>📧 Sent to: {{ $user->firstname }}'s registered email</li>
            <li>⏰ Generated: {{ now()->format('M d, Y at h:i A') }}</li>
        </ul>
        
        <div class="footer">
            <p>Best regards,<br>
            <strong>Room Rental System Team</strong></p>
            <p><em>This is an automated security email. Please do not reply to this message.</em></p>
            <p style="color: #e74c3c;">💰 All transactions in Philippine Peso (₱)</p>
        </div>
    </div>
</body>
</html>
