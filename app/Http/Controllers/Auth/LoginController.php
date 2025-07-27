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

        $credentials = $request->only('email', 'password');
        $user = \App\Models\User::where('email', $request->email)->first();
        
        // Check if user exists in database
        if (!$user) {
            // User doesn't exist - clear email field and show specific error
            return back()->withErrors(['email' => 'No account found with this email address.'])
                         ->withInput($request->except('email')); // Don't preserve email
        }
        
        // User exists, now check password
        if (!Auth::validate($credentials)) {
            // Wrong password - keep email field and show specific error
            return back()->withErrors(['password' => 'The password you entered is incorrect.'])
                         ->withInput($request->only('email')); // Preserve email only
        }
        
        // Credentials are valid - check for 2FA
        if ($user->two_factor_enabled) {
            // Store user ID in session for 2FA verification
            session(['2fa_user_id' => $user->id]);
            return redirect()->route('2fa.verify.form');
        }
        
        // No 2FA - log them in normally
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // Log for debugging
            \Illuminate\Support\Facades\Log::info('User logged in successfully', [
                'user_id' => Auth::user()->id,
                'email' => Auth::user()->email,
                'is_admin' => Auth::user()->is_admin ?? false
            ]);
            
            // Direct redirect for admin users
            if (Auth::user()->is_admin) {
                \Illuminate\Support\Facades\Log::info('Redirecting admin to admin dashboard');
                return redirect()->route('admin.dashboard');
            } else {
                \Illuminate\Support\Facades\Log::info('Redirecting regular user to home');
                return redirect()->route('home');
            }
        }

        // Fallback error (shouldn't reach here normally)
        return back()->withErrors(['email' => 'Login failed. Please try again.'])
                     ->withInput($request->only('email'));
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
