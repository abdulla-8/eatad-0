@extends('dealer.layouts.app')

@section('title', t('dealer.dashboard'))

@section('content')

    <!-- Welcome Card -->
    <div class="bg-gradient-to-r from-blue-900 to-blue-800 rounded-xl p-6 lg:p-8 text-white mb-8">
        <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between">
            <div class="flex items-center space-x-4 {{ $isRtl ? 'space-x-reverse' : '' }} mb-4 lg:mb-0">
                <div class="w-16 h-16 bg-blue-500 rounded-xl p-3">
                    <svg class="w-full h-full text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                        </path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl lg:text-3xl font-bold">{{ t('auth.welcome_back') }}</h1>
                    <p class="text-blue-200">{{ $dealer->legal_name }}</p>
                </div>
            </div>
            <div class="text-{{ $isRtl ? 'left' : 'right' }}">
                <p class="text-blue-300 text-sm">{{ t('dealer.my_profile') }}</p>
                <p class="text-xl font-bold">{{ $stats['profile_completion'] }}% Complete</p>
            </div>
        </div>
    </div>

    <!-- Account Status Alert -->
    @if (!$dealer->is_approved)
        <div class="bg-yellow-100 border border-yellow-400 text-yellow-800 px-4 py-3 rounded-lg mb-6">
            <div class="flex items-center">
                <svg class="w-5 h-5 {{ $isRtl ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="font-medium">Account Pending Approval</span>
                <span class="{{ $isRtl ? 'mr-2' : 'ml-2' }}">- Your account is awaiting admin approval to access all
                    features.</span>
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
                    <p class="text-3xl font-bold text-blue-600">{{ $stats['profile_completion'] }}%</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Account Status -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Account Status</p>
                    <p
                        class="text-xl font-bold {{ $stats['account_status'] == 'approved' ? 'text-green-600' : 'text-yellow-600' }}">
                        {{ ucfirst($stats['account_status']) }}
                    </p>
                </div>
                <div
                    class="w-12 h-12 {{ $stats['account_status'] == 'approved' ? 'bg-green-100' : 'bg-yellow-100' }} rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 {{ $stats['account_status'] == 'approved' ? 'text-green-600' : 'text-yellow-600' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="{{ $stats['account_status'] == 'approved' ? 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' : 'M12 8v4l3 3m6-3a9 90 11-18 0 9 9 0 0118 0z' }}">
                        </path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Registration Date -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Member Since</p>
                    <p class="text-lg font-bold text-gray-900">{{ $stats['registration_date']->format('M Y') }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Specialization -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Specialization</p>
                    <p class="text-sm font-bold text-gray-900">{{ $stats['specialization'] ?? 'Not Set' }}</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                        </path>
                    </svg>
                </div>
            </div>
        </div>
    </div>
    <!-- Actions & Info Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="bg-blue-500 text-white px-6 py-4 rounded-t-xl">
                <h3 class="text-lg font-bold flex items-center">
                    <svg class="w-5 h-5 {{ $isRtl ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    Quick Actions
                </h3>
            </div>
            <div class="p-6 space-y-4">
                <a href="#"
                    class="flex items-center p-4 bg-gray-50 hover:bg-blue-50 rounded-lg transition-colors duration-200 group border border-gray-200 hover:border-blue-300">
                    <div
                        class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center {{ $isRtl ? 'ml-4' : 'mr-4' }} group-hover:scale-110 transition-transform duration-200">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <span
                        class="font-semibold text-gray-900 group-hover:text-blue-800 transition-colors duration-200">{{ t('dealer.my_profile') }}</span>
                </a>

                <div class="flex items-center p-4 bg-gray-50 rounded-lg border border-gray-200 opacity-60">
                    <div
                        class="w-10 h-10 bg-gray-400 rounded-lg flex items-center justify-center {{ $isRtl ? 'ml-4' : 'mr-4' }}">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <span class="font-semibold text-gray-600">{{ t('dealer.inventory') }} (Coming Soon)</span>
                </div>

                <div class="flex items-center p-4 bg-gray-50 rounded-lg border border-gray-200 opacity-60">
                    <div
                        class="w-10 h-10 bg-gray-400 rounded-lg flex items-center justify-center {{ $isRtl ? 'ml-4' : 'mr-4' }}">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                            </path>
                        </svg>
                    </div>
                    <span class="font-semibold text-gray-600">{{ t('dealer.orders') }} (Coming Soon)</span>
                </div>
            </div>
        </div>

        <!-- Account Information -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="bg-gray-800 text-white px-6 py-4 rounded-t-xl">
                <h3 class="text-lg font-bold flex items-center">
                    <svg class="w-5 h-5 {{ $isRtl ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Account Information
                </h3>
            </div>
            <div class="p-6 space-y-4">
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-600">Phone Number</span>
                    <span class="font-medium">{{ $dealer->formatted_phone }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-600">Commercial Register</span>
                    <span class="font-medium">{{ $dealer->commercial_register }}</span>
                </div>
                @if ($dealer->tax_number)
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-600">Tax Number</span>
                        <span class="font-medium">{{ $dealer->tax_number }}</span>
                    </div>
                @endif
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-600">Business Type</span>
                    <span class="font-medium">{{ $dealer->type }}</span>
                </div>
            </div>
        @endsection
