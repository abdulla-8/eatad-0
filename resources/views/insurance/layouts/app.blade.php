<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', t(auth('insurance_company')->user()->translation_group . '.dashboard', 'Dashboard')) - {{ auth('insurance_company')->user()->legal_name }}</title>

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
                        primary: '{{ auth('insurance_company')->user()->primary_color }}',
                        secondary: '{{ auth('insurance_company')->user()->secondary_color }}',
                        dark: {
                            900: '#191919',
                            800: '#2d2d2d',
                            700: '#3a3a3a'
                        }
                    }
                }
            }
        }
    </script>

    @stack('styles')
</head>

<body class="bg-gray-100 {{ app()->getLocale() == 'ar' ? 'font-cairo' : 'font-inter' }}">
    @php
        $company = auth('insurance_company')->user();
    @endphp

    <div class="min-h-screen">

        <!-- Mobile Header -->
        <header class="lg:hidden bg-white shadow-sm border-b border-gray-200 px-4 py-3 relative z-50">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3 {{ $isRtl ? 'space-x-reverse' : '' }}">
                    <button id="mobileMenuBtn" class="p-2 rounded-lg hover:bg-gray-100 transition-colors">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    <div class="flex items-center space-x-2 {{ $isRtl ? 'space-x-reverse' : '' }}">
                        @if ($company->company_logo)
                            <img src="{{ $company->logo_url }}" alt="{{ $company->legal_name }}"
                                class="w-8 h-8 object-contain">
                        @else
                            <div class="w-8 h-8 rounded flex items-center justify-center"
                                style="background: {{ $company->primary_color }};">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                    </path>
                                </svg>
                            </div>
                        @endif
                        <h1 class="font-bold text-lg">{{ t($company->translation_group . '.dashboard', 'Dashboard') }}
                        </h1>
                    </div>
                </div>
                <x-language-switcher class="bg-gray-50 border border-gray-200 text-gray-700 hover:bg-gray-100" />
            </div>
        </header>

        <div class="flex lg:flex-row">
            <!-- Sidebar -->
            <aside id="sidebar"
                class="fixed lg:static inset-y-0 {{ $isRtl ? 'right-0' : 'left-0' }} z-40 w-72 bg-dark-900 text-white transform {{ $isRtl ? 'translate-x-full' : '-translate-x-full' }} lg:translate-x-0 transition-transform duration-300 ease-in-out lg:flex lg:flex-col">

                <!-- Logo Section -->
                <div class="p-6 border-b border-gray-700">
                    <div class="flex items-center space-x-3 {{ $isRtl ? 'space-x-reverse' : '' }}">
                        <div class="w-12 h-12 bg-white rounded-xl p-2 shadow-lg">
                            @if ($company->company_logo)
                                <img src="{{ $company->logo_url }}" alt="{{ $company->legal_name }}"
                                    class="w-full h-full object-contain">
                            @else
                                <div class="w-full h-full rounded-lg flex items-center justify-center"
                                    style="background: {{ $company->primary_color }};">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                        </path>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div>
                            <h1 class="text-lg font-bold">
                                {{ t($company->translation_group . '.dashboard', 'Dashboard') }}</h1>
                            <p class="text-sm" style="color: {{ $company->primary_color }};">
                                {{ $company->legal_name }}</p>
                        </div>
                    </div>
                </div>

                <!-- Navigation -->
                <nav class="mt-6 px-4">
                    <ul class="space-y-2">
                        <li>
                            <a href="{{ route('insurance.dashboard', ['companyRoute' => $company->company_slug]) }}"
                                class="flex items-center px-4 py-3 rounded-lg transition-colors duration-200 {{ request()->routeIs('insurance.dashboard') ? 'text-dark-900' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}"
                                style="{{ request()->routeIs('insurance.dashboard') ? 'background: ' . $company->primary_color : '' }}">
                                <svg class="w-5 h-5 {{ $isRtl ? 'ml-3' : 'mr-3' }}" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
                                </svg>
                                {{ t($company->translation_group . '.dashboard', 'Dashboard') }}
                            </a>
                        </li>


                        <li>
                            <a href="{{ route('insurance.claims.index', ['companyRoute' => $company->company_slug]) }}"
                                class="flex items-center px-4 py-3 rounded-lg transition-colors duration-200 {{ request()->routeIs('insurance.claims.*') ? 'text-dark-900' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}"
                                style="{{ request()->routeIs('insurance.claims.*') ? 'background: ' . $company->primary_color : '' }}">
                                <svg class="w-5 h-5 {{ $isRtl ? 'ml-3' : 'mr-3' }}" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                {{ t($company->translation_group . '.claims', 'Claims') }}
                            </a>
                        </li>

                        
                        <li>
                            <a href="#"
                                class="flex items-center px-4 py-3 rounded-lg transition-colors duration-200 text-gray-300 hover:bg-gray-800 hover:text-white opacity-60 cursor-not-allowed">
                                <svg class="w-5 h-5 {{ $isRtl ? 'ml-3' : 'mr-3' }}" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                {{ t($company->translation_group . '.my_profile', 'My Profile') }}
                                <span
                                    class="text-xs bg-gray-700 px-2 py-1 rounded {{ $isRtl ? 'mr-auto' : 'ml-auto' }}">Soon</span>
                            </a>
                        </li>

                        <li>
                            <a href="#"
                                class="flex items-center px-4 py-3 rounded-lg transition-colors duration-200 text-gray-300 hover:bg-gray-800 hover:text-white opacity-60 cursor-not-allowed">
                                <svg class="w-5 h-5 {{ $isRtl ? 'ml-3' : 'mr-3' }}" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                {{ t($company->translation_group . '.claims', 'Claims') }}
                                <span
                                    class="text-xs bg-gray-700 px-2 py-1 rounded {{ $isRtl ? 'mr-auto' : 'ml-auto' }}">Soon</span>
                            </a>
                        </li>

                        <li>
                            <a href="#"
                                class="flex items-center px-4 py-3 rounded-lg transition-colors duration-200 text-gray-300 hover:bg-gray-800 hover:text-white opacity-60 cursor-not-allowed">
                                <svg class="w-5 h-5 {{ $isRtl ? 'ml-3' : 'mr-3' }}" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                    </path>
                                </svg>
                                {{ t($company->translation_group . '.policies', 'Policies') }}
                                <span
                                    class="text-xs bg-gray-700 px-2 py-1 rounded {{ $isRtl ? 'mr-auto' : 'ml-auto' }}">Soon</span>
                            </a>
                        </li>
                    </ul>
                </nav>

                <!-- User Section -->
                <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-700">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3 {{ $isRtl ? 'space-x-reverse' : '' }}">
                            <div class="w-10 h-10 rounded-lg flex items-center justify-center"
                                style="background: {{ $company->primary_color }};">
                                @if ($company->company_logo)
                                    <img src="{{ $company->logo_url }}" alt="{{ $company->legal_name }}"
                                        class="w-6 h-6 object-contain">
                                @else
                                    <span
                                        class="text-white font-bold text-sm">{{ substr($company->legal_name, 0, 2) }}</span>
                                @endif
                            </div>
                            <div class="hidden lg:block">
                                <p class="text-white text-sm font-medium">{{ Str::limit($company->legal_name, 20) }}
                                </p>
                                <p class="text-gray-400 text-xs">Insurance Company</p>
                            </div>
                        </div>
                        <form method="POST"
                            action="{{ route('insurance.logout', ['companyRoute' => $company->company_slug]) }}">
                            @csrf
                            <button type="submit"
                                class="p-2 text-gray-400 hover:text-red-400 rounded-lg transition-colors"
                                title="{{ t($company->translation_group . '.logout', 'Logout') }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                    </path>
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
                            <h2 class="text-2xl font-bold text-gray-900">@yield('title', t($company->translation_group . '.dashboard', 'Dashboard'))</h2>
                            <p class="text-gray-600">
                                {{ t($company->translation_group . '.welcome_back', 'Welcome Back') }},
                                {{ $company->legal_name }}</p>
                        </div>
                        <x-language-switcher
                            class="bg-gray-50 border border-gray-200 text-gray-700 hover:bg-gray-100" />
                    </div>
                </header>

                <!-- Content -->
                <div class="p-4 lg:p-8">
                    <!-- Messages -->
                    @if (session('success'))
                        <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 {{ $isRtl ? 'ml-2' : 'mr-2' }}" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                {{ session('success') }}
                            </div>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 {{ $isRtl ? 'ml-2' : 'mr-2' }}" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
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

    @stack('scripts')

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
