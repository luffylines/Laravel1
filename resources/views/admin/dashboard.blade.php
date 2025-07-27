<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Room Rental Management</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background-color: #f8fafc;
            color: #374151;
            min-height: 100vh;
        }
        
        .header {
            background-color: #ffffff;
            border-bottom: 1px solid #e5e7eb;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .logo-icon {
            width: 32px;
            height: 32px;
            background-color: #3b82f6;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }
        
        .user-menu {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        .welcome-section {
            background-color: #ffffff;
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        
        .welcome-title {
            font-size: 2rem;
            font-weight: 600;
            color: #111827;
            margin-bottom: 0.5rem;
        }
        
        .welcome-subtitle {
            color: #6b7280;
            font-size: 1.1rem;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background-color: #ffffff;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            border-left: 4px solid #3b82f6;
            position: relative;
            overflow: hidden;
        }
        
        .stat-card.rooms {
            border-left-color: #10b981;
        }
        
        .stat-card.bookings {
            border-left-color: #f59e0b;
        }
        
        .stat-card.payments {
            border-left-color: #ef4444;
        }
        
        .stat-card.revenue {
            border-left-color: #8b5cf6;
        }
        
        .stat-title {
            font-size: 0.875rem;
            font-weight: 500;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.5rem;
        }
        
        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: #111827;
        }
        
        .stat-change {
            font-size: 0.75rem;
            margin-top: 0.5rem;
        }
        
        .stat-change.positive {
            color: #10b981;
        }
        
        .stat-change.negative {
            color: #ef4444;
        }
        
        .actions-section {
            background-color: #ffffff;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        }
        
        .actions-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #111827;
            margin-bottom: 1.5rem;
        }
        
        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }
        
        .action-button {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem;
            background-color: #f8fafc;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            text-decoration: none;
            color: #374151;
            transition: all 0.2s;
        }
        
        .action-button:hover {
            background-color: #3b82f6;
            color: #ffffff;
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        
        .action-icon {
            width: 20px;
            height: 20px;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #3b82f6;
            color: white;
            font-size: 12px;
        }
        
        .logout-btn {
            background-color: #ef4444;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.875rem;
            transition: background-color 0.2s;
        }
        
        .logout-btn:hover {
            background-color: #dc2626;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="logo">
            <div class="logo-icon">🏨</div>
            <h2>Room Rental Admin</h2>
        </div>
        <div class="user-menu">
            <span>Welcome, {{ Auth::user()->firstname }} {{ Auth::user()->lastname }}</span>
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container">
        <!-- Welcome Section -->
        <div class="welcome-section">
            <h1 class="welcome-title">Room Rental Management Dashboard</h1>
            <p class="welcome-subtitle">Manage users, rooms, bookings, and payments from this central hub</p>
        </div>

        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-title">Total Users</div>
                <div class="stat-value">{{ $stats['total_users'] ?? 0 }}</div>
                <div class="stat-change positive">+{{ $stats['new_users_today'] ?? 0 }} today</div>
            </div>
            <div class="stat-card rooms">
                <div class="stat-title">Available Rooms</div>
                <div class="stat-value">{{ $stats['total_rooms'] ?? 0 }}</div>
                <div class="stat-change">{{ $stats['available_rooms'] ?? 0 }} available</div>
            </div>
            <div class="stat-card bookings">
                <div class="stat-title">Total Bookings</div>
                <div class="stat-value">{{ $stats['total_bookings'] ?? 0 }}</div>
                <div class="stat-change">{{ $stats['pending_bookings'] ?? 0 }} pending</div>
            </div>
            <div class="stat-card revenue">
                <div class="stat-title">Total Revenue</div>
                <div class="stat-value">₱{{ number_format($stats['total_revenue'] ?? 0, 2) }}</div>
                <div class="stat-change positive">This month</div>
            </div>
            <div class="stat-card payments">
                <div class="stat-title">Pending Payments</div>
                <div class="stat-value">{{ $stats['pending_payments'] ?? 0 }}</div>
                <div class="stat-change negative">Requires approval</div>
            </div>
            <div class="stat-card">
                <div class="stat-title">AI Recommendations</div>
                <div class="stat-value">{{ $stats['ai_recommendations'] ?? 0 }}</div>
                <div class="stat-change">{{ $stats['clicked_recommendations'] ?? 0 }} clicked</div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="actions-section">
            <h2 class="actions-title">Quick Actions</h2>
            <div class="actions-grid">
                <a href="{{ route('admin.users.index') }}" class="action-button">
                    <div class="action-icon">👥</div>
                    <span>Manage Users</span>
                </a>
                <a href="{{ route('admin.users.create') }}" class="action-button">
                    <div class="action-icon">➕</div>
                    <span>Add New User</span>
                </a>
                <a href="#" class="action-button">
                    <div class="action-icon">📊</div>
                    <span>View Reports</span>
                </a>
                <a href="#" class="action-button">
                    <div class="action-icon">⚙️</div>
                    <span>System Settings</span>
                </a>
                <a href="#" class="action-button">
                    <div class="action-icon">🔒</div>
                    <span>Security Logs</span>
                </a>
                <a href="#" class="action-button">
                    <div class="action-icon">📈</div>
                    <span>Analytics</span>
                </a>
            </div>
        </div>
    </div>
</body>
</html>