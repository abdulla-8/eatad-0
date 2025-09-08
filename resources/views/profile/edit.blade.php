{{-- resources/views/profile/edit.blade.php --}}
@extends($userType === 'insurance_company' ? 'insurance.layouts.app' : ($userType === 'service_center' ? 'service-center.layouts.app' : 'insurance-user.layouts.app'))

@section('title', t('profile'. '.edit_profile'))

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
    <div class="max-w-4xl mx-auto py-8 px-2 sm:px-3 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center gap-4">
                @if($userType === 'insurance_company')
                    <a href="{{ route('insurance.profile.show', ['companyRoute' => $user->company_slug]) }}" 
                       class="p-3 rounded-xl bg-white shadow-md hover:bg-gray-50 transition-colors duration-200">
                @elseif($userType === 'insurance_user')
                    <a href="{{ route('insurance.user.profile.show', ['companySlug' => $user->company->company_slug]) }}" 
                       class="p-3 rounded-xl bg-white shadow-md hover:bg-gray-50 transition-colors duration-200">
                @else
                    <a href="{{ route('service-center.profile.show') }}" 
                       class="p-3 rounded-xl bg-white shadow-md hover:bg-gray-50 transition-colors duration-200">
                @endif
                    <svg class="w-5 h-5 text-gray-600 {{ app()->getLocale() === 'ar' ? 'rotate-180' : '' }}" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ t('profile'. '.edit_profile') }}</h1>
                    <p class="text-gray-600 mt-1">{{ t('profile'. '.update_personal_information') }}</p>
                </div>
            </div>
        </div>

        <!-- Edit Form -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            @if($userType === 'insurance_company')
                <form method="POST" action="{{ route('insurance.profile.update', ['companyRoute' => $user->company_slug]) }}">
            @elseif($userType === 'insurance_user')
                <form method="POST" action="{{ route('insurance.user.profile.update', ['companySlug' => $user->company->company_slug]) }}">
            @else
                <form method="POST" action="{{ route('service-center.profile.update') }}">
            @endif
                @csrf
                @method('PUT')
                
                <!-- Form Header -->
                <div class="border-b border-gray-200 px-3 md:px-8 py-6">
                    <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 rtl:mr-0 rtl:ml-3" style="background-color: {{ $profileData['colors']['primary'] ?? '#3b82f6' }}20;">
                            <svg class="w-5 h-5" style="color: {{ $profileData['colors']['primary'] ?? '#3b82f6' }}" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                            </svg>
                        </div>
                        {{ t('profile'. '.edit_personal_information') }}
                    </h2>
                </div>

                <!-- Form Fields -->
                <div class="px-3 md:px-8 py-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($profileData['display_fields'] as $field => $info)
                            <div class="group">
                                <label for="{{ $field }}" class="block text-sm font-medium text-gray-700 mb-3 flex items-center">
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-2 rtl:mr-0 rtl:ml-2" style="background-color: {{ $profileData['colors']['primary'] ?? '#3b82f6' }}15;">
                                        @if($field === 'legal_name' || $field === 'full_name')
                                            <svg class="w-4 h-4" style="color: {{ $profileData['colors']['primary'] ?? '#3b82f6' }}" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/>
                                            </svg>
                                        @elseif($field === 'phone')
                                            <svg class="w-4 h-4" style="color: {{ $profileData['colors']['primary'] ?? '#3b82f6' }}" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
                                            </svg>
                                        @elseif($field === 'email')
                                            <svg class="w-4 h-4" style="color: {{ $profileData['colors']['primary'] ?? '#3b82f6' }}" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.89 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                                            </svg>
                                        @elseif($field === 'commercial_register')
                                            <svg class="w-4 h-4" style="color: {{ $profileData['colors']['primary'] ?? '#3b82f6' }}" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/>
                                            </svg>
                                        @elseif($field === 'tax_number')
                                            <svg class="w-4 h-4" style="color: {{ $profileData['colors']['primary'] ?? '#3b82f6' }}" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z"/>
                                            </svg>
                                        @elseif(str_contains($field, 'address'))
                                            <svg class="w-4 h-4" style="color: {{ $profileData['colors']['primary'] ?? '#3b82f6' }}" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                                            </svg>
                                        @elseif($field === 'employee_count')
                                            <svg class="w-4 h-4" style="color: {{ $profileData['colors']['primary'] ?? '#3b82f6' }}" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M16 13c1.66 0 2.99-1.34 2.99-3S17.66 7 16 7c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 7 8 7C6.34 7 5 8.34 5 10s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-0.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-0.5c0-2.33-4.67-3.5-7-3.5z"/>
                                            </svg>
                                        @elseif($field === 'insured_cars_count')
                                            <svg class="w-4 h-4" style="color: {{ $profileData['colors']['primary'] ?? '#3b82f6' }}" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.5 16c-.83 0-1.5-.67-1.5-1.5S5.67 13 6.5 13s1.5.67 1.5 1.5S7.33 16 6.5 16zm11 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zM5 11l1.5-4.5h11L19 11H5z"/>
                                            </svg>
                                        @elseif(str_contains($field, 'technicians'))
                                            <svg class="w-4 h-4" style="color: {{ $profileData['colors']['primary'] ?? '#3b82f6' }}" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M22.7 19l-9.1-9.1c.9-2.3.4-5-1.5-6.9-2-2-5-2.4-7.4-1.3L9 6 6 9 1.6 4.7C.4 7.1.9 10.1 2.9 12.1c1.9 1.9 4.6 2.4 6.9 1.5l9.1 9.1c.4.4 1 .4 1.4 0l2.3-2.3c.5-.4.5-1.1.1-1.4z"/>
                                            </svg>
                                        @elseif($field === 'national_id')
                                            <svg class="w-4 h-4" style="color: {{ $profileData['colors']['primary'] ?? '#3b82f6' }}" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                            </svg>
                                        @elseif($field === 'policy_number')
                                            <svg class="w-4 h-4" style="color: {{ $profileData['colors']['primary'] ?? '#3b82f6' }}" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 2L2 7v10c0 5.55 3.84 9.74 9 10.5c5.16-.76 9-4.95 9-10.5V7L12 2z"/>
                                            </svg>
                                        @elseif($field === 'website')
                                            <svg class="w-4 h-4" style="color: {{ $profileData['colors']['primary'] ?? '#3b82f6' }}" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/>
                                            </svg>
                                        @elseif($field === 'license_number')
                                            <svg class="w-4 h-4" style="color: {{ $profileData['colors']['primary'] ?? '#3b82f6' }}" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                            </svg>
                                        @elseif($field === 'tow_trucks_count')
                                            <svg class="w-4 h-4" style="color: {{ $profileData['colors']['primary'] ?? '#3b82f6' }}" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M20 8h-3V4H3c-1.1 0-2 .9-2 2v11h2c0 1.66 1.34 3 3 3s3-1.34 3-3h6c0 1.66 1.34 3 3 3s3-1.34 3-3h2v-5l-3-4zM6 18.5c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zm13.5-9l1.96 2.5H17V9.5h2.5zm-1.5 9c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5z"/>
                                            </svg>
                                        @elseif($field === 'daily_tow_capacity')
                                            <svg class="w-4 h-4" style="color: {{ $profileData['colors']['primary'] ?? '#3b82f6' }}" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M20.38 8.57l-1.23-1.23c-.51-.51-1.32-.51-1.83 0L9.93 14.7l-6.69-6.69c-.51-.51-1.32-.51-1.83 0L.18 9.25c-.51.51-.51 1.32 0 1.83l9.02 9.02c.51.51 1.32.51 1.83 0l13.35-13.35c.51-.51.51-1.32 0-1.83z"/>
                                            </svg>
                                        @elseif($field === 'specialization')
                                            <svg class="w-4 h-4" style="color: {{ $profileData['colors']['primary'] ?? '#3b82f6' }}" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                            </svg>
                                        @elseif($field === 'established_date')
                                            <svg class="w-4 h-4" style="color: {{ $profileData['colors']['primary'] ?? '#3b82f6' }}" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z"/>
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4" style="color: {{ $profileData['colors']['primary'] ?? '#3b82f6' }}" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
                                            </svg>
                                        @endif
                                    </div>
                                    {{ t('profile'. '.' . $field) ?? $info['label'] }}
                                </label>
                                
                                @if(str_contains($field, 'address'))
                                    <textarea id="{{ $field }}" 
                                              name="{{ $field }}" 
                                              rows="3"
                                              class="w-full border border-gray-300 rounded-xl focus:ring-2 focus:border-transparent px-4 py-3 transition-all duration-200 @error($field) border-red-500 @enderror"
                                              style="focus:ring-color: {{ $profileData['colors']['primary'] ?? '#3b82f6' }};"
                                              placeholder="{{ t('profile'. '.enter') }} {{ t('profile'. '.' . $field) ?? $info['label'] }}">{{ old($field, $user->$field) }}</textarea>
                                @else
                                    <input type="{{ str_contains($field, 'count') || str_contains($field, 'technicians') ? 'number' : 'text' }}" 
                                           id="{{ $field }}" 
                                           name="{{ $field }}" 
                                           value="{{ old($field, $user->$field) }}"
                                           {{ str_contains($field, 'count') || str_contains($field, 'technicians') ? 'min="0"' : '' }}
                                           class="w-full border border-gray-300 rounded-xl focus:ring-2 focus:border-transparent px-4 py-3 transition-all duration-200 @error($field) border-red-500 @enderror"
                                           style="focus:ring-color: {{ $profileData['colors']['primary'] ?? '#3b82f6' }};"
                                           placeholder="{{ t('profile'. '.enter') }} {{ t('profile'. '.' . $field) ?? $info['label'] }}">
                                @endif
                                
                                @error($field)
                                    <p class="text-red-500 text-sm mt-2 flex items-center">
                                        <svg class="w-4 h-4 mr-1 rtl:mr-0 rtl:ml-1" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        @endforeach
                    </div>
                </div>

  <!-- Tow Service Availability Toggle (for Service Centers) -->
  @if( $userType === 'service_center')
                        <div class="mt-8 p-6 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-lg flex items-center justify-center bg-blue-100">
                                        <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M20 8h-3V4H3c-1.1 0-2 .9-2 2v11h2c0 1.66 1.34 3 3 3s3-1.34 3-3h6c0 1.66 1.34 3 3 3s3-1.34 3-3h2v-5l-3-4zM6 18.5c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zm13.5-9l1.96 2.5H17V9.5h2.5zm-1.5 9c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">{{ t('profile'. '.tow_service_availability') }}</h3>
                                        <p class="text-sm text-gray-600">{{ t('profile'. '.tow_service_availability_description') }}</p>
                                    </div>
                                </div>
                                
                                <!-- Toggle Switch -->
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" 
                                           name="has_tow_service" 
                                           value="1" 
                                           class="sr-only peer"
                                           {{ old('has_tow_service', $user->has_tow_service ?? false) ? 'checked' : '' }}>
                                    <div class="w-14 h-7 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-blue-600"></div>
                                    <span class="ml-3 text-sm font-medium text-gray-700">
                                        {{ old('has_tow_service', $user->has_tow_service ?? false) ? t('profile'. '.enabled') : t('profile'. '.disabled') }}
                                    </span>
                                </label>
                            </div>
                            
                            <!-- Status Indicator -->
                            <div class="mt-4 flex items-center gap-2">
                                @if(old('has_tow_service', $user->has_tow_service ?? false))
                                    <div class="flex items-center gap-2 px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                                        </svg>
                                        {{ t('profile'. '.tow_service_enabled') }}
                                    </div>
                                @else
                                    <div class="flex items-center gap-2 px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm font-medium">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                                        </svg>
                                        {{ t('profile'. '.tow_service_disabled') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>



                <!-- Service Center Classification Section (for Service Centers) -->
@if($userType === 'service_center')
    <div class="mt-8 p-6 bg-gradient-to-r from-purple-50 to-indigo-50 rounded-xl border border-purple-200">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 rounded-lg flex items-center justify-center bg-purple-100">
                <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-xl font-bold text-gray-900">{{ t('profile.service_center_classification') }}</h3>
                <p class="text-sm text-gray-600">{{ t('profile.service_center_classification_description') }}</p>
            </div>
        </div>

        <!-- Classification Type Selection -->
        <div class="space-y-4">
            <div class="flex gap-6">
                <label class="flex items-center cursor-pointer">
                    <input type="radio" name="classification" value="unclassified" 
                           {{ old('classification', $user->classification ?? 'unclassified') === 'unclassified' ? 'checked' : '' }}
                           class="w-4 h-4 text-purple-600 border-gray-300 focus:ring-purple-500">
                    <span class="ml-2 text-sm font-medium text-gray-700">{{ t('profile.unclassified') }}</span>
                </label>
                <label class="flex items-center cursor-pointer">
                    <input type="radio" name="classification" value="classified" 
                           {{ old('classification', $user->classification ?? 'unclassified') === 'classified' ? 'checked' : '' }}
                           class="w-4 h-4 text-purple-600 border-gray-300 focus:ring-purple-500">
                    <span class="ml-2 text-sm font-medium text-gray-700">{{ t('profile.classified') }}</span>
                </label>
            </div>

            <!-- Classified Options (shown when classified is selected) -->
            <div id="classified-options" class="space-y-6 {{ old('classification', $user->classification ?? 'unclassified') === 'classified' ? '' : 'hidden' }}">
                
                <!-- Photo Upload -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ t('profile.classification_photo') }}
                    </label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-purple-400 transition-colors">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="classification_photo" class="relative cursor-pointer bg-white rounded-md font-medium text-purple-600 hover:text-purple-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-purple-500">
                                    <span>{{ t('profile.upload_photo') }}</span>
                                    <input id="classification_photo" name="classification_photo" type="file" class="sr-only" accept="image/*">
                                </label>
                                <p class="pl-1">{{ t('profile.or_drag_drop') }}</p>
                            </div>
                            <p class="text-xs text-gray-500">{{ t('profile.photo_requirements') }}</p>
                        </div>
                    </div>
                    @if($user->classification_photo)
                        <div class="mt-2">
                            <img src="{{ asset('storage/' . $user->classification_photo) }}" alt="Current photo" class="h-20 w-20 object-cover rounded-lg">
                        </div>
                    @endif
                </div>

                <!-- Star Rating -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ t('profile.classification_rating') }}
                    </label>
                    <div class="flex items-center space-x-1 rtl:space-x-reverse">
                        @for($i = 1; $i <= 5; $i++)
                            <button type="button" 
                                    class="star-rating text-2xl text-gray-300 hover:text-yellow-400 focus:outline-none focus:text-yellow-400 transition-colors"
                                    data-rating="{{ $i }}">
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                </svg>
                            </button>
                        @endfor
                        <input type="hidden" name="classification_rating" id="classification_rating" value="{{ old('classification_rating', $user->classification_rating ?? 0) }}">
                        <span class="ml-2 text-sm text-gray-600" id="rating-text">{{ t('profile.select_rating') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endif

                <!-- Form Actions -->
                <div class="border-t border-gray-200 px-4 md:px-8 py-6">
                    <div class="flex gap-2 md:gap-4 ">
                        <button type="submit" 
                                class="flex-1 flex items-center justify-center py-3 px-6 text-white rounded-xl font-medium transition-all duration-200 hover:shadow-lg"
                                style="background-color: {{ $profileData['colors']['primary'] ?? '#3b82f6' }};">
                            <svg class="w-4 h-4 mr-2 rtl:mr-0 rtl:ml-2" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V7l-4-4zm-5 16c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3zm3-10H5V5h10v4z"/>
                            </svg>
                            {{ t('profile'. '.save_changes') }}
                        </button>
                        
                        @if($userType === 'insurance_company')
                            <a href="{{ route('insurance.profile.show', ['companyRoute' => $user->company_slug]) }}" 
                               class="flex-1 flex items-center justify-center py-3 px-6 bg-gray-500 text-white rounded-xl font-medium hover:bg-gray-600 transition-colors duration-200">
                        @elseif($userType === 'insurance_user')
                            <a href="{{ route('insurance.user.profile.show', ['companySlug' => $user->company->company_slug]) }}" 
                               class="flex-1 flex items-center justify-center py-3 px-6 bg-gray-500 text-white rounded-xl font-medium hover:bg-gray-600 transition-colors duration-200">
                        @else
                            <a href="{{ route('service-center.profile.show') }}" 
                               class="flex-1 flex items-center justify-center py-3 px-6 bg-gray-500 text-white rounded-xl font-medium hover:bg-gray-600 transition-colors duration-200">
                        @endif
                            <svg class="w-4 h-4 mr-2 rtl:mr-0 rtl:ml-2" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                            </svg>
                            {{ t('profile'. '.cancel') }}
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

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
        {{ t('profile'. '.please_fix_errors_below') }}
    </div>
    <script>
        setTimeout(() => {
            document.querySelector('.fixed.top-4').remove();
        }, 5000);
    </script>
@endif

<script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle classified options visibility
            const classificationRadios = document.querySelectorAll('input[name="classification"]');
            const classifiedOptions = document.getElementById('classified-options');
            const ratingInput = document.getElementById('classification_rating');
            const ratingText = document.getElementById('rating-text');
            const photoInput = document.getElementById('classification_photo');

            // Endpoint (only for service center)
            const classificationUpdateUrl = "{{ $userType === 'service_center' ? route('service-center.profile.classification.update') : '' }}";
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            function sendClassificationAjax() {
                if (!classificationUpdateUrl) return; // only service center

                const checked = document.querySelector('input[name="classification"]:checked');
                if (!checked) return;

                const formData = new FormData();
                formData.append('classification', checked.value);

                // Only send rating/photo if classified
                if (checked.value === 'classified') {
                    const ratingVal = ratingInput?.value || '';
                    if (ratingVal) formData.append('classification_rating', ratingVal);
                    if (photoInput && photoInput.files && photoInput.files[0]) {
                        formData.append('classification_photo', photoInput.files[0]);
                    }
                }

                // Spoof PUT for Laravel when using FormData
                formData.append('_method', 'PUT');

                fetch(classificationUpdateUrl, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken || '',
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(async (res) => {
                    const data = await res.json().catch(() => ({}));
                    if (!res.ok) throw data;
                    // Optional: show lightweight success feedback
                    console.log('Classification updated', data);
                })
                .catch((err) => {
                    console.error('Failed to update classification', err);
                });
            }

            classificationRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    if (this.value === 'classified') {
                        classifiedOptions.classList.remove('hidden');
                    } else {
                        classifiedOptions.classList.add('hidden');
                    }
                    // Trigger AJAX on change
                    sendClassificationAjax();
                });
            });

            // Star rating functionality
            const stars = document.querySelectorAll('.star-rating');
            stars.forEach((star, index) => {
                star.addEventListener('click', function() {
                    const rating = parseInt(this.dataset.rating);
                    ratingInput.value = rating;

                    // Update star display
                    stars.forEach((s, i) => {
                        if (i < rating) {
                            s.classList.remove('text-gray-300');
                            s.classList.add('text-yellow-400');
                        } else {
                            s.classList.remove('text-yellow-400');
                            s.classList.add('text-gray-300');
                        }
                    });

                    // Update rating text
                    const ratingTexts = {
                        1: '{{ t("profile.very_poor") }}',
                        2: '{{ t("profile.poor") }}',
                        3: '{{ t("profile.average") }}',
                        4: '{{ t("profile.good") }}',
                        5: '{{ t("profile.excellent") }}'
                    };
                    ratingText.textContent = ratingTexts[rating] || '{{ t("profile.select_rating") }}';

                    // Trigger AJAX after selecting rating
                    sendClassificationAjax();
                });
            });

            // Trigger AJAX when user selects a photo
            if (photoInput) {
                photoInput.addEventListener('change', function() {
                    sendClassificationAjax();
                });
            }

            // Initialize rating display
            const currentRating = parseInt(ratingInput.value);
            if (currentRating > 0) {
                const starsInit = document.querySelectorAll('.star-rating');
                starsInit.forEach((star, index) => {
                    if (index < currentRating) {
                        star.classList.remove('text-gray-300');
                        star.classList.add('text-yellow-400');
                    }
                });
            }
        });
    </script>

@endsection

