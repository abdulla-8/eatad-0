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
    ],

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),
];