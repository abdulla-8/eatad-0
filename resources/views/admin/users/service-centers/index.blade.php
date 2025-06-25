@extends('admin.layouts.app')

@section('title', t('admin.service_centers_management'))

@section('content')

<!-- Page Header -->
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
    <div>
        <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-2">{{ t('admin.service_centers_management') }}</h1>
        <nav class="flex text-sm">
            <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-gold-600">{{ t('admin.dashboard') }}</a>
            <span class="mx-2 text-gray-400">></span>
            <span class="text-gold-600 font-medium">{{ t('admin.service_centers') }}</span>
        </nav>
    </div>
    <div class="flex items-center space-x-3 {{ $isRtl ? 'space-x-reverse' : '' }} mt-4 sm:mt-0">
        <div class="bg-gray-50 px-3 py-2 rounded-lg border">
            <span class="text-sm text-gray-600">{{ t('admin.total') }}: </span>
            <span class="font-bold text-gold-600">{{ $serviceCenters->count() }}</span>
        </div>
        <a href="{{ route('admin.users.service-centers.create') }}" class="bg-gold-500 hover:bg-gold-600 text-dark-900 px-4 py-2 rounded-lg font-medium transition-colors">
            <svg class="w-4 h-4 inline {{ $isRtl ? 'ml-1' : 'mr-1' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            {{ t('admin.add_service_center') }}
        </a>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">{{ t('admin.total_centers') }}</p>
                <p class="text-3xl font-bold text-gray-900">{{ $serviceCenters->count() }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">{{ t('admin.active_centers') }}</p>
                <p class="text-3xl font-bold text-green-600">{{ $serviceCenters->where('is_active', true)->count() }}</p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">{{ t('admin.total_technicians') }}</p>
                <p class="text-3xl font-bold text-purple-600">{{ $serviceCenters->sum('body_work_technicians') + $serviceCenters->sum('mechanical_technicians') + $serviceCenters->sum('painting_technicians') + $serviceCenters->sum('electrical_technicians') + $serviceCenters->sum('other_technicians') }}</p>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">{{ t('admin.pending_approval') }}</p>
                <p class="text-3xl font-bold text-yellow-600">{{ $serviceCenters->where('is_approved', false)->count() }}</p>
            </div>
            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Service Centers Container -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="bg-gold-500 text-dark-900 px-6 py-4">
        <h2 class="text-lg font-bold flex items-center">
            <svg class="w-5 h-5 {{ $isRtl ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            {{ t('admin.service_centers') }}
        </h2>
    </div>
    
    @if($serviceCenters->count() > 0)
        <!-- Desktop Table -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-{{ $isRtl ? 'right' : 'left' }} text-xs font-semibold text-gray-600 uppercase">{{ t('admin.center_info') }}</th>
                        <th class="px-6 py-3 text-{{ $isRtl ? 'right' : 'left' }} text-xs font-semibold text-gray-600 uppercase">{{ t('admin.location_info') }}</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">{{ t('admin.specialization') }}</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">{{ t('admin.technicians') }}</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">{{ t('admin.status') }}</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">{{ t('admin.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($serviceCenters as $serviceCenter)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div>
                                    <div class="font-semibold text-gray-900">{{ $serviceCenter->legal_name }}</div>
                                    <div class="text-sm text-gray-500">{{ t('admin.phone') }}: {{ $serviceCenter->formatted_phone }}</div>
                                    <div class="text-sm text-gray-500">{{ t('admin.commercial_register') }}: {{ $serviceCenter->commercial_register }}</div>
                                    @if($serviceCenter->center_area_sqm)
                                        <div class="text-sm text-gray-500">{{ t('admin.area') }}: {{ number_format($serviceCenter->center_area_sqm) }} م²</div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div>
                                    @if($serviceCenter->industrialArea)
                                        <div class="font-medium text-gray-900">{{ $serviceCenter->industrialArea->display_name }}</div>
                                    @endif
                                    @if($serviceCenter->center_address)
                                        <div class="text-sm text-gray-500">{{ Str::limit($serviceCenter->center_address, 50) }}</div>
                                    @endif
                                    @if($serviceCenter->location_url)
                                        <a href="{{ $serviceCenter->location_url }}" target="_blank" class="text-xs text-blue-600 hover:text-blue-800">
                                            {{ t('admin.view_on_map') }}
                                        </a>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($serviceCenter->serviceSpecialization)
                                    <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">{{ $serviceCenter->serviceSpecialization->display_name }}</span>
                                @else
                                    <span class="text-gray-400 text-xs">{{ t('admin.not_specified') }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="space-y-1">
                                    <div class="text-lg font-bold text-gray-900">{{ $serviceCenter->total_technicians }}</div>
                                    @if($serviceCenter->total_technicians > 0)
                                        <div class="text-xs text-gray-500">
                                            @if($serviceCenter->body_work_technicians > 0)
                                                <span class="block">سمكرة: {{ $serviceCenter->body_work_technicians }}</span>
                                            @endif
                                            @if($serviceCenter->mechanical_technicians > 0)
                                                <span class="block">ميكانيكا: {{ $serviceCenter->mechanical_technicians }}</span>
                                            @endif
                                            @if($serviceCenter->painting_technicians > 0)
                                                <span class="block">دهان: {{ $serviceCenter->painting_technicians }}</span>
                                            @endif
                                            @if($serviceCenter->electrical_technicians > 0)
                                                <span class="block">كهرباء: {{ $serviceCenter->electrical_technicians }}</span>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="space-y-1">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $serviceCenter->status_badge['class'] }}">
                                        <div class="w-2 h-2 rounded-full {{ $serviceCenter->is_active ? 'bg-green-400' : 'bg-red-400' }} {{ $isRtl ? 'ml-1' : 'mr-1' }}"></div>
                                        {{ $serviceCenter->status_badge['text'] }}
                                    </span>
                                    @if(!$serviceCenter->is_approved)
                                        <div>
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                {{ t('admin.needs_approval') }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center space-x-2 {{ $isRtl ? 'space-x-reverse' : '' }}">
                                    <a href="{{ route('admin.users.service-centers.edit', $serviceCenter) }}" class="p-2 rounded-lg bg-blue-100 text-blue-600 hover:bg-blue-200 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    
                                    <form method="POST" action="{{ route('admin.users.service-centers.toggle', $serviceCenter) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="p-2 rounded-lg {{ $serviceCenter->is_active ? 'bg-orange-100 text-orange-600 hover:bg-orange-200' : 'bg-green-100 text-green-600 hover:bg-green-200' }} transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $serviceCenter->is_active ? 'M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z' : 'M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h8m-10 5a9 9 0 1118 0 9 9 0 01-18 0z' }}"></path>
                                            </svg>
                                        </button>
                                    </form>

                                    @if(!$serviceCenter->is_approved)
                                        <form method="POST" action="{{ route('admin.users.service-centers.approve', $serviceCenter) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="p-2 rounded-lg bg-green-100 text-green-600 hover:bg-green-200 transition-colors" title="{{ t('admin.approve') }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                    
                                    <form method="POST" action="{{ route('admin.users.service-centers.destroy', $serviceCenter) }}" class="inline" onsubmit="return confirm('{{ t('admin.confirm_delete') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 rounded-lg bg-red-100 text-red-600 hover:bg-red-200 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Mobile Cards -->
        <div class="md:hidden space-y-4 p-4">
            @foreach($serviceCenters as $serviceCenter)
                <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex-1">
                            <h3 class="font-bold text-gray-900">{{ $serviceCenter->legal_name }}</h3>
                            <p class="text-sm text-gray-600">{{ $serviceCenter->formatted_phone }}</p>
                            @if($serviceCenter->industrialArea)
                                <p class="text-sm text-gray-500">{{ $serviceCenter->industrialArea->display_name }}</p>
                            @endif
                            <div class="flex items-center space-x-2 {{ $isRtl ? 'space-x-reverse' : '' }} mt-2">
                                @if($serviceCenter->serviceSpecialization)
                                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $serviceCenter->serviceSpecialization->display_name }}
                                    </span>
                                @endif
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $serviceCenter->status_badge['class'] }}">
                                    {{ $serviceCenter->status_badge['text'] }}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    @if($serviceCenter->total_technicians > 0)
                        <div class="mb-3 bg-gray-100 rounded p-2 text-xs">
                            <span class="text-gray-500">{{ t('admin.total_technicians') }}: </span>
                            <span class="font-medium">{{ $serviceCenter->total_technicians }}</span>
                        </div>
                    @endif
                    
                    <div class="flex items-center justify-center space-x-3 {{ $isRtl ? 'space-x-reverse' : '' }} pt-3 border-t border-gray-200">
                        <a href="{{ route('admin.users.service-centers.edit', $serviceCenter) }}" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium bg-blue-100 text-blue-700 hover:bg-blue-200 transition-colors">
                            <svg class="w-4 h-4 {{ $isRtl ? 'ml-1' : 'mr-1' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            {{ t('admin.edit') }}
                        </a>
                        
                        @if(!$serviceCenter->is_approved)
                            <form method="POST" action="{{ route('admin.users.service-centers.approve', $serviceCenter) }}" class="inline">
                                @csrf
                                <button type="submit" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium bg-green-100 text-green-700 hover:bg-green-200 transition-colors">
                                    <svg class="w-4 h-4 {{ $isRtl ? 'ml-1' : 'mr-1' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ t('admin.approve') }}
                                </button>
                            </form>
                        @endif
                        
                        <form method="POST" action="{{ route('admin.users.service-centers.destroy', $serviceCenter) }}" class="inline" onsubmit="return confirm('{{ t('admin.confirm_delete') }}')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium bg-red-100 text-red-700 hover:bg-red-200 transition-colors">
                                <svg class="w-4 h-4 {{ $isRtl ? 'ml-1' : 'mr-1' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                {{ t('admin.delete') }}
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="p-12 text-center">
            <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37c.996.608 2.296.07 2.572-1.065z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2
" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            <p class="text-gray-600 text-lg">{{ t('admin.no_service_centers_found') }}</p>
            <p class="text-gray-500 text-sm mt-2">{{ t('admin.add_new_service_center') }}</p>
            <a href="{{ route('admin.users.service-centers.create') }}" class="mt-4 inline-block bg-gold-500 hover:bg-gold-600 text-dark-900 px-4 py-2 rounded-lg font-medium transition-colors">
                <svg class="w-4 h-4 inline {{ $isRtl ? 'ml-1' : 'mr-1' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                {{ t('admin.add_service_center') }}
            </a>
        </div>
    @endif
</div>
@endsection
