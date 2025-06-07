<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('admin.login') }} - {{ __('admin.site_name') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="{{ $fontLink }}" rel="stylesheet">
    
    <!-- Tailwind CSS -->
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
                        gold: {
                            400: '#FFDD57',
                            500: '#FFDD57',
                            600: '#e6c64d',
                        },
                        dark: {
                            900: '#191919',
                            800: '#2d2d2d',
                            700: '#3a3a3a'
                        },
                        success: '#038A00',
                        danger: '#DB3B21',
                        info: '#3C8DBC'
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'glow-pulse': 'glowPulse 2s ease-in-out infinite alternate',
                        'slide-up': 'slideUp 0.6s ease-out',
                        'fade-in': 'fadeIn 0.8s ease-out',
                        'rotate-slow': 'rotateSlow 20s linear infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translatey(0px)' },
                            '50%': { transform: 'translatey(-10px)' }
                        },
                        glowPulse: {
                            '0%': { boxShadow: '0 0 20px rgba(255, 221, 87, 0.3), 0 0 40px rgba(255, 221, 87, 0.1)' },
                            '100%': { boxShadow: '0 0 30px rgba(255, 221, 87, 0.6), 0 0 60px rgba(255, 221, 87, 0.2)' }
                        },
                        slideUp: {
                            '0%': { transform: 'translateY(50px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' }
                        },
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' }
                        },
                        rotateSlow: {
                            '0%': { transform: 'rotate(0deg)' },
                            '100%': { transform: 'rotate(360deg)' }
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="min-h-screen bg-gradient-to-br from-dark-900 via-dark-800 to-dark-900 {{ app()->getLocale() == 'ar' ? 'font-cairo' : 'font-inter' }} relative">
    
    <!-- Animated Background Elements -->
    <div class="fixed inset-0 pointer-events-none">
        <!-- Main golden orb -->
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-gold-500 rounded-full opacity-10 blur-3xl animate-float"></div>
        <!-- Secondary orb -->
        <div class="absolute bottom-1/4 right-1/4 w-80 h-80 bg-gold-400 rounded-full opacity-5 blur-3xl animate-float" style="animation-delay: 3s;"></div>
        <!-- Rotating ring -->
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] border border-gold-500/10 rounded-full animate-rotate-slow"></div>
        <!-- Inner ring -->
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-[400px] h-[400px] border border-gold-500/5 rounded-full animate-rotate-slow" style="animation-direction: reverse; animation-duration: 30s;"></div>
    </div>

    <!-- Mesh Pattern -->
    <div class="fixed inset-0 opacity-5 pointer-events-none">
        <div class="w-full h-full bg-mesh-pattern"></div>
    </div>

    <!-- Language Switcher -->
    <div class="fixed top-8 {{ $isRtl ? 'left-8' : 'right-8' }} z-50 animate-fade-in">
        <x-language-switcher class="bg-dark-800/80 backdrop-blur-md border border-gold-500/30 text-white hover:bg-dark-700/80 hover:border-gold-500/50 transition-all duration-300 shadow-lg" />
    </div>

    <!-- Main Content -->
    <div class="relative z-10 min-h-screen flex items-center justify-center p-6">
        <div class="w-full max-w-lg animate-slide-up">
            
            <!-- Logo & Brand Section -->
            <div class="text-center mb-8">
                <!-- Logo Container -->
                <div class="relative mx-auto w-32 h-32 mb-6">
                    <!-- Glow Effect -->
                    <div class="absolute inset-0 bg-gold-500 rounded-3xl blur-xl opacity-30 animate-glow-pulse"></div>
                    <!-- Logo Background -->
                    <div class="relative bg-gradient-to-br from-dark-800 to-dark-900 rounded-3xl p-6 border-2 border-gold-500/30 shadow-2xl">
                        <img src="{{ asset('logo.png') }}" alt="{{ __('admin.site_name') }}" class="w-full h-full object-contain filter drop-shadow-2xl">
                    </div>
                    <!-- Floating particles -->
                    <div class="absolute -top-2 -right-2 w-3 h-3 bg-gold-500 rounded-full opacity-60 animate-float" style="animation-delay: 1s;"></div>
                    <div class="absolute -bottom-2 -left-2 w-2 h-2 bg-gold-400 rounded-full opacity-40 animate-float" style="animation-delay: 2s;"></div>
                </div>
                
                <!-- Brand Text -->
                <div class="space-y-2">
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-gold-400 via-gold-500 to-gold-600 bg-clip-text text-transparent">
                        {{ __('admin.site_name') }}
                    </h1>
                    <p class="text-gray-400 text-lg">{{ __('admin.login') }}</p>
                </div>
            </div>

            <!-- Login Card -->
            <div class="relative mb-6">
                <!-- Card Glow -->
                <div class="absolute inset-0 bg-gradient-to-r from-gold-500/20 to-gold-400/20 rounded-3xl blur-xl"></div>
                
                <!-- Main Card -->
                <div class="relative bg-dark-800/80 backdrop-blur-xl rounded-3xl p-8 border border-gold-500/20 shadow-2xl">
                    
                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-danger/20 border border-danger/30 rounded-2xl backdrop-blur-sm">
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
                    
                    <form method="POST" action="{{ route('admin.login') }}" class="space-y-6">
                        @csrf
                        
                        <!-- Email Field -->
                        <div class="space-y-3">
                            <label for="email" class="block text-white text-sm font-semibold tracking-wide">
                                {{ __('admin.email') }}
                            </label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 {{ $isRtl ? 'right-0 pr-4' : 'left-0 pl-4' }} flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400 group-focus-within:text-gold-500 transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                    </svg>
                                </div>
                                <input type="email" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email') }}"
                                       class="w-full {{ $isRtl ? 'pr-12 pl-4' : 'pl-12 pr-4' }} py-4 bg-dark-900/50 border border-gold-500/20 rounded-2xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-gold-500/50 focus:border-gold-500/50 transition-all duration-300 hover:border-gold-500/30 @error('email') border-danger/50 @enderror"
                                       placeholder="admin@example.com"
                                       required 
                                       autofocus>
                            </div>
                            @error('email')
                                <p class="text-red-300 text-xs flex items-center">
                                    <svg class="w-3 h-3 {{ $isRtl ? 'ml-1' : 'mr-1' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                        
                        <!-- Password Field -->
                        <div class="space-y-3">
                            <label for="password" class="block text-white text-sm font-semibold tracking-wide">
                                {{ __('admin.password') }}
                            </label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 {{ $isRtl ? 'right-0 pr-4' : 'left-0 pl-4' }} flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400 group-focus-within:text-gold-500 transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                </div>
                                <input type="password" 
                                       id="password" 
                                       name="password"
                                       class="w-full {{ $isRtl ? 'pr-12 pl-4' : 'pl-12 pr-4' }} py-4 bg-dark-900/50 border border-gold-500/20 rounded-2xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-gold-500/50 focus:border-gold-500/50 transition-all duration-300 hover:border-gold-500/30 @error('password') border-danger/50 @enderror"
                                       placeholder="••••••••"
                                       required>
                            </div>
                            @error('password')
                                <p class="text-red-300 text-xs flex items-center">
                                    <svg class="w-3 h-3 {{ $isRtl ? 'ml-1' : 'mr-1' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                        
                        <!-- Remember Me -->
                        <div class="flex items-center justify-between">
                            <label class="flex items-center cursor-pointer group">
                                <input type="checkbox" 
                                       id="remember" 
                                       name="remember"
                                       class="w-4 h-4 text-gold-500 bg-dark-900 border-gold-500/30 rounded focus:ring-gold-500/50 focus:ring-2 transition-all duration-300">
                                <span class="{{ $isRtl ? 'mr-3' : 'ml-3' }} text-gray-300 text-sm group-hover:text-white transition-colors duration-300">
                                    {{ __('admin.remember_me') }}
                                </span>
                            </label>
                        </div>
                        
                        <!-- Submit Button -->
                        <button type="submit" 
                                class="w-full relative overflow-hidden bg-gradient-to-r from-gold-500 to-gold-400 hover:from-gold-400 hover:to-gold-500 text-dark-900 font-bold py-4 px-6 rounded-2xl transition-all duration-500 transform hover:scale-[1.02] hover:shadow-2xl focus:outline-none focus:ring-2 focus:ring-gold-500/50 focus:ring-offset-2 focus:ring-offset-dark-900 group">
                            <span class="relative z-10 flex items-center justify-center">
                                <svg class="w-5 h-5 {{ $isRtl ? 'ml-2' : 'mr-2' }} group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                </svg>
                                {{ __('admin.login') }}
                            </span>
                            <!-- Button glow effect -->
                            <div class="absolute inset-0 bg-gradient-to-r from-gold-600 to-gold-500 opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-2xl"></div>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center text-gray-500 text-sm">
                <p>&copy; {{ date('Y') }} {{ __('admin.site_name') }}. جميع الحقوق محفوظة</p>
            </div>
        </div>
    </div>

    <style>
        .bg-mesh-pattern {
            background-image: 
                radial-gradient(circle at 1px 1px, rgba(255,221,87,0.15) 1px, transparent 0);
            background-size: 20px 20px;
        }
    </style>
</body>
</html>