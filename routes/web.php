<?php

use Illuminate\Support\Facades\Route;

// Admin Controllers
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\TranslationController;
use App\Http\Controllers\Admin\SpecializationController;
use App\Http\Controllers\Admin\UsersManagementController;
use App\Http\Controllers\Admin\ServiceCenterManagementController;
use App\Http\Controllers\Admin\IndustrialAreaController;
use App\Http\Controllers\Admin\ServiceSpecializationController;
use App\Http\Controllers\Admin\InsuranceUsersManagementController;
use App\Http\Controllers\Admin\TowServiceManagementController;

// Parts Dealer Controllers
use App\Http\Controllers\PartsDealer\AuthController as DealerAuthController;
use App\Http\Controllers\PartsDealer\DashboardController as DealerDashboardController;

// Insurance Company Controllers
use App\Http\Controllers\Insurance\AuthController as InsuranceAuthController;
use App\Http\Controllers\Insurance\DashboardController as InsuranceDashboardController;

// Service Center Controllers
use App\Http\Controllers\ServiceCenter\AuthController as ServiceCenterAuthController;
use App\Http\Controllers\ServiceCenter\DashboardController as ServiceCenterDashboardController;

// Insurance User Controllers
use App\Http\Controllers\InsuranceUser\AuthController as InsuranceUserAuthController;
use App\Http\Controllers\InsuranceUser\DashboardController as InsuranceUserDashboardController;

// Tow Service Controllers
use App\Http\Controllers\TowService\AuthController as TowServiceAuthController;
use App\Http\Controllers\TowService\DashboardController as TowServiceDashboardController;

// Language route
Route::get('/language/{code}', [LanguageController::class, 'changeLanguage'])
    ->name('language.change');

// Root redirect
Route::get('/', function () {
    return redirect()->route('admin.login');
})->name('home');

// Fallback login route
Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login');

// ==== ADMIN ROUTES ====
Route::prefix('admin')->name('admin.')->group(function () {
    // Redirect /admin to /admin/login if not authenticated
    Route::get('/', function () {
        if (auth('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('admin.login');
    });

    Route::middleware(['guest:admin'])->group(function () {
        Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AuthController::class, 'login']);
    });

    Route::middleware(['admin.auth'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        Route::prefix('languages')->name('languages.')->group(function () {
            Route::get('/', [LanguageController::class, 'index'])->name('index');
            Route::post('/{id}/toggle', [LanguageController::class, 'toggle'])->name('toggle');
            Route::post('/{id}/default', [LanguageController::class, 'setDefault'])->name('default');
        });

        Route::prefix('translations')->name('translations.')->group(function () {
            Route::get('/', [TranslationController::class, 'index'])->name('index');
            Route::post('/', [TranslationController::class, 'store'])->name('store');
            Route::put('/{translation}', [TranslationController::class, 'update'])->name('update');
            Route::delete('/{translation}', [TranslationController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('specializations')->name('specializations.')->group(function () {
            Route::get('/', [SpecializationController::class, 'index'])->name('index');
            Route::get('/create', [SpecializationController::class, 'create'])->name('create');
            Route::post('/', [SpecializationController::class, 'store'])->name('store');
            Route::get('/{specialization}/edit', [SpecializationController::class, 'edit'])->name('edit');
            Route::put('/{specialization}', [SpecializationController::class, 'update'])->name('update');
            Route::delete('/{specialization}', [SpecializationController::class, 'destroy'])->name('destroy');
            Route::post('/{specialization}/toggle', [SpecializationController::class, 'toggle'])->name('toggle');
            Route::post('/update-order', [SpecializationController::class, 'updateOrder'])->name('updateOrder');
        });

        Route::prefix('industrial-areas')->name('industrial-areas.')->group(function () {
            Route::get('/', [IndustrialAreaController::class, 'index'])->name('index');
            Route::get('/create', [IndustrialAreaController::class, 'create'])->name('create');
            Route::post('/', [IndustrialAreaController::class, 'store'])->name('store');
            Route::get('/{industrialArea}/edit', [IndustrialAreaController::class, 'edit'])->name('edit');
            Route::put('/{industrialArea}', [IndustrialAreaController::class, 'update'])->name('update');
            Route::delete('/{industrialArea}', [IndustrialAreaController::class, 'destroy'])->name('destroy');
            Route::post('/{industrialArea}/toggle', [IndustrialAreaController::class, 'toggle'])->name('toggle');
            Route::post('/update-order', [IndustrialAreaController::class, 'updateOrder'])->name('updateOrder');
        });

        Route::prefix('service-specializations')->name('service-specializations.')->group(function () {
            Route::get('/', [ServiceSpecializationController::class, 'index'])->name('index');
            Route::get('/create', [ServiceSpecializationController::class, 'create'])->name('create');
            Route::post('/', [ServiceSpecializationController::class, 'store'])->name('store');
            Route::get('/{serviceSpecialization}/edit', [ServiceSpecializationController::class, 'edit'])->name('edit');
            Route::put('/{serviceSpecialization}', [ServiceSpecializationController::class, 'update'])->name('update');
            Route::delete('/{serviceSpecialization}', [ServiceSpecializationController::class, 'destroy'])->name('destroy');
            Route::post('/{serviceSpecialization}/toggle', [ServiceSpecializationController::class, 'toggle'])->name('toggle');
            Route::post('/update-order', [ServiceSpecializationController::class, 'updateOrder'])->name('updateOrder');
        });

        Route::prefix('users')->name('users.')->group(function () {
            Route::prefix('parts-dealers')->name('parts-dealers.')->group(function () {
                Route::get('/', [UsersManagementController::class, 'partsDealersIndex'])->name('index');
                Route::get('/create', [UsersManagementController::class, 'partsDealersCreate'])->name('create');
                Route::post('/', [UsersManagementController::class, 'partsDealersStore'])->name('store');
                Route::get('/{partsDealer}/edit', [UsersManagementController::class, 'partsDealersEdit'])->name('edit');
                Route::put('/{partsDealer}', [UsersManagementController::class, 'partsDealersUpdate'])->name('update');
                Route::delete('/{partsDealer}', [UsersManagementController::class, 'partsDealersDestroy'])->name('destroy');
                Route::post('/{partsDealer}/toggle', [UsersManagementController::class, 'partsDealersToggle'])->name('toggle');
                Route::post('/{partsDealer}/approve', [UsersManagementController::class, 'partsDealersApprove'])->name('approve');
            });

            Route::prefix('insurance-companies')->name('insurance-companies.')->group(function () {
                Route::get('/', [UsersManagementController::class, 'insuranceCompaniesIndex'])->name('index');
                Route::get('/create', [UsersManagementController::class, 'insuranceCompaniesCreate'])->name('create');
                Route::post('/', [UsersManagementController::class, 'insuranceCompaniesStore'])->name('store');
                Route::get('/{insuranceCompany}/edit', [UsersManagementController::class, 'insuranceCompaniesEdit'])->name('edit');
                Route::put('/{insuranceCompany}', [UsersManagementController::class, 'insuranceCompaniesUpdate'])->name('update');
                Route::delete('/{insuranceCompany}', [UsersManagementController::class, 'insuranceCompaniesDestroy'])->name('destroy');
                Route::post('/{insuranceCompany}/toggle', [UsersManagementController::class, 'insuranceCompaniesToggle'])->name('toggle');
                Route::post('/{insuranceCompany}/approve', [UsersManagementController::class, 'insuranceCompaniesApprove'])->name('approve');

                Route::prefix('{insuranceCompany}/users')->name('insurance-users.')->group(function () {
                    Route::get('/', [InsuranceUsersManagementController::class, 'index'])->name('index');
                    Route::post('/{user}/toggle', [InsuranceUsersManagementController::class, 'toggle'])->name('toggle');
                    Route::delete('/{user}', [InsuranceUsersManagementController::class, 'destroy'])->name('destroy');
                    Route::post('/{user}/reset-password', [InsuranceUsersManagementController::class, 'resetPassword'])->name('reset-password');
                });
            });

            Route::get('/insurance-users-stats', [InsuranceUsersManagementController::class, 'stats'])->name('insurance-users-stats');

            Route::prefix('service-centers')->name('service-centers.')->group(function () {
                Route::get('/', [ServiceCenterManagementController::class, 'serviceCentersIndex'])->name('index');
                Route::get('/create', [ServiceCenterManagementController::class, 'serviceCentersCreate'])->name('create');
                Route::post('/', [ServiceCenterManagementController::class, 'serviceCentersStore'])->name('store');
                Route::get('/{serviceCenter}/edit', [ServiceCenterManagementController::class, 'serviceCentersEdit'])->name('edit');
                Route::put('/{serviceCenter}', [ServiceCenterManagementController::class, 'serviceCentersUpdate'])->name('update');
                Route::delete('/{serviceCenter}', [ServiceCenterManagementController::class, 'serviceCentersDestroy'])->name('destroy');
                Route::post('/{serviceCenter}/toggle', [ServiceCenterManagementController::class, 'serviceCentersToggle'])->name('toggle');
                Route::post('/{serviceCenter}/approve', [ServiceCenterManagementController::class, 'serviceCentersApprove'])->name('approve');
            });

            // Tow Service Companies
            Route::prefix('tow-service-companies')->name('tow-service-companies.')->group(function () {
                Route::get('/', [TowServiceManagementController::class, 'companiesIndex'])->name('index');
                Route::get('/create', [TowServiceManagementController::class, 'companiesCreate'])->name('create');
                Route::post('/', [TowServiceManagementController::class, 'companiesStore'])->name('store');
                Route::get('/{towServiceCompany}/edit', [TowServiceManagementController::class, 'companiesEdit'])->name('edit');
                Route::put('/{towServiceCompany}', [TowServiceManagementController::class, 'companiesUpdate'])->name('update');
                Route::delete('/{towServiceCompany}', [TowServiceManagementController::class, 'companiesDestroy'])->name('destroy');
                Route::post('/{towServiceCompany}/toggle', [TowServiceManagementController::class, 'companiesToggle'])->name('toggle');
                Route::post('/{towServiceCompany}/approve', [TowServiceManagementController::class, 'companiesApprove'])->name('approve');
            });

            // Tow Service Individuals
            Route::prefix('tow-service-individuals')->name('tow-service-individuals.')->group(function () {
                Route::get('/', [TowServiceManagementController::class, 'individualsIndex'])->name('index');
                Route::get('/create', [TowServiceManagementController::class, 'individualsCreate'])->name('create');
                Route::post('/', [TowServiceManagementController::class, 'individualsStore'])->name('store');
                Route::get('/{towServiceIndividual}/edit', [TowServiceManagementController::class, 'individualsEdit'])->name('edit');
                Route::put('/{towServiceIndividual}', [TowServiceManagementController::class, 'individualsUpdate'])->name('update');
                Route::delete('/{towServiceIndividual}', [TowServiceManagementController::class, 'individualsDestroy'])->name('destroy');
                Route::post('/{towServiceIndividual}/toggle', [TowServiceManagementController::class, 'individualsToggle'])->name('toggle');
                Route::post('/{towServiceIndividual}/approve', [TowServiceManagementController::class, 'individualsApprove'])->name('approve');
            });

            Route::get('/stats', [UsersManagementController::class, 'usersStats'])->name('stats');
            Route::get('/service-centers-stats', [ServiceCenterManagementController::class, 'serviceCentersStats'])->name('service-centers-stats');
            Route::get('/tow-service-stats', [TowServiceManagementController::class, 'towServiceStats'])->name('tow-service-stats');
        });
    });
});

// ==== PARTS DEALER ROUTES ====
Route::prefix('dealer')->name('dealer.')->group(function () {
    // Redirect /dealer to /dealer/login if not authenticated
    Route::get('/', function () {
        if (auth('parts_dealer')->check()) {
            return redirect()->route('dealer.dashboard');
        }
        return redirect()->route('dealer.login');
    });

    Route::middleware(['guest:parts_dealer'])->group(function () {
        Route::get('/login', [DealerAuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [DealerAuthController::class, 'login']);
        Route::get('/register', [DealerAuthController::class, 'showRegister'])->name('register');
        Route::post('/register', [DealerAuthController::class, 'register']);
    });

    Route::middleware(['auth:parts_dealer'])->group(function () {
        Route::get('/dashboard', [DealerDashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout', [DealerAuthController::class, 'logout'])->name('logout');
    });
});

// ==== SERVICE CENTER ROUTES ====
Route::prefix('service-center')->name('service-center.')->group(function () {
    // Redirect /service-center to /service-center/login if not authenticated
    Route::get('/', function () {
        if (auth('service_center')->check()) {
            return redirect()->route('service-center.dashboard');
        }
        return redirect()->route('service-center.login');
    });

    Route::middleware(['guest:service_center'])->group(function () {
        Route::get('/login', [ServiceCenterAuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [ServiceCenterAuthController::class, 'login']);
        Route::get('/register', [ServiceCenterAuthController::class, 'showRegister'])->name('register');
        Route::post('/register', [ServiceCenterAuthController::class, 'register']);
    });

    Route::middleware(['auth:service_center'])->group(function () {
        Route::get('/dashboard', [ServiceCenterDashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout', [ServiceCenterAuthController::class, 'logout'])->name('logout');
    });
});

// ==== TOW SERVICE ROUTES ====
Route::prefix('tow-service')->name('tow-service.')->group(function () {
    // Redirect /tow-service to /tow-service/login if not authenticated
    Route::get('/', function () {
        if (auth('tow_service_company')->check() || auth('tow_service_individual')->check()) {
            return redirect()->route('tow-service.dashboard');
        }
        return redirect()->route('tow-service.login');
    });

    Route::middleware(['guest:tow_service_company', 'guest:tow_service_individual'])->group(function () {
        Route::get('/login', [TowServiceAuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [TowServiceAuthController::class, 'login']);
        Route::get('/register', [TowServiceAuthController::class, 'showRegister'])->name('register');
        Route::post('/register', [TowServiceAuthController::class, 'register']);
    });

    Route::group(['middleware' => function ($request, $next) {
        if (auth('tow_service_company')->check() || auth('tow_service_individual')->check()) {
            return $next($request);
        }
        return redirect()->route('tow-service.login');
    }], function () {
        Route::get('/dashboard', [TowServiceDashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout', [TowServiceAuthController::class, 'logout'])->name('logout');
    });
});

// ==== INSURANCE COMPANY ROUTES ====
Route::prefix('{companyRoute}')->name('insurance.')->middleware(['company.route'])->group(function () {
    // Redirect /{companyRoute} to /{companyRoute}/login if not authenticated
    Route::get('/', function () {
        if (auth('insurance_company')->check()) {
            return redirect()->route('insurance.dashboard', request()->route('companyRoute'));
        }
        return redirect()->route('insurance.login', request()->route('companyRoute'));
    });

    Route::middleware(['guest:insurance_company'])->group(function () {
        Route::get('/login', [InsuranceAuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [InsuranceAuthController::class, 'login']);
        Route::get('/register', [InsuranceAuthController::class, 'showRegister'])->name('register');
        Route::post('/register', [InsuranceAuthController::class, 'register']);
    });

    Route::middleware(['auth:insurance_company'])->group(function () {
        Route::get('/dashboard', [InsuranceDashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout', [InsuranceAuthController::class, 'logout'])->name('logout');

        // Claims management for insurance companies
        Route::prefix('claims')->name('claims.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Insurance\ClaimsController::class, 'index'])->name('index');
            Route::get('/{claim}', [\App\Http\Controllers\Insurance\ClaimsController::class, 'show'])->name('show');
            Route::post('/{claim}/approve', [\App\Http\Controllers\Insurance\ClaimsController::class, 'approve'])->name('approve');
            Route::post('/{claim}/reject', [\App\Http\Controllers\Insurance\ClaimsController::class, 'reject'])->name('reject');
            Route::get('/api/service-centers', [\App\Http\Controllers\Insurance\ClaimsController::class, 'getServiceCenters'])->name('service-centers');
        });
    });
});

// ==== INSURANCE USER ROUTES ====
Route::prefix('{companySlug}/user')->name('insurance.user.')->middleware(['company.route'])->group(function () {
    // Redirect /{companySlug}/user to /{companySlug}/user/login if not authenticated
    Route::get('/', function () {
        if (auth('insurance_user')->check()) {
            return redirect()->route('insurance.user.dashboard', request()->route('companySlug'));
        }
        return redirect()->route('insurance.user.login', request()->route('companySlug'));
    });

    Route::middleware(['guest:insurance_user'])->group(function () {
        Route::get('/login', [InsuranceUserAuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [InsuranceUserAuthController::class, 'login']);
        Route::get('/register', [InsuranceUserAuthController::class, 'showRegister'])->name('register');
        Route::post('/register', [InsuranceUserAuthController::class, 'register']);
    });

    Route::middleware(['auth:insurance_user'])->group(function () {
        Route::get('/dashboard', [InsuranceUserDashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout', [InsuranceUserAuthController::class, 'logout'])->name('logout');
        // Claims management for insurance users
        Route::prefix('claims')->name('claims.')->group(function () {
            Route::get('/', [\App\Http\Controllers\InsuranceUser\ClaimsController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\InsuranceUser\ClaimsController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\InsuranceUser\ClaimsController::class, 'store'])->name('store');
            Route::get('/{claim}', [\App\Http\Controllers\InsuranceUser\ClaimsController::class, 'show'])->name('show');
            Route::get('/{claim}/edit', [\App\Http\Controllers\InsuranceUser\ClaimsController::class, 'edit'])->name('edit');
            Route::put('/{claim}', [\App\Http\Controllers\InsuranceUser\ClaimsController::class, 'update'])->name('update');
            Route::post('/{claim}/tow-service', [\App\Http\Controllers\InsuranceUser\ClaimsController::class, 'updateTowService'])->name('tow-service');
            Route::delete('/{claim}/attachments/{attachment}', [\App\Http\Controllers\InsuranceUser\ClaimsController::class, 'deleteAttachment'])->name('attachments.delete');
        });
    });
});