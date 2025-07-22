<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', t($company->translation_group . '.dashboard', 'Dashboard')) - {{ $company->legal_name }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="{{ $fontLink ?? 'https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap' }}" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        // التحقق من صحة الألوان
        const primaryColor = '{{ $company->primary_color }}';
        const secondaryColor = '{{ $company->secondary_color }}';
        
        function isValidColor(color) {
            const s = new Option().style;
            s.color = color;
            return s.color !== '';
        }

        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'cairo': ['Cairo', 'sans-serif'],
                        'inter': ['Inter', 'sans-serif'],
                    },
                    colors: {
                        primary: isValidColor(primaryColor) ? primaryColor : '#3B82F6',
                        secondary: isValidColor(secondaryColor) ? secondaryColor : '#6B7280',
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

    <style>
        /* إصلاح شامل لمشكلة overflow الصايدبار */
        html, body {
            overflow-x: hidden !important;
            max-width: 100vw;
            box-sizing: border-box;
        }

        * {
            box-sizing: border-box;
        }

        /* تخصيص الموقع للـ sidebar باستخدام RTL-friendly approach */
        .sidebar-hidden {
            inset-inline-start: -100%;
        }
        .sidebar-visible {
            inset-inline-start: 0;
        }
        
        /* تأثير انتقال سلس */
        .sidebar-transition {
            transition: inset-inline-start 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            will-change: inset-inline-start;
        }
        
        /* تأثير blur للخلفية */
        .sidebar-backdrop {
            backdrop-filter: blur(4px);
        }
        
        /* تحسين الأداء */
        .gpu-acceleration {
            will-change: transform;
            backface-visibility: hidden;
        }

        /* منع overflow عند فتح الصايدبار على الموبايل */
        body.sidebar-open {
            overflow: hidden !important;
            position: fixed;
            width: 100%;
            height: 100vh;
        }

        /* تحسين الصايدبار للشاشات الصغيرة */
        @media (max-width: 1023px) {
            #sidebar {
                position: fixed !important;
                top: 0;
                bottom: 0;
                width: min(280px, calc(100vw - 40px)) !important;
                max-width: 320px;
                height: 100vh;
                z-index: 50;
                overflow-y: auto;
                overflow-x: hidden;
                -webkit-overflow-scrolling: touch;
            }

            /* إضافة CSS محسن للصايدبار - للموبايل فقط */
            #sidebar.sidebar-transition {
                transform: translateX({{ $isRtl ? '100%' : '-100%' }});
            }

            #sidebar.sidebar-visible.sidebar-transition {
                transform: translateX(0);
            }

            #sidebar.sidebar-hidden.sidebar-transition {
                transform: translateX({{ $isRtl ? '100%' : '-100%' }});
            }
        }

        /* للشاشات الكبيرة */
        @media (min-width: 1024px) {
            #sidebar {
                position: static !important;
                inset-inline-start: 0 !important;
                width: 288px;
                height: auto;
                transform: none !important; /* إلغاء أي تأثير transform */
            }

            /* إزالة تأثيرات التحويل على الشاشات الكبيرة */
            #sidebar.sidebar-transition {
                transform: none !important;
            }

            #sidebar.sidebar-visible.sidebar-transition,
            #sidebar.sidebar-hidden.sidebar-transition {
                transform: none !important;
            }
        }

        /* منع horizontal scrolling للمحتوى الرئيسي */
        .main-content-wrapper {
            overflow-x: hidden;
            max-width: 100vw;
            width: 100%;
        }

        /* تحسين المحتوى الداخلي */
        .content-container {
            width: 100%;
            max-width: 100%;
            overflow-x: hidden;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
    </style>

    @stack('styles')
</head>

<body class="bg-gray-100 {{ app()->getLocale() == 'ar' ? 'font-cairo' : 'font-inter' }}">

    <div class="min-h-screen overflow-x-hidden">

        <!-- Mobile Header -->
        <header class="lg:hidden bg-white shadow-sm border-b border-gray-200 px-4 py-3 relative z-50 w-full max-w-full overflow-x-hidden">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3 rtl:space-x-reverse min-w-0 flex-1">
                    <button id="mobileMenuBtn" class="p-2 rounded-lg hover:bg-gray-100 transition-colors focus:outline-none focus:ring-2 focus:ring-primary/20 flex-shrink-0">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    <div class="flex items-center space-x-2 rtl:space-x-reverse min-w-0 flex-1">
                        @if ($company->company_logo)
                            <img src="{{ $company->logo_url }}" alt="{{ $company->legal_name }}" class="w-8 h-8 object-contain flex-shrink-0">
                        @else
                            <div class="w-8 h-8 rounded flex items-center justify-center flex-shrink-0" style="background: {{ $company->primary_color }};">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                        @endif
                        <h1 class="font-bold text-lg truncate min-w-0">{{ t($company->translation_group . '.dashboard', 'Dashboard') }}</h1>
                    </div>
                </div>
                @if (function_exists('get_active_languages'))
                    <div class="relative flex-shrink-0 ml-2">
                        <select onchange="changeLanguage(this.value)" class="bg-gray-50 border border-gray-200 text-gray-700 hover:bg-gray-100 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/50">
                            @foreach (get_active_languages() as $language)
                                <option value="{{ $language->code }}" {{ app()->getLocale() == $language->code ? 'selected' : '' }}>
                                    {{ $language->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif
            </div>
        </header>

        <div class="flex lg:flex-row w-full max-w-full overflow-x-hidden">
            <!-- Sidebar محسن مع RTL support وحل مشكلة overflow -->
            <aside id="sidebar" class="fixed lg:static inset-y-0 z-40 w-72 bg-dark-900 text-white sidebar-transition sidebar-hidden lg:sidebar-visible lg:flex lg:flex-col gpu-acceleration">

                <!-- Logo Section -->
                <div class="p-6 border-b border-gray-700 overflow-hidden">
                    <div class="flex items-center space-x-3 rtl:space-x-reverse">
                        <div class="w-12 h-12 bg-white rounded-xl p-2 shadow-lg flex-shrink-0">
                            @if ($company->company_logo)
                                <img src="{{ $company->logo_url }}" alt="{{ $company->legal_name }}" class="w-full h-full object-contain">
                            @else
                                <div class="w-full h-full rounded-lg flex items-center justify-center" style="background: {{ $company->primary_color }};">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="min-w-0 flex-1">
                            <h1 class="text-lg font-bold truncate">{{ t($company->translation_group . '.user_dashboard', 'User Dashboard') }}</h1>
                            <p class="text-sm truncate" style="color: {{ $company->primary_color }};">{{ $company->legal_name }}</p>
                        </div>
                    </div>
                </div>

                <!-- Navigation -->
                <nav class="mt-6 px-4 flex-1 overflow-y-auto overflow-x-hidden">
                    <ul class="space-y-2">
                        <li>
                            <a href="{{ route('insurance.user.dashboard', ['companySlug' => $company->company_slug]) }}" class="flex items-center px-4 py-3 rounded-lg transition-colors duration-200 {{ request()->routeIs('insurance.user.dashboard') ? 'text-dark-900' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }} break-words" style="{{ request()->routeIs('insurance.user.dashboard') ? 'background: ' . $company->primary_color : '' }}">
                                <svg class="w-5 h-5 me-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
                                </svg>
                                <span class="truncate">{{ t($company->translation_group . '.dashboard', 'Dashboard') }}</span>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('insurance.user.claims.index', ['companySlug' => $company->company_slug]) }}" class="flex items-center px-4 py-3 rounded-lg transition-colors duration-200 {{ request()->routeIs('insurance.user.claims.*') ? 'text-dark-900' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }} break-words" style="{{ request()->routeIs('insurance.user.claims.*') ? 'background: ' . $company->primary_color : '' }}">
                                <svg class="w-5 h-5 me-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span class="truncate">{{ t($company->translation_group . '.my_claims', 'My Claims') }}</span>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('insurance.user.complaints.index', ['companySlug' => $company->company_slug ?? 'default']) }}"
                               class="flex items-center px-4 py-3 rounded-lg transition-colors duration-200 {{ request()->routeIs('insurance.user.complaints.*') ? 'text-dark-900' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }} break-words"
                               style="{{ request()->routeIs('insurance.user.complaints.*') ? 'background: ' . ($company->primary_color ?? '#3B82F6') : '' }}">
                                <svg class="w-5 h-5 me-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                                <span class="truncate">{{ t(($company->translation_group ?? 'default') . '.complaints_inquiries') }}</span>
                            </a>
                        </li>

                        @php
                            $company = auth('insurance_user')->user()->company ?? \App\Models\InsuranceCompany::find(auth('insurance_user')->user()->insurance_company_id);
                        @endphp

                  <li>
    <a href="{{ route('insurance.user.profile.show', ['companySlug' => $company->company_slug]) }}" 
       class="flex items-center px-4 py-3 rounded-lg transition-colors duration-200 {{ request()->routeIs('insurance.user.profile.*') ? 'text-dark-900' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }} break-words"
       style="{{ request()->routeIs('insurance.user.profile.*') ? 'background: ' . $company->primary_color : '' }}">
        <svg class="w-5 h-5 {{ $isRtl ? 'ml-3' : 'mr-3' }} flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
        </svg>
        <span class="truncate">{{ t($company->translation_group . '.my_profile', 'My Profile') }}</span>
    </a>
</li>


                        <li>
                            <a href="#" class="flex items-center px-4 py-3 rounded-lg transition-colors duration-200 text-gray-300 hover:bg-gray-800 hover:text-white opacity-60 cursor-not-allowed break-words">
                                <svg class="w-5 h-5 me-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                                <span class="truncate">{{ t($company->translation_group . '.policy_details', 'Policy Details') }}</span>
                                <span class="text-xs bg-gray-700 px-2 py-1 rounded ms-auto flex-shrink-0">Soon</span>
                            </a>
                        </li>

                        <li>
                            <a href="#" class="flex items-center px-4 py-3 rounded-lg transition-colors duration-200 text-gray-300 hover:bg-gray-800 hover:text-white opacity-60 cursor-not-allowed break-words">
                                <svg class="w-5 h-5 me-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 1.26a2 2 0 001.11 0L20 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                <span class="truncate">{{ t($company->translation_group . '.contact_support', 'Contact Support') }}</span>
                                <span class="text-xs bg-gray-700 px-2 py-1 rounded ms-auto flex-shrink-0">Soon</span>
                            </a>
                        </li>
                    </ul>
                </nav>

                <!-- User Section -->
                <div class="p-4 border-t border-gray-700 overflow-hidden">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3 rtl:space-x-reverse min-w-0 flex-1">
                            <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0" style="background: {{ $company->primary_color }};">
                                <span class="text-white font-bold text-sm">{{ substr(auth('insurance_user')->user()->full_name, 0, 2) }}</span>
                            </div>
                            <div class="hidden lg:block min-w-0 flex-1">
                                <p class="text-white text-sm font-medium truncate">{{ Str::limit(auth('insurance_user')->user()->full_name, 20) }}</p>
                                <p class="text-gray-400 text-xs truncate">{{ t($company->translation_group . '.insured_member', 'Insured Member') }}</p>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('insurance.user.logout', ['companySlug' => $company->company_slug]) }}">
                            @csrf
                            <button type="submit" class="p-2 text-gray-400 hover:text-red-400 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-red-400/20 flex-shrink-0" title="{{ t($company->translation_group . '.logout', 'Logout') }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </aside>

            <!-- Main Content -->
            <main class="flex-1 min-h-screen main-content-wrapper">

                <!-- Desktop Header -->
                <header class="hidden lg:block bg-white border-b border-gray-200 px-8 py-6 w-full max-w-full overflow-x-hidden">
                    <div class="flex items-center justify-between">
                        <div class="min-w-0 flex-1">
                            <h2 class="text-2xl font-bold text-gray-900 break-words">@yield('title', t($company->translation_group . '.dashboard', 'Dashboard'))</h2>
                            <p class="text-gray-600 break-words">{{ t($company->translation_group . '.welcome_back', 'Welcome Back') }}, {{ auth('insurance_user')->user()->full_name }}</p>
                        </div>
                        @if (function_exists('get_active_languages'))
                            <div class="relative flex-shrink-0 ml-4">
                                <select onchange="changeLanguage(this.value)" class="bg-gray-50 border border-gray-200 text-gray-700 hover:bg-gray-100 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/50">
                                    @foreach (get_active_languages() as $language)
                                        <option value="{{ $language->code }}" {{ app()->getLocale() == $language->code ? 'selected' : '' }}>
                                            {{ $language->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                    </div>
                </header>

                <!-- Content -->
                <div class="p-4 lg:p-8 content-container">
                    <!-- Messages -->
                    @if (session('success'))
                        <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg shadow-sm break-words">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 me-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="break-words">{{ session('success') }}</span>
                            </div>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg shadow-sm break-words">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 me-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                <span class="break-words">{{ session('error') }}</span>
                            </div>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg shadow-sm break-words">
                            <div class="flex items-center mb-2">
                                <svg class="w-5 h-5 me-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                <span class="font-medium break-words">{{ t($company->translation_group . '.validation_errors', 'Validation Errors') }}</span>
                            </div>
                            <ul class="list-disc list-inside text-sm max-h-40 overflow-y-auto">
                                @foreach ($errors->all() as $error)
                                    <li class="break-words">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Mobile Overlay -->
    <div id="mobileOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 lg:hidden hidden sidebar-backdrop"></div>

    @stack('scripts')

    <script>
        // JavaScript محسن للـ sidebar مع RTL support وحل مشكلة overflow
        document.addEventListener('DOMContentLoaded', function() {
            // انتظار قصير لضمان تحميل العناصر بالكامل
            setTimeout(function() {
                const mobileMenuBtn = document.getElementById('mobileMenuBtn');
                const sidebar = document.getElementById('sidebar');
                const mobileOverlay = document.getElementById('mobileOverlay');

                // التحقق من وجود العناصر مع إعادة محاولة
                if (!mobileMenuBtn || !sidebar || !mobileOverlay) {
                    console.error('Required elements not found:', {
                        mobileMenuBtn: !!mobileMenuBtn,
                        sidebar: !!sidebar,
                        mobileOverlay: !!mobileOverlay
                    });
                    // إعادة المحاولة بعد 200ms
                    setTimeout(arguments.callee, 200);
                    return;
                }

                // وظائف التحكم في الصايدبار - للموبايل فقط
                function toggleMobileMenu() {
                    // التحقق من أن الشاشة صغيرة
                    if (window.innerWidth >= 1024) return;
                    
                    try {
                        const isCurrentlyVisible = sidebar.classList.contains('sidebar-visible');
                        
                        if (isCurrentlyVisible) {
                            closeMobileMenu();
                        } else {
                            openMobileMenu();
                        }
                    } catch (error) {
                        console.error('Error toggling menu:', error);
                    }
                }

                function openMobileMenu() {
                    // التحقق من أن الشاشة صغيرة
                    if (window.innerWidth >= 1024) return;
                    
                    sidebar.classList.remove('sidebar-hidden');
                    sidebar.classList.add('sidebar-visible');
                    mobileOverlay.classList.remove('hidden');
                    document.body.classList.add('sidebar-open');
                }

                function closeMobileMenu() {
                    try {
                        sidebar.classList.add('sidebar-hidden');
                        sidebar.classList.remove('sidebar-visible');
                        mobileOverlay.classList.add('hidden');
                        document.body.classList.remove('sidebar-open');
                    } catch (error) {
                        console.error('Error closing menu:', error);
                    }
                }

                // Event listeners مع معالجة أفضل للأخطاء
                mobileMenuBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    toggleMobileMenu();
                });
                
                mobileOverlay.addEventListener('click', closeMobileMenu);

                // إغلاق الـ sidebar عند الضغط على أي رابط - للموبايل فقط
                document.querySelectorAll('#sidebar a').forEach(link => {
                    link.addEventListener('click', () => {
                        if (window.innerWidth < 1024) {
                            closeMobileMenu();
                        }
                    });
                });

                // إغلاق القائمة عند الضغط على ESC - للموبايل فقط
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' && window.innerWidth < 1024 && sidebar.classList.contains('sidebar-visible')) {
                        closeMobileMenu();
                    }
                });

                // تحديث الحالة عند تغيير حجم الشاشة
                window.addEventListener('resize', function() {
                    if (window.innerWidth >= 1024) {
                        // إزالة جميع classes الخاصة بالموبايل عند الانتقال للديسكتوب
                        sidebar.classList.remove('sidebar-hidden', 'sidebar-visible');
                        mobileOverlay.classList.add('hidden');
                        document.body.classList.remove('sidebar-open');
                    } else {
                        // إضافة class مخفي للموبايل إذا لم يكن موجود
                        if (!sidebar.classList.contains('sidebar-visible')) {
                            sidebar.classList.add('sidebar-hidden');
                        }
                    }
                });

                // تهيئة الحالة الابتدائية
                if (window.innerWidth < 1024) {
                    sidebar.classList.add('sidebar-hidden');
                } else {
                    sidebar.classList.remove('sidebar-hidden', 'sidebar-visible');
                }

                // منع horizontal scrolling
                document.documentElement.style.overflowX = 'hidden';
            }, 50); // انتظار 50ms فقط
        });

        // وظيفة تغيير اللغة - بقيت كما هي
        function changeLanguage(code) {
            window.location.href = '/language/' + code;
        }

        // تحسين الأداء مع Intersection Observer
        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate-fadeIn');
                    }
                });
            });

            document.querySelectorAll('.animate-on-scroll').forEach(el => {
                observer.observe(el);
            });
        }
    </script>
</body>

</html>
