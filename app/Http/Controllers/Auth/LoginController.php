<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();

            // Check if 2FA is enabled
            if ($user->two_factor_enabled) {
                return redirect()->route('2fa.verify.form'); // Redirect to 2FA verification page
            }

            return redirect()->intended('/'); // Redirect to the intended page
        }

        return back()->withErrors(['email' => 'Invalid credentials.']);
    }

    protected function redirectTo()
    {
        // Redirect admin users to the admin dashboard
        if (Auth::user()->is_admin) {
            return route('admin.dashboard');
        }

        // Redirect regular users to the home page
        return route('home');
    }
    
}
