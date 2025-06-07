<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Helpers\LanguageHelper;
use App\Models\Language;
use App\Models\Translation;

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

        // Register trans_db helper function
        if (!function_exists('trans_db')) {
            function trans_db($key, $parameters = [], $locale = null)
            {
                $locale = $locale ?: app()->getLocale();
                
                // Split group.key
                if (strpos($key, '.') !== false) {
                    list($group, $translationKey) = explode('.', $key, 2);
                } else {
                    $group = 'default';
                    $translationKey = $key;
                }
                
                try {
                    $language = Language::where('code', $locale)->first();
                    
                    if (!$language) {
                        return $key;
                    }
                    
                    $translation = Translation::where('language_id', $language->id)
                        ->where('group', $group)
                        ->where('key', $translationKey)
                        ->first();
                    
                    if ($translation) {
                        return $translation->value;
                    }
                    
                    return $key;
                    
                } catch (\Exception $e) {
                    return $key;
                }
            }
        }
    }
}