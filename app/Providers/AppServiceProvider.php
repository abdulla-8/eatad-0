<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\TranslationHelper;
use Illuminate\Database\Eloquent\Relations\Relation;
class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $currentLanguage = TranslationHelper::getCurrentLanguage();
            $isRtl = $currentLanguage && $currentLanguage->direction === 'rtl';
            
            // تحديد الخط المناسب حسب الاتجاه
            $fontLink = $isRtl 
                ? 'https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap'
                : 'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap';
            
            $view->with([
                'currentLanguage' => $currentLanguage,
                'activeLanguages' => TranslationHelper::getActiveLanguages(),
                'isRtl' => $isRtl, 
                'fontLink' => $fontLink,
            ]);

            
        // إضافة Morph Map للـ Polymorphic Relationships
        Relation::morphMap([
            'insurance_company' => \App\Models\InsuranceCompany::class,
            'service_center' => \App\Models\ServiceCenter::class,
        ]);
    
        });
        
    }
}