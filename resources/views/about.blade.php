{{-- filepath: resources/views/about.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <title>About</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <!-- DaisyUI (Tailwind CSS + DaisyUI CDN) -->   
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <style>
        body {
            font-family: 'Arial', sans-serif;       
            background-color: #f8f9fa;
            color: #343a40;
        }
        h1 {
            color: #007bff;
        }   
        </style>
</head>

<body>
    
    <h1>About This Site</h1>
    <p>This is a sample Laravel application with Login, Signup, and About pages.</p>
    <a href="{{ url('/') }}">Back to Home</a>
</body>
</html>