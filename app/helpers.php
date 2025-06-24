<?php

use App\Models\Language;
use App\Models\Translation;

if (!function_exists('t')) {
    function t($key, $default = null, $useCompanyGroup = false)
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

            $translationKey = $key;
            
            if ($useCompanyGroup) {
                $company = session('current_company');
                if ($company && $company->translation_group) {
                    $companyKey = $company->translation_group . '.' . $key;
                    
                    $companyTranslation = Translation::where('language_id', $currentLang)
                        ->where('translation_key', $companyKey)
                        ->value('translation_value');
                    
                    if ($companyTranslation) {
                        return $companyTranslation;
                    }
                }
            }

            $translation = Translation::where('language_id', $currentLang)
                ->where('translation_key', $translationKey)
                ->value('translation_value');

            return $translation ?: ($default ?: $key);
            
        } catch (\Exception $e) {
            return $default ?: $key;
        }
    }
}

if (!function_exists('get_current_language')) {
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
    function get_active_languages()
    {
        try {
            return Language::where('is_active', true)->get();
        } catch (\Exception $e) {
            return collect([]);
        }
    }
}

if (!function_exists('get_company_translation')) {
    function get_company_translation($key, $default = null)
    {
        $company = session('current_company');
        if (!$company || !$company->translation_group) {
            return t($key, $default);
        }

        $companyKey = $company->translation_group . '.' . $key;
        return t($companyKey, $default);
    }
}