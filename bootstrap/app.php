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
        // أضف الـ SetLanguage middleware للـ web group
        $middleware->web(append: [
            \App\Http\Middleware\SetLanguage::class,
        ]);
        
        // يمكنك أيضاً إضافة alias للـ middleware
        $middleware->alias([
            'set.language' => \App\Http\Middleware\SetLanguage::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();