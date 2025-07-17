{{-- resources/views/insurance/service-centers/create.blade.php --}}
@extends('insurance.layouts.app')

@section('title', 'إضافة مركز صيانة جديد')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 py-8">
    <!-- Container أوسع يغطي كامل الشاشة -->
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-full">
        
        <!-- Header محسن مع عرض أكبر -->
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden mb-8 border border-gray-100">
            <div class="relative bg-gradient-to-r from-green-600 via-green-700 to-emerald-800 px-6 sm:px-8 lg:px-12 py-8 lg:py-12">
                <!-- نمط هندسي في الخلفية -->
                <div class="absolute inset-0 opacity-10">
                    <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                        <polygon points="0,0 100,0 85,100 0,75" fill="white"/>
                    </svg>
                </div>
                
                <div class="relative z-10 flex flex-col xl:flex-row items-start xl:items-center justify-between gap-8">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6">
                        <!-- أيقونة محسنة -->
                        <div class="w-20 h-20 lg:w-28 lg:h-28 bg-white/20 backdrop-blur-sm rounded-3xl flex items-center justify-center border-2 border-white/40 shadow-2xl">
                            <svg class="w-10 h-10 lg:w-14 lg:h-14 text-white drop-shadow-lg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                        
                        <!-- النصوص مع تحسين المسافات -->
                        <div class="text-white">
                            <h1 class="text-3xl lg:text-5xl font-bold mb-3 drop-shadow-lg leading-tight">
                                إضافة مركز صيانة جديد
                            </h1>
                            <p class="text-lg lg:text-xl text-green-100 font-medium drop-shadow-md">
                                قم بإضافة مركز صيانة جديد تابع لشركتك
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Container مع عرض كامل -->
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden border border-gray-100">
            <form method="POST" action="{{ route('insurance.service-centers.store', ['companyRoute' => auth()->user()->company_slug]) }}" enctype="multipart/form-data">
                @csrf
                
                <!-- Form Header محسن -->
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-8 lg:px-12 py-8 border-b border-gray-200">
                    <h2 class="text-2xl lg:text-3xl font-bold text-gray-900 flex items-center">
                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center {{ app()->getLocale() === 'ar' ? 'ml-4' : 'mr-4' }} shadow-lg bg-gradient-to-br from-green-500 to-emerald-600">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        المعلومات الأساسية
                    </h2>
                </div>

                <div class="px-8 lg:px-12 py-8 lg:py-12">
                    <!-- Basic Information مع Grid محسن -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-8 mb-12">
                        <!-- Legal Name -->
                        <div class="space-y-3">
                            <label class="flex items-center text-sm font-bold text-gray-700 {{ app()->getLocale() === 'ar' ? 'text-right' : 'text-left' }}">
                                <div class="w-8 h-8 rounded-xl flex items-center justify-center {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }} bg-green-100">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                                اسم المركز القانوني
                                <span class="text-red-500 {{ app()->getLocale() === 'ar' ? 'mr-2' : 'ml-2' }}">*</span>
                            </label>
                            <input type="text" name="legal_name" value="{{ old('legal_name') }}" required
                                   class="w-full px-5 py-4 border-2 border-gray-200 rounded-2xl focus:border-green-500 focus:ring-4 focus:ring-green-100 transition-all duration-300 text-gray-900 text-lg shadow-sm hover:shadow-md {{ app()->getLocale() === 'ar' ? 'text-right' : 'text-left' }}"
                                   placeholder="أدخل اسم المركز القانوني">
                            @error('legal_name')
                                <p class="text-red-500 text-sm mt-2 flex items-center {{ app()->getLocale() === 'ar' ? 'flex-row-reverse' : 'flex-row' }}">
                                    <svg class="w-4 h-4 {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Center Slug -->
                        <div class="space-y-3">
                            <label class="flex items-center text-sm font-bold text-gray-700 {{ app()->getLocale() === 'ar' ? 'text-right' : 'text-left' }}">
                                <div class="w-8 h-8 rounded-xl flex items-center justify-center {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }} bg-green-100">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                    </svg>
                                </div>
                                رمز المركز
                                <span class="text-red-500 {{ app()->getLocale() === 'ar' ? 'mr-2' : 'ml-2' }}">*</span>
                            </label>
                            <input type="text" name="center_slug" value="{{ old('center_slug') }}" required
                                   class="w-full px-5 py-4 border-2 border-gray-200 rounded-2xl focus:border-green-500 focus:ring-4 focus:ring-green-100 transition-all duration-300 text-gray-900 text-lg shadow-sm hover:shadow-md {{ app()->getLocale() === 'ar' ? 'text-right' : 'text-left' }}"
                                   placeholder="center-slug">
                            @error('center_slug')
                                <p class="text-red-500 text-sm mt-2 flex items-center {{ app()->getLocale() === 'ar' ? 'flex-row-reverse' : 'flex-row' }}">
                                    <svg class="w-4 h-4 {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div class="space-y-3">
                            <label class="flex items-center text-sm font-bold text-gray-700 {{ app()->getLocale() === 'ar' ? 'text-right' : 'text-left' }}">
                                <div class="w-8 h-8 rounded-xl flex items-center justify-center {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }} bg-green-100">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                </div>
                                رقم الهاتف
                                <span class="text-red-500 {{ app()->getLocale() === 'ar' ? 'mr-2' : 'ml-2' }}">*</span>
                            </label>
                            <input type="tel" name="phone" value="{{ old('phone') }}" required
                                   class="w-full px-5 py-4 border-2 border-gray-200 rounded-2xl focus:border-green-500 focus:ring-4 focus:ring-green-100 transition-all duration-300 text-gray-900 text-lg shadow-sm hover:shadow-md {{ app()->getLocale() === 'ar' ? 'text-right' : 'text-left' }}"
                                   placeholder="966501234567">
                            @error('phone')
                                <p class="text-red-500 text-sm mt-2 flex items-center {{ app()->getLocale() === 'ar' ? 'flex-row-reverse' : 'flex-row' }}">
                                    <svg class="w-4 h-4 {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="space-y-3">
                            <label class="flex items-center text-sm font-bold text-gray-700 {{ app()->getLocale() === 'ar' ? 'text-right' : 'text-left' }}">
                                <div class="w-8 h-8 rounded-xl flex items-center justify-center {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }} bg-green-100">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                </div>
                                كلمة المرور
                                <span class="text-red-500 {{ app()->getLocale() === 'ar' ? 'mr-2' : 'ml-2' }}">*</span>
                            </label>
                            <input type="password" name="password" required
                                   class="w-full px-5 py-4 border-2 border-gray-200 rounded-2xl focus:border-green-500 focus:ring-4 focus:ring-green-100 transition-all duration-300 text-gray-900 text-lg shadow-sm hover:shadow-md {{ app()->getLocale() === 'ar' ? 'text-right' : 'text-left' }}"
                                   placeholder="أدخل كلمة المرور">
                            @error('password')
                                <p class="text-red-500 text-sm mt-2 flex items-center {{ app()->getLocale() === 'ar' ? 'flex-row-reverse' : 'flex-row' }}">
                                    <svg class="w-4 h-4 {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Commercial Register -->
                        <div class="space-y-3">
                            <label class="flex items-center text-sm font-bold text-gray-700 {{ app()->getLocale() === 'ar' ? 'text-right' : 'text-left' }}">
                                <div class="w-8 h-8 rounded-xl flex items-center justify-center {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }} bg-green-100">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                السجل التجاري
                                <span class="text-red-500 {{ app()->getLocale() === 'ar' ? 'mr-2' : 'ml-2' }}">*</span>
                            </label>
                            <input type="text" name="commercial_register" value="{{ old('commercial_register') }}" required
                                   class="w-full px-5 py-4 border-2 border-gray-200 rounded-2xl focus:border-green-500 focus:ring-4 focus:ring-green-100 transition-all duration-300 text-gray-900 text-lg shadow-sm hover:shadow-md {{ app()->getLocale() === 'ar' ? 'text-right' : 'text-left' }}"
                                   placeholder="أدخل رقم السجل التجاري">
                            @error('commercial_register')
                                <p class="text-red-500 text-sm mt-2 flex items-center {{ app()->getLocale() === 'ar' ? 'flex-row-reverse' : 'flex-row' }}">
                                    <svg class="w-4 h-4 {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Tax Number -->
                        <div class="space-y-3">
                            <label class="flex items-center text-sm font-bold text-gray-700 {{ app()->getLocale() === 'ar' ? 'text-right' : 'text-left' }}">
                                <div class="w-8 h-8 rounded-xl flex items-center justify-center {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }} bg-green-100">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                الرقم الضريبي
                            </label>
                            <input type="text" name="tax_number" value="{{ old('tax_number') }}"
                                   class="w-full px-5 py-4 border-2 border-gray-200 rounded-2xl focus:border-green-500 focus:ring-4 focus:ring-green-100 transition-all duration-300 text-gray-900 text-lg shadow-sm hover:shadow-md {{ app()->getLocale() === 'ar' ? 'text-right' : 'text-left' }}"
                                   placeholder="أدخل الرقم الضريبي">
                            @error('tax_number')
                                <p class="text-red-500 text-sm mt-2 flex items-center {{ app()->getLocale() === 'ar' ? 'flex-row-reverse' : 'flex-row' }}">
                                    <svg class="w-4 h-4 {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <!-- Location Information -->
                    <div class="mb-12">
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-3xl p-8 border border-blue-100">
                            <h3 class="text-2xl font-bold text-gray-900 mb-8 flex items-center {{ app()->getLocale() === 'ar' ? 'text-right' : 'text-left' }}">
                                <div class="w-12 h-12 rounded-2xl flex items-center justify-center {{ app()->getLocale() === 'ar' ? 'ml-4' : 'mr-4' }} shadow-lg bg-gradient-to-br from-blue-500 to-indigo-600">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                                معلومات الموقع
                            </h3>
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                <!-- Industrial Area -->
                                <div class="space-y-3">
                                    <label class="flex items-center text-sm font-bold text-gray-700 {{ app()->getLocale() === 'ar' ? 'text-right' : 'text-left' }}">
                                        <div class="w-8 h-8 rounded-xl flex items-center justify-center {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }} bg-blue-100">
                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                            </svg>
                                        </div>
                                        المنطقة الصناعية
                                    </label>
                                    <select name="industrial_area_id" 
                                            class="w-full px-5 py-4 border-2 border-gray-200 rounded-2xl focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300 text-gray-900 text-lg shadow-sm hover:shadow-md {{ app()->getLocale() === 'ar' ? 'text-right' : 'text-left' }}">
                                        <option value="">اختر المنطقة الصناعية</option>
                                        @foreach($industrialAreas as $area)
                                            <option value="{{ $area->id }}" {{ old('industrial_area_id') == $area->id ? 'selected' : '' }}>
                                                {{ $area->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('industrial_area_id')
                                        <p class="text-red-500 text-sm mt-2 flex items-center {{ app()->getLocale() === 'ar' ? 'flex-row-reverse' : 'flex-row' }}">
                                            <svg class="w-4 h-4 {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- Service Specialization -->
                                <div class="space-y-3">
                                    <label class="flex items-center text-sm font-bold text-gray-700 {{ app()->getLocale() === 'ar' ? 'text-right' : 'text-left' }}">
                                        <div class="w-8 h-8 rounded-xl flex items-center justify-center {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }} bg-blue-100">
                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                            </svg>
                                        </div>
                                        التخصص
                                    </label>
                                    <select name="service_specialization_id" 
                                            class="w-full px-5 py-4 border-2 border-gray-200 rounded-2xl focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300 text-gray-900 text-lg shadow-sm hover:shadow-md {{ app()->getLocale() === 'ar' ? 'text-right' : 'text-left' }}">
                                        <option value="">اختر التخصص</option>
                                        @foreach($serviceSpecializations as $specialization)
                                            <option value="{{ $specialization->id }}" {{ old('service_specialization_id') == $specialization->id ? 'selected' : '' }}>
                                                {{ $specialization->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('service_specialization_id')
                                        <p class="text-red-500 text-sm mt-2 flex items-center {{ app()->getLocale() === 'ar' ? 'flex-row-reverse' : 'flex-row' }}">
                                            <svg class="w-4 h-4 {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- Center Address -->
                                <div class="space-y-3 lg:col-span-2">
                                    <label class="flex items-center text-sm font-bold text-gray-700 {{ app()->getLocale() === 'ar' ? 'text-right' : 'text-left' }}">
                                        <div class="w-8 h-8 rounded-xl flex items-center justify-center {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }} bg-blue-100">
                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                        </div>
                                        عنوان المركز
                                    </label>
                                    <textarea name="center_address" rows="4"
                                              class="w-full px-5 py-4 border-2 border-gray-200 rounded-2xl focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300 text-gray-900 text-lg shadow-sm hover:shadow-md resize-none {{ app()->getLocale() === 'ar' ? 'text-right' : 'text-left' }}"
                                              placeholder="أدخل عنوان المركز التفصيلي">{{ old('center_address') }}</textarea>
                                    @error('center_address')
                                        <p class="text-red-500 text-sm mt-2 flex items-center {{ app()->getLocale() === 'ar' ? 'flex-row-reverse' : 'flex-row' }}">
                                            <svg class="w-4 h-4 {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Technicians Information -->
                    <div class="mb-12">
                        <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-3xl p-8 border border-purple-100">
                            <h3 class="text-2xl font-bold text-gray-900 mb-8 flex items-center {{ app()->getLocale() === 'ar' ? 'text-right' : 'text-left' }}">
                                <div class="w-12 h-12 rounded-2xl flex items-center justify-center {{ app()->getLocale() === 'ar' ? 'ml-4' : 'mr-4' }} shadow-lg bg-gradient-to-br from-purple-500 to-pink-600">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                                معلومات الفنيين
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6">
                                <!-- Body Work Technicians -->
                                <div class="space-y-3">
                                    <label class="flex items-center text-sm font-bold text-gray-700 {{ app()->getLocale() === 'ar' ? 'text-right' : 'text-left' }}">
                                        <div class="w-8 h-8 rounded-xl flex items-center justify-center {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }} bg-purple-100">
                                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM21 5a2 2 0 00-2-2h-4a2 2 0 00-2 2v12a4 4 0 004 4 4 4 0 004-4V5z"></path>
                                            </svg>
                                        </div>
                                        فنيو هيكل
                                    </label>
                                    <input type="number" name="body_work_technicians" value="{{ old('body_work_technicians', 0) }}" min="0"
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-2xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 text-gray-900 text-center text-lg font-semibold shadow-sm hover:shadow-md">
                                    @error('body_work_technicians')
                                        <p class="text-red-500 text-sm mt-2 flex items-center {{ app()->getLocale() === 'ar' ? 'flex-row-reverse' : 'flex-row' }}">
                                            <svg class="w-4 h-4 {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- Mechanical Technicians -->
                                <div class="space-y-3">
                                    <label class="flex items-center text-sm font-bold text-gray-700 {{ app()->getLocale() === 'ar' ? 'text-right' : 'text-left' }}">
                                        <div class="w-8 h-8 rounded-xl flex items-center justify-center {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }} bg-purple-100">
                                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                        </div>
                                        فنيو ميكانيكا
                                    </label>
                                    <input type="number" name="mechanical_technicians" value="{{ old('mechanical_technicians', 0) }}" min="0"
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-2xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 text-gray-900 text-center text-lg font-semibold shadow-sm hover:shadow-md">
                                    @error('mechanical_technicians')
                                        <p class="text-red-500 text-sm mt-2 flex items-center {{ app()->getLocale() === 'ar' ? 'flex-row-reverse' : 'flex-row' }}">
                                            <svg class="w-4 h-4 {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- Painting Technicians -->
                                <div class="space-y-3">
                                    <label class="flex items-center text-sm font-bold text-gray-700 {{ app()->getLocale() === 'ar' ? 'text-right' : 'text-left' }}">
                                        <div class="w-8 h-8 rounded-xl flex items-center justify-center {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }} bg-purple-100">
                                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM21 5a2 2 0 00-2-2h-4a2 2 0 00-2 2v12a4 4 0 004 4 4 4 0 004-4V5z"></path>
                                            </svg>
                                        </div>
                                        فنيو دهان
                                    </label>
                                    <input type="number" name="painting_technicians" value="{{ old('painting_technicians', 0) }}" min="0"
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-2xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 text-gray-900 text-center text-lg font-semibold shadow-sm hover:shadow-md">
                                    @error('painting_technicians')
                                        <p class="text-red-500 text-sm mt-2 flex items-center {{ app()->getLocale() === 'ar' ? 'flex-row-reverse' : 'flex-row' }}">
                                            <svg class="w-4 h-4 {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- Electrical Technicians -->
                                <div class="space-y-3">
                                    <label class="flex items-center text-sm font-bold text-gray-700 {{ app()->getLocale() === 'ar' ? 'text-right' : 'text-left' }}">
                                        <div class="w-8 h-8 rounded-xl flex items-center justify-center {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }} bg-purple-100">
                                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                            </svg>
                                        </div>
                                        فنيو كهرباء
                                    </label>
                                    <input type="number" name="electrical_technicians" value="{{ old('electrical_technicians', 0) }}" min="0"
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-2xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 text-gray-900 text-center text-lg font-semibold shadow-sm hover:shadow-md">
                                    @error('electrical_technicians')
                                        <p class="text-red-500 text-sm mt-2 flex items-center {{ app()->getLocale() === 'ar' ? 'flex-row-reverse' : 'flex-row' }}">
                                            <svg class="w-4 h-4 {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- Other Technicians -->
                                <div class="space-y-3">
                                    <label class="flex items-center text-sm font-bold text-gray-700 {{ app()->getLocale() === 'ar' ? 'text-right' : 'text-left' }}">
                                        <div class="w-8 h-8 rounded-xl flex items-center justify-center {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }} bg-purple-100">
                                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                        </div>
                                        فنيو آخرين
                                    </label>
                                    <input type="number" name="other_technicians" value="{{ old('other_technicians', 0) }}" min="0"
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-2xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 text-gray-900 text-center text-lg font-semibold shadow-sm hover:shadow-md">
                                    @error('other_technicians')
                                        <p class="text-red-500 text-sm mt-2 flex items-center {{ app()->getLocale() === 'ar' ? 'flex-row-reverse' : 'flex-row' }}">
                                            <svg class="w-4 h-4 {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <div class="mb-12">
                        <div class="bg-gradient-to-r from-orange-50 to-red-50 rounded-3xl p-8 border border-orange-100">
                            <h3 class="text-2xl font-bold text-gray-900 mb-8 flex items-center {{ app()->getLocale() === 'ar' ? 'text-right' : 'text-left' }}">
                                <div class="w-12 h-12 rounded-2xl flex items-center justify-center {{ app()->getLocale() === 'ar' ? 'ml-4' : 'mr-4' }} shadow-lg bg-gradient-to-br from-orange-500 to-red-600">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                معلومات إضافية
                            </h3>
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                <!-- Center Area -->
                                <div class="space-y-3">
                                    <label class="flex items-center text-sm font-bold text-gray-700 {{ app()->getLocale() === 'ar' ? 'text-right' : 'text-left' }}">
                                        <div class="w-8 h-8 rounded-xl flex items-center justify-center {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }} bg-orange-100">
                                            <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
                                            </svg>
                                        </div>
                                        مساحة المركز (متر مربع)
                                    </label>
                                    <input type="number" name="center_area_sqm" value="{{ old('center_area_sqm') }}" min="0" step="0.01"
                                           class="w-full px-5 py-4 border-2 border-gray-200 rounded-2xl focus:border-orange-500 focus:ring-4 focus:ring-orange-100 transition-all duration-300 text-gray-900 text-lg shadow-sm hover:shadow-md {{ app()->getLocale() === 'ar' ? 'text-right' : 'text-left' }}"
                                           placeholder="مساحة المركز">
                                    @error('center_area_sqm')
                                        <p class="text-red-500 text-sm mt-2 flex items-center {{ app()->getLocale() === 'ar' ? 'flex-row-reverse' : 'flex-row' }}">
                                            <svg class="w-4 h-4 {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- Center Logo -->
                                <div class="space-y-3">
                                    <label class="flex items-center text-sm font-bold text-gray-700 {{ app()->getLocale() === 'ar' ? 'text-right' : 'text-left' }}">
                                        <div class="w-8 h-8 rounded-xl flex items-center justify-center {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }} bg-orange-100">
                                            <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                        شعار المركز
                                    </label>
                                    <input type="file" name="center_logo" accept="image/*"
                                           class="w-full px-5 py-4 border-2 border-gray-200 rounded-2xl focus:border-orange-500 focus:ring-4 focus:ring-orange-100 transition-all duration-300 text-gray-900 text-lg shadow-sm hover:shadow-md file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100">
                                    @error('center_logo')
                                        <p class="text-red-500 text-sm mt-2 flex items-center {{ app()->getLocale() === 'ar' ? 'flex-row-reverse' : 'flex-row' }}">
                                            <svg class="w-4 h-4 {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Status Options -->
                    <div class="mb-12">
                        <div class="bg-gradient-to-r from-teal-50 to-cyan-50 rounded-3xl p-8 border border-teal-100">
                            <h3 class="text-2xl font-bold text-gray-900 mb-8 flex items-center {{ app()->getLocale() === 'ar' ? 'text-right' : 'text-left' }}">
                                <div class="w-12 h-12 rounded-2xl flex items-center justify-center {{ app()->getLocale() === 'ar' ? 'ml-4' : 'mr-4' }} shadow-lg bg-gradient-to-br from-teal-500 to-cyan-600">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                الحالة
                            </h3>
                            <div class="flex items-center {{ app()->getLocale() === 'ar' ? 'justify-end' : 'justify-start' }}">
                                <label class="flex items-center cursor-pointer group">
                                    <div class="relative">
                                        <input type="checkbox" name="is_active" value="1" {{ old('is_active') ? 'checked' : '' }}
                                               class="sr-only">
                                        <div class="block bg-gray-300 w-14 h-8 rounded-full transition-colors group-hover:bg-gray-400"></div>
                                        <div class="dot absolute left-1 top-1 bg-white w-6 h-6 rounded-full transition-transform"></div>
                                    </div>
                                    <span class="text-lg font-semibold text-gray-700 {{ app()->getLocale() === 'ar' ? 'mr-4' : 'ml-4' }}">تفعيل المركز</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-6 pt-8 border-t-2 border-gray-200">
                        <a href="{{ route('insurance.service-centers.index', ['companyRoute' => auth()->user()->company_slug]) }}" 
                           class="w-full sm:w-auto px-8 py-4 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-2xl font-bold transition-all duration-300 flex items-center justify-center shadow-lg hover:shadow-xl {{ app()->getLocale() === 'ar' ? 'text-right' : 'text-left' }}">
                            <svg class="w-5 h-5 {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            إلغاء
                        </a>
                        <button type="submit" 
                                class="w-full sm:w-auto px-8 py-4 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white rounded-2xl font-bold transition-all duration-300 flex items-center justify-center shadow-lg hover:shadow-xl transform hover:scale-105 {{ app()->getLocale() === 'ar' ? 'text-right' : 'text-left' }}">
                            <svg class="w-5 h-5 {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                            </svg>
                            حفظ المركز
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
input[type="checkbox"]:checked + .block {
    background-color: #10b981;
}

input[type="checkbox"]:checked + .block .dot {
    transform: translateX(100%);
}

@media (max-width: 768px) {
    .flex-row-reverse {
        flex-direction: row-reverse;
    }
}
</style>
@endsection
