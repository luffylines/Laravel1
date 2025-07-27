<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Verification - Room Rental System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .verification-container {
            background: #ffffff;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .verification-header {
            margin-bottom: 2rem;
        }

        .verification-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .verification-title {
            color: #2d3748;
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .verification-subtitle {
            color: #718096;
            font-size: 0.9rem;
            line-height: 1.5;
        }

        .form-group {
            margin-bottom: 1.5rem;
            text-align: left;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            color: #2d3748;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .form-input {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 1.1rem;
            text-align: center;
            letter-spacing: 0.2rem;
            font-weight: 600;
            transition: border-color 0.3s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .verify-btn {
            width: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .verify-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }

        .verify-btn:active {
            transform: translateY(0);
        }

        .resend-section {
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e2e8f0;
        }

        .resend-text {
            color: #718096;
            font-size: 0.85rem;
            margin-bottom: 0.5rem;
        }

        .resend-btn {
            background: none;
            border: none;
            color: #667eea;
            font-weight: 600;
            cursor: pointer;
            text-decoration: underline;
            font-size: 0.9rem;
        }

        .resend-btn:hover {
            color: #764ba2;
        }

        .alert {
            padding: 0.75rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }

        .alert-success {
            background-color: #c6f6d5;
            border: 1px solid #9ae6b4;
            color: #2f855a;
        }

        .alert-error {
            background-color: #fed7d7;
            border: 1px solid #fc8181;
            color: #c53030;
        }

        .back-link {
            display: inline-block;
            margin-top: 1rem;
            color: #718096;
            text-decoration: none;
            font-size: 0.9rem;
        }

        .back-link:hover {
            color: #2d3748;
        }

        .timer {
            color: #f56565;
            font-weight: 600;
            font-size: 0.85rem;
        }

        @media (max-width: 480px) {
            .verification-container {
                margin: 1rem;
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="verification-container">
        <div class="verification-header">
            <div class="verification-icon">🔐</div>
            <h1 class="verification-title">Email Verification</h1>
            <p class="verification-subtitle">
                We've sent a 6-digit verification code to your email address. 
                Please enter it below to complete your login.
            </p>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-error">
                @foreach($errors->all() as $error)
                    {{ $error }}
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('login.verify.post') }}">
            @csrf
            <div class="form-group">
                <label for="verification_code" class="form-label">Verification Code</label>
                <input 
                    type="text" 
                    id="verification_code" 
                    name="verification_code" 
                    class="form-input" 
                    placeholder="000000"
                    maxlength="6"
                    pattern="[0-9]{6}"
                    required
                    autofocus
                >
            </div>

            <button type="submit" class="verify-btn">
                Verify & Continue
            </button>
        </form>

        <div class="resend-section">
            <p class="resend-text">Didn't receive the code?</p>
            <form method="POST" action="{{ route('login.resend') }}" style="display: inline;">
                @csrf
                <button type="submit" class="resend-btn">
                    Resend Code
                </button>
            </form>
            <p class="timer" id="timer" style="display: none;">
                Resend available in: <span id="countdown">60</span>s
            </p>
        </div>

        <a href="{{ route('login') }}" class="back-link">
            ← Back to Login
        </a>
    </div>

    <script>
        // Auto-format the verification code input
        document.getElementById('verification_code').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 6) {
                value = value.substring(0, 6);
            }
            e.target.value = value;
        });

        // Auto-submit when 6 digits are entered
        document.getElementById('verification_code').addEventListener('input', function(e) {
            if (e.target.value.length === 6) {
                // Small delay to allow user to see the complete code
                setTimeout(function() {
                    document.querySelector('form').submit();
                }, 500);
            }
        });

        // Resend timer
        let resendTimer = 60;
        let timerInterval;

        function startResendTimer() {
            document.querySelector('.resend-btn').style.display = 'none';
            document.getElementById('timer').style.display = 'block';
            
            timerInterval = setInterval(function() {
                resendTimer--;
                document.getElementById('countdown').textContent = resendTimer;
                
                if (resendTimer <= 0) {
                    clearInterval(timerInterval);
                    document.querySelector('.resend-btn').style.display = 'inline';
                    document.getElementById('timer').style.display = 'none';
                    resendTimer = 60;
                }
            }, 1000);
        }

        // Start timer when resend button is clicked
        document.querySelector('.resend-btn').addEventListener('click', function() {
            startResendTimer();
        });

        // Focus on input when page loads
        window.addEventListener('load', function() {
            document.getElementById('verification_code').focus();
        });
    </script>
</body>
</html>
