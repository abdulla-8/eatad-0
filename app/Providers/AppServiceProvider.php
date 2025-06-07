<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\TranslationHelper;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $view->with([
                'currentLanguage' => TranslationHelper::getCurrentLanguage(),
                'activeLanguages' => TranslationHelper::getActiveLanguages(),
                'isRtl' => false,
            ]);
        });
    }
}