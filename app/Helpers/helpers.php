<?php

use App\Models\Language;
use App\Models\Translation;

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