<?php
// app/Http/Middleware/SetLanguage.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Models\Language;

class SetLanguage
{
    public function handle(Request $request, Closure $next)
    {
        try {
            // جلب الكود من الـ session أو استخدام الافتراضي
            $langCode = session('language_code');
            
            if (!$langCode) {
                $defaultLang = Language::where('is_default', true)->first();
                $langCode = $defaultLang ? $defaultLang->code : 'ar';
            }

            // تعيين اللغة
            App::setLocale($langCode);
            session(['language_code' => $langCode]);

            // حفظ معرف اللغة للاستخدام في الترجمة
            $language = Language::where('code', $langCode)->first();
            if ($language) {
                session(['current_language_id' => $language->id]);
            }

        } catch (\Exception $e) {
            // في حالة الخطأ، استخدم العربية كافتراضي
            App::setLocale('ar');
            session(['language_code' => 'ar', 'current_language_id' => 1]);
        }

        return $next($request);
    }
}