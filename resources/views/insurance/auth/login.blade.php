<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ t('auth.login') }} - {{ t('insurance.dashboard') }}</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="{{ $fontLink }}" rel="stylesheet">
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
                        gold: { 400: '#FFDD57', 500: '#FFDD57', 600: '#e6c64d' },
                        dark: { 900: '#191919', 800: '#2d2d2d', 700: '#3a3a3a' }
                    }
                }
            }
        }
    </script>
</head>
<body class="min-h-screen bg-gradient-to-br from-dark-900 via-dark-800 to-dark-900 {{ $isRtl ? 'font-cairo' : 'font-inter' }}">
    
    <!-- Language Switcher -->
    <div class="fixed top-8 {{ $isRtl ? 'left-8' : 'right-8' }} z-50">
        <x-language-switcher class="bg-dark-800/80 backdrop-blur-md border border-gold-500/30 text-white hover:bg-dark-700/80 hover:border-gold-500/50 transition-all duration-300 shadow-lg" />
    </div>

    <!-- Main Content -->
    <div class="min-h-screen flex items-center justify-center p-6">
        <div class="w-full max-w-lg">
            
            <!-- Logo & Brand -->
            <div class="text-center mb-8">
                <div class="relative mx-auto w-24 h-24 mb-6">
                    <div class="bg-gradient-to-br from-dark-800 to-dark-900 rounded-2xl p-4 border border-gold-500/30 shadow-xl">
                        <svg class="w-full h-full text-gold-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                </div>
                <h1 class="text-2xl font-bold bg-gradient-to-r from-gold-400 to-gold-600 bg-clip-text text-transparent mb-2">
                    {{ t('insurance.dashboard') }}
                </h1>
                <p class="text-gray-400">{{ t('auth.login') }}</p>
            </div>

            <!-- Login Card -->
            <div class="bg-dark-800/80 backdrop-blur-xl rounded-2xl p-8 border border-gold-500/20 shadow-2xl">
                
                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-500/20 border border-red-500/30 rounded-lg">
                        @foreach ($errors->all() as $error)
                            <p class="text-red-300 text-sm flex items-center">
                                <svg class="w-4 h-4 {{ $isRtl ? 'ml-2' : 'mr-2' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $error }}
                            </p>
                        @endforeach
                    </div>
                @endif
                
                <form method="POST" action="{{ route('insurance.login') }}" class="space-y-6">
                    @csrf
                    
                    <!-- Phone Field -->
                    <div>
                        <label for="phone" class="block text-white text-sm font-medium mb-2">
                            {{ t('auth.phone') }}
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 {{ $isRtl ? 'right-0 pr-3' : 'left-0 pl-3' }} flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                            </div>
                            <input type="text" 
                                   id="phone" 
                                   name="phone" 
                                   value="{{ old('phone') }}"
                                   class="w-full {{ $isRtl ? 'pr-10 pl-4' : 'pl-10 pr-4' }} py-3 bg-dark-900/50 border border-gold-500/20 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-gold-500/50 focus:border-gold-500/50 transition-all"
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
                            {{ t('auth.password') }}
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 {{ $isRtl ? 'right-0 pr-3' : 'left-0 pl-3' }} flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                            <input type="password" 
                                   id="password" 
                                   name="password"
                                   class="w-full {{ $isRtl ? 'pr-10 pl-4' : 'pl-10 pr-4' }} py-3 bg-dark-900/50 border border-gold-500/20 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-gold-500/50 focus:border-gold-500/50 transition-all"
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
                               class="w-4 h-4 text-gold-500 bg-dark-900 border-gold-500/30 rounded focus:ring-gold-500/50">
                        <label for="remember" class="{{ $isRtl ? 'mr-3' : 'ml-3' }} text-gray-300 text-sm">
                            {{ t('auth.remember_me') }}
                        </label>
                    </div>
                    
                    <!-- Submit Button -->
                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-gold-500 to-gold-400 hover:from-gold-400 hover:to-gold-500 text-dark-900 font-bold py-3 px-6 rounded-lg transition-all duration-300 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-gold-500/50">
                        <span class="flex items-center justify-center">
                            <svg class="w-5 h-5 {{ $isRtl ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                            </svg>
                            {{ t('auth.login') }}
                        </span>
                    </button>
                    
                    <!-- Register Link -->
                    <div class="text-center">
                        <p class="text-gray-400 text-sm">
                            {{ t('auth.dont_have_account') }}
                            <a href="{{ route('insurance.register') }}" class="text-gold-400 hover:text-gold-300 font-medium">
                                {{ t('auth.register') }}
                            </a>
                        </p>
                    </div>
                </form>
            </div>

            <!-- Footer -->
            <div class="text-center text-gray-500 text-sm mt-6">
                <p>&copy; {{ date('Y') }} {{ t('site.name', 'Etad') }}. {{ t('site.all_rights_reserved', 'All rights reserved') }}</p>
            </div>
        </div>
    </div>
</body>
</html>