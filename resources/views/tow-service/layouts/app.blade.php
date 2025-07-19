<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', t('tow.dashboard')) - {{ t('tow.dashboard') }}</title>

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

<body class="bg-gray-100 {{ app()->getLocale() == 'ar' ? 'font-cairo' : 'font-inter' }}">
    <div class="min-h-screen">
        <!-- Mobile Header -->
        <header class="lg:hidden bg-white shadow-sm border-b border-gray-200 px-4 py-3 relative z-50">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3 {{ $isRtl ? 'space-x-reverse' : '' }}">
                    <button id="mobileMenuBtn" class="p-2 rounded-lg hover:bg-gray-100 transition-colors">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    <div class="flex items-center space-x-2 {{ $isRtl ? 'space-x-reverse' : '' }}">
                        <svg class="w-8 h-8 text-gold-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                        </svg>
                        <h1 class="font-bold text-lg">{{ t('tow.dashboard') }}</h1>
                    </div>
                </div>
                <x-language-switcher class="bg-gray-50 border border-gray-200 text-gray-700 hover:bg-gray-100" />
            </div>
        </header>

        <div class="flex lg:flex-row">
            <!-- Sidebar -->
            <aside id="sidebar" class="fixed lg:static inset-y-0 {{ $isRtl ? 'right-0' : 'left-0' }} z-40 w-72 bg-dark-900 text-white transform {{ $isRtl ? 'translate-x-full' : '-translate-x-full' }} lg:translate-x-0 transition-transform duration-300 ease-in-out lg:flex lg:flex-col">
                
                <!-- Logo Section -->
                <div class="p-6 border-b border-gray-700">
                    <div class="flex items-center space-x-3 {{ $isRtl ? 'space-x-reverse' : '' }}">
                        <div class="w-12 h-12 bg-white rounded-xl p-2 shadow-lg">
                            <svg class="w-full h-full text-gold-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-lg font-bold">{{ t('tow.dashboard') }}</h1>
                            <p class="text-gold-400 text-sm">{{ isset($userType) && $userType == 'company' ? t('tow.company') : t('tow.individual') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Navigation -->
                <nav class="mt-6 px-4">
                    <ul class="space-y-2">
                        <li>
                            <a href="{{ route('tow-service.dashboard') }}" class="flex items-center px-4 py-3 rounded-lg transition-colors duration-200 {{ request()->routeIs('tow-service.dashboard') ? 'bg-gold-500 text-dark-900' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                                <svg class="w-5 h-5 {{ $isRtl ? 'ml-3' : 'mr-3' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                </svg>
                                {{ t('tow.dashboard') }}
                            </a>
                        </li>

                                                @if(auth('tow_service_company')->check())
                            <li>
                                <a href="{{ route('tow-service.company.offers.index') }}" class="flex items-center px-4 py-3 rounded-lg transition-colors duration-200 {{ request()->routeIs('tow-service.company.offers.*') ? 'bg-gold-500 text-dark-900' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                                    <svg class="w-5 h-5 {{ $isRtl ? 'ml-3' : 'mr-3' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                    </svg>
                                    {{ t('tow.tow_offers') }}
                                </a>
                            </li>
                        @endif

                        @if(auth('tow_service_individual')->check())
                            <li>
                                <a href="{{ route('tow-service.individual.offers.index') }}" class="flex items-center px-4 py-3 rounded-lg transition-colors duration-200 {{ request()->routeIs('tow-service.individual.offers.*') ? 'bg-gold-500 text-dark-900' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                                    <svg class="w-5 h-5 {{ $isRtl ? 'ml-3' : 'mr-3' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                    </svg>
                                    {{ t('tow.tow_offers') }}
                                </a>
                            </li>
                        @endif
                        

                  
<!-- رابط البروفايل لشركات خدمة السحب -->
@if(auth('tow_service_company')->check())
    <li>
        <a href="{{ route('tow-service.profile.show') }}" class="flex items-center px-4 py-3 rounded-lg transition-colors duration-200 {{ request()->routeIs('tow-service.profile.*') ? 'bg-gold-500 text-dark-900' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
            <svg class="w-5 h-5 {{ $isRtl ? 'ml-3' : 'mr-3' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            {{ t('tow.my_profile') }}
        </a>
    </li>
@endif

<!-- رابط البروفايل لأفراد خدمة السحب -->
@if(auth('tow_service_individual')->check())
    <li>
        <a href="{{ route('tow-service.profile.show') }}" class="flex items-center px-4 py-3 rounded-lg transition-colors duration-200 {{ request()->routeIs('tow-service.profile.*') ? 'bg-gold-500 text-dark-900' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
            <svg class="w-5 h-5 {{ $isRtl ? 'ml-3' : 'mr-3' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            {{ t('tow.my_profile') }}
        </a>
    </li>
@endif

                        <li>
                            <a href="#" class="flex items-center px-4 py-3 rounded-lg transition-colors duration-200 text-gray-300 hover:bg-gray-800 hover:text-white opacity-60 cursor-not-allowed">
                                <svg class="w-5 h-5 {{ $isRtl ? 'ml-3' : 'mr-3' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                {{ t('tow.requests') }}
                                <span class="text-xs bg-gray-700 px-2 py-1 rounded {{ $isRtl ? 'mr-auto' : 'ml-auto' }}">{{ t('tow.soon') }}</span>
                            </a>
                        </li>
                   


                    </ul>
                </nav>

                <!-- User Section -->
                <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-700">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3 {{ $isRtl ? 'space-x-reverse' : '' }}">
                            <div class="w-10 h-10 bg-gold-500 rounded-lg flex items-center justify-center">
                                @if(isset($user))
                                    <span class="text-dark-900 font-bold">{{ substr($user->display_name, 0, 1) }}</span>
                                @endif
                            </div>
                            <div class="hidden lg:block">
                                @if(isset($user))
                                    <p class="text-white text-sm font-medium">{{ $user->display_name }}</p>
                                    <p class="text-gray-400 text-xs">{{ $userType == 'company' ? t('tow.company') : t('tow.individual') }}</p>
                                @endif
                            </div>
                        </div>
                        <form method="POST" action="{{ route('tow-service.logout') }}">
                            @csrf
                            <button type="submit" class="p-2 text-gray-400 hover:text-red-400 rounded-lg transition-colors" title="{{ t('auth.logout') }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </aside>

            <!-- Main Content -->
            <main class="flex-1 min-h-screen">
                <!-- Desktop Header -->
                <header class="hidden lg:block bg-white border-b border-gray-200 px-8 py-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">@yield('title', t('tow.dashboard'))</h2>
                            @if(isset($user))
                                <p class="text-gray-600">{{ t('auth.welcome_back') }}, {{ $user->display_name }}</p>
                            @endif
                        </div>
                        <x-language-switcher class="bg-gray-50 border border-gray-200 text-gray-700 hover:bg-gray-100" />
                    </div>
                </header>

                <!-- Content -->
                <div class="p-4 lg:p-8">
                    <!-- Messages -->
                    @if (session('success'))
                        <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 {{ $isRtl ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                {{ session('success') }}
                            </div>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 {{ $isRtl ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                {{ session('error') }}
                            </div>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Mobile Overlay -->
    <div id="mobileOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 lg:hidden hidden"></div>

    <script>
        // Mobile menu toggle
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const sidebar = document.getElementById('sidebar');
        const mobileOverlay = document.getElementById('mobileOverlay');
        const isRtl = document.documentElement.dir === 'rtl';

        function toggleMobileMenu() {
            if (sidebar.classList.contains(isRtl ? 'translate-x-full' : '-translate-x-full')) {
                sidebar.classList.remove(isRtl ? 'translate-x-full' : '-translate-x-full');
                mobileOverlay.classList.remove('hidden');
            } else {
                sidebar.classList.add(isRtl ? 'translate-x-full' : '-translate-x-full');
                mobileOverlay.classList.add('hidden');
            }
        }

        if (mobileMenuBtn) {
            mobileMenuBtn.addEventListener('click', toggleMobileMenu);
        }

        if (mobileOverlay) {
            mobileOverlay.addEventListener('click', toggleMobileMenu);
        }

        document.querySelectorAll('#sidebar a').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 1024) {
                    sidebar.classList.add(isRtl ? 'translate-x-full' : '-translate-x-full');
                    mobileOverlay.classList.add('hidden');
                }
            });
        });
    </script>
</body>
</html>