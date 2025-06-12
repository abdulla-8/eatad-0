<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\TranslationController;
use App\Http\Controllers\Admin\SpecializationController;
use App\Http\Controllers\Admin\UsersManagementController;

// Parts Dealer Controllers
use App\Http\Controllers\PartsDealer\AuthController as DealerAuthController;
use App\Http\Controllers\PartsDealer\DashboardController as DealerDashboardController;

// Insurance Company Controllers
use App\Http\Controllers\Insurance\AuthController as InsuranceAuthController;
use App\Http\Controllers\Insurance\DashboardController as InsuranceDashboardController;

// Language Routes 
Route::get('/language/{code}', [LanguageController::class, 'changeLanguage'])
    ->name('language.change');

// Parts Dealer Routes
Route::prefix('dealer')->name('dealer.')->group(function () {
    // Guest Dealer Routes
    Route::middleware(['guest:parts_dealer'])->group(function () {
        Route::get('/login', [DealerAuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [DealerAuthController::class, 'login']);
        Route::get('/register', [DealerAuthController::class, 'showRegister'])->name('register');
        Route::post('/register', [DealerAuthController::class, 'register']);
    });

    // Authenticated Dealer Routes
    Route::middleware(['auth:parts_dealer'])->group(function () {
        Route::get('/dashboard', [DealerDashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout', [DealerAuthController::class, 'logout'])->name('logout');
    });
});

// Insurance Company Routes
Route::prefix('insurance')->name('insurance.')->group(function () {
    // Guest Insurance Routes
    Route::middleware(['guest:insurance_company'])->group(function () {
        Route::get('/login', [InsuranceAuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [InsuranceAuthController::class, 'login']);
        Route::get('/register', [InsuranceAuthController::class, 'showRegister'])->name('register');
        Route::post('/register', [InsuranceAuthController::class, 'register']);
    });

    // Authenticated Insurance Routes
    Route::middleware(['auth:insurance_company'])->group(function () {
        Route::get('/dashboard', [InsuranceDashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout', [InsuranceAuthController::class, 'logout'])->name('logout');
    });
});

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {

    // Guest Admin Routes
    Route::middleware(['guest:admin'])->group(function () {
        Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AuthController::class, 'login']);
    });

    // Authenticated Admin Routes
    Route::middleware(['auth:admin'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        // Language Management
        Route::prefix('languages')->name('languages.')->group(function () {
            Route::get('/', [LanguageController::class, 'index'])->name('index');
            Route::post('/{id}/toggle', [LanguageController::class, 'toggle'])->name('toggle');
            Route::post('/{id}/default', [LanguageController::class, 'setDefault'])->name('default');
        });

        // Translation Management
        Route::prefix('translations')->name('translations.')->group(function () {
            Route::get('/', [TranslationController::class, 'index'])->name('index');
            Route::post('/', [TranslationController::class, 'store'])->name('store');
            Route::put('/{translation}', [TranslationController::class, 'update'])->name('update');
            Route::delete('/{translation}', [TranslationController::class, 'destroy'])->name('destroy');
        });

        // Specialization Management
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

        // Users Management
        Route::prefix('users')->name('users.')->group(function () {
            // Parts Dealers Management
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

            // Insurance Companies Management
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

            // Users Statistics API
            Route::get('/stats', [UsersManagementController::class, 'usersStats'])->name('stats');
        });
    });
});

// Default route redirect
Route::get('/', function () {
    return view('welcome');
});


// Redirect base paths to login pages
Route::redirect('/admin', '/admin/login');
Route::redirect('/dealer', '/dealer/login'); 
Route::redirect('/insurance', '/insurance/login');