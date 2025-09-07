{{-- resources/views/profile/show.blade.php --}}
@extends($userType === 'insurance_company' ? 'insurance.layouts.app' : ($userType === 'service_center' ? 'service-center.layouts.app' : 'insurance-user.layouts.app'))

@section('title', t('profile' . '.personal_profile'))

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
    <div class="max-w-7xl mx-auto py-8 px-2 md:px-4 sm:px-6 lg:px-8">
        
        <!-- Header Section -->
        <div class="relative overflow-hidden rounded-2xl mb-8 shadow-xl">
            <!-- Background Gradient -->
            <div class="absolute inset-0 bg-gradient-to-r" style="background: linear-gradient(135deg, {{ $profileData['colors']['primary'] ?? '#3b82f6' }}, {{ $profileData['colors']['secondary'] ?? '#1e40af' }})"></div>
            
            <!-- Content -->
            <div class="relative z-10 px-3 md:px-8 py-12">
                <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between">
                    <div class="flex items-center space-x-6 rtl:space-x-reverse">
                        <!-- User Info -->
                        <div class="text-white">
                            <h1 class="text-3xl font-bold mb-2 flex items-center">
                                @if($userType === 'insurance_company')
                                    <svg class="w-8 h-8 mr-3 rtl:mr-0 rtl:ml-3 opacity-90" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2L2 7v10c0 5.55 3.84 9.74 9 10.5c5.16-.76 9-4.95 9-10.5V7L12 2z"/>
                                    </svg>
                                    {{ $user->legal_name ?? t('profile' . '.not_specified') }}
                                @elseif($userType === 'service_center')
                                    <svg class="w-8 h-8 mr-3 rtl:mr-0 rtl:ml-3 opacity-90" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M22.7 19l-9.1-9.1c.9-2.3.4-5-1.5-6.9-2-2-5-2.4-7.4-1.3L9 6 6 9 1.6 4.7C.4 7.1.9 10.1 2.9 12.1c1.9 1.9 4.6 2.4 6.9 1.5l9.1 9.1c.4.4 1 .4 1.4 0l2.3-2.3c.5-.4.5-1.1.1-1.4z"/>
                                    </svg>
                                    {{ $user->legal_name ?? t('profile' . '.not_specified') }}
                                @else
                                    <svg class="w-8 h-8 mr-3 rtl:mr-0 rtl:ml-3 opacity-90" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/>
                                    </svg>
                                    {{ $user->full_name ?? t('profile' . '.not_specified') }}
                                @endif
                            </h1>
                            <p class="text-xl opacity-90 mb-2 flex items-center">
                                <svg class="w-6 h-6 mr-2 rtl:mr-0 rtl:ml-2 opacity-75" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M14 6V4h-4v2h4zM4 8v11h16V8H4zm16-2c1.11 0 2 .89 2 2v11c0 1.11-.89 2-2 2H4c-1.11 0-2-.89-2-2V8c0-1.11.89-2 2-2h16z"/>
                                </svg>
                                {{ $profileData['company_info']['type'] ?? t('profile' . '.not_specified') }}
                            </p>
                            
                            @if($userType === 'insurance_user' && isset($profileData['company_info']['name']) && $profileData['company_info']['name'])
                                <div class="flex items-center space-x-2 rtl:space-x-reverse">
                                    <svg class="w-5 h-5 opacity-75" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2L2 7v10c0 5.55 3.84 9.74 9 10.5c5.16-.76 9-4.95 9-10.5V7L12 2z"/>
                                    </svg>
                                    <span class="opacity-90">{{ $profileData['company_info']['name'] }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex flex-col justify-center sm:justify-start sm:flex-row gap-3 mt-6 lg:mt-0">
                        @if($userType === 'insurance_company')
                            <a href="{{ route('insurance.profile.edit', ['companyRoute' => $user->company_slug]) }}" 
                               class="inline-flex items-center justify-center px-6 py-3 bg-white/20 backdrop-blur-sm text-white rounded-xl font-medium hover:bg-white/30 transition-all duration-200 border border-white/30">
                        @elseif($userType === 'insurance_user')
                            <a href="{{ route('insurance.user.profile.edit', ['companySlug' => $user->company->company_slug]) }}" 
                               class="inline-flex items-center justify-center px-6 py-3 bg-white/20 backdrop-blur-sm text-white rounded-xl font-medium hover:bg-white/30 transition-all duration-200 border border-white/30">
                        @else
                            <a href="{{ route('service-center.profile.edit') }}" 
                               class="inline-flex items-center justify-center px-6 py-3 bg-white/20 backdrop-blur-sm text-white rounded-xl font-medium hover:bg-white/30 transition-all duration-200 border border-white/30">
                        @endif
                            <svg class="w-5 h-5 mr-2 rtl:mr-0 rtl:ml-2" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                            </svg>
                            {{ t('profile' . '.edit_profile') }}
                        </a>
                        
                        <button onclick="openPasswordModal()" 
                                class="inline-flex items-center justify-center px-6 py-3 bg-white text-gray-800 rounded-xl font-medium hover:bg-gray-50 transition-all duration-200 shadow-lg">
                            <svg class="w-5 h-5 mr-2 rtl:mr-0 rtl:ml-2" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12.65 10C11.83 7.67 9.61 6 7 6c-3.31 0-6 2.69-6 6s2.69 6 6 6c2.61 0 4.83-1.67 5.65-4H17v4h4v-4h2v-4H12.65zM7 14c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2z"/>
                            </svg>
                            {{ t('profile' . '.change_password') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Profile Information -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="border-b border-gray-200 px-3 md:px-8 py-6">
                        <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 rtl:mr-0 rtl:ml-3" style="background-color: {{ $profileData['colors']['primary'] ?? '#3b82f6' }}20;">
                                <svg class="w-5 h-5" style="color: {{ $profileData['colors']['primary'] ?? '#3b82f6' }}" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                </svg>
                            </div>
                            {{ t('profile' . '.personal_information') }}
                        </h2>
                    </div>
                    
                    <div class=" px-3 md:px-8 py-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @if(isset($profileData['display_fields']) && is_array($profileData['display_fields']))
                                @foreach($profileData['display_fields'] as $field => $fieldData)
                                    <div class="group">
                                        <div class="flex items-center mb-3">
                                            <div class="w-10 h-10 rounded-lg flex items-center justify-center mr-3 rtl:mr-0 rtl:ml-3 transition-colors duration-200" style="background-color: {{ $profileData['colors']['primary'] ?? '#3b82f6' }}15;">
                                                @if($field === 'legal_name' || $field === 'full_name')
                                                    <svg class="w-5 h-5" style="color: {{ $profileData['colors']['primary'] ?? '#3b82f6' }}" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/>
                                                    </svg>
                                                @elseif($field === 'phone')
                                                    <svg class="w-5 h-5" style="color: {{ $profileData['colors']['primary'] ?? '#3b82f6' }}" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
                                                    </svg>
                                                @elseif($field === 'email')
                                                    <svg class="w-5 h-5" style="color: {{ $profileData['colors']['primary'] ?? '#3b82f6' }}" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.89 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                                                    </svg>
                                                @elseif($field === 'commercial_register')
                                                    <svg class="w-5 h-5" style="color: {{ $profileData['colors']['primary'] ?? '#3b82f6' }}" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/>
                                                    </svg>
                                                @elseif($field === 'tax_number')
                                                    <svg class="w-5 h-5" style="color: {{ $profileData['colors']['primary'] ?? '#3b82f6' }}" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z"/>
                                                    </svg>
                                                @elseif(str_contains($field, 'address'))
                                                    <svg class="w-5 h-5" style="color: {{ $profileData['colors']['primary'] ?? '#3b82f6' }}" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                                                    </svg>
                                                @elseif($field === 'employee_count')
                                                    <svg class="w-5 h-5" style="color: {{ $profileData['colors']['primary'] ?? '#3b82f6' }}" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M16 13c1.66 0 2.99-1.34 2.99-3S17.66 7 16 7c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 7 8 7C6.34 7 5 8.34 5 10s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-0.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-0.5c0-2.33-4.67-3.5-7-3.5z"/>
                                                    </svg>
                                                @elseif($field === 'insured_cars_count')
                                                    <svg class="w-5 h-5" style="color: {{ $profileData['colors']['primary'] ?? '#3b82f6' }}" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.5 16c-.83 0-1.5-.67-1.5-1.5S5.67 13 6.5 13s1.5.67 1.5 1.5S7.33 16 6.5 16zm11 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zM5 11l1.5-4.5h11L19 11H5z"/>
                                                    </svg>
                                                @elseif(str_contains($field, 'technicians'))
                                                    <svg class="w-5 h-5" style="color: {{ $profileData['colors']['primary'] ?? '#3b82f6' }}" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M22.7 19l-9.1-9.1c.9-2.3.4-5-1.5-6.9-2-2-5-2.4-7.4-1.3L9 6 6 9 1.6 4.7C.4 7.1.9 10.1 2.9 12.1c1.9 1.9 4.6 2.4 6.9 1.5l9.1 9.1c.4.4 1 .4 1.4 0l2.3-2.3c.5-.4.5-1.1.1-1.4z"/>
                                                    </svg>
                                                @elseif($field === 'national_id')
                                                    <svg class="w-5 h-5" style="color: {{ $profileData['colors']['primary'] ?? '#3b82f6' }}" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                                    </svg>
                                                @elseif($field === 'policy_number')
                                                    <svg class="w-5 h-5" style="color: {{ $profileData['colors']['primary'] ?? '#3b82f6' }}" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M12 2L2 7v10c0 5.55 3.84 9.74 9 10.5c5.16-.76 9-4.95 9-10.5V7L12 2z"/>
                                                    </svg>
                                                @elseif($field === 'website')
                                                    <svg class="w-5 h-5" style="color: {{ $profileData['colors']['primary'] ?? '#3b82f6' }}" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/>
                                                    </svg>
                                                @elseif($field === 'license_number')
                                                    <svg class="w-5 h-5" style="color: {{ $profileData['colors']['primary'] ?? '#3b82f6' }}" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                                    </svg>
                                                @elseif($field === 'tow_trucks_count')
                                                    <svg class="w-5 h-5" style="color: {{ $profileData['colors']['primary'] ?? '#3b82f6' }}" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M20 8h-3V4H3c-1.1 0-2 .9-2 2v11h2c0 1.66 1.34 3 3 3s3-1.34 3-3h6c0 1.66 1.34 3 3 3s3-1.34 3-3h2v-5l-3-4zM6 18.5c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zm13.5-9l1.96 2.5H17V9.5h2.5zm-1.5 9c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5z"/>
                                                    </svg>
                                                @elseif($field === 'daily_tow_capacity')
                                                    <svg class="w-5 h-5" style="color: {{ $profileData['colors']['primary'] ?? '#3b82f6' }}" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M20.38 8.57l-1.23-1.23c-.51-.51-1.32-.51-1.83 0L9.93 14.7l-6.69-6.69c-.51-.51-1.32-.51-1.83 0L.18 9.25c-.51.51-.51 1.32 0 1.83l9.02 9.02c.51.51 1.32.51 1.83 0l13.35-13.35c.51-.51.51-1.32 0-1.83z"/>
                                                    </svg>
                                                @elseif($field === 'specialization')
                                                    <svg class="w-5 h-5" style="color: {{ $profileData['colors']['primary'] ?? '#3b82f6' }}" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                                    </svg>
                                                @elseif($field === 'established_date')
                                                    <svg class="w-5 h-5" style="color: {{ $profileData['colors']['primary'] ?? '#3b82f6' }}" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z"/>
                                                    </svg>
                                                @else
                                                    <svg class="w-5 h-5" style="color: {{ $profileData['colors']['primary'] ?? '#3b82f6' }}" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/>
                                                    </svg>
                                                @endif
                                            </div>
                                            <span class="text-sm font-medium text-gray-700">
                                                @if(is_array($fieldData))
                                                    {{ t('profile' . '.' . $field) ?? $fieldData['label'] ?? $field }}
                                                @else
                                                    {{ t('profile' . '.' . $field) ?? $fieldData }}
                                                @endif
                                            </span>
                                        </div>
                                        <div class="bg-gray-50 rounded-xl p-4 border-2 border-gray-100 group-hover:border-gray-200 transition-colors duration-200">
                                            <span class="text-gray-900 font-medium">{{ $user->$field ?? t('profile' . '.not_specified') }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Recent Activities -->
                @if(isset($recentActivities) && $recentActivities->count() > 0)
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden mt-8">
                    <div class="border-b border-gray-200 px-3 md:px-8 py-6">
                        <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 rtl:mr-0 rtl:ml-3" style="background-color: {{ $profileData['colors']['primary'] ?? '#3b82f6' }}20;">
                                <svg class="w-5 h-5" style="color: {{ $profileData['colors']['primary'] ?? '#3b82f6' }}" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M13 3c-4.97 0-9 4.03-9 9H1l3.89 3.89.07.14L9 12H6c0-3.87 3.13-7 7-7s7 3.13 7 7-3.13 7-7 7c-1.93 0-3.68-.79-4.94-2.06l-1.42 1.42C8.27 19.99 10.51 21 13 21c4.97 0 9-4.03 9-9s-4.03-9-9-9zm-1 5v5l4.28 2.54.72-1.21-3.5-2.08V8H12z"/>
                                </svg>
                            </div>
                            {{ t('profile' . '.recent_activities') }}
                            <span class="text-sm font-normal text-gray-500 mr-2 rtl:mr-0 rtl:ml-2">({{ t('profile' . '.complaints_and_inquiries_sent') }})</span>
                        </h2>
                    </div>
                    
                    <div class="px-3 md:px-8 py-6">
                        <div class="space-y-4">
                            @foreach($recentActivities as $activity)
                                <div class="flex items-center p-1 md:p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors duration-200">
                                    <div class="w-12 h-12 rounded-full flex items-center justify-center mr-4 rtl:mr-0 rtl:ml-4" style="background-color: {{ $profileData['colors']['primary'] ?? '#3b82f6' }}20;">
                                        @if(isset($activity['type']))
                                            @if($activity['type'] === 'complaint')
                                                <svg class="w-6 h-6" style="color: {{ $profileData['colors']['primary'] ?? '#3b82f6' }}" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M1 21h4V9H1v12zm22-11c0-1.1-.9-2-2-2h-6.31l.95-4.57.03-.32c0-.41-.17-.79-.44-1.06L14.17 1 7.59 7.59C7.22 7.95 7 8.45 7 9v10c0 1.1.9 2 2 2h9c.83 0 1.54-.5 1.84-1.22l3.02-7.05c.09-.23.14-.47.14-.73v-2z"/>
                                                </svg>
                                            @elseif($activity['type'] === 'inquiry')
                                                <svg class="w-6 h-6" style="color: {{ $profileData['colors']['primary'] ?? '#3b82f6' }}" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 17h-2v-2h2v2zm2.07-7.75l-.9.92C13.45 12.9 13 13.5 13 15h-2v-.5c0-1.1.45-2.1 1.17-2.83l1.24-1.26c.37-.36.59-.86.59-1.41 0-1.1-.9-2-2-2s-2 .9-2 2H8c0-2.21 1.79-4 4-4s4 1.79 4 4c0 .88-.36 1.68-.93 2.25z"/>
                                                </svg>
                                            @else
                                                <svg class="w-6 h-6" style="color: {{ $profileData['colors']['primary'] ?? '#3b82f6' }}" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/>
                                                </svg>
                                            @endif
                                        @else
                                            <svg class="w-6 h-6" style="color: {{ $profileData['colors']['primary'] ?? '#3b82f6' }}" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-gray-900 flex items-center">
                                            <svg class="w-4 h-4 mr-2 rtl:mr-0 rtl:ml-2 text-gray-500" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M20 2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h4v3c0 .6.4 1 1 1h.5c.1 0 .3-.1.4-.2L12.5 18H20c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-3 12H7v-2h10v2zm0-4H7V8h10v2z"/>
                                            </svg>
                                            {{ $activity['title'] ?? t('profile' . '.not_specified') }}
                                        </h4>
                                        <p class="text-sm text-gray-600 flex items-center mt-1">
                                            <svg class="w-4 h-4 mr-1 rtl:mr-0 rtl:ml-1" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z"/>
                                                <path d="M12.5 7H11v6l5.25 3.15.75-1.23-4.5-2.67z"/>
                                            </svg>
                                            {{ isset($activity['date']) ? $activity['date']->format('d/m/Y H:i') : t('profile' . '.not_specified') }}
                                        </p>
                                    </div>
                                    <span class="px-3 py-1 text-xs rounded-full font-medium {{ ($activity['status'] ?? '') === t('profile' . '.read') ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        @if(($activity['status'] ?? '') === t('profile' . '.read'))
                                            <svg class="w-3 h-3 inline mr-1 rtl:mr-0 rtl:ml-1" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                                            </svg>
                                        @else
                                            <svg class="w-3 h-3 inline mr-1 rtl:mr-0 rtl:ml-1" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                                            </svg>
                                        @endif
                                        {{ $activity['status'] ?? t('profile' . '.not_specified') }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-8">
                <!-- Technicians Statistics (Service Centers Only) -->
                @if($userType === 'service_center' && isset($techniciansStats) && !empty($techniciansStats))
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="border-b border-gray-200 px-6 py-4">
                        <h3 class="text-xl font-bold text-gray-900 flex items-center">
                            <div class="w-6 h-6 rounded-lg flex items-center justify-center mr-2 rtl:mr-0 rtl:ml-2" style="background-color: {{ $profileData['colors']['primary'] ?? '#3b82f6' }}20;">
                                <svg class="w-4 h-4" style="color: {{ $profileData['colors']['primary'] ?? '#3b82f6' }}" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M22.7 19l-9.1-9.1c.9-2.3.4-5-1.5-6.9-2-2-5-2.4-7.4-1.3L9 6 6 9 1.6 4.7C.4 7.1.9 10.1 2.9 12.1c1.9 1.9 4.6 2.4 6.9 1.5l9.1 9.1c.4.4 1 .4 1.4 0l2.3-2.3c.5-.4.5-1.1.1-1.4z"/>
                                </svg>
                            </div>
                            {{ t('profile' . '.technicians_statistics') }}
                        </h3>
                    </div>
                    
                    <div class="p-6">
                        <div class="space-y-4">
                            <!-- Total Technicians -->
                            <div class="flex items-center justify-between p-4 rounded-xl" style="background: linear-gradient(135deg, {{ $profileData['colors']['primary'] ?? '#3b82f6' }}10, {{ $profileData['colors']['primary'] ?? '#3b82f6' }}05);">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center mr-3 rtl:mr-0 rtl:ml-3" style="background-color: {{ $profileData['colors']['primary'] ?? '#3b82f6' }}20;">
                                        <svg class="w-5 h-5" style="color: {{ $profileData['colors']['primary'] ?? '#3b82f6' }}" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M16 13c1.66 0 2.99-1.34 2.99-3S17.66 7 16 7c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 7 8 7C6.34 7 5 8.34 5 10s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-0.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-0.5c0-2.33-4.67-3.5-7-3.5z"/>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700">{{ t('profile' . '.total_technicians') }}</span>
                                </div>
                                <span class="text-2xl font-bold" style="color: {{ $profileData['colors']['primary'] ?? '#3b82f6' }}">{{ $techniciansStats['total'] ?? 0 }}</span>
                            </div>

                            <!-- Active Technicians -->
                            <div class="flex items-center justify-between p-4 rounded-xl" style="background: linear-gradient(135deg, #10b98120, #10b98105);">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center mr-3 rtl:mr-0 rtl:ml-3 bg-green-100">
                                        <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700">{{ t('profile' . '.active_technicians') }}</span>
                                </div>
                                <span class="text-2xl font-bold text-green-600">{{ $techniciansStats['active'] ?? 0 }}</span>
                            </div>

                            <!-- Inactive Technicians -->
                            <div class="flex items-center justify-between p-4 rounded-xl" style="background: linear-gradient(135deg, #f5973820, #f5973805);">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center mr-3 rtl:mr-0 rtl:ml-3 bg-orange-100">
                                        <svg class="w-5 h-5 text-orange-600" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700">{{ t('profile' . '.inactive_technicians') }}</span>
                                </div>
                                <span class="text-2xl font-bold text-orange-600">{{ $techniciansStats['inactive'] ?? 0 }}</span>
                            </div>

                            <!-- Specializations -->
                            @if(isset($techniciansStats['specializations']) && !empty($techniciansStats['specializations']))
                                <div class="border-t pt-4 mt-4">
                                    <h4 class="text-sm font-medium text-gray-700 mb-3 flex items-center">
                                        <svg class="w-4 h-4 mr-2 rtl:mr-0 rtl:ml-2" style="color: {{ $profileData['colors']['primary'] ?? '#3b82f6' }}" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                        </svg>
                                        {{ t('profile' . '.specializations') }}
                                    </h4>
                                    <div class="space-y-2">
                                        @foreach($techniciansStats['specializations'] as $specialization => $count)
                                            <div class="flex items-center justify-between p-2 bg-gray-50 rounded-lg">
                                                <span class="text-sm text-gray-700">{{ $specialization }}</span>
                                                <span class="text-sm font-medium" style="color: {{ $profileData['colors']['primary'] ?? '#3b82f6' }}">{{ $count }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                <!-- Statistics -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="border-b border-gray-200 px-6 py-4">
                        <h3 class="text-xl font-bold text-gray-900 flex items-center">
                            <div class="w-6 h-6 rounded-lg flex items-center justify-center mr-2 rtl:mr-0 rtl:ml-2" style="background-color: {{ $profileData['colors']['primary'] ?? '#3b82f6' }}20;">
                                <svg class="w-4 h-4" style="color: {{ $profileData['colors']['primary'] ?? '#3b82f6' }}" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z"/>
                                </svg>
                            </div>
                            {{ t('profile' . '.general_statistics') }}
                        </h3>
                    </div>
                    
                    <div class="p-6">
                        <div class="space-y-4">
                            @if(isset($stats) && is_array($stats))
                                @foreach($stats as $key => $value)
                                    <div class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl">
                                        <div class="flex items-center">
                                            @if($key === 'total_users')
                                                <svg class="w-5 h-5 text-blue-600 mr-2 rtl:mr-0 rtl:ml-2" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M16 13c1.66 0 2.99-1.34 2.99-3S17.66 7 16 7c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 7 8 7C6.34 7 5 8.34 5 10s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-0.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-0.5c0-2.33-4.67-3.5-7-3.5z"/>
                                                </svg>
                                            @elseif($key === 'total_claims')
                                                <svg class="w-5 h-5 text-green-600 mr-2 rtl:mr-0 rtl:ml-2" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M11 15h2v2h-2zm0-8h2v6h-2zm.99-5C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z"/>
                                                </svg>
                                            @elseif($key === 'total_complaints')
                                                <svg class="w-5 h-5 text-red-600 mr-2 rtl:mr-0 rtl:ml-2" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M1 21h4V9H1v12zm22-11c0-1.1-.9-2-2-2h-6.31l.95-4.57.03-.32c0-.41-.17-.79-.44-1.06L14.17 1 7.59 7.59C7.22 7.95 7 8.45 7 9v10c0 1.1.9 2 2 2h9c.83 0 1.54-.5 1.84-1.22l3.02-7.05c.09-.23.14-.47.14-.73v-2z"/>
                                                </svg>
                                            @elseif($key === 'total_technicians')
                                                <svg class="w-5 h-5 mr-2 rtl:mr-0 rtl:ml-2" style="color: {{ $profileData['colors']['primary'] ?? '#3b82f6' }}" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M22.7 19l-9.1-9.1c.9-2.3.4-5-1.5-6.9-2-2-5-2.4-7.4-1.3L9 6 6 9 1.6 4.7C.4 7.1.9 10.1 2.9 12.1c1.9 1.9 4.6 2.4 6.9 1.5l9.1 9.1c.4.4 1 .4 1.4 0l2.3-2.3c.5-.4.5-1.1.1-1.4z"/>
                                                </svg>
                                            @elseif($key === 'employee_count')
                                                <svg class="w-5 h-5 text-purple-600 mr-2 rtl:mr-0 rtl:ml-2" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/>
                                                </svg>
                                            @elseif($key === 'insured_cars_count')
                                                <svg class="w-5 h-5 text-blue-600 mr-2 rtl:mr-0 rtl:ml-2" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.5 16c-.83 0-1.5-.67-1.5-1.5S5.67 13 6.5 13s1.5.67 1.5 1.5S7.33 16 6.5 16zm11 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zM5 11l1.5-4.5h11L19 11H5z"/>
                                                </svg>
                                            @elseif($key === 'tow_trucks_count')
                                                <svg class="w-5 h-5 text-orange-600 mr-2 rtl:mr-0 rtl:ml-2" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M20 8h-3V4H3c-1.1 0-2 .9-2 2v11h2c0 1.66 1.34 3 3 3s3-1.34 3-3h6c0 1.66 1.34 3 3 3s3-1.34 3-3h2v-5l-3-4zM6 18.5c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zm13.5-9l1.96 2.5H17V9.5h2.5zm-1.5 9c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5z"/>
                                                </svg>
                                            @elseif($key === 'daily_tow_capacity')
                                                <svg class="w-5 h-5 text-indigo-600 mr-2 rtl:mr-0 rtl:ml-2" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M20.38 8.57l-1.23-1.23c-.51-.51-1.32-.51-1.83 0L9.93 14.7l-6.69-6.69c-.51-.51-1.32-.51-1.83 0L.18 9.25c-.51.51-.51 1.32 0 1.83l9.02 9.02c.51.51 1.32.51 1.83 0l13.35-13.35c.51-.51.51-1.32 0-1.83z"/>
                                                </svg>
                                            @elseif($key === 'policy_status')
                                                <svg class="w-5 h-5 text-green-600 mr-2 rtl:mr-0 rtl:ml-2" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M12 2L2 7v10c0 5.55 3.84 9.74 9 10.5c5.16-.76 9-4.95 9-10.5V7L12 2z"/>
                                                </svg>
                                            @elseif($key === 'company_name')
                                                <svg class="w-5 h-5 text-gray-600 mr-2 rtl:mr-0 rtl:ml-2" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M12 2L2 7v10c0 5.55 3.84 9.74 9 10.5c5.16-.76 9-4.95 9-10.5V7L12 2z"/>
                                                </svg>
                                            @else
                                                <svg class="w-5 h-5 text-gray-600 mr-2 rtl:mr-0 rtl:ml-2" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/>
                                                </svg>
                                            @endif
                                            <span class="text-sm font-medium text-gray-700">
                                                {{ t('profile' . '.' . $key) ?? $key }}
                                            </span>
                                        </div>
                                        <span class="text-lg font-bold" style="color: {{ $profileData['colors']['primary'] ?? '#3b82f6' }}">{{ $value ?? t('profile' . '.not_specified') }}</span>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="border-b border-gray-200 px-6 py-4">
                        <h3 class="text-xl font-bold text-gray-900 flex items-center">
                            <div class="w-6 h-6 rounded-lg flex items-center justify-center mr-2 rtl:mr-0 rtl:ml-2" style="background-color: {{ $profileData['colors']['primary'] ?? '#3b82f6' }}20;">
                                <svg class="w-4 h-4" style="color: {{ $profileData['colors']['primary'] ?? '#3b82f6' }}" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                            {{ t('profile' . '.quick_actions') }}
                        </h3>
                    </div>
                    
                    <div class="p-6">
                        <div class="space-y-3">
                            <!-- View Complaints -->
                            @if($userType === 'insurance_company')
                                <a href="{{ route('insurance.complaints.index', ['companyRoute' => $user->company_slug]) }}" 
                                   class="flex items-center p-3 rounded-xl hover:bg-gray-50 transition-colors duration-200 group">
                            @elseif($userType === 'insurance_user')
                                <a href="{{ route('insurance.user.complaints.index', ['companySlug' => $user->company->company_slug]) }}" 
                                   class="flex items-center p-3 rounded-xl hover:bg-gray-50 transition-colors duration-200 group">
                            @else
                                <a href="{{ route('service-center.complaints.index') }}" 
                                   class="flex items-center p-3 rounded-xl hover:bg-gray-50 transition-colors duration-200 group">
                            @endif
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 rtl:mr-0 rtl:ml-3 bg-red-100 group-hover:bg-red-200 transition-colors duration-200">
                                    <svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M1 21h4V9H1v12zm22-11c0-1.1-.9-2-2-2h-6.31l.95-4.57.03-.32c0-.41-.17-.79-.44-1.06L14.17 1 7.59 7.59C7.22 7.95 7 8.45 7 9v10c0 1.1.9 2 2 2h9c.83 0 1.54-.5 1.84-1.22l3.02-7.05c.09-.23.14-.47.14-.73v-2z"/>
                                    </svg>
                                </div>
                                <span class="text-gray-700 group-hover:text-gray-900 font-medium">{{ t('profile' . '.view_complaints') }}</span>
                            </a>
                            
                            <!-- Dashboard -->
                            @if($userType === 'insurance_company')
                                <a href="{{ route('insurance.dashboard', ['companyRoute' => $user->company_slug]) }}" 
                                   class="flex items-center p-3 rounded-xl hover:bg-gray-50 transition-colors duration-200 group">
                            @elseif($userType === 'insurance_user')
                                <a href="{{ route('insurance.user.dashboard', ['companySlug' => $user->company->company_slug]) }}" 
                                   class="flex items-center p-3 rounded-xl hover:bg-gray-50 transition-colors duration-200 group">
                            @else
                                <a href="{{ route('service-center.dashboard') }}" 
                                   class="flex items-center p-3 rounded-xl hover:bg-gray-50 transition-colors duration-200 group">
                            @endif
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 rtl:mr-0 rtl:ml-3 bg-blue-100 group-hover:bg-blue-200 transition-colors duration-200">
                                    <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
                                    </svg>
                                </div>
                                <span class="text-gray-700 group-hover:text-gray-900 font-medium">{{ t('profile' . '.home') }}</span>
                            </a>
                            
                            <!-- Settings (for companies only) -->
                            @if($userType === 'insurance_company')
                                <a href="{{ route('insurance.settings.index', ['companyRoute' => $user->company_slug]) }}" 
                                   class="flex items-center p-3 rounded-xl hover:bg-gray-50 transition-colors duration-200 group">
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 rtl:mr-0 rtl:ml-3 bg-gray-100 group-hover:bg-gray-200 transition-colors duration-200">
                                        <svg class="w-4 h-4 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M19.14,12.94c0.04-0.3,0.06-0.61,0.06-0.94c0-0.32-0.02-0.64-0.07-0.94l2.03-1.58c0.18-0.14,0.23-0.41,0.12-0.61 l-1.92-3.32c-0.12-0.22-0.37-0.29-0.59-0.22l-2.39,0.96c-0.5-0.38-1.03-0.7-1.62-0.94L14.4,2.81c-0.04-0.24-0.24-0.41-0.48-0.41 h-3.84c-0.24,0-0.43,0.17-0.47,0.41L9.25,5.35C8.66,5.59,8.12,5.92,7.63,6.29L5.24,5.33c-0.22-0.08-0.47,0-0.59,0.22L2.74,8.87 C2.62,9.08,2.66,9.34,2.86,9.48l2.03,1.58C4.84,11.36,4.82,11.69,4.82,12s0.02,0.64,0.07,0.94l-2.03,1.58 c-0.18,0.14-0.23,0.41-0.12,0.61l1.92,3.32c0.12,0.22,0.37,0.29,0.59,0.22l2.39-0.96c0.5,0.38,1.03,0.7,1.62,0.94l0.36,2.54 c0.05,0.24,0.24,0.41,0.48,0.41h3.84c0.24,0,0.44-0.17,0.47-0.41l0.36-2.54c0.59-0.24,1.13-0.56,1.62-0.94l2.39,0.96 c0.22,0.08,0.47,0,0.59-0.22l1.92-3.32c0.12-0.22,0.07-0.47-0.12-0.61L19.14,12.94z M12,15.6c-1.98,0-3.6-1.62-3.6-3.6 s1.62-3.6,3.6-3.6s3.6,1.62,3.6,3.6S13.98,15.6,12,15.6z"/>
                                        </svg>
                                    </div>
                                    <span class="text-gray-700 group-hover:text-gray-900 font-medium">{{ t('profile' . '.settings') }}</span>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Password Change Modal -->
<div id="passwordModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold text-gray-900 flex items-center">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ $isRtl ? 'ml-3' : 'mr-3' }}" style="background-color: {{ $profileData['colors']['primary'] ?? '#3b82f6' }}20;">
                        <svg class="w-5 h-5" style="color: {{ $profileData['colors']['primary'] ?? '#3b82f6' }}" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12.65 10C11.83 7.67 9.61 6 7 6c-3.31 0-6 2.69-6 6s2.69 6 6 6c2.61 0 4.83-1.67 5.65-4H17v4h4v-4h2v-4H12.65zM7 14c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2z"/>
                        </svg>
                    </div>
                    {{ t('profile' . '.change_password') }}
                </h3>
                <button onclick="closePasswordModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                    </svg>
                </button>
            </div>
        </div>
        
        @if($userType === 'insurance_company')
            <form method="POST" action="{{ route('insurance.profile.change-password', ['companyRoute' => $user->company_slug]) }}" class="p-6">
        @elseif($userType === 'insurance_user')
            <form method="POST" action="{{ route('insurance.user.profile.change-password', ['companySlug' => $user->company->company_slug]) }}" class="p-6">
        @else
            <form method="POST" action="{{ route('service-center.profile.change-password') }}" class="p-6">
        @endif
            @csrf
            
            <div class="space-y-6">
                <!-- Current Password (مع أيقونة العين) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                        <svg class="w-4 h-4 text-gray-500 {{ $isRtl ? 'ml-2' : 'mr-2' }}" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/>
                        </svg>
                        {{ t('profile' . '.current_password') }}
                    </label>
                    <div class="relative">
                        <input type="password" name="current_password" id="currentPassword" required 
                               class="w-full border border-gray-300 rounded-xl focus:ring-2 px-4 py-3 {{ $isRtl ? 'pl-12 pr-4' : 'pr-12 pl-4' }}" 
                               style="focus:ring-color: {{ $profileData['colors']['primary'] ?? '#3b82f6' }};">
                        <button type="button" onclick="togglePasswordVisibility('currentPassword', 'currentPasswordIcon')" 
                                class="absolute inset-y-0 {{ $isRtl ? 'left-0 pl-4' : 'right-0 pr-4' }} flex items-center text-gray-600 hover:text-gray-800 focus:outline-none transition-colors duration-200">
                            <svg id="currentPasswordIcon" class="w-5 h-5 password-eye-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 11-4.243-4.243m4.242 4.242L9.88 9.88"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                
                <!-- New Password (مع أيقونة العين المحسنة) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                        <svg class="w-4 h-4 text-gray-500 {{ $isRtl ? 'ml-2' : 'mr-2' }}" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/>
                        </svg>
                        {{ t('profile' . '.new_password') }}
                    </label>
                    <div class="relative">
                        <input type="password" name="password" id="newPassword" required 
                               class="w-full border border-gray-300 rounded-xl focus:ring-2 px-4 py-3 {{ $isRtl ? 'pl-12 pr-4' : 'pr-12 pl-4' }}"
                               style="focus:ring-color: {{ $profileData['colors']['primary'] ?? '#3b82f6' }};">
                        <button type="button" onclick="togglePasswordVisibility('newPassword', 'newPasswordIcon')" 
                                class="absolute inset-y-0 {{ $isRtl ? 'left-0 pl-4' : 'right-0 pr-4' }} flex items-center text-gray-600 hover:text-gray-800 focus:outline-none transition-colors duration-200">
                            <svg id="newPasswordIcon" class="w-5 h-5 password-eye-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 11-4.243-4.243m4.242 4.242L9.88 9.88"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                
                <!-- Confirm Password (مع أيقونة العين المحسنة) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                        <svg class="w-4 h-4 text-gray-500 {{ $isRtl ? 'ml-2' : 'mr-2' }}" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                        </svg>
                        {{ t('profile' . '.confirm_password') }}
                    </label>
                    <div class="relative">
                        <input type="password" name="password_confirmation" id="confirmPassword" required 
                               class="w-full border border-gray-300 rounded-xl focus:ring-2 px-4 py-3 {{ $isRtl ? 'pl-12 pr-4' : 'pr-12 pl-4' }}"
                               style="focus:ring-color: {{ $profileData['colors']['primary'] ?? '#3b82f6' }};">
                        <button type="button" onclick="togglePasswordVisibility('confirmPassword', 'confirmPasswordIcon')" 
                                class="absolute inset-y-0 {{ $isRtl ? 'left-0 pl-4' : 'right-0 pr-4' }} flex items-center text-gray-600 hover:text-gray-800 focus:outline-none transition-colors duration-200">
                            <svg id="confirmPasswordIcon" class="w-5 h-5 password-eye-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 11-4.243-4.243m4.242 4.242L9.88 9.88"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="flex gap-3 mt-8">
                <button type="submit" 
                        class="flex-1 py-3 px-6 text-white rounded-xl font-medium transition-colors duration-200 flex items-center justify-center"
                        style="background-color: {{ $profileData['colors']['primary'] ?? '#3b82f6' }};">
                    <svg class="w-4 h-4 {{ $isRtl ? 'ml-2' : 'mr-2' }}" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V7l-4-4zm-5 16c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3zm3-10H5V5h10v4z"/>
                    </svg>
                    {{ t('profile' . '.save_changes') }}
                </button>
                <button type="button" onclick="closePasswordModal()" 
                        class="flex-1 py-3 px-6 bg-gray-500 text-white rounded-xl font-medium hover:bg-gray-600 transition-colors duration-200">
                    {{ t('profile' . '.cancel') }}
                </button>
            </div>
        </form>
    </div>
</div>

<style>
.password-eye-icon {
    stroke: #4b5563 !important;
    stroke-width: 2;
    filter: contrast(1.2) brightness(1.1);
    transition: all 0.3s ease;
}

.password-eye-icon:hover {
    stroke: #374151 !important;
    transform: scale(1.1);
}

@media (prefers-color-scheme: dark) {
    .password-eye-icon {
        stroke: #9ca3af !important;
    }
    
    .password-eye-icon:hover {
        stroke: #d1d5db !important;
    }
}
</style>

<script>
function openPasswordModal() {
    document.getElementById('passwordModal').classList.remove('hidden');
}

function closePasswordModal() {
    document.getElementById('passwordModal').classList.add('hidden');
}

// Toggle password visibility with enhanced icons
function togglePasswordVisibility(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    
    if (input.type === 'password') {
        // تغيير نوع الـ input لإظهار كلمة المرور
        input.type = 'text';
        
        // تغيير الأيقونة إلى العين المفتوحة
        icon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
        `;
    } else {
        // تغيير نوع الـ input لإخفاء كلمة المرور
        input.type = 'password';
        
        // تغيير الأيقونة إلى العين المغلقة الجديدة (واضحة ومحسنة)
        icon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 11-4.243-4.243m4.242 4.242L9.88 9.88"></path>
        `;
    }
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


@if(session('success'))
    <div class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-xl shadow-lg z-50 animate-pulse">
        <svg class="w-4 h-4 inline mr-2 rtl:mr-0 rtl:ml-2" fill="currentColor" viewBox="0 0 24 24">
            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
        </svg>
        {{ session('success') }}
    </div>
    <script>
        setTimeout(() => {
            document.querySelector('.fixed.top-4').remove();
        }, 4000);
    </script>
@endif

@if($errors->any())
    <div class="fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-xl shadow-lg z-50">
        <svg class="w-4 h-4 inline mr-2 rtl:mr-0 rtl:ml-2" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
        </svg>
        {{ $errors->first() }}
    </div>
    <script>
        setTimeout(() => {
            document.querySelector('.fixed.top-4').remove();
        }, 5000);
    </script>
@endif
@endsection
