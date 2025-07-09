@extends('tow-service.layouts.app')

@section('title', t('tow.dashboard'))

@section('content')

<!-- Welcome Card -->
<div class="bg-gradient-to-r from-gold-600 to-gold-500 rounded-xl p-6 lg:p-8 text-dark-900 mb-8">
    <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between">
        <div class="flex items-center space-x-4 {{ $isRtl ? 'space-x-reverse' : '' }} mb-4 lg:mb-0">
            <div class="w-16 h-16 bg-dark-900 rounded-xl p-3">
                <svg class="w-full h-full text-gold-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl lg:text-3xl font-bold">{{ t('auth.welcome_back') }}</h1>
                <p class="text-dark-800">{{ $user->display_name }}</p>
                <p class="text-sm text-dark-700">{{ $userType == 'company' ? t('tow.company_account') : t('tow.individual_account') }}</p>
            </div>
        </div>
        <div class="text-{{ $isRtl ? 'left' : 'right' }}">
            <p class="text-dark-700 text-sm">{{ t('tow.profile_completion') }}</p>
            <p class="text-xl font-bold">{{ $stats['profile_completion'] }}% {{ t('tow.complete') }}</p>
        </div>
    </div>
</div>

<!-- Account Status Alert -->
@if (!$user->is_approved)
    <div class="bg-yellow-100 border border-yellow-400 text-yellow-800 px-4 py-3 rounded-lg mb-6">
        <div class="flex items-center">
            <svg class="w-5 h-5 {{ $isRtl ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span class="font-medium">{{ t('tow.account_pending_approval') }}</span>
            <span class="{{ $isRtl ? 'mr-2' : 'ml-2' }}">- {{ t('tow.account_pending_approval_desc') }}</span>
        </div>
    </div>
@endif

<!-- Stats Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Profile Completion -->
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">{{ t('tow.profile_completion') }}</p>
                <p class="text-3xl font-bold text-gold-600">{{ $stats['profile_completion'] }}%</p>
            </div>
            <div class="w-12 h-12 bg-gold-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-gold-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Account Status -->
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">{{ t('tow.account_status') }}</p>
                <p class="text-xl font-bold {{ $stats['account_status'] == 'approved' ? 'text-green-600' : 'text-yellow-600' }}">
                    {{ $stats['account_status'] == 'approved' ? t('admin.approved') : t('admin.pending') }}
                </p>
            </div>
            <div class="w-12 h-12 {{ $stats['account_status'] == 'approved' ? 'bg-green-100' : 'bg-yellow-100' }} rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 {{ $stats['account_status'] == 'approved' ? 'text-green-600' : 'text-yellow-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $stats['account_status'] == 'approved' ? 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' : 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z' }}"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Registration Date -->
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">{{ t('tow.member_since') }}</p>
                <p class="text-lg font-bold text-gray-900">{{ $stats['registration_date']->format('M Y') }}</p>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- User Type -->
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">{{ t('tow.account_type') }}</p>
                <p class="text-sm font-bold text-gray-900">{{ $userType == 'company' ? t('tow.company') : t('tow.individual') }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    @if($userType == 'company')
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    @else
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    @endif
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Account Info Grid -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Quick Actions -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="bg-gold-500 text-dark-900 px-6 py-4 rounded-t-xl">
            <h3 class="text-lg font-bold flex items-center">
                <svg class="w-5 h-5 {{ $isRtl ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
                {{ t('tow.quick_actions') }}
            </h3>
        </div>
        <div class="p-6 space-y-4">
            <a href="#" class="flex items-center p-4 bg-gray-50 hover:bg-gold-50 rounded-lg transition-colors duration-200 group border border-gray-200 hover:border-gold-300">
                <div class="w-10 h-10 bg-gold-500 rounded-lg flex items-center justify-center {{ $isRtl ? 'ml-4' : 'mr-4' }} group-hover:scale-110 transition-transform duration-200">
                    <svg class="w-5 h-5 text-dark-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <span class="font-semibold text-gray-900 group-hover:text-gold-800 transition-colors duration-200">{{ t('tow.my_profile') }}</span>
            </a>

            <div class="flex items-center p-4 bg-gray-50 rounded-lg border border-gray-200 opacity-60">
                <div class="w-10 h-10 bg-gray-400 rounded-lg flex items-center justify-center {{ $isRtl ? 'ml-4' : 'mr-4' }}">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <span class="font-semibold text-gray-600">{{ t('tow.requests') }} ({{ t('tow.soon') }})</span>
            </div>
        </div>
    </div>

    <!-- Account Information -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="bg-gray-800 text-white px-6 py-4 rounded-t-xl">
            <h3 class="text-lg font-bold flex items-center">
                <svg class="w-5 h-5 {{ $isRtl ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ t('tow.account_information') }}
            </h3>
        </div>
        <div class="p-6 space-y-4">
            @if($userType == 'company')
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-600">{{ t('tow.legal_name') }}</span>
                    <span class="font-medium">{{ $user->legal_name }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-600">{{ t('tow.phone_number') }}</span>
                    <span class="font-medium">{{ $user->formatted_phone }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-600">{{ t('tow.commercial_register') }}</span>
                    <span class="font-medium">{{ $user->commercial_register }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-600">{{ t('tow.office_number') }}</span>
                    <span class="font-medium">{{ $user->office_number ?? t('tow.not_set') }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-600">{{ t('tow.delegate_number') }}</span>
                    <span class="font-medium">{{ $user->delegate_number ?? t('tow.not_set') }}</span>
                </div>
                <div class="flex justify-between items-center py-2">
                    <span class="text-gray-600">{{ t('tow.daily_capacity') }}</span>
                    <span class="font-medium">{{ $user->daily_capacity ?? t('tow.not_set') }}</span>
                </div>
            @else
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-600">{{ t('tow.full_name') }}</span>
                    <span class="font-medium">{{ $user->full_name }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-600">{{ t('tow.phone_number') }}</span>
                    <span class="font-medium">{{ $user->formatted_phone }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-600">{{ t('tow.national_id') }}</span>
                    <span class="font-medium">{{ $user->formatted_national_id }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-600">{{ t('tow.plate_number') }}</span>
                    <span class="font-medium">{{ $user->tow_truck_plate_number ?? t('tow.not_set') }}</span>
                </div>
                <div class="flex justify-between items-center py-2">
                    <span class="text-gray-600">{{ t('tow.truck_form') }}</span>
                    <span class="font-medium">
                        @if($user->tow_truck_form)
                            <span class="text-green-600">{{ t('tow.uploaded') }}</span>
                        @else
                            <span class="text-red-600">{{ t('tow.not_uploaded') }}</span>
                        @endif
                    </span>
                </div>
            @endif
            </div>
        </div>
    </div>

    <!-- Next Steps Section -->
    @if($stats['profile_completion'] < 100 || !$user->is_approved)
    <div class="mt-8 bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="bg-gradient-to-r from-gold-500 to-gold-600 text-dark-900 px-6 py-4 rounded-t-xl">
            <h3 class="text-lg font-bold flex items-center">
                <svg class="w-5 h-5 {{ $isRtl ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ t('tow.next_steps') }}
            </h3>
        </div>
        <div class="p-6">
            @if($stats['profile_completion'] < 100)
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-gray-700">{{ t('tow.profile_completion') }}</span>
                        <span class="text-sm font-medium text-gold-600">{{ $stats['profile_completion'] }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-gold-500 h-2 rounded-full transition-all duration-300" style="width: {{ $stats['profile_completion'] }}%"></div>
                    </div>
                </div>
            @endif

            <div class="space-y-3">
                @if($stats['profile_completion'] < 100)
                    <div class="flex items-center p-3 bg-gold-50 border border-gold-200 rounded-lg">
                        <svg class="w-5 h-5 text-gold-600 {{ $isRtl ? 'ml-3' : 'mr-3' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-gold-800">{{ t('tow.complete_profile_message') }}</span>
                    </div>
                @endif

                @if(!$user->is_approved)
                    <div class="flex items-center p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <svg class="w-5 h-5 text-yellow-600 {{ $isRtl ? 'ml-3' : 'mr-3' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-yellow-800">{{ t('tow.waiting_approval_message') }}</span>
                    </div>
                @endif

                @if($user->is_approved && $stats['profile_completion'] >= 100)
                    <div class="flex items-center p-3 bg-green-50 border border-green-200 rounded-lg">
                        <svg class="w-5 h-5 text-green-600 {{ $isRtl ? 'ml-3' : 'mr-3' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="text-green-800">{{ t('tow.account_ready_message') }}</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @endif

@endsection