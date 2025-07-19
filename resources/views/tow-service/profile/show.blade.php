@extends('tow-service.layouts.app')

@section('title', 'البروفايل الشخصي - خدمة السحب')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        
        <!-- Header Section -->
        <div class="relative overflow-hidden rounded-2xl mb-8 shadow-xl">
            <!-- Background Gradient -->
            <div class="absolute inset-0 bg-gradient-to-r from-yellow-500 to-yellow-600"></div>
            
            <!-- Decorative Elements -->
            <div class="absolute top-0 right-0 w-32 h-32 bg-white bg-opacity-10 rounded-full -mr-16 -mt-16"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-white bg-opacity-10 rounded-full -ml-12 -mb-12"></div>
            
            <!-- Content -->
            <div class="relative z-10 px-6 py-8 lg:px-8 lg:py-12">
                <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between space-y-6 lg:space-y-0">
                    <!-- User Info Section -->
                    <div class="flex flex-col sm:flex-row items-start sm:items-center space-y-4 sm:space-y-0 sm:space-x-6 rtl:sm:space-x-reverse">
                        <!-- Avatar/Logo -->
                        <div class="w-20 h-20 sm:w-24 sm:h-24 lg:w-28 lg:h-28 rounded-full bg-white bg-opacity-20 backdrop-blur-sm flex items-center justify-center flex-shrink-0">
                            @if(isset($profileData['company_info']['logo']) && $profileData['company_info']['logo'])
                                <img src="{{ asset('storage/' . $profileData['company_info']['logo']) }}" 
                                     alt="{{ $profileData['company_info']['name'] ?? 'صورة المستخدم' }}" 
                                     class="w-full h-full rounded-full object-cover">
                            @else
                                @if($userType === 'tow_service_company')
                                    <svg class="w-10 h-10 sm:w-12 sm:h-12 lg:w-14 lg:h-14 text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M20,8h-3V4H3C1.89,4 1,4.89 1,6v12h2c0,1.66 1.34,3 3,3s3-1.34 3-3h6c0,1.66 1.34,3 3,3s3-1.34 3-3h2v-5L20,8z M6,18.5c-0.83,0 -1.5,-0.67 -1.5,-1.5s0.67,-1.5 1.5,-1.5s1.5,0.67 1.5,1.5S6.83,18.5 6,18.5z M18,18.5c-0.83,0 -1.5,-0.67 -1.5,-1.5s0.67,-1.5 1.5,-1.5s1.5,0.67 1.5,1.5S18.83,18.5 18,18.5z M19,13h-4V9h1.5l2.5,4z"/>
                                    </svg>
                                @else
                                    <svg class="w-10 h-10 sm:w-12 sm:h-12 lg:w-14 lg:h-14 text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M18.92,6.01C18.72,5.42 18.16,5 17.5,5h-11C5.84,5 5.28,5.42 5.08,6.01L3,12v8c0,0.55 0.45,1 1,1h1c0.55,0 1,-0.45 1,-1v-1h12v1c0,0.55 0.45,1 1,1h1c0.55,0 1,-0.45 1,-1v-8L18.92,6.01z M6.5,16C5.67,16 5,15.33 5,14.5S5.67,13 6.5,13S8,13.67 8,14.5S7.33,16 6.5,16z M17.5,16C16.67,16 16,15.33 16,14.5S16.67,13 17.5,13S19,13.67 19,14.5S18.33,16 17.5,16z M5,11l1.5-4.5h11L19,11H5z"/>
                                    </svg>
                                @endif
                            @endif
                        </div>
                        
                        <!-- User Details -->
                        <div class="text-white text-center sm:text-right rtl:sm:text-left">
                            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold mb-2">
                                @if($userType === 'tow_service_company')
                                    {{ $user->legal_name ?? 'غير محدد' }}
                                @else
                                    {{ $user->full_name ?? 'غير محدد' }}
                                @endif
                            </h1>
                            <p class="text-lg sm:text-xl opacity-90 mb-3">
                                {{ $profileData['company_info']['type'] ?? 'غير محدد' }}
                            </p>
                            
                            <!-- Status Badge -->
                            <div class="mt-3">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-white bg-opacity-20 backdrop-blur-sm">
                                    <span class="w-2 h-2 {{ isset($stats['account_status']) && str_contains($stats['account_status'], 'نشط') ? 'bg-green-300' : 'bg-yellow-300' }} rounded-full mr-2 rtl:mr-0 rtl:ml-2"></span>
                                    {{ $stats['account_status'] ?? 'في الانتظار' }}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                        <a href="{{ route('tow-service.profile.edit') }}" 
                           class="inline-flex items-center justify-center px-6 py-3 bg-white/20 backdrop-blur-sm text-white rounded-xl font-medium hover:bg-white/30 transition-all duration-200 border border-white/30">
                            <svg class="w-5 h-5 mr-2 rtl:mr-0 rtl:ml-2" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                            </svg>
                            <span class="whitespace-nowrap">تعديل البروفايل</span>
                        </a>
                        
                        <button onclick="openPasswordModal()" 
                                class="inline-flex items-center justify-center px-6 py-3 bg-white text-gray-800 rounded-xl font-medium hover:bg-gray-50 transition-all duration-200 shadow-lg">
                            <svg class="w-5 h-5 mr-2 rtl:mr-0 rtl:ml-2" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12.65 10C11.83 7.67 9.61 6 7 6c-3.31 0-6 2.69-6 6s2.69 6 6 6c2.61 0 4.83-1.67 5.65-4H17v4h4v-4h2v-4H12.65zM7 14c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2z"/>
                            </svg>
                            <span class="whitespace-nowrap">تغيير كلمة المرور</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
            <!-- Profile Information (Left Column) -->
            <div class="xl:col-span-2 space-y-8">
                <!-- Personal Information Card -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="border-b border-gray-200 px-6 py-6">
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-900 flex items-center">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 rtl:mr-0 rtl:ml-3 bg-yellow-100">
                                <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                </svg>
                            </div>
                            المعلومات الشخصية
                        </h2>
                    </div>
                    
                    <div class="p-6">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            @if(isset($profileData['display_fields']) && is_array($profileData['display_fields']))
                                @foreach($profileData['display_fields'] as $field => $fieldData)
                                    <div class="group">
                                        <div class="flex items-center mb-3">
                                            <div class="w-10 h-10 rounded-lg flex items-center justify-center mr-3 rtl:mr-0 rtl:ml-3 transition-colors duration-200 bg-yellow-50">
                                                @if($field === 'legal_name' || $field === 'full_name')
                                                    <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/>
                                                    </svg>
                                                @elseif($field === 'phone')
                                                    <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
                                                    </svg>
                                                @elseif($field === 'commercial_register')
                                                    <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/>
                                                    </svg>
                                                @elseif($field === 'tax_number')
                                                    <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z"/>
                                                    </svg>
                                                @elseif(str_contains($field, 'address'))
                                                    <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                                                    </svg>
                                                @elseif($field === 'national_id')
                                                    <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                                    </svg>
                                                @elseif($field === 'daily_capacity')
                                                    <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                                    </svg>
                                                @elseif($field === 'tow_truck_plate_number')
                                                    <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M20,8h-3V4H3C1.89,4 1,4.89 1,6v12h2c0,1.66 1.34,3 3,3s3-1.34 3-3h6c0,1.66 1.34,3 3,3s3-1.34 3-3h2v-5L20,8z M6,18.5c-0.83,0 -1.5,-0.67 -1.5,-1.5s0.67,-1.5 1.5,-1.5s1.5,0.67 1.5,1.5S6.83,18.5 6,18.5z M18,18.5c-0.83,0 -1.5,-0.67 -1.5,-1.5s0.67,-1.5 1.5,-1.5s1.5,0.67 1.5,1.5S18.83,18.5 18,18.5z M19,13h-4V9h1.5l2.5,4z"/>
                                                    </svg>
                                                @else
                                                    <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/>
                                                    </svg>
                                                @endif
                                            </div>
                                            <span class="text-sm font-medium text-gray-700">
                                                @if(is_array($fieldData))
                                                    {{ $fieldData['label'] ?? $field }}
                                                @else
                                                    {{ $fieldData }}
                                                @endif
                                            </span>
                                        </div>
                                        <div class="bg-gray-50 rounded-xl p-4 border-2 border-gray-100 group-hover:border-yellow-200 transition-colors duration-200">
                                            <span class="text-gray-900 font-medium break-words">{{ $user->$field ?? 'غير محدد' }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Recent Activities -->
                @if(isset($recentActivities) && $recentActivities->count() > 0)
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="border-b border-gray-200 px-6 py-6">
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-900 flex items-center">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 rtl:mr-0 rtl:ml-3 bg-yellow-100">
                                <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M13 3c-4.97 0-9 4.03-9 9H1l3.89 3.89.07.14L9 12H6c0-3.87 3.13-7 7-7s7 3.13 7 7-3.13 7-7 7c-1.93 0-3.68-.79-4.94-2.06l-1.42 1.42C8.27 19.99 10.51 21 13 21c4.97 0 9-4.03 9-9s-4.03-9-9-9zm-1 5v5l4.28 2.54.72-1.21-3.5-2.08V8H12z"/>
                                </svg>
                            </div>
                            النشاطات الأخيرة
                            <span class="text-sm font-normal text-gray-500 mr-2 rtl:mr-0 rtl:ml-2 hidden sm:inline">
                                (الشكاوى وطلبات السحب)
                            </span>
                        </h2>
                    </div>
                    
                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach($recentActivities as $activity)
                                <div class="flex items-start p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors duration-200">
                                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full flex items-center justify-center mr-4 rtl:mr-0 rtl:ml-4 flex-shrink-0 bg-yellow-100">
                                        @if(isset($activity['type']))
                                            @if($activity['type'] === 'complaint')
                                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-yellow-600" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M1 21h4V9H1v12zm22-11c0-1.1-.9-2-2-2h-6.31l.95-4.57.03-.32c0-.41-.17-.79-.44-1.06L14.17 1 7.59 7.59C7.22 7.95 7 8.45 7 9v10c0 1.1.9 2 2 2h9c.83 0 1.54-.5 1.84-1.22l3.02-7.05c.09-.23.14-.47.14-.73v-2z"/>
                                                </svg>
                                            @elseif($activity['type'] === 'tow_request')
                                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-yellow-600" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M20,8h-3V4H3C1.89,4 1,4.89 1,6v12h2c0,1.66 1.34,3 3,3s3-1.34 3-3h6c0,1.66 1.34,3 3,3s3-1.34 3-3h2v-5L20,8z M6,18.5c-0.83,0 -1.5,-0.67 -1.5,-1.5s0.67,-1.5 1.5,-1.5s1.5,0.67 1.5,1.5S6.83,18.5 6,18.5z M18,18.5c-0.83,0 -1.5,-0.67 -1.5,-1.5s0.67,-1.5 1.5,-1.5s1.5,0.67 1.5,1.5S18.83,18.5 18,18.5z M19,13h-4V9h1.5l2.5,4z"/>
                                                </svg>
                                            @else
                                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-yellow-600" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/>
                                                </svg>
                                            @endif
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-semibold text-gray-900 mb-1 break-words">
                                            {{ $activity['title'] ?? 'غير محدد' }}
                                        </h4>
                                        <p class="text-sm text-gray-600 flex items-center">
                                            <svg class="w-4 h-4 mr-1 rtl:mr-0 rtl:ml-1 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z"/>
                                                <path d="M12.5 7H11v6l5.25 3.15.75-1.23-4.5-2.67z"/>
                                            </svg>
                                            <span class="break-words">{{ isset($activity['date']) ? $activity['date']->format('d/m/Y H:i') : 'غير محدد' }}</span>
                                        </p>
                                    </div>
                                    <div class="flex-shrink-0 mr-4 rtl:mr-0 rtl:ml-4">
                                        <span class="inline-flex items-center px-2 py-1 text-xs rounded-full font-medium {{ 
                                            (($activity['status'] ?? '') === 'مقروءة' || ($activity['status'] ?? '') === 'completed') ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            @if(($activity['status'] ?? '') === 'مقروءة' || ($activity['status'] ?? '') === 'completed')
                                                <svg class="w-3 h-3 mr-1 rtl:mr-0 rtl:ml-1" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                                                </svg>
                                            @else
                                                <svg class="w-3 h-3 mr-1 rtl:mr-0 rtl:ml-1" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                                                </svg>
                                            @endif
                                            <span class="whitespace-nowrap">{{ $activity['status'] ?? 'غير محدد' }}</span>
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Right Sidebar -->
            <div class="xl:col-span-1 space-y-6">
                <!-- Statistics -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="border-b border-gray-200 px-6 py-4">
                        <h3 class="text-lg sm:text-xl font-bold text-gray-900 flex items-center">
                            <div class="w-6 h-6 rounded-lg flex items-center justify-center mr-2 rtl:mr-0 rtl:ml-2 bg-yellow-100">
                                <svg class="w-4 h-4 text-yellow-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z"/>
                                </svg>
                            </div>
                            <span class="truncate">الإحصائيات العامة</span>
                        </h3>
                    </div>
                    
                    <div class="p-6">
                        <div class="space-y-4">
                            @if(isset($stats) && is_array($stats))
                                @foreach($stats as $key => $value)
                                    <div class="flex items-center justify-between p-3 sm:p-4 bg-gradient-to-r from-yellow-50 to-yellow-100 rounded-xl">
                                        <div class="flex items-center min-w-0 flex-1">
                                            @if($key === 'total_requests')
                                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-yellow-600 mr-2 rtl:mr-0 rtl:ml-2 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z"/>
                                                </svg>
                                            @elseif($key === 'completed_requests')
                                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-green-600 mr-2 rtl:mr-0 rtl:ml-2 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                                                </svg>
                                            @elseif($key === 'daily_capacity')
                                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600 mr-2 rtl:mr-0 rtl:ml-2 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                                </svg>
                                            @elseif($key === 'truck_plate')
                                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-yellow-600 mr-2 rtl:mr-0 rtl:ml-2 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M20,8h-3V4H3C1.89,4 1,4.89 1,6v12h2c0,1.66 1.34,3 3,3s3-1.34 3-3h6c0,1.66 1.34,3 3,3s3-1.34 3-3h2v-5L20,8z M6,18.5c-0.83,0 -1.5,-0.67 -1.5,-1.5s0.67,-1.5 1.5,-1.5s1.5,0.67 1.5,1.5S6.83,18.5 6,18.5z M18,18.5c-0.83,0 -1.5,-0.67 -1.5,-1.5s0.67,-1.5 1.5,-1.5s1.5,0.67 1.5,1.5S18.83,18.5 18,18.5z M19,13h-4V9h1.5l2.5,4z"/>
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-600 mr-2 rtl:mr-0 rtl:ml-2 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/>
                                                </svg>
                                            @endif
                                            <span class="text-xs sm:text-sm font-medium text-gray-700 truncate">
                                                @if($key === 'total_requests')
                                                    إجمالي الطلبات
                                                @elseif($key === 'completed_requests')
                                                    الطلبات المكتملة
                                                @elseif($key === 'daily_capacity')
                                                    السعة اليومية
                                                @elseif($key === 'account_status')
                                                    حالة الحساب
                                                @elseif($key === 'truck_plate')
                                                    رقم لوحة السطحة
                                                @else
                                                    {{ $key }}
                                                @endif
                                            </span>
                                        </div>
                                        <span class="text-sm sm:text-lg font-bold flex-shrink-0 mr-2 rtl:mr-0 rtl:ml-2 text-yellow-600">
                                            <span class="break-words">{{ $value ?? 'غير محدد' }}</span>
                                        </span>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
<!-- Quick Actions -->
<div class="bg-white rounded-2xl shadow-lg overflow-hidden">
    <div class="border-b border-gray-200 px-6 py-4">
        <h3 class="text-lg sm:text-xl font-bold text-gray-900 flex items-center">
            <div class="w-6 h-6 rounded-lg flex items-center justify-center mr-2 rtl:mr-0 rtl:ml-2 bg-yellow-100">
                <svg class="w-4 h-4 text-yellow-600" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <span class="truncate">إجراءات سريعة</span>
        </h3>
    </div>
    
    <div class="p-6">
        <div class="space-y-4">
            <!-- View Offers -->
            @if($userType === 'tow_service_company')
                <a href="{{ route('tow-service.company.offers.index') }}" 
                   class="flex items-center justify-between p-3 sm:p-4 bg-gradient-to-r from-yellow-50 to-yellow-100 rounded-xl hover:from-yellow-100 hover:to-yellow-200 transition-all duration-200 group">
            @else
                <a href="{{ route('tow-service.individual.offers.index') }}" 
                   class="flex items-center justify-between p-3 sm:p-4 bg-gradient-to-r from-yellow-50 to-yellow-100 rounded-xl hover:from-yellow-100 hover:to-yellow-200 transition-all duration-200 group">
            @endif
                <div class="flex items-center min-w-0 flex-1">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-yellow-600 mr-2 rtl:mr-0 rtl:ml-2 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M9 17h6v-2H9v2zm0-4h6v-2H9v2zm0-4h6V7H9v2zm-3-2v14c0 1.1.89 2 2 2h8c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2H8c-1.1 0-2 .9-2 2zm12 14H8V5h8v14z"/>
                    </svg>
                    <span class="text-xs sm:text-sm font-medium text-gray-700 truncate">عروض السحب</span>
                </div>
                <svg class="w-4 h-4 text-yellow-600 flex-shrink-0 mr-2 rtl:mr-0 rtl:ml-2 group-hover:translate-x-1 transition-transform duration-200" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 4l-1.41 1.41L16.17 11H4v2h12.17l-5.58 5.59L12 20l8-8z"/>
                </svg>
            </a>

            <!-- Dashboard -->
            <a href="{{ route('tow-service.dashboard') }}" 
               class="flex items-center justify-between p-3 sm:p-4 bg-gradient-to-r from-yellow-50 to-yellow-100 rounded-xl hover:from-yellow-100 hover:to-yellow-200 transition-all duration-200 group">
                <div class="flex items-center min-w-0 flex-1">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-yellow-600 mr-2 rtl:mr-0 rtl:ml-2 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
                    </svg>
                    <span class="text-xs sm:text-sm font-medium text-gray-700 truncate">لوحة التحكم</span>
                </div>
                <svg class="w-4 h-4 text-yellow-600 flex-shrink-0 mr-2 rtl:mr-0 rtl:ml-2 group-hover:translate-x-1 transition-transform duration-200" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 4l-1.41 1.41L16.17 11H4v2h12.17l-5.58 5.59L12 20l8-8z"/>
                </svg>
            </a>
        </div>
    </div>
</div>

    </div>
</div>

<!-- Password Change Modal -->
<div id="passwordModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl mx-4">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold text-gray-900 flex items-center">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 rtl:mr-0 rtl:ml-3 bg-yellow-100">
                        <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12.65 10C11.83 7.67 9.61 6 7 6c-3.31 0-6 2.69-6 6s2.69 6 6 6c2.61 0 4.83-1.67 5.65-4H17v4h4v-4h2v-4H12.65zM7 14c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2z"/>
                        </svg>
                    </div>
                    تغيير كلمة المرور
                </h3>
                <button onclick="closePasswordModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                    </svg>
                </button>
            </div>
        </div>
        
        <form method="POST" action="{{ route('tow-service.profile.change-password') }}" class="p-6">
            @csrf
            
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        كلمة المرور الحالية
                    </label>
                    <input type="password" name="current_password" required 
                           class="w-full border border-gray-300 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-transparent px-4 py-3">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        كلمة المرور الجديدة
                    </label>
                    <input type="password" name="password" required 
                           class="w-full border border-gray-300 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-transparent px-4 py-3">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        تأكيد كلمة المرور
                    </label>
                    <input type="password" name="password_confirmation" required 
                           class="w-full border border-gray-300 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-transparent px-4 py-3">
                </div>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-3 mt-8">
                <button type="submit" 
                        class="flex-1 py-3 px-6 bg-yellow-500 text-white rounded-xl font-medium hover:bg-yellow-600 transition-colors duration-200 flex items-center justify-center">
                    <svg class="w-4 h-4 mr-2 rtl:mr-0 rtl:ml-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V7l-4-4zm-5 16c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3zm3-10H5V5h10v4z"/>
                    </svg>
                    حفظ التغييرات
                </button>
                <button type="button" onclick="closePasswordModal()" 
                        class="flex-1 py-3 px-6 bg-gray-500 text-white rounded-xl font-medium hover:bg-gray-600 transition-colors duration-200">
                    إلغاء
                </button>
            </div>
        </form>
    </div>
</div>

<!-- JavaScript -->
<script>
function openPasswordModal() {
    document.getElementById('passwordModal').classList.remove('hidden');
}

function closePasswordModal() {
    document.getElementById('passwordModal').classList.add('hidden');
}

// Close modal on outside click
document.getElementById('passwordModal').addEventListener('click', function(e) {
    if (e.target === this) closePasswordModal();
});

// Close modal on escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closePasswordModal();
});
</script>

<!-- Success/Error Messages -->
@if(session('success'))
    <div class="fixed top-4 left-1/2 transform -translate-x-1/2 bg-green-500 text-white px-6 py-3 rounded-xl shadow-lg z-50 max-w-sm w-full mx-4">
        <div class="flex items-center">
            <svg class="w-4 h-4 mr-2 rtl:mr-0 rtl:ml-2 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
            </svg>
            <span class="break-words">{{ session('success') }}</span>
        </div>
    </div>
    <script>
        setTimeout(() => {
            const successMessage = document.querySelector('.fixed.top-4');
            if (successMessage) successMessage.remove();
        }, 4000);
    </script>
@endif

@if($errors->any())
    <div class="fixed top-4 left-1/2 transform -translate-x-1/2 bg-red-500 text-white px-6 py-3 rounded-xl shadow-lg z-50 max-w-sm w-full mx-4">
        <div class="flex items-center">
            <svg class="w-4 h-4 mr-2 rtl:mr-0 rtl:ml-2 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
            </svg>
            <span class="break-words">{{ $errors->first() }}</span>
        </div>
    </div>
    <script>
        setTimeout(() => {
            const errorMessage = document.querySelector('.fixed.top-4');
            if (errorMessage) errorMessage.remove();
        }, 5000);
    </script>
@endif
@endsection
