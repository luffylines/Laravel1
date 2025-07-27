<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PHPMailerControler;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TwoFactorController;

// Home route
Route::get('/', function () {
    return view('welcome');
});

// Dashboard route
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
// Auth routes with email verification
Auth::routes(['verify' => true]);


// PHPMailer SignUp
Route::get('/signup', [PHPMailerControler::class, 'getsignup'])->name('signup.form');
Route::post('/signup', [PHPMailerControler::class, 'postsignup'])->name('signup');

// Login routes
Route::get('/login', [PHPMailerControler::class, 'getlogin'])->name('login.form');
Route::post('/login', [PHPMailerControler::class, 'postlogin'])->name('login');

// Other routes
// Contact routes
Route::get('/contact', [PHPMailerControler::class, 'getcontact'])->name('contact.form');
Route::post('/contact', [PHPMailerControler::class, 'postcontact'])->name('contact');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/users', [PHPMailerControler::class, 'showUsers'])->name('userslist');
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Edit Profile route
Route::middleware(['auth', 'throttle:10,1'])->group(function () {
    Route::get('/profile/settings', [ProfileController::class, 'settings'])->name('profile.settings');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/delete', [ProfileController::class, 'delete'])->name('profile.delete');
});
//Route::get('/profile/settings', [ProfileController::class, 'settings'])->name('profile.settings')->middleware('auth');
//Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update')->middleware('auth');
//Route::delete('/profile/delete', [ProfileController::class, 'delete'])->name('profile.delete')->middleware('auth');
//admin middleware
Route::middleware(['auth', 'is_admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/users', [AdminController::class, 'index'])->name('admin.users.index');
    Route::get('/admin/users/create', [AdminController::class, 'create'])->name('admin.users.create');
    Route::post('/admin/users', [AdminController::class, 'store'])->name('admin.users.store');
    Route::get('/admin/users/{user}/edit', [AdminController::class, 'edit'])->name('admin.users.edit');
    Route::put('/admin/users/{user}', [AdminController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{user}', [AdminController::class, 'destroy'])->name('admin.users.destroy');
});

    
// Two-Factor Authentication routes
Route::middleware(['auth'])->group(function () {
    Route::get('/2fa/setup/setup', [TwoFactorController::class, 'setup'])->name('2fa.setup');
    Route::post('/2fa/setup/verify', [TwoFactorController::class, 'verify'])->name('2fa.verify.setup');
    Route::post('/2fa/setup/disable', [TwoFactorController::class, 'disable'])->name('2fa.disable'); 
   // Gmail send to User
    Route::post('/gmail/send', [TwoFactorController::class, 'sendGmailOtp'])->name('gmail.send');
    // Gmail OTP routes
    Route::get('/gmail/verify', [TwoFactorController::class, 'showGmailVerifyForm'])->name('gmail.verify');
   // Route for Gmail OTP verification logic
    Route::post('/gmail/verify', [TwoFactorController::class, 'verifyGmailOtp'])->name('gmail.verify.post');
    // Forgot OTP route
    Route::post('/2fa/setup/forgot', [TwoFactorController::class, 'disable'])->name('2fa.forgot');
}); 

// Route for verifying the 2FA login
Route::post('/2fa/login/verify', [TwoFactorController::class, 'verifyLogin'])->name('2fa.verify.login')->middleware('auth');
// Route for showing the 2FA verification form
Route::get('/2fa/login/verify', [TwoFactorController::class, 'showVerifyForm'])->name('2fa.verify.form')->middleware('auth');
// Route for disabling 2FA
Route::post('/2fa/login/disable', [TwoFactorController::class, 'disable'])->name('2fa.disable')->middleware('auth');