<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Action Verification - Room Rental System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .verification-container {
            background: #ffffff;
            padding: 2.5rem;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 450px;
            text-align: center;
        }

        .verification-header {
            margin-bottom: 2rem;
        }

        .verification-icon {
            font-size: 3.5rem;
            margin-bottom: 1rem;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .verification-title {
            color: #2d3748;
            font-size: 1.6rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .verification-subtitle {
            color: #718096;
            font-size: 0.95rem;
            line-height: 1.6;
        }

        .action-info {
            background: linear-gradient(135deg, #fff5f5 0%, #fed7d7 100%);
            border: 2px solid #e74c3c;
            border-radius: 12px;
            padding: 1.5rem;
            margin: 1.5rem 0;
        }

        .action-title {
            color: #e74c3c;
            font-weight: 600;
            font-size: 1rem;
            margin-bottom: 0.5rem;
        }

        .action-description {
            color: #2d3748;
            font-size: 1.1rem;
            font-weight: 500;
        }

        .form-group {
            margin-bottom: 1.5rem;
            text-align: left;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            color: #2d3748;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .form-input {
            width: 100%;
            padding: 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 1.2rem;
            text-align: center;
            letter-spacing: 0.3rem;
            font-weight: 700;
            transition: all 0.3s ease;
            background: #f8fafc;
        }

        .form-input:focus {
            outline: none;
            border-color: #e74c3c;
            box-shadow: 0 0 0 4px rgba(231, 76, 60, 0.1);
            background: white;
        }

        .verify-btn {
            width: 100%;
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
            border: none;
            padding: 1rem 1.5rem;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .verify-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(231, 76, 60, 0.4);
        }

        .verify-btn:active {
            transform: translateY(0);
        }

        .verify-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .resend-section {
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e2e8f0;
        }

        .resend-text {
            color: #718096;
            font-size: 0.9rem;
            margin-bottom: 0.8rem;
        }

        .resend-btn {
            background: none;
            border: 2px solid #e74c3c;
            color: #e74c3c;
            padding: 0.6rem 1.2rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .resend-btn:hover {
            background: #e74c3c;
            color: white;
        }

        .alert {
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            border: 1px solid;
        }

        .alert-success {
            background-color: #f0fff4;
            border-color: #9ae6b4;
            color: #2f855a;
        }

        .alert-error {
            background-color: #fed7d7;
            border-color: #fc8181;
            color: #c53030;
        }

        .alert-info {
            background-color: #ebf8ff;
            border-color: #90cdf4;
            color: #2b6cb0;
        }

        .back-link {
            display: inline-block;
            margin-top: 1.5rem;
            color: #718096;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .back-link:hover {
            color: #2d3748;
        }

        .timer {
            color: #e74c3c;
            font-weight: 600;
            font-size: 0.85rem;
            margin-top: 0.5rem;
        }

        .security-info {
            background: #f7fafc;
            border-radius: 8px;
            padding: 1rem;
            margin: 1rem 0;
            font-size: 0.85rem;
            color: #4a5568;
        }

        @media (max-width: 480px) {
            .verification-container {
                margin: 1rem;
                padding: 2rem;
            }
            
            .verification-title {
                font-size: 1.4rem;
            }
        }
    </style>
</head>
<body>
    <div class="verification-container">
        <div class="verification-header">
            <div class="verification-icon">🔐</div>
            <h1 class="verification-title">Action Verification</h1>
            <p class="verification-subtitle">
                To proceed with this action, please verify your identity using the code sent to your email.
            </p>
        </div>

        <div class="action-info">
            <div class="action-title">Action Requiring Verification:</div>
            <div class="action-description">{{ $actionDisplay }}</div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('info'))
            <div class="alert alert-info">
                {{ session('info') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-error">
                @foreach($errors->all() as $error)
                    {{ $error }}
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('action.otp.verify') }}" id="otpForm">
            @csrf
            <div class="form-group">
                <label for="otp_code" class="form-label">Verification Code</label>
                <input 
                    type="text" 
                    id="otp_code" 
                    name="otp_code" 
                    class="form-input" 
                    placeholder="000000"
                    maxlength="6"
                    pattern="[0-9]{6}"
                    required
                    autofocus
                >
            </div>

            <button type="submit" class="verify-btn" id="submitBtn">
                ✅ Verify & Continue
            </button>
        </form>

        <div class="security-info">
            <strong>🔒 Security Information:</strong><br>
            • This code expires in 5 minutes<br>
            • Can only be used once<br>
            • Never share this code with anyone
        </div>

        <div class="resend-section">
            <p class="resend-text">Didn't receive the code?</p>
            <form method="POST" action="{{ route('action.otp.resend') }}" style="display: inline;">
                @csrf
                <button type="submit" class="resend-btn" id="resendBtn">
                    📧 Resend Code
                </button>
            </form>
            <p class="timer" id="timer" style="display: none;">
                Resend available in: <span id="countdown">60</span>s
            </p>
        </div>

        <a href="{{ route('dashboard') }}" class="back-link">
            ← Cancel and Return to Dashboard
        </a>
    </div>

    <script>
        // Auto-format the OTP input
        document.getElementById('otp_code').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 6) {
                value = value.substring(0, 6);
            }
            e.target.value = value;

            // Auto-submit when 6 digits are entered
            if (value.length === 6) {
                document.getElementById('submitBtn').textContent = '⏳ Verifying...';
                document.getElementById('submitBtn').disabled = true;
                
                setTimeout(function() {
                    document.getElementById('otpForm').submit();
                }, 800);
            }
        });

        // Resend timer
        let resendTimer = 60;
        let timerInterval;

        function startResendTimer() {
            document.getElementById('resendBtn').style.display = 'none';
            document.getElementById('timer').style.display = 'block';
            
            timerInterval = setInterval(function() {
                resendTimer--;
                document.getElementById('countdown').textContent = resendTimer;
                
                if (resendTimer <= 0) {
                    clearInterval(timerInterval);
                    document.getElementById('resendBtn').style.display = 'inline-block';
                    document.getElementById('timer').style.display = 'none';
                    resendTimer = 60;
                }
            }, 1000);
        }

        // Start timer when resend button is clicked
        document.getElementById('resendBtn').addEventListener('click', function() {
            startResendTimer();
        });

        // Focus on input when page loads
        window.addEventListener('load', function() {
            document.getElementById('otp_code').focus();
        });

        // Add loading state to form submission
        document.getElementById('otpForm').addEventListener('submit', function() {
            document.getElementById('submitBtn').textContent = '⏳ Verifying...';
            document.getElementById('submitBtn').disabled = true;
        });
    </script>
</body>
</html>
