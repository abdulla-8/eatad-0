@extends('insurance.layouts.app')

@section('title', t('insurance.dashboard'))

@section('content')

<!-- Welcome Card -->
<div class="bg-gradient-to-r from-green-900 to-green-800 rounded-xl p-6 lg:p-8 text-white mb-8">
    <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between">
        <div class="flex items-center space-x-4 {{ $isRtl ? 'space-x-reverse' : '' }} mb-4 lg:mb-0">
            <div class="w-16 h-16 bg-green-500 rounded-xl p-3">
                <svg class="w-full h-full text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl lg:text-3xl font-bold">{{ t('auth.welcome_back') }}</h1>
                <p class="text-green-200">{{ $company->legal_name }}</p>
            </div>
        </div>
        <div class="text-{{ $isRtl ? 'left' : 'right' }}">
            <p class="text-green-300 text-sm">{{ t('insurance.my_profile') }}</p>
            <p class="text-xl font-bold">{{ $stats['profile_completion'] }}% Complete</p>
        </div>
    </div>
</div>

<!-- Account Status Alert -->
@if(!$company->is_approved)
<div class="bg-yellow-100 border border-yellow-400 text-yellow-800 px-4 py-3 rounded-lg mb-6">
    <div class="flex items-center">
        <svg class="w-5 h-5 {{ $isRtl ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span class="font-medium">Account Pending Approval</span>
        <span class="{{ $isRtl ? 'mr-2' : 'ml-2' }}">- Your account is awaiting admin approval to access all features.</span>
    </div>
</div>
@endif

<!-- Stats Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Profile Completion -->
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Profile Completion</p>
                <p class="text-3xl font-bold text-green-600">{{ $stats['profile_completion'] }}%</p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </div>
        </div>
    </div>
    
    <!-- Account Status -->
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Account Status</p>
                <p class="text-xl font-bold {{ $stats['account_status'] == 'approved' ? 'text-green-600' : 'text-yellow-600' }}">
                    {{ ucfirst($stats['account_status']) }}
                </p>
            </div>
            <div class="w-12 h-12 {{ $stats['account_status'] == 'approved' ? 'bg-green-100' : 'bg-yellow-100' }} rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 {{ $stats['account_status'] == 'approved' ? 'text-green-600' : 'text-yellow-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $stats['account_status'] == 'approved' ? 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' : 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z' }}"></path>
                </svg>
            </div>
        </div>
    </div>
    
    <!-- Employee Count -->
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Employees</p>
                <p class="text-2xl font-bold text-purple-600">{{ $stats['employee_count'] ? number_format($stats['employee_count']) : 'Not Set' }}</p>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
        </div>
    </div>
    
    <!-- Insured Cars -->
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Insured Cars</p>
                <p class="text-2xl font-bold text-blue-600">{{ $stats['insured_cars_count'] ? number_format($stats['insured_cars_count']) : 'Not Set' }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Actions & Info Grid -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    
    <!-- Quick Actions -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="bg-green-500 text-white px-6 py-4 rounded-t-xl">
            <h3 class="text-lg font-bold flex items-center">
                <svg class="w-5 h-5 {{ $isRtl ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
                Quick Actions
            </h3>
        </div>
        <div class="p-6 space-y-4">
            <a href="#" class="flex items-center p-4 bg-gray-50 hover:bg-green-50 rounded-lg transition-colors duration-200 group border border-gray-200 hover:border-green-300">
                <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center {{ $isRtl ? 'ml-4' : 'mr-4' }} group-hover:scale-110 transition-transform duration-200">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <span class="font-semibold text-gray-900 group-hover:text-green-800 transition-colors duration-200">{{ t('insurance.my_profile') }}</span>
            </a>
            
            <div class="flex items-center p-4 bg-gray-50 rounded-lg border border-gray-200 opacity-60">
                <div class="w-10 h-10 bg-gray-400 rounded-lg flex items-center justify-center {{ $isRtl ? 'ml-4' : 'mr-4' }}">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <span class="font-semibold text-gray-600">{{ t('insurance.claims') }} (Coming Soon)</span>
            </div>
            
            <div class="flex items-center p-4 bg-gray-50 rounded-lg border border-gray-200 opacity-60">
                <div class="w-10 h-10 bg-gray-400 rounded-lg flex items-center justify-center {{ $isRtl ? 'ml-4' : 'mr-4' }}">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
                <span class="font-semibold text-gray-600">{{ t('insurance.policies') }} (Coming Soon)</span>
            </div>
        </div>
    </div>
    
    <!-- Company Information -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="bg-gray-800 text-white px-6 py-4 rounded-t-xl">
            <h3 class="text-lg font-bold flex items-center">
                <svg class="w-5 h-5 {{ $isRtl ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Company Information
            </h3>
        </div>
        <div class="p-6 space-y-4">
            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                <span class="text-gray-600">Phone Number</span>
                <span class="font-medium">{{ $company->formatted_phone }}</span>
            </div>
            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                <span class="text-gray-600">Commercial Register</span>
                <span class="font-medium">{{ $company->commercial_register }}</span>
            </div>
            @if($company->tax_number)
            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                <span class="text-gray-600">Tax Number</span>
                <span class="font-medium">{{ $company->tax_number }}</span>
            </div>
            @endif
            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                <span class="text-gray-600">Additional Phones</span>
                <span class="font-medium">{{ $stats['additional_phones'] }}</span>
            </div>
            @if($company->office_address)
            <div class="py-2">
                <span class="text-gray-600 block mb-1">Office Address</span>
                <span class="font-medium text-sm">{{ $company->office_address }}</span>
            </div>
            @endif
        </div>
    </div>
</div>

@endsection