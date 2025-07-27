<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings</title>
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
        
        .page-header {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
            margin-bottom: 30px;
            text-align: center;
        }
        
        .page-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 10px;
        }
        
        .booking-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
            margin-bottom: 20px;
            border-left: 4px solid #667eea;
        }
        
        .booking-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .booking-id {
            font-size: 1.2rem;
            font-weight: 600;
            color: #1a202c;
        }
        
        .booking-status {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
        }
        
        .status-confirmed {
            background: #d1fae5;
            color: #065f46;
        }
        
        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }
        
        .status-cancelled {
            background: #fee2e2;
            color: #991b1b;
        }
        
        .booking-content {
            display: grid;
            grid-template-columns: 200px 1fr auto;
            gap: 20px;
            align-items: center;
        }
        
        .room-image {
            width: 180px;
            height: 120px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 0.9rem;
            text-align: center;
        }
        
        .booking-details h3 {
            font-size: 1.3rem;
            font-weight: 600;
            color: #1a202c;
            margin-bottom: 8px;
        }
        
        .booking-details p {
            color: #6b7280;
            margin-bottom: 4px;
        }
        
        .booking-dates {
            background: #f9fafb;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            min-width: 200px;
        }
        
        .date-label {
            font-size: 0.8rem;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .date-value {
            font-weight: 600;
            color: #1a202c;
            margin-bottom: 10px;
        }
        
        .total-price {
            font-size: 1.5rem;
            font-weight: 700;
            color: #667eea;
        }
        
        .no-bookings {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
        }
        
        .no-bookings-icon {
            font-size: 4rem;
            margin-bottom: 20px;
        }
        
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: background 0.3s;
        }
        
        .btn:hover {
            background: #5a67d8;
        }
        
        .btn-outline {
            background: transparent;
            border: 2px solid #667eea;
            color: #667eea;
        }
        
        .btn-outline:hover {
            background: #667eea;
            color: white;
        }
        
        .btn-sm {
            padding: 8px 16px;
            font-size: 0.9rem;
        }
        
        .btn-edit {
            background: #10b981;
            border: 2px solid #10b981;
        }
        
        .btn-edit:hover {
            background: #059669;
            border-color: #059669;
        }
        
        .btn-delete {
            background: #ef4444;
            border: 2px solid #ef4444;
        }
        
        .btn-delete:hover {
            background: #dc2626;
            border-color: #dc2626;
        }
        
        .btn-cancel {
            background: #f59e0b;
            border: 2px solid #f59e0b;
        }
        
        .btn-cancel:hover {
            background: #d97706;
            border-color: #d97706;
        }
        
        .action-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 15px;
        }
        
        .booking-actions {
            padding: 15px 0;
            border-top: 1px solid #e5e7eb;
            margin-top: 15px;
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
        
        .modal-header {
            margin-bottom: 20px;
        }
        
        .modal h3 {
            color: #1a202c;
            margin-bottom: 10px;
        }
        
        .modal p {
            color: #6b7280;
            margin-bottom: 20px;
        }
        
        .modal-buttons {
            display: flex;
            gap: 10px;
            justify-content: center;
        }
        
        .close-modal {
            position: absolute;
            right: 15px;
            top: 15px;
            font-size: 24px;
            color: #6b7280;
            cursor: pointer;
        }
        
        .close-modal:hover {
            color: #1a202c;
        }
        
        @media (max-width: 768px) {
            .booking-content {
                grid-template-columns: 1fr;
                text-align: center;
            }
            
            .booking-header {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="page-header">
            <h1 class="page-title">My Bookings</h1>
            <p>Manage your room reservations</p>
        </div>
        
        @if($bookings->count() > 0)
            @foreach($bookings as $booking)
            <div class="booking-card">
                <div class="booking-header">
                    <div class="booking-id">
                        Booking #{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}
                    </div>
                    <div class="booking-status status-{{ $booking->status }}">
                        {{ ucfirst($booking->status) }}
                    </div>
                </div>
                
                <div class="booking-content">
                    <div class="room-image">
                        📸<br>Room Photo
                    </div>
                    
                    <div class="booking-details">
                        <h3>{{ $booking->room->name }}</h3>
                        <p><strong>Location:</strong> {{ $booking->room->city }}, {{ $booking->room->country }}</p>
                        <p><strong>Room Type:</strong> {{ ucfirst($booking->room->type) }}</p>
                        <p><strong>Guests:</strong> {{ $booking->guests }} guest{{ $booking->guests > 1 ? 's' : '' }}</p>
                        <p><strong>Booked on:</strong> {{ $booking->created_at->format('M j, Y') }}</p>
                        @if($booking->special_requests)
                            <p><strong>Special Requests:</strong> {{ $booking->special_requests }}</p>
                        @endif
                    </div>
                    
                    <div class="booking-dates">
                        <div class="date-label">Check-in</div>
                        <div class="date-value">{{ $booking->check_in_date->format('M j, Y') }}</div>
                        
                        <div class="date-label">Check-out</div>
                        <div class="date-value">{{ $booking->check_out_date->format('M j, Y') }}</div>
                        
                        <div class="date-label">Duration</div>
                        <div class="date-value">{{ $booking->check_in_date->diffInDays($booking->check_out_date) }} nights</div>
                        
                        <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #e5e7eb;">
                            <div class="total-price">₱{{ number_format($booking->total_price, 2) }}</div>
                        </div>
                        
                        <div class="booking-actions">
                            <div class="action-buttons">
                                @if($booking->status !== 'cancelled')
                                    <a href="{{ route('bookings.edit', $booking) }}" class="btn btn-edit btn-sm">
                                        ✏️ Edit
                                    </a>
                                    
                                    @if($booking->check_in_date->isFuture())
                                        <button onclick="showCancelModal({{ $booking->id }})" class="btn btn-cancel btn-sm">
                                            ❌ Cancel
                                        </button>
                                    @endif
                                @endif
                                
                                <button onclick="showDeleteModal({{ $booking->id }})" class="btn btn-delete btn-sm">
                                    🗑️ Delete
                                </button>
                                
                                <a href="{{ route('bookings.show', $booking) }}" class="btn btn-outline btn-sm">
                                    👁️ View Details
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            
            {{ $bookings->links() }}
        @else
            <div class="no-bookings">
                <div class="no-bookings-icon">📅</div>
                <h2>No Bookings Yet</h2>
                <p>You haven't made any room bookings yet. Start exploring our amazing rooms!</p>
                <div style="margin-top: 20px;">
                    <a href="{{ route('rooms.index') }}" class="btn">Browse Rooms</a>
                </div>
            </div>
        @endif
        
        <div style="text-align: center; margin-top: 30px;">
            <a href="{{ route('home') }}" class="btn btn-outline">Back to Dashboard</a>
        </div>
    </div>
    
    <!-- Cancel Booking Modal -->
    <div id="cancel-modal" class="modal">
        <div class="modal-content">
            <span class="close-modal" onclick="closeCancelModal()">&times;</span>
            <div class="modal-header">
                <h3>Cancel Booking</h3>
                <p>Are you sure you want to cancel this booking?</p>
            </div>
            
            <form id="cancel-form" method="POST">
                @csrf
                @method('PATCH')
                <div style="margin-bottom: 20px;">
                    <label for="cancellation_reason" style="display: block; margin-bottom: 8px; font-weight: 600;">Reason for cancellation (optional):</label>
                    <textarea id="cancellation_reason" name="cancellation_reason" rows="3" style="width: 100%; padding: 10px; border: 2px solid #e5e7eb; border-radius: 8px;" placeholder="Please let us know why you're cancelling..."></textarea>
                </div>
                
                <div class="modal-buttons">
                    <button type="button" onclick="closeCancelModal()" class="btn btn-outline">Keep Booking</button>
                    <button type="submit" class="btn btn-cancel">Yes, Cancel Booking</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Delete Booking Modal -->
    <div id="delete-modal" class="modal">
        <div class="modal-content">
            <span class="close-modal" onclick="closeDeleteModal()">&times;</span>
            <div class="modal-header">
                <h3>Delete Booking</h3>
                <p>⚠️ This action cannot be undone. Are you sure you want to permanently delete this booking?</p>
            </div>
            
            <form id="delete-form" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-buttons">
                    <button type="button" onclick="closeDeleteModal()" class="btn btn-outline">Cancel</button>
                    <button type="submit" class="btn btn-delete">Yes, Delete Booking</button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        function showCancelModal(bookingId) {
            const modal = document.getElementById('cancel-modal');
            const form = document.getElementById('cancel-form');
            form.action = `/bookings/${bookingId}/cancel`;
            modal.style.display = 'block';
        }
        
        function closeCancelModal() {
            document.getElementById('cancel-modal').style.display = 'none';
        }
        
        function showDeleteModal(bookingId) {
            const modal = document.getElementById('delete-modal');
            const form = document.getElementById('delete-form');
            form.action = `/bookings/${bookingId}`;
            modal.style.display = 'block';
        }
        
        function closeDeleteModal() {
            document.getElementById('delete-modal').style.display = 'none';
        }
        
        // Close modals when clicking outside
        window.onclick = function(event) {
            const cancelModal = document.getElementById('cancel-modal');
            const deleteModal = document.getElementById('delete-modal');
            
            if (event.target === cancelModal) {
                closeCancelModal();
            }
            if (event.target === deleteModal) {
                closeDeleteModal();
            }
        }
        
        // Show success/error messages
        @if(session('success'))
            alert('✅ {{ session('success') }}');
        @endif
        
        @if(session('error'))
            alert('❌ {{ session('error') }}');
        @endif
    </script>
</body>
</html>
