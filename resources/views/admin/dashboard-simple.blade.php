<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Test</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .header {
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }
        
        .stat-number {
            font-size: 2em;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 0.9em;
            opacity: 0.9;
        }
        
        .welcome-message {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .navigation {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-top: 30px;
        }
        
        .nav-button {
            background-color: #28a745;
            color: white;
            text-decoration: none;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
            transition: background-color 0.3s;
        }
        
        .nav-button:hover {
            background-color: #218838;
            text-decoration: none;
            color: white;
        }
        
        .logout-btn {
            background-color: #dc3545;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            display: inline-block;
            margin-top: 20px;
        }
        
        .logout-btn:hover {
            background-color: #c82333;
            text-decoration: none;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🏢 Admin Dashboard</h1>
            <p>Welcome, {{ Auth::user()->firstname ?? 'Admin' }} {{ Auth::user()->lastname ?? '' }}!</p>
        </div>
        
        <div class="welcome-message">
            <strong>✅ Success!</strong> You are now logged in as an administrator. This is the admin dashboard.
        </div>
        
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number">{{ $stats['total_users'] ?? 0 }}</div>
                <div class="stat-label">Total Users</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $stats['total_rooms'] ?? 0 }}</div>
                <div class="stat-label">Total Rooms</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $stats['total_bookings'] ?? 0 }}</div>
                <div class="stat-label">Total Bookings</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $stats['pending_bookings'] ?? 0 }}</div>
                <div class="stat-label">Pending Bookings</div>
            </div>
        </div>
        
        <h2>📋 Admin Navigation</h2>
        <div class="navigation">
            <a href="{{ route('admin.users.index') }}" class="nav-button">👥 Manage Users</a>
            <a href="{{ route('admin.rooms.index') }}" class="nav-button">🏠 Manage Rooms</a>
            <a href="{{ route('admin.bookings.index') }}" class="nav-button">📅 Manage Bookings</a>
            <a href="{{ route('admin.payments.index') }}" class="nav-button">💰 Payments</a>
            <a href="{{ route('admin.reports') }}" class="nav-button">📊 Reports</a>
            <a href="{{ route('admin.analytics') }}" class="nav-button">📈 Analytics</a>
        </div>
        
        <div style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #ddd;">
            <h3>🔧 Admin Information</h3>
            <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
            <p><strong>Admin Status:</strong> {{ Auth::user()->is_admin ? 'Yes' : 'No' }}</p>
            <p><strong>Login Time:</strong> {{ now()->format('Y-m-d H:i:s') }}</p>
            
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="logout-btn">🚪 Logout</button>
            </form>
        </div>
    </div>
</body>
</html>
