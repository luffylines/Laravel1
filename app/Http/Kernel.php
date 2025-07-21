<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;
use App\Http\Middleware\IsAdmin;

class Kernel extends HttpKernel
{
    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        // Other middleware...
        'is_admin' => \App\Http\Middleware\IsAdmin::class,
        'set_timezone' => \App\Http\Middleware\SetUserTimezone::class,
        '2fa' => \App\Http\Middleware\TwoFactorMiddleware::class,
    ];
}