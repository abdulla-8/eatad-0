@extends('service-center.layouts.app')
@section('title', t('service_center.dashboard'))

@section('content')
<div class="container mx-auto px-4 py-6 rtl:text-right ltr:text-left" dir="rtl">
    <!-- Welcome Section -->
    <div class="rounded-2xl p-6 md:p-8 text-white text-center mb-6 md:mb-8 shadow-xl" 
         style="background: linear-gradient(135deg, {{ $serviceCenter->primary_color ?? '#10b981' }}, {{ $serviceCenter->secondary_color ?? '#059669' }});">
        <h2 class="text-2xl md:text-3xl font-light mb-3">{{ t('service_center.welcome_back', 'أهلاً وسهلاً') }}، {{ $stats['center_info']['legal_name'] }}</h2>
        <p class="text-white/80 text-base md:text-lg">{{ t('service_center.dashboard_subtitle', 'إدارة مركز الصيانة بكل سهولة') }}</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 md:gap-6 mb-6 md:mb-8">
        <!-- Total Claims Card -->
        <div class="rounded-xl md:rounded-2xl p-4 md:p-6 text-white shadow-lg transform transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl relative overflow-hidden"
             style="background: linear-gradient(135deg, {{ $serviceCenter->primary_color ?? '#10b981' }}, {{ $serviceCenter->primary_color ?? '#10b981' }}dd);">
            <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent pointer-events-none"></div>
            <div class="relative z-10 flex items-center justify-between">
                <div class="flex-1">
                    <div class="text-2xl md:text-3xl font-bold mb-2">{{ $stats['claims_stats']['total_claims'] }}</div>
                    <div class="text-xs md:text-sm opacity-90">{{ t('service_center.total_claims', 'إجمالي المطالبات') }}</div>
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
        
        <!-- Pending Claims Card -->
        <div class="rounded-xl md:rounded-2xl p-4 md:p-6 text-white shadow-lg transform transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl relative overflow-hidden"
             style="background: linear-gradient(135deg, #f59e0b, #f59e0bdd);">
            <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent pointer-events-none"></div>
            <div class="relative z-10 flex items-center justify-between">
                <div class="flex-1">
                    <div class="text-2xl md:text-3xl font-bold mb-2">{{ $stats['claims_stats']['pending_claims'] }}</div>
                    <div class="text-xs md:text-sm opacity-90">{{ t('service_center.pending_claims', 'مطالبات معلقة') }}</div>
                </div>
                <div class="text-3xl md:text-4xl opacity-80 ml-3 md:ml-4">
                    <!-- Clock SVG Icon -->
                    <svg class="w-8 h-8 md:w-12 md:h-12" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M16.2,16.2L11,13V7H12.5V12.2L17,14.7L16.2,16.2Z"/>
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
                    <div class="text-xs md:text-sm opacity-90">{{ t('service_center.approved_claims', 'مطالبات مقبولة') }}</div>
                </div>
                <div class="text-3xl md:text-4xl opacity-80 ml-3 md:ml-4">
                    <!-- Check Circle SVG Icon -->
                    <svg class="w-8 h-8 md:w-12 md:h-12" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12,2A10,10 0 0,1 22,12A10,10 0 0,1 12,22A10,10 0 0,1 2,12A10,10 0 0,1 12,2M11,16.5L18,9.5L16.59,8.09L11,13.67L7.91,10.59L6.5,12L11,16.5Z"/>
                    </svg>
                </div>
            </div>
        </div>
        
        <!-- Total Technicians Card -->
        <div class="rounded-xl md:rounded-2xl p-4 md:p-6 text-white shadow-lg transform transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl relative overflow-hidden"
             style="background: linear-gradient(135deg, #6366f1, #6366f1dd);">
            <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent pointer-events-none"></div>
            <div class="relative z-10 flex items-center justify-between">
                <div class="flex-1">
                    <div class="text-2xl md:text-3xl font-bold mb-2">{{ $stats['technicians_stats']['total_technicians'] }}</div>
                    <div class="text-xs md:text-sm opacity-90">{{ t('service_center.total_technicians', 'إجمالي الفنيين') }}</div>
                </div>
                <div class="text-3xl md:text-4xl opacity-80 ml-3 md:ml-4">
                    <!-- Technician SVG Icon -->
                    <svg class="w-8 h-8 md:w-12 md:h-12" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12,15C7.58,15 4,16.79 4,19V21H20V19C20,16.79 16.42,15 12,15M8,9A4,4 0 0,0 12,13A4,4 0 0,0 16,9M11.5,2C11.2,2 11,2.2 11,2.5V5.5C11,5.8 11.2,6 11.5,6C11.8,6 12,5.8 12,5.5V2.5C12,2.2 11.8,2 11.5,2M18.5,6C18.8,6 19,5.8 19,5.5V2.5C19,2.2 18.8,2 18.5,2C18.2,2 18,2.2 18,2.5V5.5C18,5.8 18.2,6 18.5,6Z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Complaints Card -->
        <div class="rounded-xl md:rounded-2xl p-4 md:p-6 text-white shadow-lg transform transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl relative overflow-hidden"
             style="background: linear-gradient(135deg, {{ $serviceCenter->secondary_color ?? '#8b5cf6' }}, {{ $serviceCenter->secondary_color ?? '#8b5cf6' }}dd);">
            <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent pointer-events-none"></div>
            <div class="relative z-10 flex items-center justify-between">
                <div class="flex-1">
                    <div class="text-2xl md:text-3xl font-bold mb-2">{{ $stats['complaints_stats']['total_complaints'] }}</div>
                    <div class="text-xs md:text-sm opacity-90">{{ t('service_center.total_complaints', 'إجمالي الشكاوى') }}</div>
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
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6 mb-6 md:mb-8">
        <!-- Claims Over Time Chart -->
        <div class="lg:col-span-2 bg-white rounded-xl md:rounded-2xl p-4 md:p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-shadow duration-300">
            <h3 class="text-base md:text-lg font-semibold text-gray-800 text-center mb-4 md:mb-6">{{ t('service_center.claims_over_time', 'المطالبات على مدار الأشهر') }}</h3>
            <div class="h-64 md:h-80">
                <canvas id="claimsChart" class="w-full h-full"></canvas>
            </div>
        </div>
        
        <!-- Technicians Breakdown Chart -->
        <div class="bg-white rounded-xl md:rounded-2xl p-4 md:p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-shadow duration-300">
            <h3 class="text-base md:text-lg font-semibold text-gray-800 text-center mb-4 md:mb-6">{{ t('service_center.technicians_breakdown', 'توزيع الفنيين') }}</h3>
            <div class="h-64 md:h-80">
                <canvas id="techniciansChart" class="w-full h-full"></canvas>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 md:gap-8">
        <!-- Center Information -->
        <div class="bg-white rounded-xl md:rounded-2xl p-4 md:p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <h5 class="flex items-center text-lg md:text-xl font-semibold text-gray-800 mb-4 md:mb-6">
                <!-- Building SVG Icon -->
                <svg class="w-6 h-6 ml-2 md:ml-3" fill="{{ $serviceCenter->primary_color ?? '#10b981' }}" viewBox="0 0 24 24">
                    <path d="M13,11H18L16.5,9.5L17.92,8.08L21.84,12L17.92,15.92L16.5,14.5L18,13H13V11M1,18V6C1,4.89 1.89,4 3,4H15A2,2 0 0,1 17,6V8H15V6H3V18H15V16H17V18A2,2 0 0,1 15,20H3C1.89,20 1,19.11 1,18Z"/>
                </svg>
                {{ t('service_center.center_info', 'معلومات المركز') }}
            </h5>
            
            <div class="space-y-3 md:space-y-4">
                <div class="flex items-center py-2 md:py-3 border-b border-gray-100 last:border-b-0">
                    <div class="flex-1">
                        <div class="text-xs md:text-sm text-gray-500 mb-1">{{ t('service_center.member_since', 'عضو منذ') }}</div>
                        <div class="text-sm md:text-base font-semibold text-gray-800">{{ $stats['center_info']['member_since'] }}</div>
                    </div>
                    <div class="bg-gray-50 rounded-full w-8 h-8 md:w-10 md:h-10 flex items-center justify-center text-gray-600 mr-3 md:mr-4">
                        <svg class="w-4 h-4 md:w-5 md:h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19,3H18V1H16V3H8V1H6V3H5A2,2 0 0,0 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5A2,2 0 0,0 19,3M19,19H5V8H19V19Z"/>
                        </svg>
                    </div>
                </div>
                
                <div class="flex items-center py-2 md:py-3 border-b border-gray-100 last:border-b-0">
                    <div class="flex-1">
                        <div class="text-xs md:text-sm text-gray-500 mb-1">{{ t('service_center.commercial_register', 'السجل التجاري') }}</div>
                        <div class="text-sm md:text-base font-semibold text-gray-800">{{ $stats['center_info']['commercial_register'] }}</div>
                    </div>
                    <div class="bg-gray-50 rounded-full w-8 h-8 md:w-10 md:h-10 flex items-center justify-center text-gray-600 mr-3 md:mr-4">
                        <svg class="w-4 h-4 md:w-5 md:h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M4,4A2,2 0 0,0 2,6V18A2,2 0 0,0 4,20H20A2,2 0 0,0 22,18V6A2,2 0 0,0 20,4H4M4,6H20V18H4V6M6,8V10H8V8H6M10,8V10H18V8H10M6,12V14H8V12H6M10,12V14H18V12H10M6,16V18H8V16H6M10,16V18H18V16H10Z"/>
                        </svg>
                    </div>
                </div>
                
                <div class="flex items-center py-2 md:py-3 border-b border-gray-100 last:border-b-0">
                    <div class="flex-1">
                        <div class="text-xs md:text-sm text-gray-500 mb-1">{{ t('service_center.phone', 'رقم الهاتف') }}</div>
                        <div class="text-sm md:text-base font-semibold text-gray-800">{{ $stats['center_info']['phone'] }}</div>
                    </div>
                    <div class="bg-gray-50 rounded-full w-8 h-8 md:w-10 md:h-10 flex items-center justify-center text-gray-600 mr-3 md:mr-4">
                        <svg class="w-4 h-4 md:w-5 md:h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M6.62,10.79C8.06,13.62 10.38,15.94 13.21,17.38L15.41,15.18C15.69,14.9 16.08,14.82 16.43,14.93C17.55,15.3 18.75,15.5 20,15.5A1,1 0 0,1 21,16.5V20A1,1 0 0,1 20,21A17,17 0 0,1 3,4A1,1 0 0,1 4,3H7.5A1,1 0 0,1 8.5,4C8.5,5.25 8.7,6.45 9.07,7.57C9.18,7.92 9.1,8.31 8.82,8.59L6.62,10.79Z"/>
                        </svg>
                    </div>
                </div>
                
                @if(isset($stats['insurance_company']) && $stats['insurance_company'])
                <div class="flex items-center py-2 md:py-3 border-b border-gray-100 last:border-b-0">
                    <div class="flex-1">
                        <div class="text-xs md:text-sm text-gray-500 mb-1">{{ t('service_center.insurance_company', 'شركة التأمين') }}</div>
                        <div class="text-sm md:text-base font-semibold text-gray-800">{{ $stats['insurance_company']['name'] }}</div>
                    </div>
                    <div class="bg-gray-50 rounded-full w-8 h-8 md:w-10 md:h-10 flex items-center justify-center text-gray-600 mr-3 md:mr-4">
                        <svg class="w-4 h-4 md:w-5 md:h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M13,11H18L16.5,9.5L17.92,8.08L21.84,12L17.92,15.92L16.5,14.5L18,13H13V11M1,18V6C1,4.89 1.89,4 3,4H15A2,2 0 0,1 17,6V8H15V6H3V18H15V16H17V18A2,2 0 0,1 15,20H3C1.89,20 1,19.11 1,18Z"/>
                        </svg>
                    </div>
                </div>
                @endif
                
                <div class="flex items-center py-2 md:py-3 border-b border-gray-100 last:border-b-0">
                    <div class="flex-1">
                        <div class="text-xs md:text-sm text-gray-500 mb-1">{{ t('service_center.specialization', 'التخصص') }}</div>
                        <div class="text-sm md:text-base font-semibold text-gray-800">{{ $stats['center_info']['specialization'] ?? 'غير محدد' }}</div>
                    </div>
                    <div class="bg-gray-50 rounded-full w-8 h-8 md:w-10 md:h-10 flex items-center justify-center text-gray-600 mr-3 md:mr-4">
                        <svg class="w-4 h-4 md:w-5 md:h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12,15.5A3.5,3.5 0 0,1 8.5,12A3.5,3.5 0 0,1 12,8.5A3.5,3.5 0 0,1 15.5,12A3.5,3.5 0 0,1 12,15.5M19.43,12.97C19.47,12.65 19.5,12.33 19.5,12C19.5,11.67 19.47,11.34 19.43,11L21.54,9.37C21.73,9.22 21.78,8.95 21.66,8.73L19.66,5.27C19.54,5.05 19.27,4.96 19.05,5.05L16.56,6.05C16.04,5.66 15.5,5.32 14.87,5.07L14.5,2.42C14.46,2.18 14.25,2 14,2H10C9.75,2 9.54,2.18 9.5,2.42L9.13,5.07C8.5,5.32 7.96,5.66 7.44,6.05L4.95,5.05C4.73,4.96 4.46,5.05 4.34,5.27L2.34,8.73C2.22,8.95 2.27,9.22 2.46,9.37L4.57,11C4.53,11.34 4.5,11.67 4.5,12C4.5,12.33 4.53,12.65 4.57,12.97L2.46,14.63C2.27,14.78 2.22,15.05 2.34,15.27L4.34,18.73C4.46,18.95 4.73,19.03 4.95,18.95L7.44,17.94C7.96,18.34 8.5,18.68 9.13,18.93L9.5,21.58C9.54,21.82 9.75,22 10,22H14C14.25,22 14.46,21.82 14.5,21.58L14.87,18.93C15.5,18.67 16.04,18.34 16.56,17.94L19.05,18.95C19.27,19.03 19.54,18.95 19.66,18.73L21.66,15.27C21.78,15.05 21.73,14.78 21.54,14.63L19.43,12.97Z"/>
                        </svg>
                    </div>
                </div>
                
                <div class="flex items-center py-2 md:py-3 border-b border-gray-100 last:border-b-0">
                    <div class="flex-1">
                        <div class="text-xs md:text-sm text-gray-500 mb-1">{{ t('service_center.last_login', 'آخر دخول') }}</div>
                        <div class="text-sm md:text-base font-semibold text-gray-800">{{ $stats['center_info']['last_login'] }}</div>
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
        {{ t('service_center.quick_actions', 'الإجراءات السريعة') }}
    </h5>
    
    <div class="space-y-3">
        <!-- رابط عرض المطالبات -->
        <a href="{{ route('service-center.claims.index') }}"
           class="flex items-center p-3 md:p-4 text-white rounded-lg md:rounded-xl transition-all duration-300 hover:translate-x-1 group cursor-pointer"
           style="background: linear-gradient(135deg, {{ $serviceCenter->primary_color ?? '#10b981' }}, {{ $serviceCenter->primary_color ?? '#10b981' }}dd);">
            <div class="flex-1">
                <div class="font-bold text-base md:text-lg">{{ t('service_center.view_claims', 'عرض المطالبات') }}</div>
                <div class="text-xs md:text-sm text-white/80">{{ t('service_center.manage_claims', 'إدارة المطالبات') }}</div>
            </div>
            <div class="text-xl md:text-2xl mr-3 md:mr-4 group-hover:scale-110 transition-transform duration-300">
                <svg class="w-6 h-6 md:w-8 md:h-8" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                </svg>
            </div>
        </a>
        
        <!-- رابط عرض الشكاوى -->
        <a href="{{ route('service-center.complaints.index') }}" 
           class="flex items-center p-3 md:p-4 text-white rounded-lg md:rounded-xl transition-all duration-300 hover:translate-x-1 group cursor-pointer"
           style="background: linear-gradient(135deg, {{ $serviceCenter->primary_color ?? '#10b981' }}, {{ $serviceCenter->primary_color ?? '#10b981' }}dd);">
            <div class="flex-1">
                <div class="font-bold text-base md:text-lg">{{ t('service_center.view_complaints', 'عرض الشكاوى') }}</div>
                <div class="text-xs md:text-sm text-white/80">{{ t('service_center.manage_complaints', 'إدارة الشكاوى') }}</div>
            </div>
            <div class="text-xl md:text-2xl mr-3 md:mr-4 group-hover:scale-110 transition-transform duration-300">
                <svg class="w-6 h-6 md:w-8 md:h-8" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M12,6A1,1 0 0,1 13,7A1,1 0 0,1 12,8A1,1 0 0,1 11,7A1,1 0 0,1 12,6M12,10C12.55,10 13,10.45 13,11V17C13,17.55 12.55,18 12,18C11.45,18 11,17.55 11,17V11C11,10.45 11.45,10 12,10Z"/>
                </svg>
            </div>
        </a>
        
        <!-- رابط الملف الشخصي -->
        <a href="{{ route('service-center.profile.show') }}" 
           class="flex items-center p-3 md:p-4 text-white rounded-lg md:rounded-xl transition-all duration-300 hover:translate-x-1 group cursor-pointer"
           style="background: linear-gradient(135deg, {{ $serviceCenter->primary_color ?? '#10b981' }}, {{ $serviceCenter->primary_color ?? '#10b981' }}dd);">
            <div class="flex-1">
                <div class="font-bold text-sm md:text-base">{{ t('service_center.view_profile', 'عرض الملف الشخصي') }}</div>
                <div class="text-xs md:text-sm text-white/80">{{ t('service_center.view_center_info', 'عرض معلومات المركز') }}</div>
            </div>
            <div class="text-lg md:text-xl mr-3 md:mr-4 group-hover:scale-110 transition-transform duration-300">
                <svg class="w-5 h-5 md:w-6 md:h-6" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12,4A4,4 0 0,1 16,8A4,4 0 0,1 12,12A4,4 0 0,1 8,8A4,4 0 0,1 12,4M12,14C16.42,14 20,15.79 20,18V20H4V18C4,15.79 7.58,14 12,14Z"/>
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
    const primaryColor = '{{ $serviceCenter->primary_color ?? "#10b981" }}';
    const secondaryColor = '{{ $serviceCenter->secondary_color ?? "#059669" }}';

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
                    label: '{{ t("service_center.number_of_claims", "عدد المطالبات") }}',
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

    // Technicians breakdown chart
    const techniciansCtx = document.getElementById('techniciansChart');
    if (techniciansCtx) {
        const techniciansChart = new Chart(techniciansCtx.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: [
                    '{{ t("service_center.body_work", "هيكل ومعدن") }}',
                    '{{ t("service_center.mechanical", "ميكانيكي") }}',
                    '{{ t("service_center.painting", "دهانات") }}',
                    '{{ t("service_center.electrical", "كهربائي") }}',
                    '{{ t("service_center.other", "أخرى") }}'
                ],
                datasets: [{
                    data: [
                        {{ $stats['technicians_stats']['technicians_data']['body_work'] }},
                        {{ $stats['technicians_stats']['technicians_data']['mechanical'] }},
                        {{ $stats['technicians_stats']['technicians_data']['painting'] }},
                        {{ $stats['technicians_stats']['technicians_data']['electrical'] }},
                        {{ $stats['technicians_stats']['technicians_data']['other'] }}
                    ],
                    backgroundColor: [
                        primaryColor,
                        secondaryColor,
                        '#f59e0b',
                        '#6366f1',
                        '#8b5cf6'
                    ],
                    borderWidth: 0,
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            usePointStyle: true,
                            color: '#6B7280',
                            font: {
                                size: 11
                            }
                        }
                    }
                }
            }
        });
    }
});
</script>
@endsection
