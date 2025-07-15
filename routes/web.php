<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome', ['message' => 'Hello from Laravel!']);
});

Route::get('/about', function () {
    return view('about');
});