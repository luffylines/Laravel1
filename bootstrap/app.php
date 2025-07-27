<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'is_admin' => \App\Http\Middleware\IsAdmin::class,
            'set_timezone' => \App\Http\Middleware\SetUserTimezone::class,
            '2fa' => \App\Http\Middleware\TwoFactorMiddleware::class,
            'redirect_admin' => \App\Http\Middleware\RedirectAdminToDashboard::class,
            'require_2fa_session' => \App\Http\Middleware\Require2FASession::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
