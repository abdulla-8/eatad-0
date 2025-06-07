<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Helpers\LanguageHelper;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('*', function ($view) {
            $view->with([
                'currentLanguage' => LanguageHelper::getCurrentLanguage(),
                'activeLanguages' => LanguageHelper::getActiveLanguages(),
                'isRtl' => LanguageHelper::isRtl(),
                'fontFamily' => LanguageHelper::getFontFamily(),
                'bootstrapCss' => LanguageHelper::getBootstrapCss(),
                'fontLink' => LanguageHelper::getFontLink(),
            ]);
        });
    }
}