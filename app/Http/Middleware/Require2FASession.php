<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Require2FASession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Debug logging
        \Illuminate\Support\Facades\Log::info('Require2FASession middleware called', [
            'session_2fa_user_id' => session('2fa_user_id'),
            'request_url' => $request->url(),
            'auth_check' => Auth::check()
        ]);
        
        // Check if there's a 2FA session
        if (!session('2fa_user_id')) {
            \Illuminate\Support\Facades\Log::error('Require2FASession: No 2FA session found - redirecting to login');
            return redirect()->route('login')->withErrors(['error' => 'Please login first.']);
        }
        
        // Make sure user is not already authenticated
        if (Auth::check()) {
            // If already logged in, redirect to appropriate dashboard
            if (Auth::user()->is_admin) {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('home');
        }
        
        return $next($request);
    }
}
