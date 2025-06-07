<?php
// app/helpers.php

use App\Models\Language;
use App\Models\Translation;

if (!function_exists('t')) {
    /**
     * ترجمة النص حسب اللغة الحالية
     */
    function t($key, $default = null)
    {
        try {
            $currentLang = session('current_language_id');
            
            if (!$currentLang) {
                $language = Language::where('code', app()->getLocale())->first();
                if (!$language) {
                    $language = Language::where('is_default', true)->first();
                }
                $currentLang = $language ? $language->id : 1;
                session(['current_language_id' => $currentLang]);
            }

            $translation = Translation::where('language_id', $currentLang)
                ->where('translation_key', $key)
                ->value('translation_value');

            return $translation ?: ($default ?: $key);
            
        } catch (\Exception $e) {
            return $default ?: $key;
        }
    }
}

if (!function_exists('get_current_language')) {
    /**
     * الحصول على اللغة الحالية
     */
    function get_current_language()
    {
        try {
            $code = app()->getLocale();
            return Language::where('code', $code)->first() ?: Language::where('is_default', true)->first();
        } catch (\Exception $e) {
            return null;
        }
    }
}

if (!function_exists('get_active_languages')) {
    /**
     * الحصول على اللغات النشطة
     */
    function get_active_languages()
    {
        try {
            return Language::where('is_active', true)->get();
        } catch (\Exception $e) {
            return collect([]);
        }
    }
}