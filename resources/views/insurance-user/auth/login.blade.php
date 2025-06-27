<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ t($company->translation_group . '.user_login', 'User Login') }} - {{ t($company->translation_group . '.company_name', $company->legal_name) }}</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="{{ $fontLink ?? 'https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap' }}" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'cairo': ['Cairo', 'sans-serif'],
                        'inter': ['Inter', 'sans-serif'],
                    },
                    colors: {
                        primary: '{{ $company->primary_color }}',
                        secondary: '{{ $company->secondary_color }}',
                        dark: { 900: '#191919', 800: '#2d2d2d', 700: '#3a3a3a' }
                    }
                }
            }
        }
    </script>
</head>
<body class="min-h-screen bg-gradient-to-br from-dark-900 via-dark-800 to-dark-900 {{ $isRtl ?? false ? 'font-cairo' : 'font-inter' }}">
    
    <!-- Language Switcher -->
    <div class="fixed top-8 {{ $isRtl ?? false ? 'left-8' : 'right-8' }} z-50">
        @if(function_exists('get_active_languages'))
            <div class="relative">
                <select onchange="changeLanguage(this.value)" class="bg-dark-800/80 backdrop-blur-md border border-primary/30 text-white hover:bg-dark-700/80 hover:border-primary/50 transition-all duration-300 shadow-lg rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/50">
                    @foreach(get_active_languages() as $language)
                        <option value="{{ $language->code }}" {{ app()->getLocale() == $language->code ? 'selected' : '' }}>
                            {{ $language->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        @endif
    </div>

    <!-- Main Content -->
    <div class="min-h-screen flex items-center justify-center p-6">
        <div class="w-full max-w-md">
            
            <!-- Logo & Brand -->
            <div class="text-center mb-8">
                <div class="relative mx-auto w-24 h-24 mb-6">
                    @if($company->company_logo)
                        <div class="bg-white rounded-2xl p-4 border border-primary/30 shadow-xl">
                            <img src="{{ $company->logo_url }}" alt="{{ $company->legal_name }}" class="w-full h-full object-contain">
                        </div>
                    @else
                        <div class="bg-gradient-to-br from-dark-800 to-dark-900 rounded-2xl p-4 border border-primary/30 shadow-xl">
                            <svg class="w-full h-full text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                    @endif
                </div>
                <h1 class="text-2xl font-bold text-white mb-2">
                    {{ t($company->translation_group . '.user_login', 'User Login') }}
                </h1>
                <p class="text-gray-400">{{ t($company->translation_group . '.company_name', $company->legal_name) }}</p>
            </div>

            <!-- Login Card -->
            <div class="bg-dark-800/80 backdrop-blur-xl rounded-2xl p-8 border border-primary/20 shadow-2xl">
                
                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-500/20 border border-red-500/30 rounded-lg">
                        @foreach ($errors->all() as $error)
                            <p class="text-red-300 text-sm flex items-center">
                                <svg class="w-4 h-4 {{ $isRtl ?? false ? 'ml-2' : 'mr-2' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $error }}
                            </p>
                        @endforeach
                    </div>
                @endif
                
                <form method="POST" action="{{ route('insurance.user.login', ['companySlug' => $company->company_slug]) }}" class="space-y-6">
                    @csrf
                    
                    <!-- Phone Field -->
                    <div>
                        <label for="phone" class="block text-white text-sm font-medium mb-2">
                            {{ t($company->translation_group . '.phone', 'Phone Number') }}
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 {{ $isRtl ?? false ? 'right-0 pr-3' : 'left-0 pl-3' }} flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                            </div>
                            <input type="text" 
                                   id="phone" 
                                   name="phone" 
                                   value="{{ old('phone') }}"
                                   class="w-full {{ $isRtl ?? false ? 'pr-10 pl-4' : 'pl-10 pr-4' }} py-3 bg-dark-900/50 border border-primary/20 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary/50 transition-all"
                                   placeholder="01234567890"
                                   required 
                                   autofocus>
                        </div>
                        @error('phone')
                            <p class="text-red-300 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Password Field -->
                    <div>
                        <label for="password" class="block text-white text-sm font-medium mb-2">
                            {{ t($company->translation_group . '.password', 'Password') }}
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 {{ $isRtl ?? false ? 'right-0 pr-3' : 'left-0 pl-3' }} flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                            <input type="password" 
                                   id="password" 
                                   name="password"
                                   class="w-full {{ $isRtl ?? false ? 'pr-10 pl-4' : 'pl-10 pr-4' }} py-3 bg-dark-900/50 border border-primary/20 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary/50 transition-all"
                                   placeholder="••••••••"
                                   required>
                        </div>
                        @error('password')
                            <p class="text-red-300 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Remember Me -->
                    <div class="flex items-center">
                        <input type="checkbox" 
                               id="remember" 
                               name="remember"
                               class="w-4 h-4 text-primary bg-dark-900 border-primary/30 rounded focus:ring-primary/50"
                               style="accent-color: {{ $company->primary_color }};">
                        <label for="remember" class="{{ $isRtl ?? false ? 'mr-3' : 'ml-3' }} text-gray-300 text-sm">
                            {{ t('auth.remember_me', 'Remember Me') }}
                        </label>
                    </div>
                    
                    <!-- Submit Button -->
                    <button type="submit" 
                            class="w-full text-white font-bold py-3 px-6 rounded-lg transition-all duration-300 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-primary/50 hover:opacity-90"
                            style="background: linear-gradient(to right, {{ $company->primary_color }}, {{ $company->secondary_color }});">
                        <span class="flex items-center justify-center">
                            <svg class="w-5 h-5 {{ $isRtl ?? false ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                            </svg>
                            {{ t($company->translation_group . '.login', 'Login') }}
                        </span>
                    </button>
                    
                    <!-- Register Link -->
                    <div class="text-center">
                        <p class="text-gray-400 text-sm">
                            {{ t('auth.dont_have_account', 'Don\'t have an account?') }}
                            <a href="{{ route('insurance.user.register', ['companySlug' => $company->company_slug]) }}" class="font-medium hover:underline" style="color: {{ $company->primary_color }};">
                                {{ t($company->translation_group . '.register', 'Register') }}
                            </a>
                        </p>
                    </div>
                </form>
            </div>

            <!-- Footer -->
            <div class="text-center text-gray-500 text-sm mt-6">
                <p>&copy; {{ date('Y') }} {{ t($company->translation_group . '.company_name', $company->legal_name) }}. {{ t('site.all_rights_reserved', 'All rights reserved') }}</p>
            </div>
        </div>
    </div>

    <script>
        // Phone input formatting
        document.getElementById('phone').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 11) {
                value = value.substring(0, 11);
            }
            e.target.value = value;
        });

        // Language change function
        function changeLanguage(code) {
            window.location.href = '/language/' + code;
        }

        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const phone = document.getElementById('phone').value;
            const password = document.getElementById('password').value;

            if (!phone || !password) {
                e.preventDefault();
                alert('Please fill in all fields');
                return;
            }

            if (!/^01[0-9]{9}$/.test(phone)) {
                e.preventDefault();
                alert('Please enter a valid Egyptian phone number');
                return;
            }
        });
    </script>
</body>
</html>