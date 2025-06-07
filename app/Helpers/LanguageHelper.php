<?php

namespace App\Helpers;

use App\Models\Language;
use Illuminate\Support\Facades\Cache;

class LanguageHelper
{
    public static function getCurrentLanguage()
    {
        return Cache::remember('current_language_' . app()->getLocale(), 3600, function () {
            return Language::where('code', app()->getLocale())->first();
        });
    }

    public static function getActiveLanguages()
    {
        return Cache::remember('active_languages', 3600, function () {
            return Language::active()->get();
        });
    }

    public static function getCurrentLanguageName()
    {
        $currentLang = self::getCurrentLanguage();
        return $currentLang ? $currentLang->name : 'Unknown';
    }

    public static function isRtl()
    {
        $currentLang = self::getCurrentLanguage();
        return $currentLang ? $currentLang->isRtl() : false;
    }

    public static function getFontFamily()
    {
        $currentLang = self::getCurrentLanguage();
        
        if (!$currentLang) return "'Inter', sans-serif";
        
        return match($currentLang->code) {
            'ar' => "'Cairo', sans-serif",
            'en' => "'Inter', sans-serif",
            'es' => "'Roboto', sans-serif", 
            'fr' => "'Poppins', sans-serif",
            default => "'Inter', sans-serif"
        };
    }

    public static function getBootstrapCss()
    {
        return self::isRtl() 
            ? "https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css"
            : "https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css";
    }

    public static function getFontLink()
    {
        $currentLang = self::getCurrentLanguage();
        
        if (!$currentLang) return "https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap";
        
        return match($currentLang->code) {
            'ar' => "https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap",
            'en' => "https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap",
            'es' => "https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;600;700&display=swap",
            'fr' => "https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap",
            default => "https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap"
        };
    }
}