<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PHPMailerControler;
use Illuminate\Support\Facades\Auth;

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
