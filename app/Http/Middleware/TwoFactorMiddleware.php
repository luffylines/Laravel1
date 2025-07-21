<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class TwoFactorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
         $user = Auth::user();
        
        if ($user && $user->two_factor_enabled && !$request->session()->get('2fa_verified')) {
            return redirect()->route('2fa.verify.form');
        }
        // Check if user has 2FA secret key set
        if (Auth::check() && !Auth::user()->google2fa_secret) {
            return redirect()->route('2fa.setup'); // Redirect to 2FA setup page
        }

        return $next($request);
    }
}
