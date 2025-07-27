<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gmail OTP Verification</title>
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
        .more-options {
            margin-top: 20px;
            cursor: pointer;
            background-color: #808080; /* Grey background for "More options" button */
            color: #ffffff;
            border: none;
            border-radius: 40px;
            padding: 10px;
            font-size: 16px;
        }
        .more-options:hover {
            background-color: #a0a0a0;
        }
        .dropdown {
            display: none;
            list-style: none;
            padding: 0;
            margin: 10px 0 0;
            background-color: #161b22;
            border: 1px solid #30363d;
            border-radius: 40px;
        }
        .dropdown li {
            padding: 10px;
            cursor: pointer;
            color: #c9d1d9;
            border-radius: 40px; /* Rounded corners for dropdown items */
  
        }
        .dropdown li:hover {
            background-color: #b8bbb8;
            color: #ffffff;
        }
    </style>
    <script>
        // Function to toggle the dropdown menu
        function toggleDropdown() {
            const dropdown = document.getElementById('dropdown');
            dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
        }
        // Automatically hide error messages after 10 seconds
        setTimeout(() => {
            const errorMessage = document.querySelector('.alert-danger');
            if (errorMessage) {
                errorMessage.style.display = 'none';
            }
        }, 5000);
    </script>
</head>
<body>
    <div class="container">
        <div class="logo">
            <img src="/path/to/logo.png" alt="Logo">
        </div>
        <h1>Gmail OTP Verification</h1>
        <p>Enter the OTP sent to your Gmail account below.</p>
        <!-- Error Message -->
        @error('one_time_password')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
        <!-- Gmail OTP Form -->
        <form id="gmail-otp-form" method="POST" action="{{ route('gmail.verify.post') }}">
            @csrf
            <input id="gmail_otp" type="text" name="gmail_otp" placeholder="Enter Gmail OTP" required>
            <button type="submit">Verify Gmail OTP</button>
        </form>

        <!-- More Options Dropdown -->
        <div class="more-options" onclick="toggleDropdown()">
            More options
        </div>

        <!-- Dropdown Menu -->
        <ul id="dropdown" class="dropdown">
            <li onclick="window.location.href='{{ route('2fa.verify.form') }}';">Two-Factor Authentication</li>
        </ul>
    </div>
</body>
</html>