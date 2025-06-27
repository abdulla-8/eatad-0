@extends('insurance-user.layouts.app')

@section('title', t($company->translation_group . '.dashboard', 'Dashboard'))

@section('content')

<!-- Welcome Card -->
<div class="rounded-xl p-6 lg:p-8 text-white mb-8" style="background: linear-gradient(to right, {{ $company->primary_color }}, {{ $company->secondary_color }});">
    <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between">
        <div class="flex items-center space-x-4 {{ $isRtl ? 'space-x-reverse' : '' }} mb-4 lg:mb-0">
            <div class="w-16 h-16 bg-white/20 rounded-xl p-3 backdrop-blur-sm">
                @if($company->company_logo)
                    <img src="{{ $company->logo_url }}" alt="{{ $company->legal_name }}" class="w-full h-full object-contain">
                @else
                    <svg class="w-full h-full text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                @endif
            </div>
            <div>
                <h1 class="text-2xl lg:text-3xl font-bold">{{ t($company->translation_group . '.welcome_back', 'Welcome Back') }}</h1>
                <p class="text-white/80">{{ $stats['user_info']['full_name'] }}</p>
            </div>
        </div>
        <div class="text-{{ $isRtl ? 'left' : 'right' }}">
            <p class="text-white/70 text-sm">{{ t($company->translation_group . '.member_since', 'Member Since') }}</p>
            <p class="text-xl font-bold">{{ $stats['user_info']['member_since'] }}</p>
        </div>
    </div>
</div>

<!-- User Information Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Policy Info -->
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">{{ t($company->translation_group . '.policy_number', 'Policy Number') }}</p>
                <p class="text-lg font-bold font-mono" style="color: {{ $company->primary_color }};">{{ $stats['user_info']['policy_number'] }}</p>
            </div>
            <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background: {{ $company->primary_color }}20;">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: {{ $company->primary_color }};">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
        </div>
    </div>
    
    <!-- Contact Info -->
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">{{ t($company->translation_group . '.phone', 'Phone') }}</p>
                <p class="text-lg font-bold text-blue-600">{{ $stats['user_info']['phone'] }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                </svg>
            </div>
        </div>
    </div>
    
    <!-- National ID -->
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">{{ t($company->translation_group . '.national_id', 'National ID') }}</p>
                <p class="text-sm font-bold text-purple-600 font-mono">{{ $stats['user_info']['national_id'] }}</p>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                </svg>
            </div>
        </div>
    </div>
    
    <!-- Last Login -->
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">{{ t($company->translation_group . '.last_login', 'Last Login') }}</p>
                <p class="text-sm font-bold text-green-600">{{ $stats['user_info']['last_login'] }}</p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Actions & Info Grid -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    
    <!-- Quick Actions -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="text-white px-6 py-4 rounded-t-xl" style="background: {{ $company->primary_color }};">
            <h3 class="text-lg font-bold flex items-center">
                <svg class="w-5 h-5 {{ $isRtl ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
                {{ t($company->translation_group . '.quick_actions', 'Quick Actions') }}
            </h3>
        </div>
        <div class="p-6 space-y-4">
            <a href="#" class="flex items-center p-4 bg-gray-50 rounded-lg transition-colors duration-200 group border border-gray-200 hover:border-opacity-50" style="hover:background: {{ $company->primary_color }}10; hover:border-color: {{ $company->primary_color }};">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center {{ $isRtl ? 'ml-4' : 'mr-4' }} group-hover:scale-110 transition-transform duration-200" style="background: {{ $company->primary_color }};">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <span class="font-semibold text-gray-900 group-hover:opacity-80 transition-colors duration-200">{{ t($company->translation_group . '.my_profile', 'My Profile') }}</span>
            </a>
            
            <div class="flex items-center p-4 bg-gray-50 rounded-lg border border-gray-200 opacity-60">
                <div class="w-10 h-10 bg-gray-400 rounded-lg flex items-center justify-center {{ $isRtl ? 'ml-4' : 'mr-4' }}">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <span class="font-semibold text-gray-600">{{ t($company->translation_group . '.my_claims', 'My Claims') }} (Coming Soon)</span>
            </div>
            
            <div class="flex items-center p-4 bg-gray-50 rounded-lg border border-gray-200 opacity-60">
                <div class="w-10 h-10 bg-gray-400 rounded-lg flex items-center justify-center {{ $isRtl ? 'ml-4' : 'mr-4' }}">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
                <span class="font-semibold text-gray-600">{{ t($company->translation_group . '.policy_details', 'Policy Details') }} (Coming Soon)</span>
            </div>
            
            <div class="flex items-center p-4 bg-gray-50 rounded-lg border border-gray-200 opacity-60">
                <div class="w-10 h-10 bg-gray-400 rounded-lg flex items-center justify-center {{ $isRtl ? 'ml-4' : 'mr-4' }}">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 1.26a2 2 0 001.11 0L20 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <span class="font-semibold text-gray-600">{{ t($company->translation_group . '.contact_support', 'Contact Support') }} (Coming Soon)</span>
            </div>
        </div>
    </div>
    
    <!-- Company Information -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="bg-gray-800 text-white px-6 py-4 rounded-t-xl">
            <h3 class="text-lg font-bold flex items-center">
                <svg class="w-5 h-5 {{ $isRtl ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                {{ t($company->translation_group . '.company_information', 'Company Information') }}
            </h3>
        </div>
        <div class="p-6 space-y-4">
            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                <span class="text-gray-600">{{ t($company->translation_group . '.company_name', 'Company Name') }}</span>
                <span class="font-medium">{{ $stats['company_info']['name'] }}</span>
            </div>
            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                <span class="text-gray-600">{{ t($company->translation_group . '.company_phone', 'Company Phone') }}</span>
                <span class="font-medium">{{ $stats['company_info']['phone'] }}</span>
            </div>
            @if($stats['company_info']['address'])
            <div class="py-2">
                <span class="text-gray-600 block mb-1">{{ t($company->translation_group . '.company_address', 'Company Address') }}</span>
                <span class="font-medium text-sm">{{ $stats['company_info']['address'] }}</span>
            </div>
            @endif
            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                <span class="text-gray-600">{{ t($company->translation_group . '.member_since', 'Member Since') }}</span>
                <span class="font-medium">{{ $stats['user_info']['member_since'] }}</span>
            </div>
        </div>
    </div>
</div>

@endsection