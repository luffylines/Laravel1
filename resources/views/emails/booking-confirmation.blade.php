<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .booking-details {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #667eea;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .label {
            font-weight: bold;
            color: #555;
        }
        .value {
            color: #333;
        }
        .price-summary {
            background: #e8f4fd;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .total-price {
            font-size: 1.2em;
            font-weight: bold;
            color: #667eea;
            border-top: 2px solid #667eea;
            padding-top: 10px;
            margin-top: 10px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding: 20px;
            color: #666;
            font-size: 0.9em;
        }
        .button {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            margin: 15px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🎉 Booking Confirmed!</h1>
        <p>Thank you for your reservation</p>
    </div>
    
    <div class="content">
        <h2>Hello {{ $user->firstname }}!</h2>
        <p>Your booking has been confirmed. Here are the details:</p>
        
        <div class="booking-details">
            <h3>📍 {{ $room->name }}</h3>
            <p style="color: #666; margin-bottom: 15px;">{{ $room->address }}, {{ $room->city }}, {{ $room->country }}</p>
            
            <div class="detail-row">
                <span class="label">Booking ID:</span>
                <span class="value">#{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</span>
            </div>
            
            <div class="detail-row">
                <span class="label">Check-in Date:</span>
                <span class="value">{{ $booking->check_in_date->format('l, F j, Y') }}</span>
            </div>
            
            <div class="detail-row">
                <span class="label">Check-out Date:</span>
                <span class="value">{{ $booking->check_out_date->format('l, F j, Y') }}</span>
            </div>
            
            <div class="detail-row">
                <span class="label">Duration:</span>
                <span class="value">{{ $booking->check_in_date->diffInDays($booking->check_out_date) }} night{{ $booking->check_in_date->diffInDays($booking->check_out_date) > 1 ? 's' : '' }}</span>
            </div>
            
            <div class="detail-row">
                <span class="label">Guests:</span>
                <span class="value">{{ $booking->guests }} guest{{ $booking->guests > 1 ? 's' : '' }}</span>
            </div>
            
            <div class="detail-row">
                <span class="label">Room Type:</span>
                <span class="value">{{ ucfirst($room->type) }}</span>
            </div>
            
            @if($booking->special_requests)
            <div class="detail-row">
                <span class="label">Special Requests:</span>
                <span class="value">{{ $booking->special_requests }}</span>
            </div>
            @endif
            
            <div class="detail-row">
                <span class="label">Status:</span>
                <span class="value" style="color: #28a745; font-weight: bold;">{{ ucfirst($booking->status) }}</span>
            </div>
        </div>
        
        <div class="price-summary">
            <h3>💰 Price Summary</h3>
            
            <div class="detail-row">
                <span class="label">₱{{ number_format($room->price_per_night, 2) }} × {{ $booking->check_in_date->diffInDays($booking->check_out_date) }} nights</span>
                <span class="value">₱{{ number_format(($booking->total_price - $booking->service_fee), 2) }}</span>
            </div>
            
            <div class="detail-row">
                <span class="label">Service Fee</span>
                <span class="value">₱{{ number_format($booking->service_fee, 2) }}</span>
            </div>
            
            <div class="detail-row total-price">
                <span class="label">Total Amount</span>
                <span class="value">₱{{ number_format($booking->total_price, 2) }}</span>
            </div>
        </div>
        
        <div style="text-align: center;">
            <a href="{{ route('bookings.show', $booking) }}" class="button">View Booking Details</a>
        </div>
        
        <h3>📋 What to Expect</h3>
        <ul>
            <li><strong>Check-in:</strong> Available from 3:00 PM on your arrival date</li>
            <li><strong>Check-out:</strong> Please check out by 11:00 AM on your departure date</li>
            <li><strong>Payment:</strong> Your payment is currently {{ $booking->payment_status }}</li>
            <li><strong>Cancellation:</strong> You can cancel your booking up to 24 hours before check-in</li>
        </ul>
        
        @if($room->amenities && count($room->amenities) > 0)
        <h3>🎯 Room Amenities</h3>
        <p>{{ implode(' • ', $room->amenities) }}</p>
        @endif
        
        <div style="background: #fff3cd; padding: 15px; border-radius: 8px; border-left: 4px solid #ffc107; margin: 20px 0;">
            <h4>📞 Need Help?</h4>
            <p>If you have any questions or need to make changes to your booking, please contact us:</p>
            <p><strong>Email:</strong> support@roomrental.com<br>
            <strong>Phone:</strong> +1 (555) 123-4567</p>
        </div>
    </div>
    
    <div class="footer">
        <p>Thank you for choosing our room rental service!</p>
        <p>This is an automated email. Please do not reply to this message.</p>
        <p>&copy; {{ date('Y') }} Room Rental System. All rights reserved.</p>
    </div>
</body>
</html>
