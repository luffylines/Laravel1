<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Approvals - Admin Panel</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
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
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        
        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: #111827;
        }
        
        .btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .btn-success {
            background-color: #10b981;
            color: white;
        }
        
        .btn-success:hover {
            background-color: #059669;
        }
        
        .btn-danger {
            background-color: #ef4444;
            color: white;
        }
        
        .btn-danger:hover {
            background-color: #dc2626;
        }
        
        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }
        
        .payment-card {
            background-color: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            border-left: 4px solid #f59e0b;
        }
        
        .payment-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }
        
        .payment-info h3 {
            font-size: 1.25rem;
            font-weight: 600;
            color: #111827;
            margin-bottom: 0.5rem;
        }
        
        .payment-meta {
            color: #6b7280;
            font-size: 0.875rem;
        }
        
        .payment-amount {
            font-size: 1.5rem;
            font-weight: 700;
            color: #f59e0b;
        }
        
        .payment-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }
        
        .detail-item {
            display: flex;
            flex-direction: column;
        }
        
        .detail-label {
            font-size: 0.75rem;
            font-weight: 500;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.25rem;
        }
        
        .detail-value {
            font-weight: 500;
            color: #111827;
        }
        
        .payment-actions {
            display: flex;
            gap: 0.5rem;
            padding-top: 1rem;
            border-top: 1px solid #e5e7eb;
        }
        
        .back-link {
            color: #3b82f6;
            text-decoration: none;
            font-weight: 500;
            margin-bottom: 1rem;
            display: inline-block;
        }
        
        .back-link:hover {
            color: #2563eb;
        }
        
        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
        }
        
        .alert-success {
            background-color: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }
        
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #6b7280;
        }
        
        .empty-state h3 {
            font-size: 1.25rem;
            margin-bottom: 0.5rem;
        }
        
        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background-color: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        
        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 0.25rem;
        }
        
        .stat-label {
            font-size: 0.875rem;
            color: #6b7280;
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
    </div>

    <div class="container">
        <a href="{{ route('admin.dashboard') }}" class="back-link">← Back to Dashboard</a>
        
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        <div class="page-header">
            <h1 class="page-title">Payment Approvals</h1>
        </div>

        <div class="stats-row">
            <div class="stat-card">
                <div class="stat-value">{{ $payments->total() }}</div>
                <div class="stat-label">Pending Payments</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">₱{{ number_format($payments->sum('total_price'), 2) }}</div>
                <div class="stat-label">Total Amount</div>
            </div>
        </div>

        @forelse($payments as $payment)
        <div class="payment-card">
            <div class="payment-header">
                <div class="payment-info">
                    <h3>Booking #{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</h3>
                    <div class="payment-meta">
                        {{ $payment->user->firstname }} {{ $payment->user->lastname }} • {{ $payment->created_at->diffForHumans() }}
                    </div>
                </div>
                <div class="payment-amount">
                    ₱{{ number_format($payment->total_price, 2) }}
                </div>
            </div>
            
            <div class="payment-details">
                <div class="detail-item">
                    <div class="detail-label">Room</div>
                    <div class="detail-value">{{ $payment->room->name }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Check-in</div>
                    <div class="detail-value">{{ $payment->check_in_date->format('M j, Y') }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Check-out</div>
                    <div class="detail-value">{{ $payment->check_out_date->format('M j, Y') }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Guests</div>
                    <div class="detail-value">{{ $payment->guests }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Duration</div>
                    <div class="detail-value">{{ $payment->check_in_date->diffInDays($payment->check_out_date) }} nights</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Guest Email</div>
                    <div class="detail-value">{{ $payment->user->email }}</div>
                </div>
            </div>
            
            <div class="payment-actions">
                <form method="POST" action="{{ route('admin.payments.approve', $payment) }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-success btn-sm" 
                            onclick="return confirm('Are you sure you want to approve this payment?')">
                        ✅ Approve Payment
                    </button>
                </form>
                
                <form method="POST" action="{{ route('admin.payments.reject', $payment) }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm" 
                            onclick="return confirm('Are you sure you want to reject this payment? This will cancel the booking.')">
                        ❌ Reject Payment
                    </button>
                </form>
                
                <a href="{{ route('admin.bookings.show', $payment) }}" class="btn btn-secondary btn-sm">
                    👁️ View Details
                </a>
            </div>
        </div>
        @empty
        <div class="payment-card">
            <div class="empty-state">
                <h3>🎉 All caught up!</h3>
                <p>No payments pending approval at the moment.</p>
            </div>
        </div>
        @endforelse
        
        @if($payments->hasPages())
        <div class="pagination">
            {{ $payments->links() }}
        </div>
        @endif
    </div>
</body>
</html>
