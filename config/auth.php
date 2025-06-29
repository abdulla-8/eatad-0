<?php

return [
    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
        
        'admin' => [
            'driver' => 'session',
            'provider' => 'admins',
        ],

        'parts_dealer' => [
            'driver' => 'session',
            'provider' => 'parts_dealers',
        ],

        'insurance_company' => [
            'driver' => 'session',
            'provider' => 'insurance_companies',
        ],

        'insurance_user' => [
            'driver' => 'session',
            'provider' => 'insurance_users',
        ],

        'service_center' => [
            'driver' => 'session',
            'provider' => 'service_centers',
        ],

        // New Tow Service Guards
        'tow_service_company' => [
            'driver' => 'session',
            'provider' => 'tow_service_companies',
        ],

        'tow_service_individual' => [
            'driver' => 'session',
            'provider' => 'tow_service_individuals',
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => env('AUTH_MODEL', App\Models\User::class),
        ],
        
        'admins' => [
            'driver' => 'eloquent',
            'model' => App\Models\Admin::class,
        ],

        'parts_dealers' => [
            'driver' => 'eloquent',
            'model' => App\Models\PartsDealer::class,
        ],

        'insurance_companies' => [
            'driver' => 'eloquent',
            'model' => App\Models\InsuranceCompany::class,
        ],

        'insurance_users' => [
            'driver' => 'eloquent',
            'model' => App\Models\InsuranceUser::class,
        ],

        'service_centers' => [
            'driver' => 'eloquent',
            'model' => App\Models\ServiceCenter::class,
        ],

        // New Tow Service Providers
        'tow_service_companies' => [
            'driver' => 'eloquent',
            'model' => App\Models\TowServiceCompany::class,
        ],

        'tow_service_individuals' => [
            'driver' => 'eloquent',
            'model' => App\Models\TowServiceIndividual::class,
        ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire' => 60,
            'throttle' => 60,
        ],
        
        'admins' => [
            'provider' => 'admins',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],

        'parts_dealers' => [
            'provider' => 'parts_dealers',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],

        'insurance_companies' => [
            'provider' => 'insurance_companies',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],

        'insurance_users' => [
            'provider' => 'insurance_users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],

        'service_centers' => [
            'provider' => 'service_centers',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],

        // New Tow Service Password Resets
        'tow_service_companies' => [
            'provider' => 'tow_service_companies',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],

        'tow_service_individuals' => [
            'provider' => 'tow_service_individuals',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

    // إضافة home routes لكل guard
    'home' => [
        'admin' => '/admin/dashboard',
        'parts_dealer' => '/dealer/dashboard',
        'insurance_company' => function($guard) {
            $company = session('current_company');
            return $company ? "/{$company->company_slug}/dashboard" : '/';
        },
        'insurance_user' => function($guard) {
            $company = session('current_company');
            return $company ? "/{$company->company_slug}/user/dashboard" : '/';
        },
        'service_center' => '/service-center/dashboard',
        'tow_service_company' => '/tow-service/dashboard',
        'tow_service_individual' => '/tow-service/dashboard',
    ],

    // تحديد login routes لكل guard
    'login_routes' => [
        'admin' => 'admin.login',
        'parts_dealer' => 'dealer.login',
        'insurance_company' => function($guard) {
            $company = session('current_company');
            return $company ? route('insurance.login', $company->company_slug) : route('admin.login');
        },
        'insurance_user' => function($guard) {
            $company = session('current_company');
            return $company ? route('insurance.user.login', $company->company_slug) : route('admin.login');
        },
        'service_center' => 'service-center.login',
        'tow_service_company' => 'tow-service.login',
        'tow_service_individual' => 'tow-service.login',
    ],
];