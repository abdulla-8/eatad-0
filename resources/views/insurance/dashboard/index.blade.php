@extends('insurance.layouts.app')
@section('title', t($company->translation_group . '.dashboard', 'Dashboard'))

@section('content')
<div class="container mx-auto px-4 py-6 rtl:text-right ltr:text-left" dir="rtl">
    <!-- Welcome Section -->
    <div class="rounded-2xl p-6 md:p-8 text-white text-center mb-6 md:mb-8 shadow-xl" 
         style="background: linear-gradient(135deg, {{ $company->primary_color ?? '#10b981' }}, {{ $company->secondary_color ?? '#059669' }});">
        <h2 class="text-2xl md:text-3xl font-light mb-3">{{ t($company->translation_group . '.welcome_back', 'أهلاً وسهلاً') }}، {{ $stats['company_info']['legal_name'] }}</h2>
        <p class="text-white/80 text-base md:text-lg">{{ t($company->translation_group . '.dashboard_subtitle', 'إدارة شركة التأمين بكل سهولة') }}</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 md:gap-6 mb-6 md:mb-8">
        <!-- Total Users Card -->
        <div class="rounded-xl md:rounded-2xl p-4 md:p-6 text-white shadow-lg transform transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl relative overflow-hidden"
             style="background: linear-gradient(135deg, {{ $company->primary_color ?? '#10b981' }}, {{ $company->primary_color ?? '#10b981' }}dd);">
            <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent pointer-events-none"></div>
            <div class="relative z-10 flex items-center justify-between">
                <div class="flex-1">
                    <div class="text-2xl md:text-3xl font-bold mb-2">{{ $stats['users_stats']['total_users'] }}</div>
                    <div class="text-xs md:text-sm opacity-90">{{ t($company->translation_group . '.total_users', 'إجمالي المستخدمين') }}</div>
                </div>
                <div class="text-3xl md:text-4xl opacity-80 ml-3 md:ml-4">
                    <!-- Users SVG Icon -->
                    <svg class="w-8 h-8 md:w-12 md:h-12" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12,5.5A3.5,3.5 0 0,1 15.5,9A3.5,3.5 0 0,1 12,12.5A3.5,3.5 0 0,1 8.5,9A3.5,3.5 0 0,1 12,5.5M5,8C5.56,8 6.08,8.15 6.53,8.42C6.38,9.85 6.8,11.27 7.66,12.38C7.16,13.34 6.16,14 5,14A3,3 0 0,1 2,11A3,3 0 0,1 5,8M19,8A3,3 0 0,1 22,11A3,3 0 0,1 19,14C17.84,14 16.84,13.34 16.34,12.38C17.2,11.27 17.62,9.85 17.47,8.42C17.92,8.15 18.44,8 19,8M5.5,18.25C5.5,16.18 8.41,14.5 12,14.5C15.59,14.5 18.5,16.18 18.5,18.25V20H5.5V18.25M0,20V18.5C0,17.11 1.89,15.94 4.45,15.6C3.86,16.28 3.5,17.22 3.5,18.25V20H0M24,20H20.5V18.25C20.5,17.22 20.14,16.28 19.55,15.6C22.11,15.94 24,17.11 24,18.5V20Z"/>
                    </svg>
                </div>
            </div>
        </div>
        
        <!-- Total Service Centers Card -->
        <div class="rounded-xl md:rounded-2xl p-4 md:p-6 text-white shadow-lg transform transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl relative overflow-hidden"
             style="background: linear-gradient(135deg, #6366f1, #6366f1dd);">
            <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent pointer-events-none"></div>
            <div class="relative z-10 flex items-center justify-between">
                <div class="flex-1">
                    <div class="text-2xl md:text-3xl font-bold mb-2">{{ $stats['service_centers_stats']['total_centers'] }}</div>
                    <div class="text-xs md:text-sm opacity-90">{{ t($company->translation_group . '.total_centers', 'مراكز الصيانة') }}</div>
                </div>
                <div class="text-3xl md:text-4xl opacity-80 ml-3 md:ml-4">
                    <!-- Building SVG Icon -->
                    <svg class="w-8 h-8 md:w-12 md:h-12" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M15,11V5L12,2L9,5V7H3V21H21V11H15M7,19H5V17H7V19M7,15H5V13H7V15M7,11H5V9H7V11M13,19H11V17H13V19M13,15H11V13H13V15M13,11H11V9H13V11M13,7H11V5H13V7M19,19H17V17H19V19M19,15H17V13H19V15Z"/>
                    </svg>
                </div>
            </div>
        </div>
        
        <!-- Total Claims Card -->
        <div class="rounded-xl md:rounded-2xl p-4 md:p-6 text-white shadow-lg transform transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl relative overflow-hidden"
             style="background: linear-gradient(135deg, #f59e0b, #f59e0bdd);">
            <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent pointer-events-none"></div>
            <div class="relative z-10 flex items-center justify-between">
                <div class="flex-1">
                    <div class="text-2xl md:text-3xl font-bold mb-2">{{ $stats['claims_stats']['total_claims'] }}</div>
                    <div class="text-xs md:text-sm opacity-90">{{ t($company->translation_group . '.total_claims', 'إجمالي المطالبات') }}</div>
                </div>
                <div class="text-3xl md:text-4xl opacity-80 ml-3 md:ml-4">
                    <!-- Claims SVG Icon -->
                    <svg class="w-8 h-8 md:w-12 md:h-12" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                        <path d="M8,12V14H16V12H8M8,16V18H13V16H8Z"/>
                    </svg>
                </div>
            </div>
        </div>
        
        <!-- Approved Claims Card -->
        <div class="rounded-xl md:rounded-2xl p-4 md:p-6 text-white shadow-lg transform transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl relative overflow-hidden"
             style="background: linear-gradient(135deg, #10b981, #10b981dd);">
            <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent pointer-events-none"></div>
            <div class="relative z-10 flex items-center justify-between">
                <div class="flex-1">
                    <div class="text-2xl md:text-3xl font-bold mb-2">{{ $stats['claims_stats']['approved_claims'] }}</div>
                    <div class="text-xs md:text-sm opacity-90">{{ t($company->translation_group . '.approved_claims', 'مطالبات مقبولة') }}</div>
                </div>
                <div class="text-3xl md:text-4xl opacity-80 ml-3 md:ml-4">
                    <!-- Check Circle SVG Icon -->
                    <svg class="w-8 h-8 md:w-12 md:h-12" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12,2A10,10 0 0,1 22,12A10,10 0 0,1 12,22A10,10 0 0,1 2,12A10,10 0 0,1 12,2M11,16.5L18,9.5L16.59,8.09L11,13.67L7.91,10.59L6.5,12L11,16.5Z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Complaints Card -->
        <div class="rounded-xl md:rounded-2xl p-4 md:p-6 text-white shadow-lg transform transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl relative overflow-hidden"
             style="background: linear-gradient(135deg, {{ $company->secondary_color ?? '#8b5cf6' }}, {{ $company->secondary_color ?? '#8b5cf6' }}dd);">
            <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent pointer-events-none"></div>
            <div class="relative z-10 flex items-center justify-between">
                <div class="flex-1">
                    <div class="text-2xl md:text-3xl font-bold mb-2">{{ $stats['complaints_stats']['total_complaints'] }}</div>
                    <div class="text-xs md:text-sm opacity-90">{{ t($company->translation_group . '.total_complaints', 'إجمالي الشكاوى') }}</div>
                </div>
                <div class="text-3xl md:text-4xl opacity-80 ml-3 md:ml-4">
                    <!-- Alert SVG Icon -->
                    <svg class="w-8 h-8 md:w-12 md:h-12" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M12,6A1,1 0 0,1 13,7A1,1 0 0,1 12,8A1,1 0 0,1 11,7A1,1 0 0,1 12,6M12,10C12.55,10 13,10.45 13,11V17C13,17.55 12.55,18 12,18C11.45,18 11,17.55 11,17V11C11,10.45 11.45,10 12,10Z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6 mb-6 md:mb-8">
        <!-- Claims Over Time Chart -->
        <div class="bg-white rounded-xl md:rounded-2xl p-4 md:p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-shadow duration-300">
            <h3 class="text-base md:text-lg font-semibold text-gray-800 text-center mb-4 md:mb-6">{{ t($company->translation_group . '.claims_over_time', 'المطالبات على مدار الأشهر') }}</h3>
            <div class="h-64 md:h-80">
                <canvas id="claimsChart" class="w-full h-full"></canvas>
            </div>
        </div>
        
        <!-- Users Registration Chart -->
        <div class="bg-white rounded-xl md:rounded-2xl p-4 md:p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-shadow duration-300">
            <h3 class="text-base md:text-lg font-semibold text-gray-800 text-center mb-4 md:mb-6">{{ t($company->translation_group . '.users_registration', 'تسجيل المستخدمين الجدد') }}</h3>
            <div class="h-64 md:h-80">
                <canvas id="usersChart" class="w-full h-full"></canvas>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 md:gap-8">
        <!-- Company Information -->
        <div class="bg-white rounded-xl md:rounded-2xl p-4 md:p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <h5 class="flex items-center text-lg md:text-xl font-semibold text-gray-800 mb-4 md:mb-6">
                <!-- Building SVG Icon -->
                <svg class="w-6 h-6 ml-2 md:ml-3" fill="{{ $company->primary_color ?? '#10b981' }}" viewBox="0 0 24 24">
                    <path d="M13,11H18L16.5,9.5L17.92,8.08L21.84,12L17.92,15.92L16.5,14.5L18,13H13V11M1,18V6C1,4.89 1.89,4 3,4H15A2,2 0 0,1 17,6V8H15V6H3V18H15V16H17V18A2,2 0 0,1 15,20H3C1.89,20 1,19.11 1,18Z"/>
                </svg>
                {{ t($company->translation_group . '.company_info', 'معلومات الشركة') }}
            </h5>
            
            <div class="space-y-3 md:space-y-4">
                <div class="flex items-center py-2 md:py-3 border-b border-gray-100 last:border-b-0">
                    <div class="flex-1">
                        <div class="text-xs md:text-sm text-gray-500 mb-1">{{ t($company->translation_group . '.member_since', 'عضو منذ') }}</div>
                        <div class="text-sm md:text-base font-semibold text-gray-800">{{ $stats['company_info']['member_since'] }}</div>
                    </div>
                    <div class="bg-gray-50 rounded-full w-8 h-8 md:w-10 md:h-10 flex items-center justify-center text-gray-600 mr-3 md:mr-4">
                        <svg class="w-4 h-4 md:w-5 md:h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19,3H18V1H16V3H8V1H6V3H5A2,2 0 0,0 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5A2,2 0 0,0 19,3M19,19H5V8H19V19Z"/>
                        </svg>
                    </div>
                </div>
                
                <div class="flex items-center py-2 md:py-3 border-b border-gray-100 last:border-b-0">
                    <div class="flex-1">
                        <div class="text-xs md:text-sm text-gray-500 mb-1">{{ t($company->translation_group . '.commercial_register', 'السجل التجاري') }}</div>
                        <div class="text-sm md:text-base font-semibold text-gray-800">{{ $stats['company_info']['commercial_register'] }}</div>
                    </div>
                    <div class="bg-gray-50 rounded-full w-8 h-8 md:w-10 md:h-10 flex items-center justify-center text-gray-600 mr-3 md:mr-4">
                        <svg class="w-4 h-4 md:w-5 md:h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M4,4A2,2 0 0,0 2,6V18A2,2 0 0,0 4,20H20A2,2 0 0,0 22,18V6A2,2 0 0,0 20,4H4M4,6H20V18H4V6M6,8V10H8V8H6M10,8V10H18V8H10M6,12V14H8V12H6M10,12V14H18V12H10M6,16V18H8V16H6M10,16V18H18V16H10Z"/>
                        </svg>
                    </div>
                </div>
                
                <div class="flex items-center py-2 md:py-3 border-b border-gray-100 last:border-b-0">
                    <div class="flex-1">
                        <div class="text-xs md:text-sm text-gray-500 mb-1">{{ t($company->translation_group . '.employee_count', 'عدد الموظفين') }}</div>
                        <div class="text-sm md:text-base font-semibold text-gray-800">{{ number_format($stats['company_info']['employee_count'] ?? 0) }}</div>
                    </div>
                    <div class="bg-gray-50 rounded-full w-8 h-8 md:w-10 md:h-10 flex items-center justify-center text-gray-600 mr-3 md:mr-4">
                        <svg class="w-4 h-4 md:w-5 md:h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12,5.5A3.5,3.5 0 0,1 15.5,9A3.5,3.5 0 0,1 12,12.5A3.5,3.5 0 0,1 8.5,9A3.5,3.5 0 0,1 12,5.5M5,8C5.56,8 6.08,8.15 6.53,8.42C6.38,9.85 6.8,11.27 7.66,12.38C7.16,13.34 6.16,14 5,14A3,3 0 0,1 2,11A3,3 0 0,1 5,8M19,8A3,3 0 0,1 22,11A3,3 0 0,1 19,14C17.84,14 16.84,13.34 16.34,12.38C17.2,11.27 17.62,9.85 17.47,8.42C17.92,8.15 18.44,8 19,8M5.5,18.25C5.5,16.18 8.41,14.5 12,14.5C15.59,14.5 18.5,16.18 18.5,18.25V20H5.5V18.25M0,20V18.5C0,17.11 1.89,15.94 4.45,15.6C3.86,16.28 3.5,17.22 3.5,18.25V20H0M24,20H20.5V18.25C20.5,17.22 20.14,16.28 19.55,15.6C22.11,15.94 24,17.11 24,18.5V20Z"/>
                        </svg>
                    </div>
                </div>
                
                <div class="flex items-center py-2 md:py-3 border-b border-gray-100 last:border-b-0">
                    <div class="flex-1">
                        <div class="text-xs md:text-sm text-gray-500 mb-1">{{ t($company->translation_group . '.insured_cars', 'السيارات المؤمنة') }}</div>
                        <div class="text-sm md:text-base font-semibold text-gray-800">{{ number_format($stats['company_info']['insured_cars_count'] ?? 0) }}</div>
                    </div>
                    <div class="bg-gray-50 rounded-full w-8 h-8 md:w-10 md:h-10 flex items-center justify-center text-gray-600 mr-3 md:mr-4">
                        <svg class="w-4 h-4 md:w-5 md:h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M5,11L6.5,6.5H17.5L19,11M17.5,16A1.5,1.5 0 0,1 16,14.5A1.5,1.5 0 0,1 17.5,13A1.5,1.5 0 0,1 19,14.5A1.5,1.5 0 0,1 17.5,16M6.5,16A1.5,1.5 0 0,1 5,14.5A1.5,1.5 0 0,1 6.5,13A1.5,1.5 0 0,1 8,14.5A1.5,1.5 0 0,1 6.5,16M18.92,6C18.72,5.42 18.16,5 17.5,5H6.5C5.84,5 5.28,5.42 5.08,6L3,12V20A1,1 0 0,0 4,21H5A1,1 0 0,0 6,20V19H18V20A1,1 0 0,0 19,21H20A1,1 0 0,0 21,20V12L18.92,6Z"/>
                        </svg>
                    </div>
                </div>
                
                <div class="flex items-center py-2 md:py-3 border-b border-gray-100 last:border-b-0">
                    <div class="flex-1">
                        <div class="text-xs md:text-sm text-gray-500 mb-1">{{ t($company->translation_group . '.last_login', 'آخر دخول') }}</div>
                        <div class="text-sm md:text-base font-semibold text-gray-800">{{ $stats['company_info']['last_login'] }}</div>
                    </div>
                    <div class="bg-gray-50 rounded-full w-8 h-8 md:w-10 md:h-10 flex items-center justify-center text-gray-600 mr-3 md:mr-4">
                        <svg class="w-4 h-4 md:w-5 md:h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M10,17V14H3V10H10V7L15,12L10,17M10,2H19A2,2 0 0,1 21,4V20A2,2 0 0,1 19,22H10A2,2 0 0,1 8,20V18H10V20H19V4H10V6H8V4A2,2 0 0,1 10,2Z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

     <!-- Quick Actions -->
<div class="bg-white rounded-xl md:rounded-2xl p-4 md:p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
    <h5 class="flex items-center text-lg md:text-xl font-semibold text-gray-800 mb-4 md:mb-6">
        <!-- Bolt SVG Icon -->
        <svg class="w-6 h-6 ml-2 md:ml-3" fill="#f59e0b" viewBox="0 0 24 24">
            <path d="M11,21L12.5,18.5L9.5,16.5L13,3L8,3L3.5,12L6.5,12L5,15.5L8,17.5L11,21M13,21L14.5,18.5L11.5,16.5L15,3L20,3L24.5,12L21.5,12L23,15.5L20,17.5L13,21Z"/>
        </svg>
        {{ t($company->translation_group . '.quick_actions', 'الإجراءات السريعة') }}
    </h5>
    
    <div class="space-y-3">
        <!-- رابط إدارة المستخدمين -->
        <a href="{{ route('insurance.users.index', $company->company_slug) }}"
           class="flex items-center p-3 md:p-4 text-white rounded-lg md:rounded-xl transition-all duration-300 hover:translate-x-1 group cursor-pointer"
           style="background: linear-gradient(135deg, {{ $company->primary_color ?? '#10b981' }}, {{ $company->primary_color ?? '#10b981' }}dd);">
            <div class="flex-1">
                <div class="font-bold text-base md:text-lg">{{ t($company->translation_group . '.manage_users', 'إدارة المستخدمين') }}</div>
                <div class="text-xs md:text-sm text-white/80">{{ t($company->translation_group . '.view_all_users', 'عرض جميع المستخدمين') }}</div>
            </div>
            <div class="text-xl md:text-2xl mr-3 md:mr-4 group-hover:scale-110 transition-transform duration-300">
                <svg class="w-6 h-6 md:w-8 md:h-8" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                </svg>
            </div>
        </a>
        
        <!-- رابط إدارة مراكز الصيانة -->
        <a href="{{ route('insurance.service-centers.index', ['companyRoute' => $company->company_slug]) }}" 
           class="flex items-center p-3 md:p-4 text-white rounded-lg md:rounded-xl transition-all duration-300 hover:translate-x-1 group cursor-pointer"
           style="background: linear-gradient(135deg, {{ $company->primary_color ?? '#10b981' }}, {{ $company->primary_color ?? '#10b981' }}dd);">
            <div class="flex-1">
                <div class="font-bold text-base md:text-lg">{{ t($company->translation_group . '.manage_centers', 'إدارة مراكز الصيانة') }}</div>
                <div class="text-xs md:text-sm text-white/80">{{ t($company->translation_group . '.view_service_centers', 'عرض مراكز الصيانة') }}</div>
            </div>
            <div class="text-xl md:text-2xl mr-3 md:mr-4 group-hover:scale-110 transition-transform duration-300">
                <svg class="w-6 h-6 md:w-8 md:h-8" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
        </a>
        
        <!-- رابط عرض المطالبات -->
        <a href="{{ route('insurance.claims.index', ['companyRoute' => $company->company_slug]) }}" 
           class="flex items-center p-3 md:p-4 text-white rounded-lg md:rounded-xl transition-all duration-300 hover:translate-x-1 group cursor-pointer"
           style="background: linear-gradient(135deg, {{ $company->primary_color ?? '#10b981' }}, {{ $company->primary_color ?? '#10b981' }}dd);">
            <div class="flex-1">
                <div class="font-bold text-sm md:text-base">{{ t($company->translation_group . '.view_claims', 'عرض المطالبات') }}</div>
                <div class="text-xs md:text-sm text-white/80">{{ t($company->translation_group . '.manage_all_claims', 'إدارة جميع المطالبات') }}</div>
            </div>
            <div class="text-lg md:text-xl mr-3 md:mr-4 group-hover:scale-110 transition-transform duration-300">
                <svg class="w-5 h-5 md:w-6 md:h-6" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
        </a>

        <!-- رابط الملف الشخصي - الجديد -->
        <a href="{{ route('insurance.profile.show', ['companyRoute' => $company->company_slug]) }}" 
           class="flex items-center p-3 md:p-4 text-white rounded-lg md:rounded-xl transition-all duration-300 hover:translate-x-1 group cursor-pointer"
           style="background: linear-gradient(135deg, {{ $company->primary_color ?? '#10b981' }}, {{ $company->primary_color ?? '#10b981' }}dd);">
            <div class="flex-1">
                <div class="font-bold text-sm md:text-base">{{ t($company->translation_group . '.my_profile', 'الملف الشخصي') }}</div>
                <div class="text-xs md:text-sm text-white/80">{{ t($company->translation_group . '.view_company_profile', 'عرض ملف الشركة') }}</div>
            </div>
            <div class="text-lg md:text-xl mr-3 md:mr-4 group-hover:scale-110 transition-transform duration-300">
                <svg class="w-5 h-5 md:w-6 md:h-6" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
        </a>

        <!-- رابط الشكاوى والاستفسارات -->
        <a href="{{ route('insurance.complaints.index', ['companyRoute' => $company->company_slug]) }}" 
           class="flex items-center p-3 md:p-4 text-white rounded-lg md:rounded-xl transition-all duration-300 hover:translate-x-1 group cursor-pointer"
           style="background: linear-gradient(135deg, {{ $company->primary_color ?? '#10b981' }}, {{ $company->primary_color ?? '#10b981' }}dd);">
            <div class="flex-1">
                <div class="font-bold text-sm md:text-base">{{ t($company->translation_group . '.complaints_inquiries', 'الشكاوى والاستفسارات') }}</div>
                <div class="text-xs md:text-sm text-white/80">{{ t($company->translation_group . '.manage_complaints', 'إدارة الشكاوى') }}</div>
            </div>
            <div class="text-lg md:text-xl mr-3 md:mr-4 group-hover:scale-110 transition-transform duration-300">
                <svg class="w-5 h-5 md:w-6 md:h-6" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
            </div>
        </a>
    </div>
</div>

    </div>
</div>

<!-- تحميل Chart.js قبل السكريبت -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // استخدام الألوان من قاعدة البيانات
    const primaryColor = '{{ $company->primary_color ?? "#10b981" }}';
    const secondaryColor = '{{ $company->secondary_color ?? "#059669" }}';

    // Claims over time chart
    const claimsCtx = document.getElementById('claimsChart');
    if (claimsCtx) {
        const claimsChart = new Chart(claimsCtx.getContext('2d'), {
            type: 'line',
            data: {
                labels: [
                    @foreach($stats['claims_stats']['by_month'] as $month)
                        '{{ $month['month'] }}',
                    @endforeach
                ],
                datasets: [{
                    label: '{{ t($company->translation_group . ".number_of_claims", "عدد المطالبات") }}',
                    data: [
                        @foreach($stats['claims_stats']['by_month'] as $month)
                            {{ $month['count'] }},
                        @endforeach
                    ],
                    borderColor: primaryColor,
                    backgroundColor: primaryColor + '20',
                    tension: 0.4,
                    fill: true,
                    borderWidth: 3,
                    pointBackgroundColor: primaryColor,
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                            color: '#6B7280'
                        },
                        grid: {
                            color: '#F3F4F6'
                        }
                    },
                    x: {
                        ticks: {
                            color: '#6B7280'
                        },
                        grid: {
                            color: '#F3F4F6'
                        }
                    }
                }
            }
        });
    }

    // Users registration chart
    const usersCtx = document.getElementById('usersChart');
    if (usersCtx) {
        const usersChart = new Chart(usersCtx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: [
                    @foreach($stats['users_stats']['by_month'] as $month)
                        '{{ $month['month'] }}',
                    @endforeach
                ],
                datasets: [{
                    label: '{{ t($company->translation_group . ".new_users", "مستخدمين جدد") }}',
                    data: [
                        @foreach($stats['users_stats']['by_month'] as $month)
                            {{ $month['count'] }},
                        @endforeach
                    ],
                    backgroundColor: secondaryColor + '80',
                    borderColor: secondaryColor,
                    borderWidth: 2,
                    borderRadius: 8,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                            color: '#6B7280'
                        },
                        grid: {
                            color: '#F3F4F6'
                        }
                    },
                    x: {
                        ticks: {
                            color: '#6B7280'
                        },
                        grid: {
                            color: '#F3F4F6'
                        }
                    }
                }
            }
        });
    }
});
</script>
@endsection
