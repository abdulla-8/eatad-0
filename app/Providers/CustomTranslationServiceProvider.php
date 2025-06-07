<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\DatabaseTranslationLoader;
use Illuminate\Translation\Translator;

class CustomTranslationServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->registerLoader();
        $this->app->singleton('translator', function ($app) {
            $loader = $app['translation.loader'];
            $locale = $app['config']['app.locale'];
            $trans = new Translator($loader, $locale);
            $trans->setFallback($app['config']['app.fallback_locale']);
            return $trans;
        });
    }

    protected function registerLoader()
    {
        $this->app->singleton('translation.loader', function ($app) {
            return new DatabaseTranslationLoader($app['files'], $app['path.lang']);
        });
    }

    public function provides()
    {
        return ['translator', 'translation.loader'];
    }
}