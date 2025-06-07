<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\TranslationController;

// Language Routes 
Route::get('/language/{code}', [LanguageController::class, 'changeLanguage'])
    ->name('language.change');

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
    });
});

// Default route redirect to admin
Route::get('/', function () {
    return redirect()->route('admin.login');
});
