<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthenticationException;

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
            'admin.auth' => \App\Http\Middleware\AdminAuth::class,
        ]);
    })
    ->withSchedule(function (Schedule $schedule) {
        // Process expired tow service stages every 5 minutes
        $schedule->command('tow:process-expired-stages')
                 ->everyFiveMinutes()
                 ->withoutOverlapping()
                 ->runInBackground()
                 ->appendOutputTo(storage_path('logs/tow-processing.log'));
    })
    ->withCommands([
        \App\Console\Commands\ProcessExpiredTowStages::class,
    ])
    ->withExceptions(function (Exceptions $exceptions) {
        // Handle authentication exceptions
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }

            // تحديد الـ guard المناسب حسب الـ URL
            $path = $request->getPathInfo();
            
            if (str_starts_with($path, '/admin')) {
                return redirect()->route('admin.login');
            }
            
            if (str_starts_with($path, '/dealer')) {
                return redirect()->route('dealer.login');
            }
            
            if (str_starts_with($path, '/service-center')) {
                return redirect()->route('service-center.login');
            }
            
            if (str_starts_with($path, '/tow-service')) {
                return redirect()->route('tow-service.login');
            }
            
            // للـ insurance routes اللي بتستخدم company slug
            $segments = explode('/', trim($path, '/'));
            if (count($segments) >= 1) {
                $companySlug = $segments[0];
                
                // تحقق من وجود company بهذا الـ slug
                $company = \App\Models\InsuranceCompany::where('company_slug', $companySlug)
                    ->where('is_active', true)
                    ->first();
                
                if ($company) {
                    // إذا كان المسار يحتوي على /user فهو insurance user
                    if (isset($segments[1]) && $segments[1] === 'user') {
                        return redirect()->route('insurance.user.login', $companySlug);
                    }
                    // وإلا فهو insurance company
                    return redirect()->route('insurance.login', $companySlug);
                }
            }
            
            // افتراضي للـ admin
            return redirect()->route('admin.login');
        });
    })->create();