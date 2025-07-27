<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Room;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminPanelController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'is_admin']);
    }

    /**
     * Show the admin dashboard.
     */
    public function dashboard()
    {
        try {
            // Log that we reached the dashboard method
            \Illuminate\Support\Facades\Log::info('Admin dashboard method called', [
                'user_id' => Auth::id(),
                'user_email' => Auth::user()->email,
                'is_admin' => Auth::user()->is_admin
            ]);

            // Get dashboard statistics safely
            $stats = [
                'total_users' => \App\Models\User::count(),
                'total_rooms' => 0,
                'total_bookings' => 0,
                'pending_bookings' => 0,
            ];

            // Check if Room model exists
            if (class_exists('\App\Models\Room')) {
                try {
                    $stats['total_rooms'] = \App\Models\Room::count();
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::warning('Could not count rooms: ' . $e->getMessage());
                }
            }

            // Check if Booking model exists
            if (class_exists('\App\Models\Booking')) {
                try {
                    $stats['total_bookings'] = \App\Models\Booking::count();
                    $stats['pending_bookings'] = \App\Models\Booking::where('status', 'pending')->count();
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::warning('Could not count bookings: ' . $e->getMessage());
                }
            }

            \Illuminate\Support\Facades\Log::info('Dashboard stats', $stats);

            return view('admin.dashboard-simple', compact('stats'));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Admin dashboard error: ' . $e->getMessage());
            return response()->view('admin.dashboard-simple', [
                'stats' => [
                    'total_users' => 0,
                    'total_rooms' => 0,
                    'total_bookings' => 0,
                    'pending_bookings' => 0,
                ]
            ]);
        }
    }

    /**
     * Show all users.
     */
    public function users()
    {
        $users = User::paginate(15);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show create user form.
     */
    public function createUser()
    {
        return view('admin.users.create');
    }

    /**
     * Store a new user.
     */
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
            'password' => bcrypt($request->password),
            'is_admin' => $request->has('is_admin'),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    /**
     * Show edit user form.
     */
    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update user.
     */
    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'is_admin' => 'boolean',
        ]);

        $data = [
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'is_admin' => $request->has('is_admin'),
        ];

        if ($request->password) {
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Delete user.
     */
    public function deleteUser(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->withErrors(['error' => 'You cannot delete your own account.']);
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }

    /**
     * Show all rooms.
     */
    public function rooms()
    {
        $rooms = Room::paginate(15);
        return view('admin.rooms.index', compact('rooms'));
    }

    /**
     * Show create room form.
     */
    public function createRoom()
    {
        return view('admin.rooms.create');
    }

    /**
     * Show edit room form.
     */
    public function editRoom(Room $room)
    {
        return view('admin.rooms.edit', compact('room'));
    }

    /**
     * Show all bookings.
     */
    public function bookings()
    {
        $bookings = Booking::with(['user', 'room'])->paginate(15);
        return view('admin.bookings.index', compact('bookings'));
    }

    /**
     * Show specific booking.
     */
    public function showBooking(Booking $booking)
    {
        return view('admin.bookings.show', compact('booking'));
    }

    /**
     * Show payments.
     */
    public function payments()
    {
        // Add payment logic here
        return view('admin.payments.index');
    }

    /**
     * Show reports.
     */
    public function reports()
    {
        return view('admin.reports');
    }

    /**
     * Show analytics.
     */
    public function analytics()
    {
        return view('admin.analytics');
    }
}
