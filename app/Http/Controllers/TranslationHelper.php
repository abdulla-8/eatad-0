<?php

namespace App\Http\Controllers;

use App\Models\Language;
use App\Models\Translation;

class TranslationHelper
{
    public static function t($key, $default = null)
    {
        try {
            $currentLang = session('current_language_id', 1);
            
            $translation = Translation::where('language_id', $currentLang)
                ->where('translation_key', $key)
                ->value('translation_value');

            return $translation ?: ($default ?: $key);
            
        } catch (\Exception $e) {
            return $default ?: $key;
        }
    }
    
    public static function getCurrentLanguage()
    {
        try {
            $code = app()->getLocale();
            return Language::where('code', $code)->first();
        } catch (\Exception $e) {
            return null;
        }
    }
    
    public static function getActiveLanguages()
    {
        try {
            return Language::where('is_active', true)->get();
        } catch (\Exception $e) {
            return collect([]);
        }
    }
}