<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectAdminToDashboard
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // If user is authenticated and is an admin, redirect to admin dashboard
        if (Auth::check() && Auth::user()->is_admin && $request->is('home')) {
            return redirect()->route('admin.dashboard');
        }

        return $next($request);
    }
}
