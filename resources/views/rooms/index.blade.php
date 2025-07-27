<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Rental System</title>
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
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 60px 0;
            text-align: center;
            margin-bottom: 40px;
        }
        
        .header h1 {
            font-size: 3rem;
            margin-bottom: 10px;
            font-weight: 700;
        }
        
        .header p {
            font-size: 1.2rem;
            opacity: 0.9;
        }
        
        .ai-badge {
            display: inline-block;
            background: #ff6b6b;
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            margin-top: 20px;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        
        .search-section {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
            margin-bottom: 40px;
        }
        
        .search-form {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            align-items: end;
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
            transition: border-color 0.3s;
        }
        
        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .search-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s;
        }
        
        .search-btn:hover {
            transform: translateY(-2px);
        }
        
        .ai-recommendations {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
            color: white;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 40px;
            text-align: center;
        }
        
        .ai-recommendations h3 {
            font-size: 1.5rem;
            margin-bottom: 10px;
        }
        
        .ai-btn {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.3);
            padding: 10px 20px;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .ai-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            border-color: rgba(255, 255, 255, 0.5);
        }
        
        .rooms-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 30px;
        }
        
        .room-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .room-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }
        
        .room-image {
            width: 100%;
            height: 200px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.1rem;
            position: relative;
        }
        
        .featured-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: #ff6b6b;
            color: white;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .room-content {
            padding: 20px;
        }
        
        .room-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 10px;
        }
        
        .room-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 5px;
        }
        
        .room-price {
            font-size: 1.5rem;
            font-weight: 700;
            color: #667eea;
        }
        
        .room-location {
            color: #6b7280;
            font-size: 0.9rem;
            margin-bottom: 10px;
        }
        
        .room-type {
            display: inline-block;
            background: #e0e7ff;
            color: #3730a3;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 10px;
        }
        
        .room-description {
            color: #4b5563;
            font-size: 0.9rem;
            margin-bottom: 15px;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .room-amenities {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 15px;
        }
        
        .amenity-tag {
            background: #f3f4f6;
            color: #374151;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 0.8rem;
        }
        
        .room-stats {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
            font-size: 0.9rem;
            color: #6b7280;
        }
        
        .rating {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .rating-stars {
            color: #fbbf24;
        }
        
        .view-btn {
            width: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s;
        }
        
        .view-btn:hover {
            transform: translateY(-2px);
        }
        
        .no-rooms {
            text-align: center;
            padding: 60px 20px;
            color: #6b7280;
        }
        
        .no-rooms h3 {
            font-size: 1.5rem;
            margin-bottom: 10px;
        }
        
        .ai-features {
            background: white;
            padding: 40px;
            border-radius: 12px;
            margin-bottom: 40px;
            text-align: center;
        }
        
        .ai-features h2 {
            color: #1a202c;
            font-size: 2rem;
            margin-bottom: 20px;
        }
        
        .ai-feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            margin-top: 30px;
        }
        
        .ai-feature {
            padding: 20px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            transition: border-color 0.3s;
        }
        
        .ai-feature:hover {
            border-color: #667eea;
        }
        
        .ai-feature h4 {
            color: #667eea;
            font-size: 1.2rem;
            margin-bottom: 10px;
        }
        
        .ai-icon {
            font-size: 2rem;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="container">
            <h1>🏠 AI-Powered Room Rental</h1>
            <p>Find your perfect accommodation with intelligent recommendations</p>
            <div class="ai-badge">✨ AI-Enhanced Search</div>
        </div>
    </div>

    <div class="container">
        <!-- AI Features Section -->
        <div class="ai-features">
            <h2>🤖 AI-Powered Features</h2>
            <p>Our intelligent system learns from your preferences to provide personalized recommendations</p>
            
            <div class="ai-feature-grid">
                <div class="ai-feature">
                    <div class="ai-icon">🎯</div>
                    <h4>Smart Recommendations</h4>
                    <p>AI analyzes your booking history and preferences to suggest perfect matches</p>
                </div>
                
                <div class="ai-feature">
                    <div class="ai-icon">📊</div>
                    <h4>Collaborative Filtering</h4>
                    <p>Find rooms loved by users with similar tastes and preferences</p>
                </div>
                
                <div class="ai-feature">
                    <div class="ai-icon">🔍</div>
                    <h4>Personalized Search</h4>
                    <p>Search results ranked based on your individual preferences and history</p>
                </div>
                
                <div class="ai-feature">
                    <div class="ai-icon">📈</div>
                    <h4>Trending Analysis</h4>
                    <p>Discover popular and trending accommodations in real-time</p>
                </div>
            </div>
        </div>

        <!-- Search Section -->
        <div class="search-section">
            <form class="search-form" method="GET" action="{{ route('rooms.index') }}">
                <div class="form-group">
                    <label for="city">City</label>
                    <input type="text" id="city" name="city" placeholder="Enter city..." value="{{ request('city') }}">
                </div>
                
                <div class="form-group">
                    <label for="type">Room Type</label>
                    <select id="type" name="type">
                        <option value="">All Types</option>
                        <option value="single" {{ request('type') == 'single' ? 'selected' : '' }}>Single</option>
                        <option value="double" {{ request('type') == 'double' ? 'selected' : '' }}>Double</option>
                        <option value="suite" {{ request('type') == 'suite' ? 'selected' : '' }}>Suite</option>
                        <option value="apartment" {{ request('type') == 'apartment' ? 'selected' : '' }}>Apartment</option>
                        <option value="studio" {{ request('type') == 'studio' ? 'selected' : '' }}>Studio</option>
                        <option value="villa" {{ request('type') == 'villa' ? 'selected' : '' }}>Villa</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="min_price">Min Price</label>
                    <input type="number" id="min_price" name="min_price" placeholder="$0" value="{{ request('min_price') }}">
                </div>
                
                <div class="form-group">
                    <label for="max_price">Max Price</label>
                    <input type="number" id="max_price" name="max_price" placeholder="$1000" value="{{ request('max_price') }}">
                </div>
                
                <div class="form-group">
                    <label for="guests">Guests</label>
                    <input type="number" id="guests" name="guests" min="1" placeholder="2" value="{{ request('guests') }}">
                </div>
                
                <div class="form-group">
                    <button type="submit" class="search-btn">🔍 Search Rooms</button>
                </div>
            </form>
        </div>

        @auth
        <!-- AI Recommendations Section -->
        <div class="ai-recommendations">
            <h3>🤖 Get AI-Powered Recommendations</h3>
            <p>Let our AI find the perfect room based on your preferences and booking history</p>
            <button class="ai-btn" onclick="getAIRecommendations()">✨ Show My Recommendations</button>
        </div>
        @endauth

        <!-- Rooms Grid -->
        <div class="rooms-grid">
            @forelse($rooms as $room)
                <div class="room-card">
                    <div class="room-image">
                        @if($room->is_featured)
                            <div class="featured-badge">⭐ Featured</div>
                        @endif
                        📸 Room Image
                    </div>
                    
                    <div class="room-content">
                        <div class="room-header">
                            <div>
                                <h3 class="room-title">{{ $room->name }}</h3>
                                <div class="room-location">📍 {{ $room->city }}, {{ $room->country }}</div>
                            </div>
                            <div class="room-price">₱{{ number_format($room->price_per_night, 0) }}</div>
                        </div>
                        
                        <div class="room-type">{{ ucfirst($room->type) }}</div>
                        
                        <div class="room-description">
                            {{ $room->description }}
                        </div>
                        
                        <div class="room-stats">
                            <span>👥 {{ $room->capacity }} guests</span>
                            <span>🛏️ {{ $room->bedrooms }} bed{{ $room->bedrooms > 1 ? 's' : '' }}</span>
                            <span>🚿 {{ $room->bathrooms }} bath{{ $room->bathrooms > 1 ? 's' : '' }}</span>
                            @if($room->area_sqm)
                                <span>📐 {{ $room->area_sqm }}m²</span>
                            @endif
                        </div>
                        
                        @if($room->rating > 0)
                            <div class="rating">
                                <span class="rating-stars">
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
                        
                        @if($room->amenities && count($room->amenities) > 0)
                            <div class="room-amenities">
                                @foreach(array_slice($room->amenities, 0, 4) as $amenity)
                                    <span class="amenity-tag">{{ $amenity }}</span>
                                @endforeach
                                @if(count($room->amenities) > 4)
                                    <span class="amenity-tag">+{{ count($room->amenities) - 4 }} more</span>
                                @endif
                            </div>
                        @endif
                        
                        <button class="view-btn" onclick="viewRoom({{ $room->id }})">
                            View Details & Book
                        </button>
                    </div>
                </div>
            @empty
                <div class="no-rooms" style="grid-column: 1 / -1;">
                    <h3>🏠 No rooms found</h3>
                    <p>Try adjusting your search filters or explore our featured properties</p>
                </div>
            @endforelse
        </div>

        @if($rooms->hasPages())
            <div style="margin-top: 40px; text-align: center;">
                {{ $rooms->links() }}
            </div>
        @endif
    </div>

    <script>
        function viewRoom(roomId) {
            window.location.href = `/rooms/${roomId}`;
        }

        @auth
        async function getAIRecommendations() {
            try {
                const response = await fetch('/ai/recommendations', {
                    headers: {
                        'Authorization': 'Bearer {{ csrf_token() }}',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });
                
                if (response.ok) {
                    const data = await response.json();
                    if (data.success && data.recommendations.length > 0) {
                        displayRecommendations(data.recommendations);
                    } else {
                        alert('No recommendations available. Start booking to get personalized suggestions!');
                    }
                } else {
                    alert('Failed to load recommendations. Please try again.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error loading recommendations. Please try again.');
            }
        }

        function displayRecommendations(recommendations) {
            const modal = document.createElement('div');
            modal.style.cssText = `
                position: fixed; top: 0; left: 0; width: 100%; height: 100%; 
                background: rgba(0,0,0,0.8); z-index: 1000; display: flex; 
                align-items: center; justify-content: center; padding: 20px;
            `;
            
            const content = document.createElement('div');
            content.style.cssText = `
                background: white; border-radius: 12px; max-width: 800px; 
                max-height: 80vh; overflow-y: auto; padding: 30px;
            `;
            
            content.innerHTML = `
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h2 style="color: #1a202c;">🤖 AI Recommendations for You</h2>
                    <button onclick="this.closest('.modal').remove()" style="background: none; border: none; font-size: 24px; cursor: pointer;">×</button>
                </div>
                <div style="display: grid; gap: 20px;">
                    ${recommendations.slice(0, 5).map(rec => `
                        <div style="border: 2px solid #e5e7eb; border-radius: 8px; padding: 15px;">
                            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 10px;">
                                <h4 style="color: #1a202c;">${rec.room.name}</h4>
                                <div style="text-align: right;">
                                    <div style="color: #667eea; font-weight: bold;">${rec.formatted_price}/night</div>
                                    <div style="background: #10b981; color: white; padding: 2px 8px; border-radius: 10px; font-size: 0.8rem; margin-top: 5px;">
                                        ${rec.score_percentage} match
                                    </div>
                                </div>
                            </div>
                            <div style="color: #6b7280; font-size: 0.9rem; margin-bottom: 10px;">
                                📍 ${rec.room.city}, ${rec.room.country}
                            </div>
                            <div style="background: #f3f4f6; padding: 10px; border-radius: 6px; font-size: 0.9rem; margin-bottom: 10px;">
                                <strong>Why recommended:</strong> ${rec.reasoning.primary_reason}
                            </div>
                            <button onclick="viewRoom(${rec.room.id}); this.closest('.modal').remove();" 
                                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; padding: 8px 16px; border-radius: 6px; cursor: pointer;">
                                View Details
                            </button>
                        </div>
                    `).join('')}
                </div>
            `;
            
            modal.className = 'modal';
            modal.appendChild(content);
            document.body.appendChild(modal);
            
            modal.onclick = (e) => {
                if (e.target === modal) modal.remove();
            };
        }
        @endauth
    </script>
</body>
</html>
