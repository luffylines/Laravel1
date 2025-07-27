<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Details - #{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f8fafc;
            color: #1a202c;
            line-height: 1.6;
        }
        
        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            margin-bottom: 20px;
            transition: color 0.3s;
        }
        
        .back-button:hover {
            color: #5a67d8;
        }
        
        .booking-header {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
            margin-bottom: 30px;
            text-align: center;
        }
        
        .booking-id {
            font-size: 2.5rem;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 10px;
        }
        
        .booking-status {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 1.1rem;
        }
        
        .status-confirmed {
            background: #d1fae5;
            color: #065f46;
        }
        
        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }
        
        .status-cancelled {
            background: #fee2e2;
            color: #991b1b;
        }
        
        .content-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }
        
        .main-content {
            display: flex;
            flex-direction: column;
            gap: 30px;
        }
        
        .card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
        }
        
        .card h3 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 20px;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 10px;
        }
        
        .room-showcase {
            display: flex;
            gap: 20px;
            align-items: center;
        }
        
        .room-image {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1rem;
            text-align: center;
        }
        
        .room-details h4 {
            font-size: 1.3rem;
            font-weight: 600;
            color: #1a202c;
            margin-bottom: 8px;
        }
        
        .room-details p {
            color: #6b7280;
            margin-bottom: 5px;
        }
        
        .detail-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }
        
        .detail-item {
            text-align: center;
            padding: 20px;
            background: #f9fafb;
            border-radius: 12px;
            border: 2px solid #e5e7eb;
        }
        
        .detail-icon {
            font-size: 2rem;
            margin-bottom: 8px;
        }
        
        .detail-value {
            font-size: 1.2rem;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 5px;
        }
        
        .detail-label {
            color: #6b7280;
            font-size: 0.9rem;
        }
        
        .sidebar {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        
        .price-breakdown {
            background: #f0f9ff;
            padding: 20px;
            border-radius: 12px;
            border: 2px solid #e0f2fe;
        }
        
        .price-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding: 5px 0;
        }
        
        .price-total {
            border-top: 2px solid #667eea;
            padding-top: 15px;
            margin-top: 15px;
            font-weight: 700;
            font-size: 1.3rem;
            color: #667eea;
        }
        
        .timeline {
            position: relative;
            padding-left: 30px;
        }
        
        .timeline::before {
            content: '';
            position: absolute;
            left: 10px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e5e7eb;
        }
        
        .timeline-item {
            position: relative;
            margin-bottom: 20px;
        }
        
        .timeline-item::before {
            content: '';
            position: absolute;
            left: -25px;
            top: 5px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #667eea;
        }
        
        .timeline-date {
            font-weight: 600;
            color: #1a202c;
        }
        
        .timeline-event {
            color: #6b7280;
            font-size: 0.9rem;
        }
        
        .action-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .btn {
            display: inline-block;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            text-align: center;
            cursor: pointer;
            border: none;
            transition: all 0.3s;
        }
        
        .btn-primary {
            background: #667eea;
            color: white;
        }
        
        .btn-primary:hover {
            background: #5a67d8;
        }
        
        .btn-secondary {
            background: #e5e7eb;
            color: #374151;
        }
        
        .btn-secondary:hover {
            background: #d1d5db;
        }
        
        .btn-edit {
            background: #10b981;
            color: white;
        }
        
        .btn-edit:hover {
            background: #059669;
        }
        
        .btn-cancel {
            background: #f59e0b;
            color: white;
        }
        
        .btn-cancel:hover {
            background: #d97706;
        }
        
        @media (max-width: 768px) {
            .content-grid {
                grid-template-columns: 1fr;
            }
            
            .room-showcase {
                flex-direction: column;
                text-align: center;
            }
            
            .detail-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="{{ route('bookings.my-bookings') }}" class="back-button">
            ← Back to My Bookings
        </a>
        
        <div class="booking-header">
            <div class="booking-id">Booking #{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</div>
            <div class="booking-status status-{{ $booking->status }}">
                {{ ucfirst($booking->status) }}
            </div>
        </div>
        
        <div class="content-grid">
            <div class="main-content">
                <!-- Room Information -->
                <div class="card">
                    <h3>🏨 Room Details</h3>
                    <div class="room-showcase">
                        <div class="room-image">
                            📸<br>Room Photo
                        </div>
                        <div class="room-details">
                            <h4>{{ $booking->room->name }}</h4>
                            <p><strong>Location:</strong> {{ $booking->room->address }}, {{ $booking->room->city }}, {{ $booking->room->country }}</p>
                            <p><strong>Room Type:</strong> {{ ucfirst($booking->room->type) }}</p>
                            <p><strong>Capacity:</strong> Up to {{ $booking->room->capacity }} guests</p>
                            @if($booking->room->amenities && count($booking->room->amenities) > 0)
                                <p><strong>Amenities:</strong> {{ implode(', ', array_slice($booking->room->amenities, 0, 3)) }}{{ count($booking->room->amenities) > 3 ? '...' : '' }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Booking Details -->
                <div class="card">
                    <h3>📅 Booking Information</h3>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <div class="detail-icon">📅</div>
                            <div class="detail-value">{{ $booking->check_in_date->format('M j, Y') }}</div>
                            <div class="detail-label">Check-in Date</div>
                        </div>
                        
                        <div class="detail-item">
                            <div class="detail-icon">📅</div>
                            <div class="detail-value">{{ $booking->check_out_date->format('M j, Y') }}</div>
                            <div class="detail-label">Check-out Date</div>
                        </div>
                        
                        <div class="detail-item">
                            <div class="detail-icon">🌙</div>
                            <div class="detail-value">{{ $booking->check_in_date->diffInDays($booking->check_out_date) }}</div>
                            <div class="detail-label">Night{{ $booking->check_in_date->diffInDays($booking->check_out_date) > 1 ? 's' : '' }}</div>
                        </div>
                        
                        <div class="detail-item">
                            <div class="detail-icon">👥</div>
                            <div class="detail-value">{{ $booking->guests }}</div>
                            <div class="detail-label">Guest{{ $booking->guests > 1 ? 's' : '' }}</div>
                        </div>
                    </div>
                    
                    @if($booking->special_requests)
                        <div style="margin-top: 20px; padding: 15px; background: #fef3c7; border-radius: 8px;">
                            <h4 style="color: #92400e; margin-bottom: 8px;">📝 Special Requests</h4>
                            <p style="color: #92400e;">{{ $booking->special_requests }}</p>
                        </div>
                    @endif
                </div>
                
                <!-- Guest Information -->
                <div class="card">
                    <h3>👤 Guest Information</h3>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <div class="detail-icon">👤</div>
                            <div class="detail-value">{{ $booking->user->firstname }} {{ $booking->user->lastname }}</div>
                            <div class="detail-label">Guest Name</div>
                        </div>
                        
                        <div class="detail-item">
                            <div class="detail-icon">📧</div>
                            <div class="detail-value" style="font-size: 1rem;">{{ $booking->user->email }}</div>
                            <div class="detail-label">Email Address</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="sidebar">
                <!-- Price Breakdown -->
                <div class="card">
                    <h3>💰 Price Summary</h3>
                    <div class="price-breakdown">
                        <div class="price-row">
                            <span>₱{{ number_format($booking->room->price_per_night, 2) }} × {{ $booking->check_in_date->diffInDays($booking->check_out_date) }} nights</span>
                            <span>₱{{ number_format(($booking->total_price - $booking->service_fee), 2) }}</span>
                        </div>
                        
                        <div class="price-row">
                            <span>Service Fee</span>
                            <span>₱{{ number_format($booking->service_fee, 2) }}</span>
                        </div>
                        
                        <div class="price-row price-total">
                            <span>Total Amount</span>
                            <span>₱{{ number_format($booking->total_price, 2) }}</span>
                        </div>
                    </div>
                    
                    <div style="margin-top: 15px; padding: 10px; background: #f0f9ff; border-radius: 6px;">
                        <p style="font-size: 0.9rem; color: #1e40af;">
                            <strong>Payment Status:</strong> {{ ucfirst($booking->payment_status) }}
                        </p>
                    </div>
                </div>
                
                <!-- Booking Timeline -->
                <div class="card">
                    <h3>📋 Booking Timeline</h3>
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-date">{{ $booking->created_at->format('M j, Y') }}</div>
                            <div class="timeline-event">Booking created</div>
                        </div>
                        
                        @if($booking->status === 'confirmed')
                            <div class="timeline-item">
                                <div class="timeline-date">{{ $booking->created_at->format('M j, Y') }}</div>
                                <div class="timeline-event">Booking confirmed</div>
                            </div>
                        @endif
                        
                        @if($booking->cancelled_at)
                            <div class="timeline-item">
                                <div class="timeline-date">{{ $booking->cancelled_at->format('M j, Y') }}</div>
                                <div class="timeline-event">Booking cancelled</div>
                            </div>
                        @endif
                        
                        @if($booking->status !== 'cancelled' && $booking->check_in_date->isFuture())
                            <div class="timeline-item">
                                <div class="timeline-date">{{ $booking->check_in_date->format('M j, Y') }}</div>
                                <div class="timeline-event">Check-in date</div>
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="card">
                    <h3>⚙️ Actions</h3>
                    <div class="action-buttons">
                        @if($booking->status !== 'cancelled')
                            <a href="{{ route('bookings.edit', $booking) }}" class="btn btn-edit">
                                ✏️ Edit Booking
                            </a>
                            
                            @if($booking->check_in_date->isFuture())
                                <form method="POST" action="{{ route('bookings.cancel', $booking) }}" style="display: inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-cancel" 
                                            onclick="return confirm('Are you sure you want to cancel this booking?')">
                                        ❌ Cancel Booking
                                    </button>
                                </form>
                            @endif
                        @endif
                        
                        <a href="{{ route('rooms.show', $booking->room) }}" class="btn btn-secondary">
                            🏨 View Room
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
