<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Translations</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="p-8 {{ app()->getLocale() == 'ar' ? 'font-cairo' : 'font-inter' }}">
    
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-6">Translation Test Page</h1>
        
        <div class="mb-6">
            <h2 class="text-xl font-semibold mb-4">Language Switcher</h2>
            <div class="flex gap-4">
                @foreach($activeLanguages as $language)
                    <a href="{{ route('language.change', $language->code) }}" 
                       class="px-4 py-2 rounded {{ app()->getLocale() == $language->code ? 'bg-blue-500 text-white' : 'bg-gray-200' }}">
                        {{ $language->name }}
                    </a>
                @endforeach
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-gray-50 p-6 rounded-lg">
                <h2 class="text-xl font-semibold mb-4">Current Language Info</h2>
                <p><strong>Locale:</strong> {{ app()->getLocale() }}</p>
                <p><strong>Language:</strong> {{ $currentLanguage ? $currentLanguage->name : 'Not Found' }}</p>
                <p><strong>Direction:</strong> {{ $isRtl ? 'RTL' : 'LTR' }}</p>
            </div>

            <div class="bg-blue-50 p-6 rounded-lg">
                <h2 class="text-xl font-semibold mb-4">Testing Standard Translations</h2>
                <div class="space-y-2">
                    <p><strong>Dashboard:</strong> {{ __('admin.dashboard') }}</p>
                    <p><strong>Welcome:</strong> {{ __('admin.welcome') }}</p>
                    <p><strong>Login:</strong> {{ __('admin.login') }}</p>
                    <p><strong>Logout:</strong> {{ __('admin.logout') }}</p>
                    <p><strong>Save:</strong> {{ __('admin.save') }}</p>
                </div>
            </div>

            <div class="bg-green-50 p-6 rounded-lg">
                <h2 class="text-xl font-semibold mb-4">Testing Helper Function</h2>
                <div class="space-y-2">
                    <p><strong>Dashboard:</strong> {{ trans_db('admin.dashboard') }}</p>
                    <p><strong>Welcome:</strong> {{ trans_db('admin.welcome') }}</p>
                    <p><strong>Login:</strong> {{ trans_db('admin.login') }}</p>
                    <p><strong>Logout:</strong> {{ trans_db('admin.logout') }}</p>
                    <p><strong>Save:</strong> {{ trans_db('admin.save') }}</p>
                </div>
            </div>

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
        </div>

        <div class="mt-6">
            <a href="{{ route('admin.login') }}" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">
                Go to Admin Login
            </a>
        </div>
    </div>

</body>
</html>