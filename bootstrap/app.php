<?php

use App\Http\Middleware\AdminUserMiddleware;
use App\Http\Middleware\RequestLogger;
use App\Http\Middleware\ThrottleBots;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'useradmin' => AdminUserMiddleware::class,
            'requestlog' => RequestLogger::class,
            'throttle.bots' => ThrottleBots::class,
        ]);
        // Tambahkan ke grup 'web' dan/atau 'api'
        $middleware->appendToGroup('web', RequestLogger::class);
        $middleware->appendToGroup('api', RequestLogger::class);
    })

    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
