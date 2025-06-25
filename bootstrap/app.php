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
    ->withMiddleware(function (Middleware $middleware) {
        // Apply language middleware to all web routes
        $middleware->web(append: [
            \App\Http\Middleware\SetLanguage::class,
        ]);
        
        // Register middleware aliases
        $middleware->alias([
            'set.language' => \App\Http\Middleware\SetLanguage::class,
            'company.route' => \App\Http\Middleware\CompanyRouteMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();