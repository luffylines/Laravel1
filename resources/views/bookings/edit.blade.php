<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edit Booking - {{ $booking->room->name }}</title>
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
            max-width: 800px;
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
        
        .edit-header {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
            margin-bottom: 30px;
            text-align: center;
        }
        
        .edit-title {
            font-size: 2rem;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 10px;
        }
        
        .edit-subtitle {
            color: #6b7280;
            font-size: 1.1rem;
        }
        
        .edit-content {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 30px;
        }
        
        .edit-form {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
        }
        
        .booking-summary {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
            height: fit-content;
            position: sticky;
            top: 20px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: #374151;
        }
        
        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 12px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 16px;
        }
        
        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        
        .readonly {
            background-color: #f9fafb;
            color: #6b7280;
        }
        
        .info-box {
            background: #eff6ff;
            border: 2px solid #dbeafe;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }
        
        .info-box h4 {
            color: #1e40af;
            margin-bottom: 8px;
        }
        
        .info-box p {
            color: #1e3a8a;
            font-size: 0.9rem;
        }
        
        .warning-box {
            background: #fef3c7;
            border: 2px solid #fde68a;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }
        
        .warning-box h4 {
            color: #92400e;
            margin-bottom: 8px;
        }
        
        .warning-box p {
            color: #92400e;
            font-size: 0.9rem;
        }
        
        .submit-btn {
            width: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 16px;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s;
            margin-top: 20px;
        }
        
        .submit-btn:hover {
            transform: translateY(-2px);
        }
        
        .submit-btn:disabled {
            background: #9ca3af;
            cursor: not-allowed;
            transform: none;
        }
        
        .room-info {
            background: #f9fafb;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 20px;
        }
        
        .room-info h3 {
            color: #1a202c;
            margin-bottom: 10px;
        }
        
        .room-info p {
            color: #6b7280;
            margin-bottom: 5px;
        }
        
        .booking-details {
            background: #f0f9ff;
            padding: 15px;
            border-radius: 8px;
        }
        
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }
        
        .detail-row:last-child {
            border-top: 1px solid #e0e7eb;
            padding-top: 8px;
            margin-top: 8px;
            font-weight: 600;
        }
        
        @media (max-width: 768px) {
            .edit-content {
                grid-template-columns: 1fr;
            }
            
            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="{{ route('bookings.my-bookings') }}" class="back-button">
            ← Back to My Bookings
        </a>
        
        <div class="edit-header">
            <h1 class="edit-title">Edit Your Booking</h1>
            <p class="edit-subtitle">Update your reservation details</p>
        </div>
        
        <div class="edit-content">
            <div class="edit-form">
                @if($booking->status === 'cancelled')
                    <div class="warning-box">
                        <h4>⚠️ Booking Cancelled</h4>
                        <p>This booking has been cancelled and cannot be edited. You can only update special requests.</p>
                    </div>
                @elseif($booking->check_in_date->isPast())
                    <div class="warning-box">
                        <h4>⚠️ Limited Editing</h4>
                        <p>Check-in date has passed. You can only update special requests and contact information.</p>
                    </div>
                @else
                    <div class="info-box">
                        <h4>ℹ️ Booking Information</h4>
                        <p>You can update your special requests and guest information. Date changes may require availability confirmation.</p>
                    </div>
                @endif
                
                <form method="POST" action="{{ route('bookings.update', $booking) }}">
                    @csrf
                    @method('PATCH')
                    
                    <h3 style="margin-bottom: 20px;">Booking Details</h3>
                    
                    @if($booking->status !== 'cancelled' && $booking->check_in_date->isFuture())
                    <div class="form-row">
                        <div class="form-group">
                            <label for="check_in_date">Check-in Date</label>
                            <input type="date" id="check_in_date" name="check_in_date" 
                                   value="{{ $booking->check_in_date->format('Y-m-d') }}" 
                                   min="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="check_out_date">Check-out Date</label>
                            <input type="date" id="check_out_date" name="check_out_date" 
                                   value="{{ $booking->check_out_date->format('Y-m-d') }}" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="guests">Number of Guests</label>
                        <select id="guests" name="guests" required>
                            @for($i = 1; $i <= $booking->room->capacity; $i++)
                                <option value="{{ $i }}" {{ $booking->guests == $i ? 'selected' : '' }}>
                                    {{ $i }} guest{{ $i > 1 ? 's' : '' }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    @else
                    <div class="form-row">
                        <div class="form-group">
                            <label for="check_in_date">Check-in Date</label>
                            <input type="date" value="{{ $booking->check_in_date->format('Y-m-d') }}" readonly class="readonly">
                        </div>
                        <div class="form-group">
                            <label for="check_out_date">Check-out Date</label>
                            <input type="date" value="{{ $booking->check_out_date->format('Y-m-d') }}" readonly class="readonly">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="guests">Number of Guests</label>
                        <input type="number" value="{{ $booking->guests }}" readonly class="readonly">
                    </div>
                    @endif
                    
                    <h3 style="margin: 30px 0 20px 0;">Guest Information</h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="first_name">First Name</label>
                            <input type="text" value="{{ $booking->user->firstname }}" readonly class="readonly">
                        </div>
                        <div class="form-group">
                            <label for="last_name">Last Name</label>
                            <input type="text" value="{{ $booking->user->lastname }}" readonly class="readonly">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" value="{{ $booking->user->email }}" readonly class="readonly">
                    </div>
                    
                    <h3 style="margin: 30px 0 20px 0;">Special Requests</h3>
                    
                    <div class="form-group">
                        <label for="special_requests">Special Requests or Notes</label>
                        <textarea id="special_requests" name="special_requests" rows="4" 
                                  placeholder="Early check-in, late check-out, dietary requirements, etc.">{{ $booking->special_requests }}</textarea>
                    </div>
                    
                    <button type="submit" class="submit-btn">
                        Update Booking
                    </button>
                </form>
            </div>
            
            <div class="booking-summary">
                <div class="room-info">
                    <h3>{{ $booking->room->name }}</h3>
                    <p><strong>Location:</strong> {{ $booking->room->city }}, {{ $booking->room->country }}</p>
                    <p><strong>Type:</strong> {{ ucfirst($booking->room->type) }}</p>
                    <p><strong>Capacity:</strong> Up to {{ $booking->room->capacity }} guests</p>
                </div>
                
                <h3 style="margin-bottom: 15px;">Current Booking</h3>
                
                <div class="booking-details">
                    <div class="detail-row">
                        <span>Booking ID:</span>
                        <span>#{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    
                    <div class="detail-row">
                        <span>Status:</span>
                        <span style="color: {{ $booking->status === 'confirmed' ? '#10b981' : ($booking->status === 'cancelled' ? '#ef4444' : '#f59e0b') }}">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </div>
                    
                    <div class="detail-row">
                        <span>Check-in:</span>
                        <span>{{ $booking->check_in_date->format('M j, Y') }}</span>
                    </div>
                    
                    <div class="detail-row">
                        <span>Check-out:</span>
                        <span>{{ $booking->check_out_date->format('M j, Y') }}</span>
                    </div>
                    
                    <div class="detail-row">
                        <span>Duration:</span>
                        <span>{{ $booking->check_in_date->diffInDays($booking->check_out_date) }} nights</span>
                    </div>
                    
                    <div class="detail-row">
                        <span>Guests:</span>
                        <span>{{ $booking->guests }}</span>
                    </div>
                    
                    <div class="detail-row">
                        <span>Total Price:</span>
                        <span>₱{{ number_format($booking->total_price, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Set minimum check-out date when check-in changes
        const checkInInput = document.getElementById('check_in_date');
        const checkOutInput = document.getElementById('check_out_date');
        
        if (checkInInput && checkOutInput) {
            checkInInput.addEventListener('change', function() {
                const checkInDate = new Date(this.value);
                const nextDay = new Date(checkInDate);
                nextDay.setDate(nextDay.getDate() + 1);
                checkOutInput.min = nextDay.toISOString().split('T')[0];
                
                if (checkOutInput.value && checkOutInput.value <= this.value) {
                    checkOutInput.value = nextDay.toISOString().split('T')[0];
                }
            });
        }
    </script>
</body>
</html>
