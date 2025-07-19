@extends('tow-service.layouts.app')
@section('title', 'تعديل البروفايل - خدمة السحب')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
    <div class="max-w-4xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        
        <!-- Header Section -->
        <div class="relative overflow-hidden rounded-2xl mb-8 shadow-xl">
            <!-- Background Gradient -->
            <div class="absolute inset-0 bg-gradient-to-r from-yellow-500 to-yellow-600"></div>
            
            <!-- Content -->
            <div class="relative z-10 px-6 py-8">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between space-y-4 sm:space-y-0">
                    <div class="text-white">
                        <h1 class="text-2xl sm:text-3xl font-bold mb-2 flex items-center">
                            <svg class="w-8 h-8 mr-3 rtl:mr-0 rtl:ml-3 opacity-90" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                            </svg>
                            تعديل البروفايل
                        </h1>
                        <p class="text-xl opacity-90">
                            {{ $profileData['company_info']['type'] ?? 'غير محدد' }}
                        </p>
                    </div>
                    
                    <a href="{{ route('tow-service.profile.show') }}" 
                       class="inline-flex items-center px-6 py-3 bg-white/20 backdrop-blur-sm text-white rounded-xl font-medium hover:bg-white/30 transition-all duration-200 border border-white/30">
                        <svg class="w-5 h-5 mr-2 rtl:mr-0 rtl:ml-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19 7v4H5.83l3.58-3.59L8 6l-6 6 6 6 1.41-1.41L5.83 13H21V7z"/>
                        </svg>
                        العودة للبروفايل
                    </a>
                </div>
            </div>
        </div>

        <!-- Edit Form -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="border-b border-gray-200 px-6 py-6">
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900 flex items-center">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 rtl:mr-0 rtl:ml-3 bg-yellow-100">
                        <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                        </svg>
                    </div>
                    تعديل المعلومات الشخصية
                </h2>
            </div>
            
            <form method="POST" action="{{ route('tow-service.profile.update') }}" class="p-6">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    @if(isset($profileData['display_fields']) && is_array($profileData['display_fields']))
                        @foreach($profileData['display_fields'] as $field => $fieldData)
                            <div class="space-y-2">
                                <label for="{{ $field }}" class="block text-sm font-medium text-gray-700 flex items-center">
                                    @if($field === 'legal_name' || $field === 'full_name')
                                        <svg class="w-4 h-4 text-yellow-600 mr-2 rtl:mr-0 rtl:ml-2" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/>
                                        </svg>
                                    @elseif($field === 'phone')
                                        <svg class="w-4 h-4 text-yellow-600 mr-2 rtl:mr-0 rtl:ml-2" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
                                        </svg>
                                    @elseif($field === 'commercial_register')
                                        <svg class="w-4 h-4 text-yellow-600 mr-2 rtl:mr-0 rtl:ml-2" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/>
                                        </svg>
                                    @elseif($field === 'tax_number')
                                        <svg class="w-4 h-4 text-yellow-600 mr-2 rtl:mr-0 rtl:ml-2" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z"/>
                                        </svg>
                                    @elseif(str_contains($field, 'address'))
                                        <svg class="w-4 h-4 text-yellow-600 mr-2 rtl:mr-0 rtl:ml-2" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                                        </svg>
                                    @elseif($field === 'national_id')
                                        <svg class="w-4 h-4 text-yellow-600 mr-2 rtl:mr-0 rtl:ml-2" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                        </svg>
                                    @elseif($field === 'daily_capacity')
                                        <svg class="w-4 h-4 text-yellow-600 mr-2 rtl:mr-0 rtl:ml-2" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                        </svg>
                                    @elseif($field === 'tow_truck_plate_number')
                                        <svg class="w-4 h-4 text-yellow-600 mr-2 rtl:mr-0 rtl:ml-2" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M20,8h-3V4H3C1.89,4 1,4.89 1,6v12h2c0,1.66 1.34,3 3,3s3-1.34 3-3h6c0,1.66 1.34,3 3,3s3-1.34 3-3h2v-5L20,8z M6,18.5c-0.83,0 -1.5,-0.67 -1.5,-1.5s0.67,-1.5 1.5,-1.5s1.5,0.67 1.5,1.5S6.83,18.5 6,18.5z M18,18.5c-0.83,0 -1.5,-0.67 -1.5,-1.5s0.67,-1.5 1.5,-1.5s1.5,0.67 1.5,1.5S18.83,18.5 18,18.5z M19,13h-4V9h1.5l2.5,4z"/>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4 text-yellow-600 mr-2 rtl:mr-0 rtl:ml-2" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/>
                                        </svg>
                                    @endif
                                    {{ is_array($fieldData) ? $fieldData['label'] : $fieldData }}
                                </label>
                                
                                @if(str_contains($field, 'address'))
                                    <textarea 
                                        id="{{ $field }}" 
                                        name="{{ $field }}" 
                                        rows="3"
                                        class="w-full border border-gray-300 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-transparent px-4 py-3 @error($field) border-red-500 @enderror"
                                        placeholder="أدخل {{ is_array($fieldData) ? $fieldData['label'] : $fieldData }}"
                                    >{{ old($field, $user->$field ?? '') }}</textarea>
                                @else
                                    <input 
                                        type="{{ $field === 'phone' ? 'tel' : ($field === 'daily_capacity' ? 'number' : 'text') }}" 
                                        id="{{ $field }}" 
                                        name="{{ $field }}" 
                                        value="{{ old($field, $user->$field ?? '') }}"
                                        class="w-full border border-gray-300 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-transparent px-4 py-3 @error($field) border-red-500 @enderror"
                                        placeholder="أدخل {{ is_array($fieldData) ? $fieldData['label'] : $fieldData }}"
                                        {{ in_array($field, ['legal_name', 'full_name', 'phone', 'commercial_register', 'national_id', 'tow_truck_plate_number']) ? 'required' : '' }}
                                        {{ $field === 'daily_capacity' ? 'min="0"' : '' }}
                                    >
                                @endif
                                
                                @error($field)
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        @endforeach
                    @endif
                </div>
                
                <div class="flex flex-col sm:flex-row gap-4 mt-8 pt-6 border-t border-gray-200">
                    <button type="submit" 
                            class="flex-1 sm:flex-none inline-flex items-center justify-center px-8 py-3 bg-yellow-500 text-white rounded-xl font-medium hover:bg-yellow-600 transition-colors duration-200 shadow-lg">
                        <svg class="w-5 h-5 mr-2 rtl:mr-0 rtl:ml-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V7l-4-4zm-5 16c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3zm3-10H5V5h10v4z"/>
                        </svg>
                        حفظ التغييرات
                    </button>
                    
                    <a href="{{ route('tow-service.profile.show') }}" 
                       class="flex-1 sm:flex-none inline-flex items-center justify-center px-8 py-3 bg-gray-500 text-white rounded-xl font-medium hover:bg-gray-600 transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2 rtl:mr-0 rtl:ml-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19 7v4H5.83l3.58-3.59L8 6l-6 6 6 6 1.41-1.41L5.83 13H21V7z"/>
                        </svg>
                        إلغاء
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

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
