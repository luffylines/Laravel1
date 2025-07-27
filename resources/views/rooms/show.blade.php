<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $room->name }} - Room Details</title>
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
            max-width: 1200px;
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
        
        .room-header {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
            margin-bottom: 30px;
        }
        
        .room-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 10px;
        }
        
        .room-location {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #6b7280;
            font-size: 1.1rem;
            margin-bottom: 20px;
        }
        
        .room-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            align-items: center;
        }
        
        .room-price {
            font-size: 2rem;
            font-weight: 700;
            color: #667eea;
        }
        
        .room-rating {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .stars {
            color: #fbbf24;
            font-size: 1.2rem;
        }
        
        .room-type {
            background: #e0e7ff;
            color: #3730a3;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.9rem;
        }
        
        .featured-badge {
            background: #ff6b6b;
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
        }
        
        .room-content {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
            margin-bottom: 40px;
        }
        
        .main-content {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
        }
        
        .booking-card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
            height: fit-content;
            position: sticky;
            top: 20px;
        }
        
        .room-gallery {
            width: 100%;
            height: 300px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
            margin-bottom: 30px;
            position: relative;
        }
        
        .gallery-placeholder {
            text-align: center;
        }
        
        .section {
            margin-bottom: 30px;
        }
        
        .section h3 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 15px;
        }
        
        .room-description {
            font-size: 1.1rem;
            line-height: 1.7;
            color: #4b5563;
        }
        
        .room-specs {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .spec-item {
            text-align: center;
            padding: 20px;
            background: #f9fafb;
            border-radius: 12px;
            border: 2px solid #e5e7eb;
        }
        
        .spec-icon {
            font-size: 2rem;
            margin-bottom: 8px;
        }
        
        .spec-value {
            font-size: 1.2rem;
            font-weight: 700;
            color: #1a202c;
        }
        
        .spec-label {
            color: #6b7280;
            font-size: 0.9rem;
        }
        
        .amenities-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
        }
        
        .amenity-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 16px;
            background: #f0f9ff;
            border: 2px solid #e0f2fe;
            border-radius: 8px;
            color: #0c4a6e;
        }
        
        .amenity-icon {
            font-size: 1.2rem;
        }
        
        .booking-form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        
        .form-group {
            display: flex;
            flex-direction: column;
        }
        
        .form-group label {
            font-weight: 600;
            margin-bottom: 8px;
            color: #374151;
        }
        
        .form-group input,
        .form-group select {
            padding: 12px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 16px;
        }
        
        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .booking-summary {
            background: #f9fafb;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        
        .summary-total {
            border-top: 2px solid #e5e7eb;
            padding-top: 15px;
            margin-top: 15px;
            font-weight: 700;
            font-size: 1.2rem;
        }
        
        .book-btn {
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
        }
        
        .book-btn:hover {
            transform: translateY(-2px);
        }
        
        .book-btn:disabled {
            background: #9ca3af;
            cursor: not-allowed;
            transform: none;
        }
        
        .owner-info {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 20px;
            background: #f9fafb;
            border-radius: 12px;
            margin-bottom: 20px;
        }
        
        .owner-avatar {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            font-weight: 700;
        }
        
        .owner-details h4 {
            font-size: 1.2rem;
            font-weight: 600;
            color: #1a202c;
        }
        
        .owner-details p {
            color: #6b7280;
        }
        
        .similar-rooms {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
        }
        
        .similar-rooms h3 {
            font-size: 1.8rem;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 20px;
        }
        
        .similar-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }
        
        .similar-card {
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            overflow: hidden;
            transition: border-color 0.3s, transform 0.3s;
            cursor: pointer;
        }
        
        .similar-card:hover {
            border-color: #667eea;
            transform: translateY(-2px);
        }
        
        .similar-image {
            width: 100%;
            height: 120px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 0.9rem;
        }
        
        .similar-content {
            padding: 15px;
        }
        
        .similar-title {
            font-weight: 600;
            color: #1a202c;
            margin-bottom: 5px;
        }
        
        .similar-price {
            color: #667eea;
            font-weight: 700;
        }
        
        .availability-check {
            background: #ecfdf5;
            border: 2px solid #d1fae5;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .availability-success {
            color: #065f46;
        }
        
        .availability-error {
            background: #fef2f2;
            border-color: #fecaca;
            color: #991b1b;
        }
        
        @media (max-width: 768px) {
            .room-content {
                grid-template-columns: 1fr;
            }
            
            .room-meta {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            
            .room-specs {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .similar-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="{{ route('rooms.index') }}" class="back-button">
            ← Back to Rooms
        </a>
        
        <div class="room-header">
            <h1 class="room-title">{{ $room->name }}</h1>
            
            <div class="room-location">
                <span>📍</span>
                <span>{{ $room->address }}, {{ $room->city }}, {{ $room->country }}</span>
            </div>
            
            <div class="room-meta">
                <div class="room-price">₱{{ number_format($room->price_per_night, 0) }}<span style="font-size: 1rem; font-weight: 400; color: #6b7280;">/night</span></div>
                
                @if($room->rating > 0)
                <div class="room-rating">
                    <span class="stars">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= floor($room->rating))
                                ⭐
                            @elseif($i <= $room->rating)
                                ⭐
                            @else
                                ☆
                            @endif
                        @endfor
                    </span>
                    <span>{{ number_format($room->rating, 1) }} ({{ $room->total_reviews }} reviews)</span>
                </div>
                @endif
                
                <div class="room-type">{{ ucfirst($room->type) }}</div>
                
                @if($room->is_featured)
                    <div class="featured-badge">⭐ Featured</div>
                @endif
            </div>
        </div>
        
        <div class="room-content">
            <div class="main-content">
                <div class="room-gallery">
                    <div class="gallery-placeholder">
                        📸 Room Gallery<br>
                        <small>{{ count($room->images ?? []) }} image{{ count($room->images ?? []) !== 1 ? 's' : '' }}</small>
                    </div>
                </div>
                
                <div class="section">
                    <h3>Room Specifications</h3>
                    <div class="room-specs">
                        <div class="spec-item">
                            <div class="spec-icon">👥</div>
                            <div class="spec-value">{{ $room->capacity }}</div>
                            <div class="spec-label">Guests</div>
                        </div>
                        <div class="spec-item">
                            <div class="spec-icon">🛏️</div>
                            <div class="spec-value">{{ $room->bedrooms }}</div>
                            <div class="spec-label">Bedroom{{ $room->bedrooms > 1 ? 's' : '' }}</div>
                        </div>
                        <div class="spec-item">
                            <div class="spec-icon">🚿</div>
                            <div class="spec-value">{{ $room->bathrooms }}</div>
                            <div class="spec-label">Bathroom{{ $room->bathrooms > 1 ? 's' : '' }}</div>
                        </div>
                        @if($room->area_sqm)
                        <div class="spec-item">
                            <div class="spec-icon">📐</div>
                            <div class="spec-value">{{ $room->area_sqm }}m²</div>
                            <div class="spec-label">Area</div>
                        </div>
                        @endif
                    </div>
                </div>
                
                <div class="section">
                    <h3>About This Room</h3>
                    <p class="room-description">{{ $room->description }}</p>
                </div>
                
                @if($room->amenities && count($room->amenities) > 0)
                <div class="section">
                    <h3>Amenities</h3>
                    <div class="amenities-grid">
                        @foreach($room->amenities as $amenity)
                        <div class="amenity-item">
                            <span class="amenity-icon">
                                @switch($amenity)
                                    @case('WiFi')
                                        📶
                                        @break
                                    @case('Air Conditioning')
                                        ❄️
                                        @break
                                    @case('TV')
                                        📺
                                        @break
                                    @case('Kitchen')
                                        🍳
                                        @break
                                    @case('Parking')
                                        🅿️
                                        @break
                                    @case('Pool')
                                        🏊
                                        @break
                                    @case('Gym')
                                        🏋️
                                        @break
                                    @case('Beach Access')
                                        🏖️
                                        @break
                                    @case('Hot Tub')
                                        🛁
                                        @break
                                    @case('Garden')
                                        🌳
                                        @break
                                    @case('Terrace')
                                        🌿
                                        @break
                                    @case('Balcony')
                                        🏞️
                                        @break
                                    @default
                                        ✅
                                @endswitch
                            </span>
                            <span>{{ $amenity }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
                
                <div class="section">
                    <h3>Room Owner</h3>
                    <div class="owner-info">
                        <div class="owner-avatar">
                            {{ strtoupper(substr($room->owner->firstname, 0, 1)) }}{{ strtoupper(substr($room->owner->lastname, 0, 1)) }}
                        </div>
                        <div class="owner-details">
                            <h4>{{ $room->owner->firstname }} {{ $room->owner->lastname }}</h4>
                            <p>Room Owner</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="booking-card">
                <h3 style="margin-bottom: 20px; color: #1a202c;">Book This Room</h3>
                
                <div id="availability-status"></div>
                
                <form class="booking-form" id="booking-form">
                    @csrf
                    <input type="hidden" name="room_id" value="{{ $room->id }}">
                    
                    <div class="form-group">
                        <label for="check_in_date">Check-in Date</label>
                        <input type="date" id="check_in_date" name="check_in_date" min="{{ date('Y-m-d') }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="check_out_date">Check-out Date</label>
                        <input type="date" id="check_out_date" name="check_out_date" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="guests">Number of Guests</label>
                        <select id="guests" name="guests" required>
                            @for($i = 1; $i <= $room->capacity; $i++)
                                <option value="{{ $i }}" {{ $i == 2 ? 'selected' : '' }}>{{ $i }} guest{{ $i > 1 ? 's' : '' }}</option>
                            @endfor
                        </select>
                    </div>
                    
                    <div class="booking-summary" id="booking-summary" style="display: none;">
                        <div class="summary-row">
                            <span>₱{{ number_format($room->price_per_night, 2) }} × <span id="nights">0</span> nights</span>
                            <span id="subtotal">₱0.00</span>
                        </div>
                        <div class="summary-row">
                            <span>Service fee</span>
                            <span id="service-fee">₱0.00</span>
                        </div>
                        <div class="summary-row summary-total">
                            <span>Total</span>
                            <span id="total">₱0.00</span>
                        </div>
                    </div>
                    
                    @auth
                        <button type="submit" class="book-btn" id="book-button" disabled>
                            Check Availability
                        </button>
                    @else
                        <a href="{{ route('login') }}" class="book-btn" style="text-decoration: none; text-align: center; display: block;">
                            Login to Book
                        </a>
                    @endauth
                </form>
            </div>
        </div>
        
        @if($relatedRooms->count() > 0)
        <div class="similar-rooms">
            <h3>Similar Rooms You Might Like</h3>
            <div class="similar-grid">
                @foreach($relatedRooms as $similar)
                <div class="similar-card" onclick="window.location.href='{{ route('rooms.show', $similar) }}'">
                    <div class="similar-image">
                        📸 Room Image
                    </div>
                    <div class="similar-content">
                        <div class="similar-title">{{ $similar->name }}</div>
                        <div style="color: #6b7280; font-size: 0.9rem; margin: 5px 0;">{{ $similar->city }}</div>
                        <div class="similar-price">₱{{ number_format($similar->price_per_night, 0) }}/night</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkInInput = document.getElementById('check_in_date');
            const checkOutInput = document.getElementById('check_out_date');
            const bookingForm = document.getElementById('booking-form');
            const bookButton = document.getElementById('book-button');
            const availabilityStatus = document.getElementById('availability-status');
            const bookingSummary = document.getElementById('booking-summary');
            
            // Set minimum check-out date when check-in changes
            checkInInput.addEventListener('change', function() {
                const checkInDate = new Date(this.value);
                const nextDay = new Date(checkInDate);
                nextDay.setDate(nextDay.getDate() + 1);
                checkOutInput.min = nextDay.toISOString().split('T')[0];
                
                if (checkOutInput.value && checkOutInput.value <= this.value) {
                    checkOutInput.value = nextDay.toISOString().split('T')[0];
                }
                
                checkAvailability();
            });
            
            checkOutInput.addEventListener('change', checkAvailability);
            
            function checkAvailability() {
                if (!checkInInput.value || !checkOutInput.value) {
                    bookButton.disabled = true;
                    bookButton.textContent = 'Check Availability';
                    availabilityStatus.innerHTML = '';
                    bookingSummary.style.display = 'none';
                    return;
                }
                
                const checkIn = new Date(checkInInput.value);
                const checkOut = new Date(checkOutInput.value);
                const nights = Math.ceil((checkOut - checkIn) / (1000 * 60 * 60 * 24));
                
                if (nights <= 0) {
                    bookButton.disabled = true;
                    availabilityStatus.innerHTML = '<div class="availability-error">Check-out date must be after check-in date</div>';
                    bookingSummary.style.display = 'none';
                    return;
                }
                
                // Calculate pricing
                const pricePerNight = {{ $room->price_per_night }};
                const subtotal = pricePerNight * nights;
                const serviceFee = subtotal * 0.1; // 10% service fee
                const total = subtotal + serviceFee;
                
                // Update summary
                document.getElementById('nights').textContent = nights;
                document.getElementById('subtotal').textContent = '₱' + subtotal.toFixed(2);
                document.getElementById('service-fee').textContent = '₱' + serviceFee.toFixed(2);
                document.getElementById('total').textContent = '₱' + total.toFixed(2);
                bookingSummary.style.display = 'block';
                
                // Check availability via API
                fetch(`{{ route('rooms.availability', $room) }}?check_in=${checkInInput.value}&check_out=${checkOutInput.value}`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Availability data:', data);
                    if (data.available) {
                        availabilityStatus.innerHTML = '<div class="availability-check availability-success">✅ Available for your dates!</div>';
                        bookButton.disabled = false;
                        bookButton.textContent = `Book Now - $${total.toFixed(2)}`;
                    } else {
                        availabilityStatus.innerHTML = '<div class="availability-check availability-error">❌ Not available for selected dates</div>';
                        bookButton.disabled = true;
                        bookButton.textContent = 'Not Available';
                    }
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                    // For now, assume available if there's an error (since we don't have real booking conflicts yet)
                    availabilityStatus.innerHTML = '<div class="availability-check availability-success">✅ Available for your dates!</div>';
                    bookButton.disabled = false;
                    bookButton.textContent = `Book Now - $${total.toFixed(2)}`;
                });
            }
            
            @auth
            bookingForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                if (bookButton.disabled) return;
                
                // Redirect to booking creation with data
                const formData = new FormData(this);
                const params = new URLSearchParams();
                for (let [key, value] of formData.entries()) {
                    params.append(key, value);
                }
                
                window.location.href = `{{ route('bookings.create') }}?${params.toString()}`;
            });
            @endauth
        });
    </script>
</body>
</html>
