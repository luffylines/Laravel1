<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PHPMailerControler;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminPanelController;
use App\Http\Controllers\TwoFactorController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AIRecommendationController;

// Home route - redirect based on user role
Route::get('/', function () {
    if (Auth::check()) {
        if (Auth::user()->is_admin) {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('home');
        }
    }
    return view('welcome');
});

// Dashboard route - redirect admins to admin dashboard
Route::get('/dashboard', function () {
    if (Auth::user()->is_admin) {
        return redirect()->route('admin.dashboard');
    }
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
// Auth routes with email verification
Auth::routes(['verify' => true]);


// PHPMailer SignUp
Route::get('/signup', [PHPMailerControler::class, 'getsignup'])->name('signup.form');
Route::post('/signup', [PHPMailerControler::class, 'postsignup'])->name('signup');

// Login routes - Use Laravel's Auth\LoginController for proper 2FA handling
Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login.post');

// Other routes
// Contact routes
Route::get('/contact', [PHPMailerControler::class, 'getcontact'])->name('contact.form');
Route::post('/contact', [PHPMailerControler::class, 'postcontact'])->name('contact');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/users', [PHPMailerControler::class, 'showUsers'])->name('userslist');
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware(['auth', 'redirect_admin']);

// Edit Profile route
Route::middleware(['auth', 'throttle:10,1'])->group(function () {
    Route::get('/profile/settings', [ProfileController::class, 'settings'])->name('profile.settings');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/delete', [ProfileController::class, 'delete'])->name('profile.delete');
});
//Route::get('/profile/settings', [ProfileController::class, 'settings'])->name('profile.settings')->middleware('auth');
//Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update')->middleware('auth');
//Route::delete('/profile/delete', [ProfileController::class, 'delete'])->name('profile.delete')->middleware('auth');
//admin middleware - REMOVED - Duplicate routes, using the Admin Panel Routes section below
// Route::middleware(['auth', 'is_admin'])->group(function () {
//     Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
//     Route::get('/admin/users', [AdminController::class, 'index'])->name('admin.users.index');
//     Route::get('/admin/users/create', [AdminController::class, 'create'])->name('admin.users.create');
//     Route::post('/admin/users', [AdminController::class, 'store'])->name('admin.users.store');
//     Route::get('/admin/users/{user}/edit', [AdminController::class, 'edit'])->name('admin.users.edit');
//     Route::put('/admin/users/{user}', [AdminController::class, 'update'])->name('admin.users.update');
//     Route::delete('/admin/users/{user}', [AdminController::class, 'destroy'])->name('admin.users.destroy');
// });

    
// Two-Factor Authentication routes
Route::middleware(['auth'])->group(function () {
    Route::get('/2fa/setup/setup', [TwoFactorController::class, 'setup'])->name('2fa.setup');
    Route::post('/2fa/setup/verify', [TwoFactorController::class, 'verify'])->name('2fa.verify.setup');
    Route::post('/2fa/setup/disable', [TwoFactorController::class, 'disable'])->name('2fa.disable'); 
    // Forgot OTP route
    Route::post('/2fa/setup/forgot', [TwoFactorController::class, 'disable'])->name('2fa.forgot');
}); 

// Gmail OTP routes - These should be OUTSIDE auth middleware since user is not logged in during 2FA
// Gmail send to User - POST request with CSRF protection and middleware
Route::post('/gmail/send', [TwoFactorController::class, 'sendGmailOtp'])->name('gmail.send')->middleware('require_2fa_session');
// Gmail OTP routes - Protected with 2FA session middleware
Route::get('/gmail/verify', [TwoFactorController::class, 'showGmailVerifyForm'])->name('gmail.verify')->middleware('require_2fa_session');
// Route for Gmail OTP verification logic - Protected with 2FA session middleware
Route::post('/gmail/verify', [TwoFactorController::class, 'verifyGmailOtp'])->name('gmail.verify.post')->middleware('require_2fa_session');

// Route for verifying the 2FA login
Route::post('/2fa/login/verify', [TwoFactorController::class, 'verifyLogin'])->name('2fa.verify.login')->middleware('require_2fa_session');
// Route for showing the 2FA verification form
Route::get('/2fa/login/verify', [TwoFactorController::class, 'showVerifyForm'])->name('2fa.verify.form')->middleware('require_2fa_session');
// Route for canceling 2FA
Route::post('/2fa/cancel', [TwoFactorController::class, 'cancel2FA'])->name('2fa.cancel')->middleware('require_2fa_session');

// Test middleware route
Route::get('/test-middleware', function () {
    return 'Middleware is working!';
})->middleware('is_admin');

// Test 2FA session route
Route::get('/test-2fa-session', function () {
    return 'Session 2FA User ID: ' . session('2fa_user_id', 'not set') . '<br>Auth check: ' . (Auth::check() ? 'true' : 'false');
})->middleware('require_2fa_session');

// Debug session route (no middleware)
Route::get('/debug-session', function () {
    return response()->json([
        'session_data' => session()->all(),
        'auth_check' => Auth::check(),
        'current_user' => Auth::check() ? Auth::user()->email : 'none',
        '2fa_user_id' => session('2fa_user_id'),
        'session_id' => session()->getId()
    ]);
});

// Test 2FA session specifically
Route::get('/test-2fa-session-debug', function () {
    return response()->json([
        '2fa_user_id' => session('2fa_user_id'),
        'session_all' => session()->all(),
        'session_id' => session()->getId(),
        'auth_check' => Auth::check()
    ]);
});

// Room Rental System Routes
Route::resource('rooms', RoomController::class);
Route::get('/my-rooms', [RoomController::class, 'myRooms'])->name('rooms.my-rooms')->middleware('auth');
Route::get('/rooms/{room}/availability', [RoomController::class, 'checkAvailability'])->name('rooms.availability');
Route::get('/featured-rooms', [RoomController::class, 'featured'])->name('rooms.featured');

// Booking Routes
Route::middleware(['auth'])->group(function () {
    Route::resource('bookings', BookingController::class)->except(['index']);
    Route::get('/my-bookings', [BookingController::class, 'myBookings'])->name('bookings.my-bookings');
    Route::patch('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
    Route::patch('/bookings/{booking}/review', [BookingController::class, 'review'])->name('bookings.review');
});

// AI Recommendation Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/ai/recommendations', [AIRecommendationController::class, 'getRecommendations'])->name('ai.recommendations');
    Route::get('/ai/recommendations/stored', [AIRecommendationController::class, 'getStoredRecommendations'])->name('ai.recommendations.stored');
    Route::post('/ai/recommendations/{recommendation}/clicked', [AIRecommendationController::class, 'markAsClicked'])->name('ai.recommendations.clicked');
    Route::post('/ai/recommendations/{recommendation}/booked', [AIRecommendationController::class, 'markAsBooked'])->name('ai.recommendations.booked');
    Route::get('/ai/search/personalized', [AIRecommendationController::class, 'getPersonalizedSearch'])->name('ai.search.personalized');
});

// Admin AI Analytics
Route::middleware(['auth', 'is_admin'])->group(function () {
    Route::get('/admin/ai/analytics', [AIRecommendationController::class, 'getAnalytics'])->name('admin.ai.analytics');
});

// Admin Panel Routes
Route::middleware(['auth', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminPanelController::class, 'dashboard'])->name('dashboard');
    
    // User Management
    Route::get('/users', [AdminPanelController::class, 'users'])->name('users.index');
    Route::get('/users/create', [AdminPanelController::class, 'createUser'])->name('users.create');
    Route::post('/users', [AdminPanelController::class, 'storeUser'])->name('users.store');
    Route::get('/users/{user}/edit', [AdminPanelController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{user}', [AdminPanelController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}', [AdminPanelController::class, 'deleteUser'])->name('users.destroy');
    
    // Room Management
    Route::get('/rooms', [AdminPanelController::class, 'rooms'])->name('rooms.index');
    Route::get('/rooms/create', [AdminPanelController::class, 'createRoom'])->name('rooms.create');
    Route::post('/rooms', [AdminPanelController::class, 'storeRoom'])->name('rooms.store');
    Route::get('/rooms/{room}/edit', [AdminPanelController::class, 'editRoom'])->name('rooms.edit');
    Route::put('/rooms/{room}', [AdminPanelController::class, 'updateRoom'])->name('rooms.update');
    Route::delete('/rooms/{room}', [AdminPanelController::class, 'deleteRoom'])->name('rooms.destroy');
    
    // Booking Management
    Route::get('/bookings', [AdminPanelController::class, 'bookings'])->name('bookings.index');
    Route::get('/bookings/{booking}', [AdminPanelController::class, 'showBooking'])->name('bookings.show');
    Route::patch('/bookings/{booking}/status', [AdminPanelController::class, 'updateBookingStatus'])->name('bookings.status');
    
    // Payment Management
    Route::get('/payments', [AdminPanelController::class, 'payments'])->name('payments.index');
    Route::post('/payments/{booking}/approve', [AdminPanelController::class, 'approvePayment'])->name('payments.approve');
    Route::post('/payments/{booking}/reject', [AdminPanelController::class, 'rejectPayment'])->name('payments.reject');
    
    // Reports and Analytics
    Route::get('/reports', [AdminPanelController::class, 'reports'])->name('reports');
    Route::get('/analytics', [AdminPanelController::class, 'analytics'])->name('analytics');
});