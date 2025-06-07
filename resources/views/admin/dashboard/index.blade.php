@extends('admin.layouts.app')

@section('title', t('admin.dashboard'))

@section('content')

<!-- Welcome Card -->
<div class="bg-gradient-to-r from-dark-900 to-dark-800 rounded-xl p-6 lg:p-8 text-white mb-8">
    <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between">
        <div class="flex items-center space-x-4 {{ $isRtl ? 'space-x-reverse' : '' }} mb-4 lg:mb-0">
            <div class="w-16 h-16 bg-gold-500 rounded-xl p-3">
                <img src="{{ asset('logo.png') }}" alt="{{ t('admin.site_name') }}" class="w-full h-full object-contain">
            </div>
            <div>
                <h1 class="text-2xl lg:text-3xl font-bold">{{ t('admin.welcome_message') }}</h1>
                <p class="text-gray-300">{{ t('admin.dashboard_description') }}</p>
            </div>
        </div>
        <div class="text-{{ $isRtl ? 'left' : 'right' }}">
            <p class="text-gold-400 text-sm">{{ now()->format('Y-m-d') }}</p>
            <p class="text-xl font-bold">{{ now()->format('H:i') }}</p>
        </div>
    </div>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Languages -->
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">{{ t('admin.total_languages') }}</p>
                <p class="text-3xl font-bold text-gray-900">{{ $activeLanguages->count() }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path>
                </svg>
            </div>
        </div>
    </div>
    
    <!-- Current Language -->
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">{{ t('admin.current_language') }}</p>
                <p class="text-xl font-bold text-gray-900">{{ $currentLanguage->name ?? 'N/A' }}</p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>
    
    <!-- Text Direction -->
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">{{ t('admin.text_direction') }}</p>
                <p class="text-xl font-bold text-gray-900">{{ $isRtl ? 'RTL' : 'LTR' }}</p>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16"></path>
                </svg>
            </div>
        </div>
    </div>
    
    <!-- System Status -->
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">{{ t('admin.system_status') }}</p>
                <p class="text-xl font-bold text-green-600">{{ t('admin.active') }}</p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Actions & Activity Grid -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    
    <!-- Quick Actions -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="bg-gold-500 text-dark-900 px-6 py-4 rounded-t-xl">
            <h3 class="text-lg font-bold flex items-center">
                <svg class="w-5 h-5 {{ $isRtl ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
                {{ t('admin.quick_actions') }}
            </h3>
        </div>
        <div class="p-6 space-y-4">
            <a href="{{ route('admin.languages.index') }}" 
               class="flex items-center p-4 bg-gray-50 hover:bg-gold-50 rounded-lg transition-colors duration-200 group border border-gray-200 hover:border-gold-300">
                <div class="w-10 h-10 bg-gold-500 rounded-lg flex items-center justify-center {{ $isRtl ? 'ml-4' : 'mr-4' }} group-hover:scale-110 transition-transform duration-200">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path>
                    </svg>
                </div>
                <span class="font-semibold text-gray-900 group-hover:text-gold-800 transition-colors duration-200">{{ t('admin.manage_languages') }}</span>
            </a>
            
            <div class="flex items-center p-4 bg-gray-50 rounded-lg border border-gray-200 opacity-60">
                <div class="w-10 h-10 bg-gray-400 rounded-lg flex items-center justify-center {{ $isRtl ? 'ml-4' : 'mr-4' }}">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                </div>
                <span class="font-semibold text-gray-600">{{ t('admin.manage_users') }} ({{ t('admin.coming_soon') }})</span>
            </div>
        </div>
    </div>
    
    <!-- Recent Activity -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="bg-dark-900 text-white px-6 py-4 rounded-t-xl">
            <h3 class="text-lg font-bold flex items-center">
                <svg class="w-5 h-5 {{ $isRtl ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ t('admin.recent_activity') }}
            </h3>
        </div>
        <div class="p-6 space-y-4">
            <div class="flex items-center p-3 bg-green-50 rounded-lg border border-green-200">
                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center {{ $isRtl ? 'ml-3' : 'mr-3' }}">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="font-medium text-gray-900">{{ t('admin.admin_logged_in') }}</p>
                    <p class="text-sm text-gray-600">{{ now()->format('Y-m-d H:i') }}</p>
                </div>
                <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full font-medium">{{ t('admin.login') }}</span>
            </div>
            
            <div class="flex items-center p-3 bg-blue-50 rounded-lg border border-blue-200">
                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center {{ $isRtl ? 'ml-3' : 'mr-3' }}">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="font-medium text-gray-900">{{ t('admin.languages_loaded') }}</p>
                    <p class="text-sm text-gray-600">{{ t('admin.system_initialized') }}</p>
                </div>
                <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full font-medium">{{ t('admin.system') }}</span>
            </div>
        </div>
    </div>
</div>

@endsection