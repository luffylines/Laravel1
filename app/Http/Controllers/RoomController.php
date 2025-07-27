<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RoomController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * Display a listing of rooms with search and filtering
     */
    public function index(Request $request)
    {
        $query = Room::available()->with('owner');

        // Apply filters
        if ($request->city) {
            $query->inCity($request->city);
        }

        if ($request->type) {
            $query->byType($request->type);
        }

        if ($request->min_price && $request->max_price) {
            $query->priceRange($request->min_price, $request->max_price);
        }

        if ($request->guests) {
            $query->where('capacity', '>=', $request->guests);
        }

        if ($request->amenities) {
            $amenities = is_array($request->amenities) ? $request->amenities : explode(',', $request->amenities);
            foreach ($amenities as $amenity) {
                $query->whereJsonContains('amenities', trim($amenity));
            }
        }

        // Sorting
        $sortBy = $request->get('sort', 'featured');
        switch ($sortBy) {
            case 'price_low':
                $query->orderBy('price_per_night', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price_per_night', 'desc');
                break;
            case 'rating':
                $query->orderByDesc('rating')->orderByDesc('total_reviews');
                break;
            case 'newest':
                $query->orderByDesc('created_at');
                break;
            default:
                $query->orderByDesc('is_featured')->orderByDesc('rating');
        }

        $rooms = $query->paginate(12);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'rooms' => $rooms->items(),
                'pagination' => [
                    'current_page' => $rooms->currentPage(),
                    'last_page' => $rooms->lastPage(),
                    'per_page' => $rooms->perPage(),
                    'total' => $rooms->total()
                ]
            ]);
        }

        return view('rooms.index', compact('rooms'));
    }

    /**
     * Show the form for creating a new room
     */
    public function create()
    {
        $roomTypes = ['single', 'double', 'suite', 'apartment', 'studio', 'villa'];
        $amenities = [
            'WiFi', 'Air Conditioning', 'TV', 'Kitchen', 'Parking', 'Pool', 
            'Gym', 'Balcony', 'Pet Friendly', 'Smoking Allowed', 'Breakfast',
            'Laundry', 'Hot Tub', 'Fireplace', 'Garden', 'Terrace'
        ];

        return view('rooms.create', compact('roomTypes', 'amenities'));
    }

    /**
     * Store a newly created room
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|min:50',
            'type' => 'required|in:single,double,suite,apartment,studio,villa',
            'price_per_night' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1|max:20',
            'bedrooms' => 'required|integer|min:1|max:10',
            'bathrooms' => 'required|integer|min:1|max:10',
            'area_sqm' => 'nullable|numeric|min:1',
            'amenities' => 'array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        // Handle image uploads
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('rooms', 'public');
                $imagePaths[] = $path;
            }
        }

        $room = Room::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'type' => $validated['type'],
            'price_per_night' => $validated['price_per_night'],
            'capacity' => $validated['capacity'],
            'bedrooms' => $validated['bedrooms'],
            'bathrooms' => $validated['bathrooms'],
            'area_sqm' => $validated['area_sqm'],
            'amenities' => $validated['amenities'] ?? [],
            'images' => $imagePaths,
            'address' => $validated['address'],
            'city' => $validated['city'],
            'country' => $validated['country'],
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'owner_id' => Auth::id(),
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Room created successfully',
                'room' => $room
            ], 201);
        }

        return redirect()->route('rooms.show', $room)
            ->with('success', 'Room created successfully!');
    }

    /**
     * Display the specified room
     */
    public function show(Room $room)
    {
        $room->load('owner', 'bookings');
        
        // Get related rooms (same city, type, or similar price range)
        $relatedRooms = Room::available()
            ->where('id', '!=', $room->id)
            ->where(function($query) use ($room) {
                $query->where('city', $room->city)
                      ->orWhere('type', $room->type)
                      ->orWhereBetween('price_per_night', [
                          $room->price_per_night * 0.8,
                          $room->price_per_night * 1.2
                      ]);
            })
            ->orderByDesc('rating')
            ->take(4)
            ->get();

        // Check availability for next 30 days
        $availabilityCalendar = $this->generateAvailabilityCalendar($room);

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'room' => $room,
                'related_rooms' => $relatedRooms,
                'availability_calendar' => $availabilityCalendar
            ]);
        }

        return view('rooms.show', compact('room', 'relatedRooms', 'availabilityCalendar'));
    }

    /**
     * Show the form for editing the specified room
     */
    public function edit(Room $room)
    {
        // Only room owner or admin can edit
        if ($room->owner_id !== Auth::id() && !Auth::user()->is_admin) {
            abort(403, 'Unauthorized');
        }

        $roomTypes = ['single', 'double', 'suite', 'apartment', 'studio', 'villa'];
        $amenities = [
            'WiFi', 'Air Conditioning', 'TV', 'Kitchen', 'Parking', 'Pool', 
            'Gym', 'Balcony', 'Pet Friendly', 'Smoking Allowed', 'Breakfast',
            'Laundry', 'Hot Tub', 'Fireplace', 'Garden', 'Terrace'
        ];

        return view('rooms.edit', compact('room', 'roomTypes', 'amenities'));
    }

    /**
     * Update the specified room
     */
    public function update(Request $request, Room $room)
    {
        // Only room owner or admin can update
        if ($room->owner_id !== Auth::id() && !Auth::user()->is_admin) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|min:50',
            'type' => 'required|in:single,double,suite,apartment,studio,villa',
            'price_per_night' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1|max:20',
            'bedrooms' => 'required|integer|min:1|max:10',
            'bathrooms' => 'required|integer|min:1|max:10',
            'area_sqm' => 'nullable|numeric|min:1',
            'amenities' => 'array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'is_available' => 'boolean',
        ]);

        // Handle new image uploads
        $imagePaths = $room->images ?? [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('rooms', 'public');
                $imagePaths[] = $path;
            }
        }

        $room->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'type' => $validated['type'],
            'price_per_night' => $validated['price_per_night'],
            'capacity' => $validated['capacity'],
            'bedrooms' => $validated['bedrooms'],
            'bathrooms' => $validated['bathrooms'],
            'area_sqm' => $validated['area_sqm'],
            'amenities' => $validated['amenities'] ?? [],
            'images' => $imagePaths,
            'address' => $validated['address'],
            'city' => $validated['city'],
            'country' => $validated['country'],
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'is_available' => $validated['is_available'] ?? $room->is_available,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Room updated successfully',
                'room' => $room
            ]);
        }

        return redirect()->route('rooms.show', $room)
            ->with('success', 'Room updated successfully!');
    }

    /**
     * Remove the specified room
     */
    public function destroy(Room $room)
    {
        // Only room owner or admin can delete
        if ($room->owner_id !== Auth::id() && !Auth::user()->is_admin) {
            abort(403, 'Unauthorized');
        }

        // Check if room has active bookings
        $activeBookings = $room->bookings()
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('check_out_date', '>=', now())
            ->count();

        if ($activeBookings > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete room with active bookings'
            ], 400);
        }

        // Delete associated images
        if ($room->images) {
            foreach ($room->images as $imagePath) {
                Storage::disk('public')->delete($imagePath);
            }
        }

        $room->delete();

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Room deleted successfully'
            ]);
        }

        return redirect()->route('rooms.index')
            ->with('success', 'Room deleted successfully!');
    }

    /**
     * Get rooms owned by the authenticated user
     */
    public function myRooms()
    {
        $rooms = Room::where('owner_id', Auth::id())
            ->with('bookings')
            ->paginate(10);

        return view('rooms.my-rooms', compact('rooms'));
    }

    /**
     * Check room availability for specific dates
     */
    public function checkAvailability(Request $request, Room $room)
    {
        $request->validate([
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
        ]);

        $isAvailable = $room->isAvailableForDates(
            $request->check_in, 
            $request->check_out
        );

        return response()->json([
            'success' => true,
            'available' => $isAvailable,
            'room_id' => $room->id,
            'check_in' => $request->check_in,
            'check_out' => $request->check_out
        ]);
    }

    /**
     * Generate availability calendar for room
     */
    private function generateAvailabilityCalendar(Room $room, $days = 30)
    {
        $calendar = [];
        $startDate = now();

        for ($i = 0; $i < $days; $i++) {
            $date = $startDate->copy()->addDays($i);
            $nextDate = $date->copy()->addDay();
            
            $isAvailable = $room->isAvailableForDates(
                $date->format('Y-m-d'),
                $nextDate->format('Y-m-d')
            );

            $calendar[] = [
                'date' => $date->format('Y-m-d'),
                'available' => $isAvailable,
                'price' => $room->price_per_night
            ];
        }

        return $calendar;
    }

    /**
     * Get featured rooms
     */
    public function featured()
    {
        $rooms = Room::featured()
            ->available()
            ->with('owner')
            ->orderByDesc('rating')
            ->take(8)
            ->get();

        return response()->json([
            'success' => true,
            'featured_rooms' => $rooms
        ]);
    }
}
