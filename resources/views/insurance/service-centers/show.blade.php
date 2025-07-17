{{-- resources/views/insurance/service-centers/show.blade.php --}}
@extends('insurance.layouts.app')

@section('title', 'تفاصيل مركز الصيانة')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-4">
        
        <!-- Header الرئيسي -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-6">
            <div class="relative px-6 py-8" style="background: linear-gradient(135deg, {{ auth()->user()->primary_color ?? '#3b82f6' }}, {{ auth()->user()->secondary_color ?? '#1e40af' }});">
                <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
                    <!-- معلومات المركز -->
                    <div class="flex items-center gap-4">
                        <!-- شعار المركز -->
                        <div class="w-16 h-16 lg:w-20 lg:h-20 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center border border-white/30">
                            @if($serviceCenter->center_logo)
                                <img src="{{ asset('storage/' . $serviceCenter->center_logo) }}" 
                                     alt="{{ $serviceCenter->legal_name }}" 
                                     class="w-12 h-12 lg:w-16 lg:h-16 rounded-xl object-cover">
                            @else
                                <svg class="w-8 h-8 lg:w-10 lg:h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            @endif
                        </div>
                        
                        <!-- النصوص -->
                        <div class="text-white">
                            <h1 class="text-2xl lg:text-3xl font-bold mb-2">{{ $serviceCenter->legal_name }}</h1>
                            <p class="text-white/80 text-sm lg:text-base">{{ $serviceCenter->center_slug }}</p>
                        </div>
                    </div>
                    
                    <!-- الحالة -->
                    <div class="flex flex-col sm:flex-row gap-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $serviceCenter->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            <svg class="w-4 h-4 {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                @if($serviceCenter->is_active)
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                @else
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                @endif
                            </svg>
                            {{ $serviceCenter->is_active ? 'نشط' : 'غير نشط' }}
                        </span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $serviceCenter->is_approved ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800' }}">
                            <svg class="w-4 h-4 {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                @if($serviceCenter->is_approved)
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                @else
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                @endif
                            </svg>
                            {{ $serviceCenter->is_approved ? 'معتمد' : 'في انتظار الموافقة' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- أزرار الإجراءات -->
        <div class="flex items-center justify-end gap-3 mb-6">
            <a href="{{ route('insurance.service-centers.index', ['companyRoute' => auth()->user()->company_slug]) }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-400 transition-colors duration-200">
                <svg class="w-4 h-4 {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                العودة للقائمة
            </a>
            <a href="{{ route('insurance.service-centers.edit', ['companyRoute' => auth()->user()->company_slug, 'serviceCenter' => $serviceCenter->id]) }}" 
               class="inline-flex items-center px-4 py-2 text-white rounded-lg font-medium transition-colors duration-200"
               style="background-color: {{ auth()->user()->primary_color ?? '#3b82f6' }};">
                <svg class="w-4 h-4 {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                تعديل المركز
            </a>
        </div>

        <!-- المحتوى الرئيسي -->
        <div class="space-y-6">
            <!-- المعلومات الأساسية -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }}" style="color: {{ auth()->user()->primary_color ?? '#3b82f6' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        المعلومات الأساسية
                    </h2>
                </div>
                
                <div class="px-6 py-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                        <!-- اسم المركز القانوني -->
                        <div class="space-y-2">
                            <label class="flex items-center text-sm font-semibold text-gray-700">
                                <svg class="w-4 h-4 {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}" style="color: {{ auth()->user()->primary_color ?? '#3b82f6' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                اسم المركز القانوني
                            </label>
                            <div class="px-4 py-3 bg-gray-50 rounded-lg border border-gray-200">
                                <span class="text-gray-900 font-medium">{{ $serviceCenter->legal_name }}</span>
                            </div>
                        </div>

                        <!-- رقم الهاتف -->
                        <div class="space-y-2">
                            <label class="flex items-center text-sm font-semibold text-gray-700">
                                <svg class="w-4 h-4 {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}" style="color: {{ auth()->user()->primary_color ?? '#3b82f6' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                رقم الهاتف الأساسي
                            </label>
                            <div class="px-4 py-3 bg-gray-50 rounded-lg border border-gray-200">
                                <span class="text-gray-900 font-medium">{{ $serviceCenter->phone }}</span>
                            </div>
                        </div>

                        <!-- السجل التجاري -->
                        <div class="space-y-2">
                            <label class="flex items-center text-sm font-semibold text-gray-700">
                                <svg class="w-4 h-4 {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}" style="color: {{ auth()->user()->primary_color ?? '#3b82f6' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                السجل التجاري
                            </label>
                            <div class="px-4 py-3 bg-gray-50 rounded-lg border border-gray-200">
                                <span class="text-gray-900 font-medium">{{ $serviceCenter->commercial_register }}</span>
                            </div>
                        </div>

                        <!-- الرقم الضريبي -->
                        <div class="space-y-2">
                            <label class="flex items-center text-sm font-semibold text-gray-700">
                                <svg class="w-4 h-4 {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}" style="color: {{ auth()->user()->primary_color ?? '#3b82f6' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                                الرقم الضريبي
                            </label>
                            <div class="px-4 py-3 bg-gray-50 rounded-lg border border-gray-200">
                                <span class="text-gray-900 font-medium">{{ $serviceCenter->tax_number ?: 'غير محدد' }}</span>
                            </div>
                        </div>

                        <!-- المنطقة الصناعية -->
                        <div class="space-y-2">
                            <label class="flex items-center text-sm font-semibold text-gray-700">
                                <svg class="w-4 h-4 {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}" style="color: {{ auth()->user()->primary_color ?? '#3b82f6' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                المنطقة الصناعية
                            </label>
                            <div class="px-4 py-3 bg-gray-50 rounded-lg border border-gray-200">
                                <span class="text-gray-900 font-medium">{{ $serviceCenter->industrialArea->name ?? 'غير محدد' }}</span>
                            </div>
                        </div>

                        <!-- التخصص -->
                        <div class="space-y-2">
                            <label class="flex items-center text-sm font-semibold text-gray-700">
                                <svg class="w-4 h-4 {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}" style="color: {{ auth()->user()->primary_color ?? '#3b82f6' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                </svg>
                                التخصص
                            </label>
                            <div class="px-4 py-3 bg-gray-50 rounded-lg border border-gray-200">
                                <span class="text-gray-900 font-medium">{{ $serviceCenter->serviceSpecialization->name ?? 'غير محدد' }}</span>
                            </div>
                        </div>

                        <!-- مساحة المركز -->
                        <div class="space-y-2">
                            <label class="flex items-center text-sm font-semibold text-gray-700">
                                <svg class="w-4 h-4 {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}" style="color: {{ auth()->user()->primary_color ?? '#3b82f6' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
                                </svg>
                                مساحة المركز
                            </label>
                            <div class="px-4 py-3 bg-gray-50 rounded-lg border border-gray-200">
                                <span class="text-gray-900 font-medium">{{ $serviceCenter->center_area_sqm ? number_format($serviceCenter->center_area_sqm, 2) . ' متر مربع' : 'غير محدد' }}</span>
                            </div>
                        </div>

                        <!-- تاريخ الإنشاء -->
                        <div class="space-y-2">
                            <label class="flex items-center text-sm font-semibold text-gray-700">
                                <svg class="w-4 h-4 {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}" style="color: {{ auth()->user()->primary_color ?? '#3b82f6' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                تاريخ الإنشاء
                            </label>
                            <div class="px-4 py-3 bg-gray-50 rounded-lg border border-gray-200">
                                <span class="text-gray-900 font-medium">{{ $serviceCenter->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- عنوان المركز -->
            @if($serviceCenter->center_address)
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }}" style="color: {{ auth()->user()->primary_color ?? '#3b82f6' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        عنوان المركز
                    </h2>
                </div>
                <div class="px-6 py-6">
                    <div class="px-4 py-3 bg-gray-50 rounded-lg border border-gray-200">
                        <p class="text-gray-900 leading-relaxed">{{ $serviceCenter->center_address }}</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- إحصائيات الفنيين وأرقام الهواتف الإضافية -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- إحصائيات الفنيين -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }}" style="color: {{ auth()->user()->primary_color ?? '#3b82f6' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            إحصائيات الفنيين
                        </h3>
                    </div>
                    <div class="px-6 py-6">
                        <div class="space-y-4">
                            <!-- إجمالي الفنيين -->
                            <div class="rounded-xl p-4" style="background-color: {{ auth()->user()->primary_color ?? '#3b82f6' }}15;">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium" style="color: {{ auth()->user()->primary_color ?? '#3b82f6' }}">إجمالي الفنيين</span>
                                    <span class="text-2xl font-bold" style="color: {{ auth()->user()->primary_color ?? '#3b82f6' }}">
                                        {{ $serviceCenter->body_work_technicians + $serviceCenter->mechanical_technicians + $serviceCenter->painting_technicians + $serviceCenter->electrical_technicians + $serviceCenter->other_technicians }}
                                    </span>
                                </div>
                            </div>

                            <!-- فنيو هيكل -->
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-gray-600 {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                                    </svg>
                                    <span class="text-sm font-medium text-gray-700">فنيو هيكل</span>
                                </div>
                                <span class="text-lg font-bold text-gray-900">{{ $serviceCenter->body_work_technicians }}</span>
                            </div>

                            <!-- فنيو ميكانيكا -->
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-gray-600 {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span class="text-sm font-medium text-gray-700">فنيو ميكانيكا</span>
                                </div>
                                <span class="text-lg font-bold text-gray-900">{{ $serviceCenter->mechanical_technicians }}</span>
                            </div>

                            <!-- فنيو دهان -->
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-gray-600 {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM7 3V1m0 18v2M5 9h4"></path>
                                    </svg>
                                    <span class="text-sm font-medium text-gray-700">فنيو دهان</span>
                                </div>
                                <span class="text-lg font-bold text-gray-900">{{ $serviceCenter->painting_technicians }}</span>
                            </div>

                            <!-- فنيو كهرباء -->
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-gray-600 {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                    <span class="text-sm font-medium text-gray-700">فنيو كهرباء</span>
                                </div>
                                <span class="text-lg font-bold text-gray-900">{{ $serviceCenter->electrical_technicians }}</span>
                            </div>

                            <!-- فنيو آخرين -->
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-gray-600 {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a1 1 0 01-1-1V9a1 1 0 011-1h1a2 2 0 100-4H4a1 1 0 01-1-1V4a1 1 0 011-1h3a1 1 0 001-1v-1z"></path>
                                    </svg>
                                    <span class="text-sm font-medium text-gray-700">فنيو آخرين</span>
                                </div>
                                <span class="text-lg font-bold text-gray-900">{{ $serviceCenter->other_technicians }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- أرقام الهواتف الإضافية -->
                @if($serviceCenter->additionalPhones->where('is_primary', false)->count() > 0)
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }}" style="color: {{ auth()->user()->primary_color ?? '#3b82f6' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            أرقام الهواتف الإضافية
                        </h3>
                    </div>
                    <div class="px-6 py-6">
                        <div class="space-y-3">
                            @foreach($serviceCenter->additionalPhones->where('is_primary', false) as $phone)
                                <div class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }}" style="background-color: {{ auth()->user()->primary_color ?? '#3b82f6' }}20;">
                                        <svg class="w-4 h-4" style="color: {{ auth()->user()->primary_color ?? '#3b82f6' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-gray-900 font-medium">{{ $phone->phone }}</p>
                                        <p class="text-sm text-gray-600">{{ $phone->label ?: 'هاتف إضافي' }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
