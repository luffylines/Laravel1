<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Room - Admin Panel</title>
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
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        .form-card {
            background-color: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        }
        
        .form-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 1.5rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-label {
            display: block;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
        }
        
        .form-input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.2s;
        }
        
        .form-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        .form-textarea {
            min-height: 100px;
            resize: vertical;
        }
        
        .form-select {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 1rem;
            background-color: white;
        }
        
        .form-checkbox {
            margin-right: 0.5rem;
        }
        
        .checkbox-group {
            display: flex;
            align-items: center;
            margin-bottom: 0.5rem;
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
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
        
        .btn-primary {
            background-color: #3b82f6;
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #2563eb;
        }
        
        .btn-secondary {
            background-color: #6b7280;
            color: white;
        }
        
        .btn-secondary:hover {
            background-color: #4b5563;
        }
        
        .form-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
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
        
        .error-message {
            color: #ef4444;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
        
        .amenities-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 0.5rem;
            margin-top: 0.5rem;
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
        <a href="{{ route('admin.rooms.index') }}" class="back-link">← Back to Rooms</a>
        
        <div class="form-card">
            <h1 class="form-title">Add New Room</h1>
            
            <form method="POST" action="{{ route('admin.rooms.store') }}">
                @csrf
                
                <div class="form-group">
                    <label for="name" class="form-label">Room Name *</label>
                    <input type="text" id="name" name="name" class="form-input" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="description" class="form-label">Description *</label>
                    <textarea id="description" name="description" class="form-input form-textarea" required>{{ old('description') }}</textarea>
                    @error('description')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="type" class="form-label">Room Type *</label>
                        <select id="type" name="type" class="form-select" required>
                            <option value="">Select Type</option>
                            <option value="single" {{ old('type') === 'single' ? 'selected' : '' }}>Single</option>
                            <option value="double" {{ old('type') === 'double' ? 'selected' : '' }}>Double</option>
                            <option value="suite" {{ old('type') === 'suite' ? 'selected' : '' }}>Suite</option>
                            <option value="deluxe" {{ old('type') === 'deluxe' ? 'selected' : '' }}>Deluxe</option>
                        </select>
                        @error('type')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="capacity" class="form-label">Capacity *</label>
                        <input type="number" id="capacity" name="capacity" class="form-input" min="1" max="10" value="{{ old('capacity') }}" required>
                        @error('capacity')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="price_per_night" class="form-label">Price per Night (₱) *</label>
                    <input type="number" id="price_per_night" name="price_per_night" class="form-input" step="0.01" min="0" value="{{ old('price_per_night') }}" required>
                    @error('price_per_night')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="address" class="form-label">Address *</label>
                    <input type="text" id="address" name="address" class="form-input" value="{{ old('address') }}" required>
                    @error('address')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="city" class="form-label">City *</label>
                        <input type="text" id="city" name="city" class="form-input" value="{{ old('city') }}" required>
                        @error('city')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="country" class="form-label">Country *</label>
                        <input type="text" id="country" name="country" class="form-input" value="{{ old('country', 'Philippines') }}" required>
                        @error('country')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Amenities</label>
                    <div class="amenities-grid">
                        @php
                            $amenities = ['WiFi', 'Air Conditioning', 'TV', 'Mini Bar', 'Room Service', 'Balcony', 'Ocean View', 'Parking', 'Gym Access', 'Pool Access', 'Spa Access', 'Kitchen'];
                            $oldAmenities = old('amenities', []);
                        @endphp
                        @foreach($amenities as $amenity)
                        <div class="checkbox-group">
                            <input type="checkbox" id="amenity_{{ $loop->index }}" name="amenities[]" value="{{ $amenity }}" class="form-checkbox"
                                   {{ in_array($amenity, $oldAmenities) ? 'checked' : '' }}>
                            <label for="amenity_{{ $loop->index }}">{{ $amenity }}</label>
                        </div>
                        @endforeach
                    </div>
                    @error('amenities')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <div class="checkbox-group">
                        <input type="checkbox" id="is_available" name="is_available" value="1" class="form-checkbox" 
                               {{ old('is_available', true) ? 'checked' : '' }}>
                        <label for="is_available" class="form-label">Available for booking</label>
                    </div>
                    @error('is_available')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Create Room</button>
                    <a href="{{ route('admin.rooms.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
