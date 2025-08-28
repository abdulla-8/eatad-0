<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', t('insurance_company.dashboard')) - {{ t('insurance_company.dashboard') }}</title>

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
                        }
                    }
                }
            }
        }
    </script>

    <style>
        /* إصلاح شامل للـ Sidebar - ضمان Full Height والإخفاء الكامل */
        .sidebar-container {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh !important;
            min-height: 100vh !important;
            width: 18rem; /* 288px equivalent to w-72 */
            z-index: 1000;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            will-change: transform;
            display: flex !important;
            flex-direction: column !important;
            background: #191919;
        }

        /* إخفاء كامل ومضمون في الموبايل */
        @media (max-width: 1023px) {
            .sidebar-container {
                transform: translateX(-100%) !important;
                opacity: 0 !important;
                visibility: hidden !important;
                pointer-events: none !important;
            }

            .sidebar-container.rtl {
                left: auto !important;
                right: 0 !important;
                transform: translateX(100%) !important;
            }

            .sidebar-container.mobile-open {
                transform: translateX(0) !important;
                opacity: 1 !important;
                visibility: visible !important;
                pointer-events: auto !important;
            }

            /* ضمان عرض كامل للـ mobile header */
            .mobile-header {
                position: fixed !important;
                top: 0 !important;
                left: 0 !important;
                right: 0 !important;
                width: 100% !important;
                z-index: 998 !important;
                margin: 0 !important;
                padding-left: 1rem !important;
                padding-right: 1rem !important;
            }

            /* ضمان عدم تأثير الـ sidebar على الـ layout */
            .main-content {
                margin-left: 0 !important;
                margin-right: 0 !important;
                width: 100% !important;
                padding-top: 4rem !important;
                min-height: calc(100vh - 4rem) !important;
            }

            .layout-wrapper {
                margin-left: 0 !important;
                margin-right: 0 !important;
                width: 100% !important;
            }
        }

        /* إظهار دائم في الديسكتوب مع full width للصايدبار */
        @media (min-width: 1024px) {
            .sidebar-container {
                position: static !important;
                transform: translateX(0) !important;
                opacity: 1 !important;
                visibility: visible !important;
                pointer-events: auto !important;
                height: 100vh !important;
                min-height: 100vh !important;
                max-height: 100vh !important;
                width: 18rem !important; /* ضمان الـ full width للصايدبار */
                flex-shrink: 0 !important;
            }

            .mobile-header {
                display: none !important;
            }

            .main-content {
                flex: 1 !important;
                min-height: 100vh !important;
                padding-top: 0 !important;
                width: calc(100% - 18rem) !important; /* حساب العرض المتبقي */
                margin-left: 0 !important;
            }

            .layout-wrapper {
                min-height: 100vh !important;
                height: 100vh !important;
                display: flex !important;
                flex-direction: row !important;
                width: 100% !important;
            }

            /* ضمان أن desktop header يأخذ العرض الكامل المتاح */
            .desktop-header {
                width: 100% !important;
                margin: 0 !important;
            }
        }

        /* تحسين الـ Navigation ليأخذ المساحة المتاحة */
        .sidebar-navigation {
            flex: 1 !important;
            overflow-y: auto !important;
            padding-bottom: 1rem;
        }

        /* ضمان أن User Section يكون في الأسفل */
        .sidebar-user-section {
            margin-top: auto !important;
            flex-shrink: 0 !important;
        }

        /* Overlay للموبايل */
        .mobile-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
            pointer-events: none;
        }

        .mobile-overlay.show {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
        }

        /* منع scroll horizontal */
        html, body {
            overflow-x: hidden !important;
            max-width: 100% !important;
            height: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        /* ضمان الـ full height للـ root container */
        .root-container {
            min-height: 100vh !important;
            height: 100vh !important;
            display: flex !important;
            flex-direction: column !important;
            overflow-x: hidden !important;
        }

        /* تحسين الـ scrollbar */
        .sidebar-container::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar-container::-webkit-scrollbar-track {
            background: #2d2d2d;
        }

        .sidebar-container::-webkit-scrollbar-thumb {
            background: #4a4a4a;
            border-radius: 3px;
        }

        .sidebar-navigation::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar-navigation::-webkit-scrollbar-track {
            background: #2d2d2d;
        }

        .sidebar-navigation::-webkit-scrollbar-thumb {
            background: #4a4a4a;
            border-radius: 3px;
        }

        /* منع body scroll عند فتح الـ sidebar في الموبايل */
        body.sidebar-open {
            overflow: hidden !important;
        }

        /* تحسين focus للإمكانية */
        .focus-visible:focus {
            outline: 2px solid #FFDD57;
            outline-offset: 2px;
        }

        /* إضافة animation للهامبرغر */
        .hamburger-line {
            transition: all 0.3s ease;
        }

        .hamburger-active .hamburger-line:nth-child(1) {
            transform: rotate(45deg) translate(5px, 5px);
        }

        .hamburger-active .hamburger-line:nth-child(2) {
            opacity: 0;
        }

        .hamburger-active .hamburger-line:nth-child(3) {
            transform: rotate(-45deg) translate(7px, -6px);
        }

        /* ضمان أن المحتوى يأخذ المساحة المطلوبة */
        .content-wrapper {
            flex: 1 !important;
            min-height: 100% !important;
            display: flex !important;
            flex-direction: column !important;
            width: 100% !important;
        }

        .page-content {
            flex: 1 !important;
            min-height: 100% !important;
        }

        /* إضافة تحسينات إضافية للـ sidebar header */
        .sidebar-header {
            flex-shrink: 0 !important;
            border-bottom: 1px solid #374151;
        }

        /* تحسين spacing للنافيجيشن */
        .sidebar-nav-item {
            margin-bottom: 0.5rem;
        }

        .sidebar-nav-item:last-child {
            margin-bottom: 0;
        }

        /* إصلاح مشاكل التداخل */
        .container, .container-fluid {
            max-width: 100% !important;
            overflow-x: hidden !important;
        }

        /* تحسين RTL */
        [dir="rtl"] .sidebar-container {
            left: auto !important;
            right: 0 !important;
        }

        @media (min-width: 1024px) {
            [dir="rtl"] .main-content {
                margin-right: 0 !important;
                margin-left: 0 !important;
            }
        }
    </style>
</head>

<body class="bg-gray-100 {{ app()->getLocale() == 'ar' ? 'font-cairo' : 'font-inter' }}">
    <div class="root-container">

        <!-- Mobile Header -->
        <header class="mobile-header lg:hidden bg-white shadow-sm border-b border-gray-200 py-3">
            <div class="flex items-center justify-between w-full">
                <div class="flex items-center space-x-3 {{ $isRtl ? 'space-x-reverse' : '' }}">
                    <button id="mobileMenuBtn" class="p-2 rounded-lg hover:bg-gray-100 transition-colors focus-visible:focus hamburger" aria-label="فتح القائمة">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path class="hamburger-line" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16"></path>
                            <path class="hamburger-line" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 12h16"></path>
                            <path class="hamburger-line" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 18h16"></path>
                        </svg>
                    </button>
                    <div class="flex items-center space-x-2 {{ $isRtl ? 'space-x-reverse' : '' }}">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <h1 class="font-bold text-lg">{{ t('insurance_company.dashboard') }}</h1>
                    </div>
                </div>
                <x-language-switcher class="bg-gray-50 border border-gray-200 text-gray-700 hover:bg-gray-100" />
            </div>
        </header>

        <div class="layout-wrapper flex-1">
            <!-- Sidebar -->
            <aside id="sidebar" class="sidebar-container {{ $isRtl ? 'rtl' : '' }} text-white">
                <!-- Logo Section -->
                <div class="sidebar-header p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3 {{ $isRtl ? 'space-x-reverse' : '' }}">
                            <div class="w-12 h-12 bg-white rounded-xl p-2 shadow-lg">
                                <svg class="w-full h-full text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-lg font-bold">{{ t('insurance_company.dashboard') }}</h1>
                            </div>
                        </div>
                        <!-- زر إغلاق للموبايل فقط -->
                        <button id="closeSidebar" class="lg:hidden p-2 text-gray-400 hover:text-white transition-colors focus-visible:focus" aria-label="إغلاق القائمة">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Navigation -->
                <nav class="sidebar-navigation px-4">
                    <ul class="space-y-2">
                        <li class="sidebar-nav-item">
                            <a href="{{ route('insurance.dashboard',$company->company_slug) }}" class="flex items-center px-4 py-3 rounded-lg transition-colors duration-200 focus-visible:focus {{ request()->routeIs('insurance.dashboard') ? 'bg-gold-500 text-dark-900' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                                <svg class="w-5 h-5 {{ $isRtl ? 'ml-3' : 'mr-3' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
                                </svg>
                                {{ t('insurance_company.dashboard') }}
                            </a>
                        </li>

                        <li class="sidebar-nav-item">
                            <a href="{{ route('insurance.claims.index',$company->company_slug) }}" class="flex items-center px-4 py-3 rounded-lg transition-colors duration-200 focus-visible:focus {{ request()->routeIs('insurance.claims.*') ? 'bg-gold-500 text-dark-900' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                                <svg class="w-5 h-5 {{ $isRtl ? 'ml-3' : 'mr-3' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                {{ t('insurance_company.claims') }}
                            </a>
                        </li>

                  
                        <li class="sidebar-nav-item">
                            <a href="{{ route('insurance.complaints.index',$company->company_slug) }}" 
                               class="flex items-center px-4 py-3 rounded-lg transition-colors duration-200 focus-visible:focus
                                      {{ request()->routeIs('insurance.complaints.*') ? 'bg-gold-500 text-dark-900' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                                <svg class="w-5 h-5 {{ $isRtl ? 'ml-3' : 'mr-3' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                                {{ t('insurance_company.complaints_inquiries') }}
                            </a>
                        </li>


                        <li class="sidebar-nav-item">
                            <a href="{{ route('insurance.profile.show',$company->company_slug) }}" 
                               class="flex items-center px-4 py-3 rounded-lg transition-colors duration-200 focus-visible:focus
                                      {{ request()->routeIs('insurance.profile.*') ? 'bg-gold-500 text-dark-900' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                                <svg class="w-5 h-5 {{ $isRtl ? 'ml-3' : 'mr-3' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                {{ t('insurance_company.profile') }}
                            </a>
                        </li>

                        <li class="sidebar-nav-item">
                            <a href="#" class="flex items-center px-4 py-3 rounded-lg transition-colors duration-200 text-gray-300 hover:bg-gray-800 hover:text-white opacity-60 cursor-not-allowed">
                                <svg class="w-5 h-5 {{ $isRtl ? 'ml-3' : 'mr-3' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                {{ t('insurance_company.appointments') }}
                                <span class="text-xs bg-gray-700 px-2 py-1 rounded {{ $isRtl ? 'mr-auto' : 'ml-auto' }}">{{ t('insurance_company.coming_soon') }}</span>
                            </a>
                        </li>

                        <li class="sidebar-nav-item">
                            <a href="#" class="flex items-center px-4 py-3 rounded-lg transition-colors duration-200 text-gray-300 hover:bg-gray-800 hover:text-white opacity-60 cursor-not-allowed">
                                <svg class="w-5 h-5 {{ $isRtl ? 'ml-3' : 'mr-3' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                {{ t('insurance_company.reports') }}
                                <span class="text-xs bg-gray-700 px-2 py-1 rounded {{ $isRtl ? 'mr-auto' : 'ml-auto' }}">{{ t('insurance_company.coming_soon') }}</span>
                            </a>
                        </li>
                    </ul>
                </nav>

                <!-- User Section -->
                <div class="sidebar-user-section p-4 border-t border-gray-700">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3 {{ $isRtl ? 'space-x-reverse' : '' }}">
                            <div class="w-10 h-10 bg-gold-500 rounded-lg flex items-center justify-center">
                                <span class="text-dark-900 font-bold">{{ substr(auth('insurance_company')->user()->legal_name, 0, 1) }}</span>
                            </div>
                            <div class="hidden lg:block">
                                <p class="text-white text-sm font-medium">{{ Str::limit(auth('insurance_company')->user()->legal_name, 20) }}</p>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('insurance.logout',$company->company_slug) }}">
                            @csrf
                            <button type="submit" class="p-2 text-gray-400 hover:text-red-400 rounded-lg transition-colors focus-visible:focus" title="{{ t('auth.logout') }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </aside>

            <!-- Main Content -->
            <main class="main-content content-wrapper">
                <!-- Desktop Header -->
                <header class="desktop-header hidden lg:block bg-white border-b border-gray-200 px-8 py-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">@yield('title', t('insurance_company.dashboard'))</h2>
                            <p class="text-gray-600">{{ t('auth.welcome_back') }}, {{ auth('insurance_company')->user()->legal_name }}</p>
                        </div>
                        <x-language-switcher class="bg-gray-50 border border-gray-200 text-gray-700 hover:bg-gray-100" />
                    </div>
                </header>

                <!-- Content -->
                <div class="page-content p-4 lg:p-8">
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
    <div id="mobileOverlay" class="mobile-overlay lg:hidden"></div>

    <script>
        // Sidebar Manager - حل شامل ومحسن
        class AdvancedSidebarManager {
            constructor() {
                this.sidebar = document.getElementById('sidebar');
                this.overlay = document.getElementById('mobileOverlay');
                this.mobileMenuBtn = document.getElementById('mobileMenuBtn');
                this.closeSidebarBtn = document.getElementById('closeSidebar');
                this.body = document.body;
                this.isRtl = document.documentElement.dir === 'rtl';
                this.isOpen = false;
                this.isDesktop = window.innerWidth >= 1024;
                
                this.init();
            }

            init() {
                // إعداد الأحداث
                this.bindEvents();
                
                // التأكد من الحالة الأولية
                this.handleResize();
                
                // إعداد خصائص الإمكانية
                this.setupAccessibility();
                
                // ضمان الإخفاء في الموبايل عند التحميل
                this.ensureProperInitialState();
            }

            ensureProperInitialState() {
                if (!this.isDesktop) {
                    this.sidebar?.classList.remove('mobile-open');
                    this.overlay?.classList.remove('show');
                    this.mobileMenuBtn?.classList.remove('hamburger-active');
                    this.body.classList.remove('sidebar-open');
                    this.isOpen = false;
                }
            }

            bindEvents() {
                // أزرار التحكم
                this.mobileMenuBtn?.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    this.toggle();
                });

                this.closeSidebarBtn?.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    this.close();
                });

                // النقر على الـ overlay
                this.overlay?.addEventListener('click', () => {
                    this.close();
                });

                // مراقبة تغيير حجم الشاشة
                let resizeTimeout;
                window.addEventListener('resize', () => {
                    clearTimeout(resizeTimeout);
                    resizeTimeout = setTimeout(() => this.handleResize(), 150);
                });

                // مفتاح Escape
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape' && this.isOpen && !this.isDesktop) {
                        this.close();
                    }
                });

                // إغلاق عند النقر على الروابط في الموبايل
                this.sidebar?.querySelectorAll('a').forEach(link => {
                    link.addEventListener('click', () => {
                        if (!this.isDesktop) {
                            setTimeout(() => this.close(), 100);
                        }
                    });
                });

                // منع النقرات العرضية
                this.sidebar?.addEventListener('click', (e) => {
                    e.stopPropagation();
                });

                // منع النقر خارج الـ sidebar من إغلاقه في الديسكتوب
                document.addEventListener('click', (e) => {
                    if (this.isDesktop) return;
                    
                    const isClickInsideSidebar = this.sidebar?.contains(e.target);
                    const isToggleButton = this.mobileMenuBtn?.contains(e.target);
                    
                    if (this.isOpen && !isClickInsideSidebar && !isToggleButton) {
                        this.close();
                    }
                });
            }

            toggle() {
                if (this.isDesktop) return;
                this.isOpen ? this.close() : this.open();
            }

            open() {
                if (this.isDesktop || this.isOpen) return;
                
                this.isOpen = true;
                
                // إضافة classes للعرض
                this.sidebar?.classList.add('mobile-open');
                this.overlay?.classList.add('show');
                this.mobileMenuBtn?.classList.add('hamburger-active');
                
                // منع scroll في الخلفية
                this.body.classList.add('sidebar-open');
                
                // إدارة التركيز
                this.manageFocus(true);
                
                // تحديث ARIA
                this.updateAria();
            }

            close() {
                if (this.isDesktop || !this.isOpen) return;
                
                this.isOpen = false;
                
                // إزالة classes للإخفاء
                this.sidebar?.classList.remove('mobile-open');
                this.overlay?.classList.remove('show');
                this.mobileMenuBtn?.classList.remove('hamburger-active');
                
                // السماح بـ scroll مرة أخرى
                this.body.classList.remove('sidebar-open');
                
                // إدارة التركيز
                this.manageFocus(false);
                
                // تحديث ARIA
                this.updateAria();
            }

            handleResize() {
                const wasDesktop = this.isDesktop;
                this.isDesktop = window.innerWidth >= 1024;
                
                if (wasDesktop !== this.isDesktop) {
                    if (this.isDesktop) {
                        // انتقال إلى الديسكتوب - تنظيف حالة الموبايل
                        this.sidebar?.classList.remove('mobile-open');
                        this.overlay?.classList.remove('show');
                        this.mobileMenuBtn?.classList.remove('hamburger-active');
                        this.body.classList.remove('sidebar-open');
                        this.isOpen = false;
                    } else {
                        // انتقال إلى الموبايل - إغلاق الـ sidebar
                        this.close();
                    }
                    
                    this.updateAria();
                }
            }

            manageFocus(isOpening) {
                if (this.isDesktop) return;
                
                if (isOpening) {
                    // التركيز على أول عنصر قابل للتفاعل في الـ sidebar
                    const firstFocusable = this.sidebar?.querySelector('a, button, [tabindex]:not([tabindex="-1"])');
                    firstFocusable?.focus();
                } else {
                    // العودة للتركيز على زر الفتح
                    this.mobileMenuBtn?.focus();
                }
            }

            updateAria() {
                if (this.mobileMenuBtn) {
                    this.mobileMenuBtn.setAttribute('aria-expanded', (this.isOpen && !this.isDesktop).toString());
                }
                
                if (this.sidebar) {
                    this.sidebar.setAttribute('aria-hidden', (!this.isOpen && !this.isDesktop).toString());
                }
            }

            setupAccessibility() {
                // إعداد الـ ARIA labels
                if (this.sidebar) {
                    this.sidebar.setAttribute('role', 'navigation');
                    this.sidebar.setAttribute('aria-label', 'القائمة الرئيسية');
                }
                
                if (this.mobileMenuBtn) {
                    this.mobileMenuBtn.setAttribute('aria-controls', 'sidebar');
                }
                
                // تحديث الحالة الأولية
                this.updateAria();
            }
        }

        // تشغيل مدير الـ Sidebar
        document.addEventListener('DOMContentLoaded', () => {
            window.sidebarManager = new AdvancedSidebarManager();
        });

        // إعادة التهيئة عند التحديث بـ AJAX (إن وجد)
        document.addEventListener('turbo:load', () => {
            if (!window.sidebarManager) {
                window.sidebarManager = new AdvancedSidebarManager();
            }
        });

        // التأكد من الحالة الصحيحة عند تحميل الصفحة
        window.addEventListener('load', () => {
            if (window.sidebarManager) {
                window.sidebarManager.ensureProperInitialState();
            }
        });
    </script>
</body>

</html>
