<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Room;
use App\Mail\BookingConfirmationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bookings = Auth::user()->bookings()->with('room')->orderBy('created_at', 'desc')->paginate(10);
        return view('bookings.index', compact('bookings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $room = Room::findOrFail($request->room_id);
        
        // Pre-fill form data from query parameters
        $bookingData = [
            'room' => $room,
            'check_in_date' => $request->check_in_date,
            'check_out_date' => $request->check_out_date,
            'guests' => $request->guests ?? 2,
        ];
        
        // Calculate pricing
        if ($request->check_in_date && $request->check_out_date) {
            $checkIn = Carbon::parse($request->check_in_date);
            $checkOut = Carbon::parse($request->check_out_date);
            $nights = $checkIn->diffInDays($checkOut);
            
            $subtotal = $room->price_per_night * $nights;
            $serviceFee = $subtotal * 0.1; // 10% service fee
            $total = $subtotal + $serviceFee;
            
            $bookingData['nights'] = $nights;
            $bookingData['subtotal'] = $subtotal;
            $bookingData['service_fee'] = $serviceFee;
            $bookingData['total'] = $total;
        }
        
        return view('bookings.create', $bookingData);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'check_in_date' => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'guests' => 'required|integer|min:1',
            'special_requests' => 'nullable|string|max:500',
        ]);

        $room = Room::findOrFail($request->room_id);
        
        // Check availability one more time
        if (!$room->isAvailableForDates($request->check_in_date, $request->check_out_date)) {
            return response()->json([
                'success' => false,
                'message' => 'Room is no longer available for selected dates.'
            ], 400);
        }
        
        // Check capacity
        if ($request->guests > $room->capacity) {
            return response()->json([
                'success' => false,
                'message' => 'Number of guests exceeds room capacity.'
            ], 400);
        }

        // Calculate pricing
        $checkIn = Carbon::parse($request->check_in_date);
        $checkOut = Carbon::parse($request->check_out_date);
        $nights = $checkIn->diffInDays($checkOut);
        
        $subtotal = $room->price_per_night * $nights;
        $serviceFee = $subtotal * 0.1; // 10% service fee
        $total = $subtotal + $serviceFee;

        // Create booking
        $booking = Booking::create([
            'user_id' => Auth::id(),
            'room_id' => $room->id,
            'check_in_date' => $request->check_in_date,
            'check_out_date' => $request->check_out_date,
            'guests' => $request->guests,
            'total_price' => $total,
            'service_fee' => $serviceFee,
            'status' => 'confirmed',
            'payment_status' => 'pending',
            'payment_method' => 'credit_card', // Default for now
            'special_requests' => $request->special_requests,
        ]);

        // Send confirmation email
        try {
            Mail::to(Auth::user()->email)->send(new BookingConfirmationMail($booking));
        } catch (\Exception $e) {
            // Log error but don't fail the booking
            \Illuminate\Support\Facades\Log::error('Failed to send booking confirmation email: ' . $e->getMessage());
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Booking completed successfully!',
                'booking' => $booking->load('room'),
                'redirect_url' => route('home')
            ]);
        }

        return redirect()->route('home')->with('success', 'Booking completed successfully! Check your email for confirmation.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Booking $booking)
    {
        // Ensure user can only view their own bookings
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }
        
        $booking->load('room', 'user');
        return view('bookings.show', compact('booking'));
    }

    /**
     * Show user's bookings
     */
    public function myBookings()
    {
        $bookings = Auth::user()->bookings()
            ->with('room')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('bookings.my-bookings', compact('bookings'));
    }

    /**
     * Cancel a booking
     */
    public function cancel(Request $request, Booking $booking)
    {
        // Ensure user can only cancel their own bookings
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }
        
        if ($booking->status === 'cancelled') {
            return back()->with('error', 'Booking is already cancelled.');
        }
        
        $booking->update([
            'status' => 'cancelled',
            'cancellation_reason' => $request->cancellation_reason,
            'cancelled_at' => now(),
        ]);
        
        return back()->with('success', 'Booking cancelled successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Booking $booking)
    {
        // Ensure user can only edit their own bookings
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }
        
        return view('bookings.edit', compact('booking'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Booking $booking)
    {
        // Ensure user can only update their own bookings
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }
        
        $rules = [
            'special_requests' => 'nullable|string|max:500',
        ];
        
        // Allow date and guest changes only if booking is not cancelled and check-in is in future
        if ($booking->status !== 'cancelled' && $booking->check_in_date->isFuture()) {
            $rules = array_merge($rules, [
                'check_in_date' => 'required|date|after_or_equal:today',
                'check_out_date' => 'required|date|after:check_in_date',
                'guests' => 'required|integer|min:1|max:' . $booking->room->capacity,
            ]);
        }
        
        $request->validate($rules);
        
        $updateData = [
            'special_requests' => $request->special_requests,
        ];
        
        // Update dates and guests if allowed
        if ($booking->status !== 'cancelled' && $booking->check_in_date->isFuture() && $request->has('check_in_date')) {
            // Check if room is available for new dates (excluding current booking)
            $conflictingBookings = $booking->room->bookings()
                ->where('id', '!=', $booking->id)
                ->where('status', '!=', 'cancelled')
                ->where(function ($query) use ($request) {
                    $query->whereBetween('check_in_date', [$request->check_in_date, $request->check_out_date])
                          ->orWhereBetween('check_out_date', [$request->check_in_date, $request->check_out_date])
                          ->orWhere(function ($q) use ($request) {
                              $q->where('check_in_date', '<=', $request->check_in_date)
                                ->where('check_out_date', '>=', $request->check_out_date);
                          });
                })->exists();
                
            if ($conflictingBookings) {
                return back()->withErrors(['check_in_date' => 'Room is not available for the selected dates.']);
            }
            
            // Recalculate pricing if dates changed
            $checkIn = Carbon::parse($request->check_in_date);
            $checkOut = Carbon::parse($request->check_out_date);
            $nights = $checkIn->diffInDays($checkOut);
            
            $subtotal = $booking->room->price_per_night * $nights;
            $serviceFee = $subtotal * 0.1;
            $total = $subtotal + $serviceFee;
            
            $updateData = array_merge($updateData, [
                'check_in_date' => $request->check_in_date,
                'check_out_date' => $request->check_out_date,
                'guests' => $request->guests,
                'total_price' => $total,
                'service_fee' => $serviceFee,
            ]);
        }
        
        $booking->update($updateData);
        
        return redirect()->route('bookings.show', $booking)->with('success', 'Booking updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking)
    {
        // Ensure user can only delete their own bookings
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }
        
        $booking->delete();
        return redirect()->route('bookings.my-bookings')->with('success', 'Booking deleted successfully.');
    }
}
