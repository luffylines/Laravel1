<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    /**
     * Handle an incoming request.
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->withErrors(['error' => 'Please login to access admin area.']);
        }

        // Check if user has admin privileges
        if (!Auth::user()->is_admin) {
            \Illuminate\Support\Facades\Log::warning('Non-admin user tried to access admin area', [
                'user_id' => Auth::id(),
                'email' => Auth::user()->email,
                'is_admin' => Auth::user()->is_admin
            ]);
            return redirect()->route('home')->withErrors(['error' => 'You do not have admin access.']);
        }

        \Illuminate\Support\Facades\Log::info('Admin access granted', [
            'user_id' => Auth::id(),
            'email' => Auth::user()->email
        ]);

        return $next($request);
    }
}
