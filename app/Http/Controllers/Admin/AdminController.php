<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Room;
use App\Models\Booking;
use App\Models\AiRecommendation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // Admin check is now handled by the 'is_admin' middleware in routes
    }

    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_rooms' => Room::count(),
            'available_rooms' => Room::where('is_available', true)->count(),
            'total_bookings' => Booking::count(),
            'pending_bookings' => Booking::where('status', 'pending')->count(),
            'confirmed_bookings' => Booking::where('status', 'confirmed')->count(),
            'total_revenue' => Booking::where('payment_status', 'paid')->sum('total_price'),
            'pending_payments' => Booking::where('payment_status', 'pending')->count(),
            'ai_recommendations' => AiRecommendation::count(),
            'clicked_recommendations' => AiRecommendation::where('is_clicked', true)->count(),
            'new_users_today' => User::whereDate('created_at', today())->count(),
        ];

        $recent_bookings = Booking::with(['user', 'room'])
            ->latest()
            ->take(5)
            ->get();

        $pending_payments = Booking::with(['user', 'room'])
            ->where('payment_status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_bookings', 'pending_payments'));
    }

    // User Management
    public function users()
    {
        $users = User::latest()->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function createUser()
    {
        return view('admin.users.create');
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'is_admin' => 'boolean',
        ]);

        User::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => $request->boolean('is_admin'),
            'email_verified_at' => now(),
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'is_admin' => 'boolean',
        ]);

        $user->update([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'is_admin' => $request->boolean('is_admin'),
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:8|confirmed']);
            $user->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function deleteUser(User $user)
    {
        if ($user->id === Auth::id()) {
            return redirect()->back()
                ->with('error', 'You cannot delete your own account.');
        }

        $user->delete();
        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    // Room Management
    public function rooms()
    {
        $rooms = Room::latest()->paginate(15);
        return view('admin.rooms.index', compact('rooms'));
    }

    public function createRoom()
    {
        return view('admin.rooms.create');
    }

    public function storeRoom(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:single,double,suite,deluxe',
            'capacity' => 'required|integer|min:1|max:10',
            'price_per_night' => 'required|numeric|min:0',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'amenities' => 'array',
            'is_available' => 'boolean',
        ]);

        Room::create([
            'name' => $request->name,
            'description' => $request->description,
            'type' => $request->type,
            'capacity' => $request->capacity,
            'price_per_night' => $request->price_per_night,
            'address' => $request->address,
            'city' => $request->city,
            'country' => $request->country,
            'amenities' => $request->amenities ?? [],
            'is_available' => $request->boolean('is_available', true),
        ]);

        return redirect()->route('admin.rooms.index')
            ->with('success', 'Room created successfully.');
    }

    public function editRoom(Room $room)
    {
        return view('admin.rooms.edit', compact('room'));
    }

    public function updateRoom(Request $request, Room $room)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:single,double,suite,deluxe',
            'capacity' => 'required|integer|min:1|max:10',
            'price_per_night' => 'required|numeric|min:0',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'amenities' => 'array',
            'is_available' => 'boolean',
        ]);

        $room->update([
            'name' => $request->name,
            'description' => $request->description,
            'type' => $request->type,
            'capacity' => $request->capacity,
            'price_per_night' => $request->price_per_night,
            'address' => $request->address,
            'city' => $request->city,
            'country' => $request->country,
            'amenities' => $request->amenities ?? [],
            'is_available' => $request->boolean('is_available', true),
        ]);

        return redirect()->route('admin.rooms.index')
            ->with('success', 'Room updated successfully.');
    }

    public function deleteRoom(Room $room)
    {
        // Check if room has active bookings
        $activeBookings = $room->bookings()
            ->whereIn('status', ['confirmed', 'pending'])
            ->where('check_in_date', '>=', now())
            ->count();

        if ($activeBookings > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete room with active bookings.');
        }

        $room->delete();
        return redirect()->route('admin.rooms.index')
            ->with('success', 'Room deleted successfully.');
    }

    // Booking Management
    public function bookings()
    {
        $bookings = Booking::with(['user', 'room'])
            ->latest()
            ->paginate(15);
        return view('admin.bookings.index', compact('bookings'));
    }

    public function showBooking(Booking $booking)
    {
        return view('admin.bookings.show', compact('booking'));
    }

    public function updateBookingStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled',
        ]);

        $booking->update([
            'status' => $request->status,
            'cancelled_at' => $request->status === 'cancelled' ? now() : null,
        ]);

        return redirect()->back()
            ->with('success', 'Booking status updated successfully.');
    }

    // Payment Management
    public function payments()
    {
        $payments = Booking::with(['user', 'room'])
            ->where('payment_status', 'pending')
            ->latest()
            ->paginate(15);
        return view('admin.payments.index', compact('payments'));
    }

    public function approvePayment(Booking $booking)
    {
        $booking->update([
            'payment_status' => 'paid',
            'status' => 'confirmed',
        ]);

        return redirect()->back()
            ->with('success', 'Payment approved and booking confirmed.');
    }

    public function rejectPayment(Booking $booking)
    {
        $booking->update([
            'payment_status' => 'failed',
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);

        return redirect()->back()
            ->with('success', 'Payment rejected and booking cancelled.');
    }

    // Reports and Analytics
    public function reports()
    {
        $monthlyRevenue = Booking::where('payment_status', 'paid')
            ->selectRaw('MONTH(created_at) as month, SUM(total_price) as revenue')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->pluck('revenue', 'month')
            ->toArray();

        $roomPopularity = Room::withCount('bookings')
            ->orderBy('bookings_count', 'desc')
            ->take(10)
            ->get();

        $userStats = [
            'total_users' => User::count(),
            'admin_users' => User::where('is_admin', true)->count(),
            'verified_users' => User::whereNotNull('email_verified_at')->count(),
            '2fa_enabled' => User::where('two_factor_enabled', true)->count(),
        ];

        return view('admin.reports', compact('monthlyRevenue', 'roomPopularity', 'userStats'));
    }

    public function analytics()
    {
        $aiStats = [
            'total_recommendations' => AiRecommendation::count(),
            'clicked_recommendations' => AiRecommendation::where('is_clicked', true)->count(),
            'booked_recommendations' => AiRecommendation::where('is_booked', true)->count(),
            'click_through_rate' => AiRecommendation::count() > 0 
                ? (AiRecommendation::where('is_clicked', true)->count() / AiRecommendation::count()) * 100 
                : 0,
            'conversion_rate' => AiRecommendation::where('is_clicked', true)->count() > 0 
                ? (AiRecommendation::where('is_booked', true)->count() / AiRecommendation::where('is_clicked', true)->count()) * 100 
                : 0,
        ];

        $recommendationTypes = AiRecommendation::selectRaw('recommendation_type, COUNT(*) as count')
            ->groupBy('recommendation_type')
            ->pluck('count', 'recommendation_type')
            ->toArray();

        return view('admin.analytics', compact('aiStats', 'recommendationTypes'));
    }
}
