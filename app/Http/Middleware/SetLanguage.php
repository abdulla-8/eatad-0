<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use App\Models\Language;

class SetLanguage
{
    public function handle(Request $request, Closure $next)
    {
        $locale = Session::get('locale');
        
        if (!$locale) {
            $defaultLang = Language::default()->first();
            $locale = $defaultLang ? $defaultLang->code : 'ar';
            Session::put('locale', $locale);
        }

        App::setLocale($locale);
        
        return $next($request);
    }
}