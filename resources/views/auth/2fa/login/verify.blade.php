<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Two-Factor Authentication</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #0d1117;
            color: #c9d1d9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            text-align: center;
            background-color: #161b22;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);
            width: 400px;
            position: relative;
        }
        .logo {
            position: absolute;
            top: -50px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background-color: #238636;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);
        }
        .logo img {
            width: 60px;
            height: 60px;
        }
        h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }
        p {
            font-size: 14px;
            margin-bottom: 20px;
        }
        input {
            width: 80%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #30363d;
            border-radius: 4px;
            background-color: #0d1117;
            color: #c9d1d9;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #238636;
            color: #ffffff;
            border: none;
            border-radius: 40px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #95f5a8;
        }
                .alert {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
            font-size: 14px;
            text-align: center;
        }
        .alert-danger {
            background-color: #ff4d4d;
            color: #ffffff;
            border: 1px solid #ff0000;
        }
        .alert-success {
            background-color: #28a745;
            color: #ffffff;
            border: 1px solid #28a745;
        }
    </style>
    <script>
        let formSubmitting = false;
        
        // Automatically hide error messages after 5 seconds
        setTimeout(() => {
            const errorMessage = document.querySelector('.alert-danger');
            if (errorMessage) {
                errorMessage.style.display = 'none';
            }
        }, 5000);

        // Handle page unload/back button - cancel 2FA session
        window.addEventListener('beforeunload', function() {
            // Only cancel if it's not a form submission
            if (!formSubmitting) {
                // Send cancel request when user tries to leave
                navigator.sendBeacon('{{ route('2fa.cancel') }}', new FormData());
            }
        });

        // Handle browser back button
        window.addEventListener('popstate', function() {
            window.location.href = '{{ route('login') }}';
        });

        // Prevent back button from working
        history.pushState(null, null, location.href);
        window.onpopstate = function () {
            history.go(1);
        };
    </script>
    </script>
</head>
<body>
    <div class="container">
        <!-- Logo Section -->
        <div class="logo">
            <img src="/path/to/logo.png" alt="Logo">
        </div>
        <h1>Two-Factor Authentication</h1>
        <p id="instruction-text">Enter the code from your two-factor authentication app or browser extension below.</p>
        
        <!-- Error Messages -->
        @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    {{ $error }}<br>
                @endforeach
            </div>
        @endif

        <!-- Success Messages -->
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- Google Authenticator Form -->
        <div id="google-auth-form">
            <form method="POST" action="{{ route('2fa.verify.login') }}" onsubmit="formSubmitting = true;">
                @csrf
                <input id="one_time_password" type="text" name="one_time_password" placeholder="XXXXXX" required>
                <button type="submit">Verify</button>
            </form>
        </div>

        <!-- Cancel Button -->
        <form method="POST" action="{{ route('2fa.cancel') }}" style="margin-top: 10px;">
            @csrf
            <button type="submit" style="background-color: #dc3545; width: 100%;">Cancel & Return to Login</button>
        </form>

        <!-- Gmail OTP Option Button -->
        <form method="POST" action="{{ route('gmail.send') }}" style="margin-top: 10px;" onsubmit="formSubmitting = true;">
            @csrf
            <button type="submit" style="background-color: #007bff; width: 100%;">Send Gmail OTP Instead</button>
        </form>
        </div>
    </div>
</body>
</html>