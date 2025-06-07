{{ \App\Http\Controllers\TranslationHelper::t('dashboard') }}

<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
    
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ t('site_name', 'اختبار الترجمة') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="p-8 {{ app()->getLocale() == 'ar' ? 'font-arabic' : 'font-english' }}">
    
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-6">{{ t('site_name', 'اختبار الترجمة') }}</h1>
        
        <!-- Language Switcher -->
        <div class="mb-6 p-4 bg-gray-100 rounded">
            <h2 class="text-xl font-semibold mb-4">{{ t('languages', 'اللغات') }}</h2>
            <div class="flex gap-4">
                @foreach($activeLanguages as $language)
                    <a href="{{ route('language.change', $language->code) }}" 
                       class="px-4 py-2 rounded {{ app()->getLocale() == $language->code ? 'bg-blue-500 text-white' : 'bg-white border' }}">
                        {{ $language->name }}
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Test Translations -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <!-- Basic Info -->
            <div class="bg-blue-50 p-6 rounded">
                <h3 class="text-lg font-semibold mb-4">معلومات أساسية</h3>
                <p><strong>اللغة الحالية:</strong> {{ $currentLanguage ? $currentLanguage->name : 'غير محدد' }}</p>
                <p><strong>كود اللغة:</strong> {{ app()->getLocale() }}</p>
                <p><strong>الاتجاه:</strong> {{ $isRtl ? 'RTL' : 'LTR' }}</p>
            </div>

            <!-- Translations Test -->
            <div class="bg-green-50 p-6 rounded">
                <h3 class="text-lg font-semibold mb-4">اختبار الترجمات</h3>
                <ul class="space-y-2">
                    <li><strong>{{ t('dashboard') }}:</strong> Dashboard</li>
                    <li><strong>{{ t('welcome') }}:</strong> Welcome</li>
                    <li><strong>{{ t('login') }}:</strong> Login</li>
                    <li><strong>{{ t('logout') }}:</strong> Logout</li>
                    <li><strong>{{ t('languages') }}:</strong> Languages</li>
                    <li><strong>{{ t('translations') }}:</strong> Translations</li>
                </ul>
            </div>

            <!-- Actions -->
            <div class="bg-yellow-50 p-6 rounded">
                <h3 class="text-lg font-semibold mb-4">{{ t('actions', 'الإجراءات') }}</h3>
                <div class="space-y-2">
                    <a href="{{ route('admin.login') }}" class="block bg-blue-500 text-white px-4 py-2 rounded text-center">
                        {{ t('login') }}
                    </a>
                    <a href="{{ route('admin.languages.index') }}" class="block bg-green-500 text-white px-4 py-2 rounded text-center">
                        {{ t('languages') }}
                    </a>
                    <a href="{{ route('admin.translations.index') }}" class="block bg-purple-500 text-white px-4 py-2 rounded text-center">
                        {{ t('translations') }}
                    </a>
                </div>
            </div>

            <!-- Debug Info -->
            <div class="bg-red-50 p-6 rounded">
                <h3 class="text-lg font-semibold mb-4">معلومات التشخيص</h3>
                <div class="text-sm space-y-1">
                    <p><strong>Session Language ID:</strong> {{ session('current_language_id', 'غير محدد') }}</p>
                    <p><strong>Session Language Code:</strong> {{ session('language_code', 'غير محدد') }}</p>
                    <p><strong>Total Languages:</strong> {{ $activeLanguages->count() }}</p>
                    @if($currentLanguage)
                        <p><strong>Current Language ID:</strong> {{ $currentLanguage->id }}</p>
                        <p><strong>Is Default:</strong> {{ $currentLanguage->is_default ? 'نعم' : 'لا' }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

</body>
</html>