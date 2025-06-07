<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Translations</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="p-8 {{ app()->getLocale() == 'ar' ? 'font-cairo' : 'font-inter' }}">
    
    <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold mb-6">Translation Test Page</h1>
        
        <!-- Debug Info -->
        <div class="bg-gray-100 p-4 rounded-lg mb-6">
            <h2 class="text-xl font-semibold mb-4">Debug Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <strong>Current Locale:</strong> {{ app()->getLocale() }}
                </div>
                <div>
                    <strong>Session Locale:</strong> {{ session('locale', 'Not Set') }}
                </div>
                <div>
                    <strong>Default Locale:</strong> {{ config('app.locale') }}
                </div>
                <div>
                    <strong>Total Languages:</strong> {{ \App\Models\Language::count() }}
                </div>
                <div>
                    <strong>Active Languages:</strong> {{ \App\Models\Language::where('is_active', true)->count() }}
                </div>
                <div>
                    <strong>Total Translations:</strong> {{ \App\Models\Translation::count() }}
                </div>
                @php
                    $currentLang = \App\Models\Language::where('code', app()->getLocale())->first();
                @endphp
                <div>
                    <strong>Current Language ID:</strong> {{ $currentLang ? $currentLang->id : 'Not Found' }}
                </div>
                <div>
                    <strong>Translations for Current Lang:</strong> 
                    {{ $currentLang ? \App\Models\Translation::where('language_id', $currentLang->id)->count() : 0 }}
                </div>
            </div>
        </div>
        
        <!-- Language Switcher -->
        <div class="mb-6">
            <h2 class="text-xl font-semibold mb-4">Language Switcher</h2>
            <div class="flex gap-4">
                @foreach($activeLanguages as $language)
                    <a href="{{ route('language.change', $language->code) }}" 
                       class="px-4 py-2 rounded {{ app()->getLocale() == $language->code ? 'bg-blue-500 text-white' : 'bg-gray-200' }}">
                        {{ $language->name }} ({{ $language->code }})
                    </a>
                @endforeach
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Current Language Info -->
            <div class="bg-gray-50 p-6 rounded-lg">
                <h2 class="text-xl font-semibold mb-4">Current Language Info</h2>
                <p><strong>Locale:</strong> {{ app()->getLocale() }}</p>
                <p><strong>Language:</strong> {{ $currentLanguage ? $currentLanguage->name : 'Not Found' }}</p>
                <p><strong>Direction:</strong> {{ $isRtl ? 'RTL' : 'LTR' }}</p>
                <p><strong>Is Default:</strong> {{ $currentLanguage && $currentLanguage->is_default ? 'Yes' : 'No' }}</p>
            </div>

            <!-- Laravel Default Translations -->
            <div class="bg-blue-50 p-6 rounded-lg">
                <h2 class="text-xl font-semibold mb-4">Laravel Default __()</h2>
                <div class="space-y-2">
                    <p><strong>Dashboard:</strong> {{ __('admin.dashboard') }}</p>
                    <p><strong>Welcome:</strong> {{ __('admin.welcome') }}</p>
                    <p><strong>Login:</strong> {{ __('admin.login') }}</p>
                    <p><strong>Logout:</strong> {{ __('admin.logout') }}</p>
                    <p><strong>Save:</strong> {{ __('admin.save') }}</p>
                    <p><strong>Non-existent:</strong> {{ __('admin.non_existent_key') }}</p>
                </div>
            </div>

            <!-- Helper Function Test -->
            <div class="bg-green-50 p-6 rounded-lg">
                <h2 class="text-xl font-semibold mb-4">Helper Function trans_db()</h2>
                <div class="space-y-2">
                    <p><strong>Dashboard:</strong> {{ trans_db('admin.dashboard') }}</p>
                    <p><strong>Welcome:</strong> {{ trans_db('admin.welcome') }}</p>
                    <p><strong>Login:</strong> {{ trans_db('admin.login') }}</p>
                    <p><strong>Logout:</strong> {{ trans_db('admin.logout') }}</p>
                    <p><strong>Save:</strong> {{ trans_db('admin.save') }}</p>
                    <p><strong>Non-existent:</strong> {{ trans_db('admin.non_existent_key') }}</p>
                </div>
            </div>

            <!-- Direct Database Query -->
            <div class="bg-red-50 p-6 rounded-lg">
                <h2 class="text-xl font-semibold mb-4">Direct Database Query</h2>
                <div class="space-y-2">
                    @php
                        $language = \App\Models\Language::where('code', app()->getLocale())->first();
                        $translations = [];
                        if ($language) {
                            $translations = \App\Models\Translation::where('language_id', $language->id)
                                ->where('group', 'admin')
                                ->whereIn('key', ['dashboard', 'welcome', 'login', 'logout', 'save'])
                                ->pluck('value', 'key')
                                ->toArray();
                        }
                    @endphp
                    
                    @if(count($translations) > 0)
                        @foreach($translations as $key => $value)
                            <p><strong>{{ ucfirst($key) }}:</strong> {{ $value }}</p>
                        @endforeach
                    @else
                        <p class="text-red-600">No translations found in database!</p>
                        <p><strong>Language ID:</strong> {{ $language ? $language->id : 'Language not found' }}</p>
                        <p><strong>Total Translations:</strong> {{ \App\Models\Translation::count() }}</p>
                    @endif
                </div>
            </div>

            <!-- Sample Translations from DB -->
            <div class="bg-yellow-50 p-6 rounded-lg">
                <h2 class="text-xl font-semibold mb-4">Sample Translations</h2>
                @php
                    $sampleTranslations = \App\Models\Translation::with('language')
                        ->limit(10)
                        ->get();
                @endphp
                <div class="space-y-1 text-sm">
                    @foreach($sampleTranslations as $trans)
                        <p><strong>{{ $trans->language->code ?? 'N/A' }}.{{ $trans->group }}.{{ $trans->key }}:</strong> {{ Str::limit($trans->value, 30) }}</p>
                    @endforeach
                </div>
            </div>

            <!-- Cache Test -->
            <div class="bg-purple-50 p-6 rounded-lg">
                <h2 class="text-xl font-semibold mb-4">Cache Test</h2>
                @php
                    $cacheKey = 'translations.' . app()->getLocale() . '.admin';
                    $cached = Cache::get($cacheKey, 'Not Found');
                @endphp
                <div class="space-y-2 text-sm">
                    <p><strong>Cache Key:</strong> {{ $cacheKey }}</p>
                    <p><strong>Cache Status:</strong> {{ is_array($cached) ? 'Found (' . count($cached) . ' items)' : 'Not Found' }}</p>
                    @if(is_array($cached))
                        <div class="mt-2">
                            <strong>Cached Keys:</strong>
                            <ul class="list-disc list-inside">
                                @foreach(array_slice(array_keys($cached), 0, 5) as $key)
                                    <li>{{ $key }}</li>
                                @endforeach
                                @if(count($cached) > 5)
                                    <li>... and {{ count($cached) - 5 }} more</li>
                                @endif
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="mt-8 flex gap-4">
            <a href="{{ route('admin.login') }}" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">
                Go to Admin Login
            </a>
            <a href="{{ url('/admin/translations') }}" class="bg-green-500 text-white px-6 py-2 rounded hover:bg-green-600">
                Manage Translations
            </a>
            <form method="POST" action="{{ url('/test-clear-cache') }}" class="inline">
                @csrf
                <button type="submit" class="bg-red-500 text-white px-6 py-2 rounded hover:bg-red-600">
                    Clear Translation Cache
                </button>
            </form>
        </div>
    </div>

</body>
</html>