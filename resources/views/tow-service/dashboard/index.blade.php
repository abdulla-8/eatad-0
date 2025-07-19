@extends('tow-service.layouts.app')
@section('title', t('tow.dashboard'))

@section('content')
<div class="container mx-auto px-4 py-6 rtl:text-right ltr:text-left" dir="rtl">
    <!-- Welcome Section -->
    <div class="rounded-2xl p-6 md:p-8 text-white text-center mb-6 md:mb-8 shadow-xl" 
         style="background: linear-gradient(135deg, #FBBF24, #F59E0B);">
        <h2 class="text-2xl md:text-3xl font-light mb-3">{{ t('tow.welcome_back', 'أهلاً وسهلاً') }}، {{ $stats['user_info']['display_name'] }}</h2>
        <p class="text-white/80 text-base md:text-lg">
            {{ $userType == 'company' ? t('tow.company_dashboard_subtitle', 'إدارة خدمات السحب للشركة') : t('tow.individual_dashboard_subtitle', 'إدارة خدمات السحب الشخصية') }}
        </p>
        <div class="mt-4 inline-flex items-center px-3 py-1 rounded-full text-sm bg-white/20">
            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12,4A4,4 0 0,1 16,8A4,4 0 0,1 12,12A4,4 0 0,1 8,8A4,4 0 0,1 12,4M12,14C16.42,14 20,15.79 20,18V20H4V18C4,15.79 7.58,14 12,14Z"/>
            </svg>
            {{ $userType == 'company' ? t('tow.company_account', 'حساب شركة') : t('tow.individual_account', 'حساب فردي') }}
        </div>
    </div>

    <!-- Account Status Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6 mb-6 md:mb-8">
        <!-- Account Status Card -->
        <div class="rounded-xl md:rounded-2xl p-4 md:p-6 text-white shadow-lg transform transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl relative overflow-hidden"
             style="background: linear-gradient(135deg, {{ $stats['account_status'] == 'approved' ? '#FBBF24' : '#F59E0B' }}, {{ $stats['account_status'] == 'approved' ? '#F59E0B' : '#D97706' }});">
            <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent pointer-events-none"></div>
            <div class="relative z-10 flex items-center justify-between">
                <div class="flex-1">
                    <div class="text-lg md:text-xl font-bold mb-2">{{ $stats['account_status'] == 'approved' ? t('tow.approved', 'معتمد') : t('tow.pending_approval', 'قيد المراجعة') }}</div>
                    <div class="text-xs md:text-sm opacity-90">{{ t('tow.account_status', 'حالة الحساب') }}</div>
                </div>
                <div class="text-2xl md:text-3xl opacity-80 ml-3 md:ml-4">
                    @if($stats['account_status'] == 'approved')
                        <svg class="w-8 h-8 md:w-10 md:h-10" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12,2A10,10 0 0,1 22,12A10,10 0 0,1 12,22A10,10 0 0,1 2,12A10,10 0 0,1 12,2M11,16.5L18,9.5L16.59,8.09L11,13.67L7.91,10.59L6.5,12L11,16.5Z"/>
                        </svg>
                    @else
                        <svg class="w-8 h-8 md:w-10 md:h-10" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M16.2,16.2L11,13V7H12.5V12.2L17,14.7L16.2,16.2Z"/>
                        </svg>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Profile Completion Card -->
        <div class="rounded-xl md:rounded-2xl p-4 md:p-6 text-white shadow-lg transform transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl relative overflow-hidden"
             style="background: linear-gradient(135deg, #FDE047, #FBBF24);">
            <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent pointer-events-none"></div>
            <div class="relative z-10 flex items-center justify-between">
                <div class="flex-1">
                    <div class="text-lg md:text-xl font-bold mb-2">{{ $stats['profile_completion'] }}%</div>
                    <div class="text-xs md:text-sm opacity-90">{{ t('tow.profile_completion', 'اكتمال الملف') }}</div>
                </div>
                <div class="text-2xl md:text-3xl opacity-80 ml-3 md:ml-4">
                    <svg class="w-8 h-8 md:w-10 md:h-10" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12,4A4,4 0 0,1 16,8A4,4 0 0,1 12,12A4,4 0 0,1 8,8A4,4 0 0,1 12,4M12,14C16.42,14 20,15.79 20,18V20H4V18C4,15.79 7.58,14 12,14Z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Active Status Card -->
        <div class="rounded-xl md:rounded-2xl p-4 md:p-6 text-white shadow-lg transform transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl relative overflow-hidden"
             style="background: linear-gradient(135deg, {{ $stats['is_active'] ? '#FBBF24' : '#EF4444' }}, {{ $stats['is_active'] ? '#F59E0B' : '#DC2626' }});">
            <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent pointer-events-none"></div>
            <div class="relative z-10 flex items-center justify-between">
                <div class="flex-1">
                    <div class="text-lg md:text-xl font-bold mb-2">{{ $stats['is_active'] ? t('tow.active', 'نشط') : t('tow.inactive', 'غير نشط') }}</div>
                    <div class="text-xs md:text-sm opacity-90">{{ t('tow.activity_status', 'حالة النشاط') }}</div>
                </div>
                <div class="text-2xl md:text-3xl opacity-80 ml-3 md:ml-4">
                    @if($stats['is_active'])
                        <svg class="w-8 h-8 md:w-10 md:h-10" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9.5,3A6.5,6.5 0 0,1 16,9.5C16,11.11 15.41,12.59 14.44,13.73L14.71,14H15.5L20.5,19L19,20.5L14,15.5V14.71L13.73,14.44C12.59,15.41 11.11,16 9.5,16A6.5,6.5 0 0,1 3,9.5A6.5,6.5 0 0,1 9.5,3M9.5,5C7,5 5,7 5,9.5C5,12 7,14 9.5,14C12,14 14,12 14,9.5C14,7 12,5 9.5,5Z"/>
                        </svg>
                    @else
                        <svg class="w-8 h-8 md:w-10 md:h-10" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M9,9H15V15H9V9Z"/>
                        </svg>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 md:gap-8">
        <!-- Provider Information -->
        <div class="bg-white rounded-xl md:rounded-2xl p-4 md:p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <h5 class="flex items-center text-lg md:text-xl font-semibold text-gray-800 mb-4 md:mb-6">
                <svg class="w-6 h-6 ml-2 md:ml-3" fill="#FBBF24" viewBox="0 0 24 24">
                    @if($userType == 'company')
                        <path d="M13,11H18L16.5,9.5L17.92,8.08L21.84,12L17.92,15.92L16.5,14.5L18,13H13V11M1,18V6C1,4.89 1.89,4 3,4H15A2,2 0 0,1 17,6V8H15V6H3V18H15V16H17V18A2,2 0 0,1 15,20H3C1.89,20 1,19.11 1,18Z"/>
                    @else
                        <path d="M12,4A4,4 0 0,1 16,8A4,4 0 0,1 12,12A4,4 0 0,1 8,8A4,4 0 0,1 12,4M12,14C16.42,14 20,15.79 20,18V20H4V18C4,15.79 7.58,14 12,14Z"/>
                    @endif
                </svg>
                {{ $userType == 'company' ? t('tow.company_info', 'معلومات الشركة') : t('tow.provider_info', 'معلومات مقدم الخدمة') }}
            </h5>
            
            <div class="space-y-3 md:space-y-4">
                <!-- عرض البيانات حسب النوع -->
                @if($userType == 'company')
                    <!-- بيانات الشركة -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="p-3 rounded-lg" style="background: #FFFBEB;">
                            <div class="text-xs mb-1" style="color: #D97706;">{{ t('tow.legal_name', 'الاسم القانوني') }}</div>
                            <div class="font-semibold text-gray-800">{{ $stats['user_info']['display_name'] }}</div>
                        </div>
                        
                        <div class="p-3 rounded-lg" style="background: #FFFBEB;">
                            <div class="text-xs mb-1" style="color: #D97706;">{{ t('tow.commercial_register', 'السجل التجاري') }}</div>
                            <div class="font-semibold text-gray-800">{{ $stats['user_info']['commercial_register'] ?? 'غير محدد' }}</div>
                        </div>
                        
                        <div class="p-3 rounded-lg" style="background: #FFFBEB;">
                            <div class="text-xs mb-1" style="color: #D97706;">{{ t('tow.phone', 'رقم الهاتف') }}</div>
                            <div class="font-semibold text-gray-800">{{ $stats['user_info']['phone'] }}</div>
                        </div>
                        
                        <div class="p-3 rounded-lg" style="background: #FFFBEB;">
                            <div class="text-xs mb-1" style="color: #D97706;">{{ t('tow.daily_capacity', 'الطاقة اليومية') }}</div>
                            <div class="font-semibold text-gray-800">{{ $stats['user_info']['daily_capacity'] }} {{ t('tow.vehicles', 'مركبة') }}</div>
                        </div>
                        
                        @if($stats['user_info']['tax_number'])
                        <div class="p-3 rounded-lg" style="background: #FFFBEB;">
                            <div class="text-xs mb-1" style="color: #D97706;">{{ t('tow.tax_number', 'الرقم الضريبي') }}</div>
                            <div class="font-semibold text-gray-800">{{ $stats['user_info']['tax_number'] }}</div>
                        </div>
                        @endif
                        
                        @if($stats['user_info']['delegate_number'])
                        <div class="p-3 rounded-lg" style="background: #FFFBEB;">
                            <div class="text-xs mb-1" style="color: #D97706;">{{ t('tow.delegate_number', 'رقم المندوب') }}</div>
                            <div class="font-semibold text-gray-800">{{ $stats['user_info']['delegate_number'] }}</div>
                        </div>
                        @endif
                    </div>
                    
                    @if($stats['user_info']['office_address'])
                    <div class="p-3 rounded-lg" style="background: #FFFBEB;">
                        <div class="text-xs mb-1" style="color: #D97706;">{{ t('tow.office_address', 'عنوان المكتب') }}</div>
                        <div class="font-semibold text-gray-800">{{ $stats['user_info']['office_address'] }}</div>
                    </div>
                    @endif
                    
                @else
                    <!-- بيانات الفرد -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="p-3 rounded-lg" style="background: #FFFBEB;">
                            <div class="text-xs mb-1" style="color: #D97706;">{{ t('tow.full_name', 'الاسم الكامل') }}</div>
                            <div class="font-semibold text-gray-800">{{ $stats['user_info']['display_name'] }}</div>
                        </div>
                        
                        <div class="p-3 rounded-lg" style="background: #FFFBEB;">
                            <div class="text-xs mb-1" style="color: #D97706;">{{ t('tow.national_id', 'الهوية الوطنية') }}</div>
                            <div class="font-semibold text-gray-800">{{ $stats['user_info']['national_id'] ?? 'غير محدد' }}</div>
                        </div>
                        
                        <div class="p-3 rounded-lg" style="background: #FFFBEB;">
                            <div class="text-xs mb-1" style="color: #D97706;">{{ t('tow.phone', 'رقم الهاتف') }}</div>
                            <div class="font-semibold text-gray-800">{{ $stats['user_info']['phone'] }}</div>
                        </div>
                        
                        <div class="p-3 rounded-lg" style="background: #FFFBEB;">
                            <div class="text-xs mb-1" style="color: #D97706;">{{ t('tow.plate_number', 'رقم اللوحة') }}</div>
                            <div class="font-semibold text-gray-800">{{ $stats['user_info']['plate_number'] ?? 'غير محدد' }}</div>
                        </div>
                    </div>
                    
                    @if($stats['user_info']['address'])
                    <div class="p-3 rounded-lg" style="background: #FFFBEB;">
                        <div class="text-xs mb-1" style="color: #D97706;">{{ t('tow.address', 'العنوان') }}</div>
                        <div class="font-semibold text-gray-800">{{ $stats['user_info']['address'] }}</div>
                    </div>
                    @endif
                    
                    <!-- حالة المستندات -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-3 rounded-lg {{ $stats['user_info']['has_truck_form'] ? 'text-green-800' : 'text-red-800' }}"
                             style="background: {{ $stats['user_info']['has_truck_form'] ? '#FFFBEB' : '#FEE2E2' }};">
                            <div class="text-xs mb-1">{{ t('tow.truck_form', 'استمارة السطحة') }}</div>
                            <div class="font-semibold">{{ $stats['user_info']['has_truck_form'] ? t('tow.uploaded', 'مرفوعة') : t('tow.not_uploaded', 'غير مرفوعة') }}</div>
                        </div>
                        
                        <div class="p-3 rounded-lg {{ $stats['user_info']['has_profile_image'] ? 'text-green-800' : 'text-red-800' }}"
                             style="background: {{ $stats['user_info']['has_profile_image'] ? '#FFFBEB' : '#FEE2E2' }};">
                            <div class="text-xs mb-1">{{ t('tow.profile_image', 'صورة الملف') }}</div>
                            <div class="font-semibold">{{ $stats['user_info']['has_profile_image'] ? t('tow.uploaded', 'مرفوعة') : t('tow.not_uploaded', 'غير مرفوعة') }}</div>
                        </div>
                    </div>
                @endif
                
                <!-- معلومات العضوية -->
                <div class="border-t pt-4 mt-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-3 rounded-lg" style="background: #FFFBEB;">
                            <div class="text-xs mb-1" style="color: #D97706;">{{ t('tow.member_since', 'عضو منذ') }}</div>
                            <div class="font-semibold" style="color: #D97706;">{{ $stats['user_info']['member_since'] }}</div>
                        </div>
                        
                        <div class="p-3 rounded-lg" style="background: #FFFBEB;">
                            <div class="text-xs mb-1" style="color: #D97706;">{{ t('tow.last_login', 'آخر دخول') }}</div>
                            <div class="font-semibold" style="color: #D97706;">{{ $stats['user_info']['last_login'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-xl md:rounded-2xl p-4 md:p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <h5 class="flex items-center text-lg md:text-xl font-semibold text-gray-800 mb-4 md:mb-6">
                <svg class="w-6 h-6 ml-2 md:ml-3" fill="#FBBF24" viewBox="0 0 24 24">
                    <path d="M11,21L12.5,18.5L9.5,16.5L13,3L8,3L3.5,12L6.5,12L5,15.5L8,17.5L11,21M13,21L14.5,18.5L11.5,16.5L15,3L20,3L24.5,12L21.5,12L23,15.5L20,17.5L13,21Z"/>
                </svg>
                {{ t('tow.quick_actions', 'الإجراءات السريعة') }}
            </h5>
            
            <div class="space-y-3">
                <!-- عروض السحب -->
                @if($userType == 'company')
                    <a href="{{ route('tow-service.company.offers.index') }}" 
                       class="flex items-center p-3 md:p-4 text-white rounded-lg md:rounded-xl transition-all duration-300 hover:translate-x-1 group cursor-pointer"
                       style="background: linear-gradient(135deg, #FBBF24, #F59E0B);">
                        <div class="flex-1">
                            <div class="font-bold text-base md:text-lg">{{ t('tow.tow_offers', 'عروض السحب') }}</div>
                            <div class="text-xs md:text-sm text-white/80">{{ t('tow.manage_offers', 'إدارة عروض السحب') }}</div>
                        </div>
                        <div class="text-xl md:text-2xl mr-3 md:mr-4 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 md:w-8 md:h-8" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                            </svg>
                        </div>
                    </a>
                @else
                    <a href="{{ route('tow-service.individual.offers.index') }}" 
                       class="flex items-center p-3 md:p-4 text-white rounded-lg md:rounded-xl transition-all duration-300 hover:translate-x-1 group cursor-pointer"
                       style="background: linear-gradient(135deg, #FBBF24, #F59E0B);">
                        <div class="flex-1">
                            <div class="font-bold text-base md:text-lg">{{ t('tow.tow_offers', 'عروض السحب') }}</div>
                            <div class="text-xs md:text-sm text-white/80">{{ t('tow.manage_offers', 'إدارة عروض السحب') }}</div>
                        </div>
                        <div class="text-xl md:text-2xl mr-3 md:mr-4 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 md:w-8 md:h-8" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                            </svg>
                        </div>
                    </a>
                @endif
                
                <!-- الملف الشخصي -->
                <a href="{{ route('tow-service.profile.show') }}" 
                   class="flex items-center p-3 md:p-4 text-white rounded-lg md:rounded-xl transition-all duration-300 hover:translate-x-1 group cursor-pointer"
                   style="background: linear-gradient(135deg, #FBBF24, #F59E0B);">
                    <div class="flex-1">
                        <div class="font-bold text-base md:text-lg">{{ t('tow.my_profile', 'الملف الشخصي') }}</div>
                        <div class="text-xs md:text-sm text-white/80">{{ t('tow.view_edit_profile', 'عرض وتحديث الملف الشخصي') }}</div>
                    </div>
                    <div class="text-xl md:text-2xl mr-3 md:mr-4 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6 md:w-8 md:h-8" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
