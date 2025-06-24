<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\TranslationController;
use App\Http\Controllers\Admin\SpecializationController;
use App\Http\Controllers\Admin\UsersManagementController;

use App\Http\Controllers\PartsDealer\AuthController as DealerAuthController;
use App\Http\Controllers\PartsDealer\DashboardController as DealerDashboardController;

use App\Http\Controllers\Insurance\AuthController as InsuranceAuthController;
use App\Http\Controllers\Insurance\DashboardController as InsuranceDashboardController;

Route::get('/language/{code}', [LanguageController::class, 'changeLanguage'])
    ->name('language.change');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware(['guest:admin'])->group(function () {
        Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AuthController::class, 'login']);
    });

    Route::middleware(['auth:admin'])->group(function () {
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
            });

            Route::get('/stats', [UsersManagementController::class, 'usersStats'])->name('stats');
        });
    });
});

Route::prefix('dealer')->name('dealer.')->group(function () {
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

Route::get('/', function () {
    return redirect()->route('admin.login');
})->name('home');


Route::prefix('{companyRoute}')->name('insurance.')->middleware(['company.route'])->group(function () {
    Route::middleware(['guest:insurance_company'])->group(function () {
        Route::get('/login', [InsuranceAuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [InsuranceAuthController::class, 'login']);
        Route::get('/register', [InsuranceAuthController::class, 'showRegister'])->name('register');
        Route::post('/register', [InsuranceAuthController::class, 'register']);
    });

    Route::middleware(['auth:insurance_company'])->group(function () {
        Route::get('/dashboard', [InsuranceDashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout', [InsuranceAuthController::class, 'logout'])->name('logout');
    });
});