<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class AdminController extends Controller
{
    public function showUsers()
    {
        $users = User::all();
        return view('users', compact('users'));
    }
}