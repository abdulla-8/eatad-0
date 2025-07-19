@extends('admin.layouts.app')

@section('title', t('admin.dashboard'))

@section('content')

<!-- Welcome Header -->
<div class="bg-gradient-to-r from-dark-900 to-dark-800 rounded-xl p-6 lg:p-8 text-white mb-8 relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-r from-gold-500/10 to-transparent"></div>
    <div class="relative z-10">
        <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between">
            <div class="flex items-center space-x-4 {{ $isRtl ? 'space-x-reverse' : '' }} mb-4 lg:mb-0">
                <div class="w-16 h-16 bg-gold-500 rounded-xl p-3 shadow-lg">
                    <img src="{{ asset('logo.png') }}" alt="Dashboard Icon" class="w-full h-full object-cover">
                </div>
                <div>
                    <h1 class="text-2xl lg:text-3xl font-bold">{{ t('admin.welcome_back') }}</h1>
                    <p class="text-gray-300">{{ t('admin.dashboard_subtitle') }}</p>
                    <div class="flex items-center mt-2 space-x-4 {{ $isRtl ? 'space-x-reverse' : '' }}">
                        <span class="bg-gold-500/20 text-gold-300 px-3 py-1 rounded-full text-sm">
                            {{ $systemHealth['status'] === 'excellent' ? 'System Healthy' : 'Needs Attention' }}
                        </span>
                        <span class="text-gray-400 text-sm">{{ now()->format('l, F j, Y') }}</span>
                    </div>
                </div>
            </div>
            <div class="text-{{ $isRtl ? 'left' : 'right' }}">
                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4">
                    <p class="text-gold-400 text-sm">{{ t('admin.system_health') }}</p>
                    <div class="flex items-center mt-1">
                        <div class="w-24 bg-gray-600 rounded-full h-2 {{ $isRtl ? 'ml-3' : 'mr-3' }}">
                            <div class="bg-gradient-to-r from-gold-500 to-green-400 h-2 rounded-full transition-all duration-500" 
                                 style="width: {{ $systemHealth['score'] }}%"></div>
                        </div>
                        <span class="text-xl font-bold">{{ $systemHealth['score'] }}%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Top Statistics Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    @foreach($topStats as $stat)
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200 hover:shadow-md transition-all duration-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">{{ $stat['title'] }}</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">{{ number_format($stat['value']) }}</p>
                <div class="flex items-center mt-2">
                    @if($stat['trend'] === 'up')
                        <svg class="w-4 h-4 text-green-500 {{ $isRtl ? 'ml-1' : 'mr-1' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                        <span class="text-green-600 text-sm font-medium">+{{ $stat['change'] }}%</span>
                    @else
                        <svg class="w-4 h-4 text-red-500 {{ $isRtl ? 'ml-1' : 'mr-1' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                        </svg>
                        <span class="text-red-600 text-sm font-medium">{{ $stat['change'] }}%</span>
                    @endif
                </div>
            </div>
            <div class="w-12 h-12 bg-{{ $stat['color'] }}-100 rounded-lg flex items-center justify-center">
                @switch($stat['icon'])
                    @case('users')
                        <svg class="w-6 h-6 text-{{ $stat['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                        @break
                    @case('document-text')
                        <svg class="w-6 h-6 text-{{ $stat['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        @break
                    @case('clock')
                        <svg class="w-6 h-6 text-{{ $stat['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        @break
                    @case('chat-bubble-left-ellipsis')
                        <svg class="w-6 h-6 text-{{ $stat['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        @break
                @endswitch
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Main Dashboard Grid -->
<div class="grid grid-cols-1 xl:grid-cols-3 gap-8 mb-8">
    
    <!-- Quick Actions Panel -->
    <div class="xl:col-span-1">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="bg-gradient-to-r from-gold-500 to-gold-600 text-white px-6 py-4 rounded-t-xl">
                <h3 class="text-lg font-bold flex items-center">
                    <svg class="w-5 h-5 {{ $isRtl ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    {{ t('admin.quick_actions') }}
                </h3>
            </div>
            <div class="p-6 space-y-4">
                @foreach($quickActions as $action)
                <div class="border-l-4 border-{{ $action['color'] }}-500 bg-{{ $action['color'] }}-50 p-4 rounded-{{ $isRtl ? 'r' : 'l' }}-lg">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center">
                                <h4 class="font-semibold text-gray-900">{{ $action['title'] }}</h4>
                                @if($action['urgent'])
                                    <span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full {{ $isRtl ? 'mr-2' : 'ml-2' }}">Urgent</span>
                                @endif
                            </div>
                            <p class="text-sm text-gray-600 mt-1">{{ $action['description'] }}</p>
                            <div class="mt-3 space-y-1">
                                @foreach($action['actions'] as $link)
                                <a href="{{ $link['link'] }}" class="block text-sm text-{{ $action['color'] }}-600 hover:text-{{ $action['color'] }}-800 hover:underline">
                                    → {{ $link['title'] }}
                                </a>
                                @endforeach
                            </div>
                        </div>
                        <div class="w-10 h-10 bg-{{ $action['color'] }}-500 rounded-lg flex items-center justify-center {{ $isRtl ? 'mr-4' : 'ml-4' }}">
                            @switch($action['icon'])
                                @case('check-circle')
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    @break
                                @case('chat-bubble-left-ellipsis')
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                    </svg>
                                    @break
                                @case('cog-6-tooth')
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    @break
                            @endswitch
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- System Overview -->
    <div class="xl:col-span-2">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            <!-- User Statistics -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="bg-blue-500 text-white px-6 py-4 rounded-t-xl">
                    <h3 class="text-lg font-bold flex items-center">
                        <svg class="w-5 h-5 {{ $isRtl ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        {{ t('admin.user_statistics') }}
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between py-2">
                        <span class="text-gray-600">Parts Dealers</span>
                        <div class="flex items-center space-x-2 {{ $isRtl ? 'space-x-reverse' : '' }}">
                            <span class="font-semibold">{{ $userStats['parts_dealers']['total'] }}</span>
                            <span class="text-xs text-green-600 bg-green-100 px-2 py-1 rounded-full">
                                {{ $userStats['parts_dealers']['active'] }} active
                            </span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between py-2">
                        <span class="text-gray-600">Insurance Companies</span>
                        <div class="flex items-center space-x-2 {{ $isRtl ? 'space-x-reverse' : '' }}">
                            <span class="font-semibold">{{ $userStats['insurance_companies']['total'] }}</span>
                            <span class="text-xs text-blue-600 bg-blue-100 px-2 py-1 rounded-full">
                                {{ $userStats['insurance_companies']['total_users'] }} users
                            </span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between py-2">
                        <span class="text-gray-600">Service Centers</span>
                        <div class="flex items-center space-x-2 {{ $isRtl ? 'space-x-reverse' : '' }}">
                            <span class="font-semibold">{{ $userStats['service_centers']['total'] }}</span>
                            <span class="text-xs text-purple-600 bg-purple-100 px-2 py-1 rounded-full">
                                {{ $userStats['service_centers']['total_technicians'] }} techs
                            </span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between py-2">
                        <span class="text-gray-600">Tow Services</span>
                        <div class="flex items-center space-x-2 {{ $isRtl ? 'space-x-reverse' : '' }}">
                            <span class="font-semibold">{{ $userStats['tow_services']['companies']['total'] + $userStats['tow_services']['individuals']['total'] }}</span>
                            <span class="text-xs text-orange-600 bg-orange-100 px-2 py-1 rounded-full">
                                {{ $userStats['tow_services']['companies']['total_capacity'] }} capacity
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="bg-green-500 text-white px-6 py-4 rounded-t-xl">
                    <h3 class="text-lg font-bold flex items-center">
                        <svg class="w-5 h-5 {{ $isRtl ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ t('admin.recent_activity') }}
                    </h3>
                </div>
                <div class="p-6 max-h-96 overflow-y-auto">
                    <div class="space-y-4">
                        @foreach($recentActivities->take(8) as $activity)
                        <div class="flex items-start space-x-3 {{ $isRtl ? 'space-x-reverse' : '' }}">
                            <div class="w-8 h-8 bg-{{ $activity['color'] }}-100 rounded-full flex items-center justify-center flex-shrink-0">
                                @switch($activity['icon'])
                                    @case('chat-bubble-left-ellipsis')
                                        <svg class="w-4 h-4 text-{{ $activity['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                        </svg>
                                        @break
                                    @case('user-plus')
                                        <svg class="w-4 h-4 text-{{ $activity['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                        </svg>
                                        @break
                                    @case('building-office')
                                        <svg class="w-4 h-4 text-{{ $activity['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                        @break
                                    @case('document-text')
                                        <svg class="w-4 h-4 text-{{ $activity['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        @break
                                @endswitch
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-gray-900 text-sm">{{ $activity['title'] }}</p>
                                <p class="text-gray-600 text-xs">{{ $activity['description'] }}</p>
                                <p class="text-gray-400 text-xs mt-1">{{ $activity['time']->diffForHumans() }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- System Health & Activity Stats -->
<div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-8 mb-8">
    
    <!-- System Health -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="bg-{{ $systemHealth['status'] === 'excellent' ? 'green' : ($systemHealth['status'] === 'good' ? 'yellow' : 'red') }}-500 text-white px-6 py-4 rounded-t-xl">
            <h3 class="text-lg font-bold flex items-center">
                <svg class="w-5 h-5 {{ $isRtl ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ t('admin.system_health') }}
            </h3>
        </div>
        <div class="p-6">
            <div class="text-center mb-6">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-{{ $systemHealth['status'] === 'excellent' ? 'green' : ($systemHealth['status'] === 'good' ? 'yellow' : 'red') }}-100 rounded-full mb-3">
                    <span class="text-2xl font-bold text-{{ $systemHealth['status'] === 'excellent' ? 'green' : ($systemHealth['status'] === 'good' ? 'yellow' : 'red') }}-600">{{ $systemHealth['score'] }}%</span>
                </div>
                <h4 class="font-semibold text-gray-900 capitalize">{{ str_replace('_', ' ', $systemHealth['status']) }}</h4>
            </div>
            
            @if(count($systemHealth['issues']) > 0)
            <div class="mb-4">
                <h5 class="font-medium text-gray-900 mb-2">{{ t('admin.issues') }}</h5>
                <ul class="space-y-1">
                    @foreach($systemHealth['issues'] as $issue)
                    <li class="text-sm text-red-600 flex items-center">
                        <svg class="w-4 h-4 {{ $isRtl ? 'ml-2' : 'mr-2' }} flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.124 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        {{ $issue }}
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif

            @if(count($systemHealth['recommendations']) > 0)
            <div>
                <h5 class="font-medium text-gray-900 mb-2">{{ t('admin.recommendations') }}</h5>
                <ul class="space-y-1">
                    @foreach($systemHealth['recommendations'] as $recommendation)
                    <li class="text-sm text-blue-600 flex items-center">
                        <svg class="w-4 h-4 {{ $isRtl ? 'ml-2' : 'mr-2' }} flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ $recommendation }}
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>
    </div>

    <!-- Today's Activity -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="bg-purple-500 text-white px-6 py-4 rounded-t-xl">
            <h3 class="text-lg font-bold flex items-center">
                <svg class="w-5 h-5 {{ $isRtl ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                Today's Activity
            </h3>
        </div>
        <div class="p-6 space-y-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center {{ $isRtl ? 'ml-3' : 'mr-3' }}">
                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                    </div>
                    <span class="text-gray-600">New Complaints</span>
                </div>
                <span class="font-bold text-xl">{{ $activityStats['today']['new_complaints'] }}</span>
            </div>
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center {{ $isRtl ? 'ml-3' : 'mr-3' }}">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <span class="text-gray-600">New Claims</span>
                </div>
                <span class="font-bold text-xl">{{ $activityStats['today']['new_claims'] }}</span>
            </div>
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center {{ $isRtl ? 'ml-3' : 'mr-3' }}">
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                    </div>
                    <span class="text-gray-600">New Registrations</span>
                </div>
                <span class="font-bold text-xl">{{ $activityStats['today']['new_registrations'] }}</span>
            </div>
        </div>
    </div>

    <!-- This Week's Summary -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="bg-orange-500 text-white px-6 py-4 rounded-t-xl">
            <h3 class="text-lg font-bold flex items-center">
                <svg class="w-5 h-5 {{ $isRtl ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                This Week
            </h3>
        </div>
        <div class="p-6 space-y-4">
            <div class="flex items-center justify-between">
                <span class="text-gray-600">Complaints</span>
                <span class="font-bold text-xl">{{ $activityStats['this_week']['new_complaints'] }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-gray-600">Claims</span>
                <span class="font-bold text-xl">{{ $activityStats['this_week']['new_claims'] }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-gray-600">Approvals</span>
                <span class="font-bold text-xl">{{ $activityStats['this_week']['approved_accounts'] }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Content Statistics -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">{{ t('admin.specializations') }}</h3>
            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
        </div>
        <div class="space-y-2">
            <div class="flex justify-between">
                <span class="text-gray-600">Total</span>
                <span class="font-semibold">{{ $contentStats['specializations']['total'] }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Active</span>
                <span class="font-semibold text-green-600">{{ $contentStats['specializations']['active'] }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">With Images</span>
                <span class="font-semibold text-blue-600">{{ $contentStats['specializations']['with_images'] }}</span>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">{{ t('admin.industrial_areas') }}</h3>
            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </div>
        </div>
        <div class="space-y-2">
            <div class="flex justify-between">
                <span class="text-gray-600">Total</span>
                <span class="font-semibold">{{ $contentStats['industrial_areas']['total'] }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Active</span>
                <span class="font-semibold text-green-600">{{ $contentStats['industrial_areas']['active'] }}</span>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">{{ t('admin.service_specializations') }}</h3>
            <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </div>
        </div>
        <div class="space-y-2">
            <div class="flex justify-between">
                <span class="text-gray-600">Total</span>
                <span class="font-semibold">{{ $contentStats['service_specializations']['total'] }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Active</span>
                <span class="font-semibold text-green-600">{{ $contentStats['service_specializations']['active'] }}</span>
            </div>
        </div>
    </div>
</div>

<!-- System Information Footer -->
<div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
    <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ t('admin.system_information') }}</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                <div>
                    <span class="text-gray-600">Languages:</span>
                    <span class="font-medium {{ $isRtl ? 'mr-1' : 'ml-1' }}">{{ $systemStats['active_languages'] }}</span>
                </div>
                <div>
                    <span class="text-gray-600">Translations:</span>
                    <span class="font-medium {{ $isRtl ? 'mr-1' : 'ml-1' }}">{{ number_format($systemStats['total_translations']) }}</span>
                </div>
                <div>
                    <span class="text-gray-600">Total Users:</span>
                    <span class="font-medium {{ $isRtl ? 'mr-1' : 'ml-1' }}">{{ number_format($userStats['parts_dealers']['total'] + $userStats['insurance_companies']['total'] + $userStats['service_centers']['total'] + $userStats['tow_services']['companies']['total'] + $userStats['tow_services']['individuals']['total']) }}</span>
                </div>
                <div>
                    <span class="text-gray-600">Active Claims:</span>
                    <span class="font-medium {{ $isRtl ? 'mr-1' : 'ml-1' }}">{{ number_format($systemStats['active_claims']) }}</span>
                </div>
            </div>
        </div>
        <div class="mt-4 lg:mt-0 flex items-center space-x-4 {{ $isRtl ? 'space-x-reverse' : '' }}">
            <div class="text-{{ $isRtl ? 'left' : 'right' }} text-sm">
                <p class="text-gray-600">Current Time</p>
                <p class="font-semibold text-gray-900">{{ now()->format('H:i:s') }}</p>
            </div>
            <div class="text-{{ $isRtl ? 'left' : 'right' }} text-sm">
                <p class="text-gray-600">Server Status</p>
                <div class="flex items-center">
                    <div class="w-2 h-2 bg-green-500 rounded-full {{ $isRtl ? 'ml-2' : 'mr-2' }}"></div>
                    <span class="font-semibold text-green-600">Online</span>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    .hover-effect:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }
    
    .progress-bar {
        animation: progressAnimation 2s ease-in-out;
    }
    
    @keyframes progressAnimation {
        0% { width: 0%; }
        100% { width: var(--progress-width); }
    }
    
    .fade-in {
        animation: fadeIn 0.6s ease-in-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .pulse-animation {
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.8; }
    }
    
    .card-hover {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .card-hover:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    
    .gradient-border {
        background: linear-gradient(white, white) padding-box,
                    linear-gradient(45deg, #FFDD57, #10B981) border-box;
        border: 2px solid transparent;
    }
    
    .stat-card {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.05));
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add hover effects to cards
    document.querySelectorAll('.bg-white.rounded-xl').forEach(card => {
        card.classList.add('card-hover');
    });
    
    // Animate numbers on page load
    function animateNumbers() {
        document.querySelectorAll('.text-3xl.font-bold, .font-bold.text-xl').forEach(element => {
            const targetNumber = parseInt(element.textContent.replace(/[,\s]/g, ''));
            if (!isNaN(targetNumber) && targetNumber > 0) {
                animateNumber(element, 0, targetNumber, 1500);
            }
        });
    }
    
    function animateNumber(element, start, end, duration) {
        const startTime = Date.now();
        const timer = setInterval(() => {
            const elapsed = Date.now() - startTime;
            const progress = Math.min(elapsed / duration, 1);
            const current = Math.floor(start + (end - start) * easeOutQuart(progress));
            element.textContent = current.toLocaleString();
            
            if (progress === 1) {
                clearInterval(timer);
            }
        }, 16);
    }
    
    function easeOutQuart(t) {
        return 1 - (--t) * t * t * t;
    }
    
    // Progress bar animations
    function animateProgressBars() {
        document.querySelectorAll('[style*="width:"]').forEach(progressBar => {
            const width = progressBar.style.width;
            progressBar.style.setProperty('--progress-width', width);
            progressBar.style.width = '0%';
            progressBar.classList.add('progress-bar');
            
            setTimeout(() => {
                progressBar.style.width = width;
            }, 500);
        });
    }
    
    // Add fade-in animation to sections
    function addFadeInAnimations() {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('fade-in');
                }
            });
        }, { threshold: 0.1 });
        
        document.querySelectorAll('.grid > div, .bg-white').forEach(element => {
            observer.observe(element);
        });
    }
    
    // Add pulse animation to urgent items
    function addUrgentAnimations() {
        document.querySelectorAll('.bg-red-100.text-red-800').forEach(element => {
            if (element.textContent.includes('Urgent')) {
                element.classList.add('pulse-animation');
            }
        });
    }
    
    // Status indicator animation
    function animateStatusIndicators() {
        document.querySelectorAll('.bg-green-500.rounded-full').forEach(indicator => {
            indicator.classList.add('pulse-animation');
        });
    }
    
    // Initialize all animations
    setTimeout(() => {
        animateNumbers();
        animateProgressBars();
        addFadeInAnimations();
        addUrgentAnimations();
        animateStatusIndicators();
    }, 100);
    
    // Auto-refresh timestamp
    function updateTimestamp() {
        const timeElements = document.querySelectorAll('[data-timestamp]');
        timeElements.forEach(element => {
            const now = new Date();
            element.textContent = now.toLocaleTimeString();
        });
    }
    
    // Update time every second
    setInterval(updateTimestamp, 1000);
    
    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey || e.metaKey) {
            switch(e.key) {
                case 'h':
                    e.preventDefault();
                    window.location.href = '{{ route("admin.dashboard") }}';
                    break;
                case 'c':
                    e.preventDefault();
                    window.location.href = '{{ route("admin.complaints.index") }}';
                    break;
                case 'u':
                    e.preventDefault();
                    window.location.href = '{{ route("admin.users.parts-dealers.index") }}';
                    break;
            }
        }
    });
    
    // Tooltip functionality
    function initTooltips() {
        document.querySelectorAll('[title]').forEach(element => {
            element.addEventListener('mouseenter', function(e) {
                const tooltip = document.createElement('div');
                tooltip.className = 'absolute z-50 px-2 py-1 text-sm text-white bg-gray-900 rounded shadow-lg';
                tooltip.textContent = this.title;
                tooltip.style.top = (e.pageY - 30) + 'px';
                tooltip.style.left = (e.pageX + 10) + 'px';
                tooltip.id = 'tooltip';
                document.body.appendChild(tooltip);
                this.title = '';
            });
            
            element.addEventListener('mouseleave', function() {
                const tooltip = document.getElementById('tooltip');
                if (tooltip) {
                    tooltip.remove();
                }
                if (!this.title && this.getAttribute('data-original-title')) {
                    this.title = this.getAttribute('data-original-title');
                }
            });
        });
    }
    
    initTooltips();
    
    // Notification system
    function showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 max-w-sm bg-${type === 'success' ? 'green' : 'red'}-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform duration-300`;
        notification.innerHTML = `
            <div class="flex items-center justify-between">
                <span>${message}</span>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 100);
        
        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, 300);
        }, 5000);
    }
    
    // Health score circle animation
    function animateHealthScore() {
        const healthScore = {{ $systemHealth['score'] }};
        const healthElement = document.querySelector('.w-20.h-20 span');
        if (healthElement) {
            let currentScore = 0;
            const increment = healthScore / 50;
            const timer = setInterval(() => {
                currentScore += increment;
                if (currentScore >= healthScore) {
                    currentScore = healthScore;
                    clearInterval(timer);
                }
                healthElement.textContent = Math.floor(currentScore) + '%';
            }, 30);
        }
    }
    
    setTimeout(animateHealthScore, 1000);
    
    // Print functionality
    window.printDashboard = function() {
        window.print();
    };
    
    // Export functionality (if needed)
    window.exportDashboard = function(format = 'pdf') {
        showNotification('Export functionality will be implemented soon', 'info');
    };
    
    console.log('Dashboard initialized successfully');
});
</script>
@endpush