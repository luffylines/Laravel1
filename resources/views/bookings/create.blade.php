<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Complete Your Booking - {{ $room->name }}</title>
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
        
        .booking-header {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
            margin-bottom: 30px;
            text-align: center;
        }
        
        .booking-title {
            font-size: 2rem;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 10px;
        }
        
        .booking-subtitle {
            color: #6b7280;
            font-size: 1.1rem;
        }
        
        .booking-content {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 30px;
        }
        
        .booking-form {
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
        
        .room-info {
            display: flex;
            gap: 15px;
            padding: 20px;
            background: #f9fafb;
            border-radius: 12px;
            margin-bottom: 20px;
        }
        
        .room-image {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 0.8rem;
            text-align: center;
        }
        
        .room-details h3 {
            font-size: 1.2rem;
            font-weight: 600;
            color: #1a202c;
            margin-bottom: 5px;
        }
        
        .room-details p {
            color: #6b7280;
            font-size: 0.9rem;
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
        
        .summary-section h3 {
            font-size: 1.3rem;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 15px;
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding: 8px 0;
        }
        
        .summary-row.total {
            border-top: 2px solid #e5e7eb;
            padding-top: 15px;
            margin-top: 15px;
            font-weight: 700;
            font-size: 1.2rem;
            color: #667eea;
        }
        
        .booking-dates {
            background: #f0f9ff;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .date-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
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
        
        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }
        
        .modal-content {
            background-color: white;
            margin: 15% auto;
            padding: 30px;
            border-radius: 12px;
            width: 90%;
            max-width: 500px;
            text-align: center;
            position: relative;
        }
        
        .modal-success {
            color: #10b981;
            font-size: 4rem;
            margin-bottom: 20px;
        }
        
        .modal h2 {
            color: #1a202c;
            margin-bottom: 15px;
        }
        
        .modal p {
            color: #6b7280;
            margin-bottom: 20px;
        }
        
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255,255,255,.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        @media (max-width: 768px) {
            .booking-content {
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
        <a href="{{ route('rooms.show', $room) }}" class="back-button">
            ← Back to Room Details
        </a>
        
        <div class="booking-header">
            <h1 class="booking-title">Complete Your Booking</h1>
            <p class="booking-subtitle">You're just one step away from your perfect stay!</p>
        </div>
        
        <div class="booking-content">
            <div class="booking-form">
                <form id="booking-form" method="POST" action="{{ route('bookings.store') }}">
                    @csrf
                    <input type="hidden" name="room_id" value="{{ $room->id }}">
                    <input type="hidden" name="check_in_date" value="{{ $check_in_date }}">
                    <input type="hidden" name="check_out_date" value="{{ $check_out_date }}">
                    <input type="hidden" name="guests" value="{{ $guests }}">
                    
                    <h3 style="margin-bottom: 20px;">Guest Information</h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="first_name">First Name</label>
                            <input type="text" id="first_name" name="first_name" value="{{ auth()->user()->firstname }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="last_name">Last Name</label>
                            <input type="text" id="last_name" name="last_name" value="{{ auth()->user()->lastname }}" readonly>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" value="{{ auth()->user()->email }}" readonly>
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone" placeholder="Enter your phone number" required>
                    </div>
                    
                    <h3 style="margin: 30px 0 20px 0;">Special Requests</h3>
                    
                    <div class="form-group">
                        <label for="special_requests">Any special requests or notes? (Optional)</label>
                        <textarea id="special_requests" name="special_requests" rows="4" placeholder="Early check-in, late check-out, dietary requirements, etc."></textarea>
                    </div>
                    
                    <h3 style="margin: 30px 0 20px 0;">Payment Information</h3>
                    
                    <div class="form-group">
                        <label for="payment_method">Payment Method</label>
                        <select id="payment_method" name="payment_method" required>
                            <option value="credit_card">Credit Card</option>
                            <option value="debit_card">Debit Card</option>
                            <option value="paypal">PayPal</option>
                            <option value="bank_transfer">Bank Transfer</option>
                        </select>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="card_number">Card Number</label>
                            <input type="text" id="card_number" name="card_number" placeholder="1234 5678 9012 3456" required>
                        </div>
                        <div class="form-group">
                            <label for="card_expiry">Expiry Date</label>
                            <input type="text" id="card_expiry" name="card_expiry" placeholder="MM/YY" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="card_cvc">CVC</label>
                            <input type="text" id="card_cvc" name="card_cvc" placeholder="123" required>
                        </div>
                        <div class="form-group">
                            <label for="card_name">Name on Card</label>
                            <input type="text" id="card_name" name="card_name" placeholder="Full name" required>
                        </div>
                    </div>
                    
                    <button type="submit" class="submit-btn" id="submit-btn">
                        Complete Booking
                    </button>
                </form>
            </div>
            
            <div class="booking-summary">
                <div class="room-info">
                    <div class="room-image">
                        📸<br>Room
                    </div>
                    <div class="room-details">
                        <h3>{{ $room->name }}</h3>
                        <p>{{ $room->city }}, {{ $room->country }}</p>
                        <p><strong>{{ ucfirst($room->type) }}</strong> • {{ $room->capacity }} guests</p>
                    </div>
                </div>
                
                <div class="summary-section">
                    <h3>Booking Details</h3>
                    
                    <div class="booking-dates">
                        <div class="date-row">
                            <strong>Check-in:</strong>
                            <span>{{ \Carbon\Carbon::parse($check_in_date)->format('M j, Y') }}</span>
                        </div>
                        <div class="date-row">
                            <strong>Check-out:</strong>
                            <span>{{ \Carbon\Carbon::parse($check_out_date)->format('M j, Y') }}</span>
                        </div>
                        <div class="date-row">
                            <strong>Duration:</strong>
                            <span>{{ $nights }} night{{ $nights > 1 ? 's' : '' }}</span>
                        </div>
                        <div class="date-row">
                            <strong>Guests:</strong>
                            <span>{{ $guests }} guest{{ $guests > 1 ? 's' : '' }}</span>
                        </div>
                    </div>
                    
                    <div class="summary-row">
                        <span>₱{{ number_format($room->price_per_night, 2) }} × {{ $nights }} nights</span>
                        <span>₱{{ number_format($subtotal, 2) }}</span>
                    </div>
                    
                    <div class="summary-row">
                        <span>Service fee</span>
                        <span>₱{{ number_format($service_fee, 2) }}</span>
                    </div>
                    
                    <div class="summary-row total">
                        <span>Total</span>
                        <span>₱{{ number_format($total, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Success Modal -->
    <div id="success-modal" class="modal">
        <div class="modal-content">
            <div class="modal-success">✅</div>
            <h2>Booking Completed!</h2>
            <p>Your booking has been confirmed successfully. You will receive a confirmation email shortly.</p>
            <p>Redirecting to home page...</p>
        </div>
    </div>
    
    <script>
        document.getElementById('booking-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitBtn = document.getElementById('submit-btn');
            const form = e.target;
            
            // Show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="loading"></span> Processing...';
            
            // Create FormData
            const formData = new FormData(form);
            
            // Submit form via AJAX
            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success modal
                    document.getElementById('success-modal').style.display = 'block';
                    
                    // Redirect after 3 seconds
                    setTimeout(() => {
                        window.location.href = data.redirect_url || '{{ route("home") }}';
                    }, 3000);
                } else {
                    alert(data.message || 'An error occurred. Please try again.');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = 'Complete Booking';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Complete Booking';
            });
        });
        
        // Format card number input
        document.getElementById('card_number').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\s/g, '').replace(/[^0-9]/gi, '');
            let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
            e.target.value = formattedValue;
        });
        
        // Format expiry date
        document.getElementById('card_expiry').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length >= 2) {
                value = value.substring(0, 2) + '/' + value.substring(2, 4);
            }
            e.target.value = value;
        });
        
        // Limit CVC to 3 digits
        document.getElementById('card_cvc').addEventListener('input', function(e) {
            e.target.value = e.target.value.replace(/\D/g, '').substring(0, 3);
        });
    </script>
</body>
</html>
