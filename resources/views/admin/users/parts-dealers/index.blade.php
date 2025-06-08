@extends('admin.layouts.app')

@section('title', t('admin.parts_dealers_management'))

@section('content')

<!-- Page Header -->
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
    <div>
        <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-2">{{ t('admin.parts_dealers_management') }}</h1>
        <nav class="flex text-sm">
            <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-gold-600">{{ t('admin.dashboard') }}</a>
            <span class="mx-2 text-gray-400">></span>
            <span class="text-gold-600 font-medium">{{ t('admin.parts_dealers') }}</span>
        </nav>
    </div>
    <div class="flex items-center space-x-3 {{ $isRtl ? 'space-x-reverse' : '' }} mt-4 sm:mt-0">
        <div class="bg-gray-50 px-3 py-2 rounded-lg border">
            <span class="text-sm text-gray-600">{{ t('admin.total') }}: </span>
            <span class="font-bold text-gold-600">{{ $dealers->count() }}</span>
        </div>
        <a href="{{ route('admin.users.parts-dealers.create') }}" class="bg-gold-500 hover:bg-gold-600 text-dark-900 px-4 py-2 rounded-lg font-medium transition-colors">
            <svg class="w-4 h-4 inline {{ $isRtl ? 'ml-1' : 'mr-1' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            {{ t('admin.add_parts_dealer') }}
        </a>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">{{ t('admin.total_dealers') }}</p>
                <p class="text-3xl font-bold text-gray-900">{{ $dealers->count() }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">{{ t('admin.active_dealers') }}</p>
                <p class="text-3xl font-bold text-green-600">{{ $dealers->where('is_active', true)->count() }}</p>
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
                <p class="text-sm font-medium text-gray-600">{{ t('admin.scrapyard_owners') }}</p>
                <p class="text-3xl font-bold text-orange-600">{{ $dealers->where('is_scrapyard_owner', true)->count() }}</p>
            </div>
            <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">{{ t('admin.pending_approval') }}</p>
                <p class="text-3xl font-bold text-yellow-600">{{ $dealers->where('is_approved', false)->count() }}</p>
            </div>
            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Dealers Container -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="bg-gold-500 text-dark-900 px-6 py-4">
        <h2 class="text-lg font-bold flex items-center">
            <svg class="w-5 h-5 {{ $isRtl ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            {{ t('admin.parts_dealers') }}
        </h2>
    </div>
    
    @if($dealers->count() > 0)
        <!-- Desktop Table -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-{{ $isRtl ? 'right' : 'left' }} text-xs font-semibold text-gray-600 uppercase">{{ t('admin.dealer_info') }}</th>
                        <th class="px-6 py-3 text-{{ $isRtl ? 'right' : 'left' }} text-xs font-semibold text-gray-600 uppercase">{{ t('admin.contact_info') }}</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">{{ t('admin.type') }}</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">{{ t('admin.specialization') }}</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">{{ t('admin.status') }}</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">{{ t('admin.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($dealers as $dealer)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div>
                                    <div class="font-semibold text-gray-900">{{ $dealer->legal_name }}</div>
                                    <div class="text-sm text-gray-500">{{ t('admin.commercial_register') }}: {{ $dealer->commercial_register }}</div>
                                    @if($dealer->tax_number)
                                        <div class="text-sm text-gray-500">{{ t('admin.tax_number') }}: {{ $dealer->tax_number }}</div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div>
                                    <div class="font-medium text-gray-900">{{ $dealer->formatted_phone }}</div>
                                    @if($dealer->shop_address)
                                        <div class="text-sm text-gray-500">{{ Str::limit($dealer->shop_address, 50) }}</div>
                                    @endif
                                    @if($dealer->location_url)
                                        <a href="{{ $dealer->location_url }}" target="_blank" class="text-xs text-blue-600 hover:text-blue-800">
                                            {{ t('admin.view_on_map') }}
                                        </a>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2 py-1 rounded-full text-xs font-medium {{ $dealer->is_scrapyard_owner ? 'bg-orange-100 text-orange-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ $dealer->type }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($dealer->specialization)
                                    <span class="bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded">{{ $dealer->specialization->display_name }}</span>
                                @else
                                    <span class="text-gray-400 text-xs">{{ t('admin.not_specified') }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="space-y-1">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $dealer->status_badge['class'] }}">
                                        <div class="w-2 h-2 rounded-full {{ $dealer->is_active ? 'bg-green-400' : 'bg-red-400' }} {{ $isRtl ? 'ml-1' : 'mr-1' }}"></div>
                                        {{ $dealer->status_badge['text'] }}
                                    </span>
                                    @if(!$dealer->is_approved)
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
                                    <a href="{{ route('admin.users.parts-dealers.edit', $dealer) }}" class="p-2 rounded-lg bg-blue-100 text-blue-600 hover:bg-blue-200 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    
                                    <form method="POST" action="{{ route('admin.users.parts-dealers.toggle', $dealer) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="p-2 rounded-lg {{ $dealer->is_active ? 'bg-orange-100 text-orange-600 hover:bg-orange-200' : 'bg-green-100 text-green-600 hover:bg-green-200' }} transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $dealer->is_active ? 'M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z' : 'M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h8m-10 5a9 9 0 1118 0 9 9 0 01-18 0z' }}"></path>
                                            </svg>
                                        </button>
                                    </form>

                                    @if(!$dealer->is_approved)
                                        <form method="POST" action="{{ route('admin.users.parts-dealers.approve', $dealer) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="p-2 rounded-lg bg-green-100 text-green-600 hover:bg-green-200 transition-colors" title="{{ t('admin.approve') }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                    
                                    <form method="POST" action="{{ route('admin.users.parts-dealers.destroy', $dealer) }}" class="inline" onsubmit="return confirm('{{ t('admin.confirm_delete') }}')">
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
            @foreach($dealers as $dealer)
                <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex-1">
                            <h3 class="font-bold text-gray-900">{{ $dealer->legal_name }}</h3>
                            <p class="text-sm text-gray-600">{{ $dealer->formatted_phone }}</p>
                            <div class="flex items-center space-x-2 {{ $isRtl ? 'space-x-reverse' : '' }} mt-2">
                                <span class="px-2 py-1 rounded-full text-xs font-medium {{ $dealer->is_scrapyard_owner ? 'bg-orange-100 text-orange-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ $dealer->type }}
                                </span>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $dealer->status_badge['class'] }}">
                                    {{ $dealer->status_badge['text'] }}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    @if($dealer->specialization)
                        <div class="mb-3">
                            <span class="text-xs text-gray-500">{{ t('admin.specialization') }}: </span>
                            <span class="bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded">{{ $dealer->specialization->display_name }}</span>
                        </div>
                    @endif
                    
                    <div class="flex items-center justify-center space-x-3 {{ $isRtl ? 'space-x-reverse' : '' }} pt-3 border-t border-gray-200">
                        <a href="{{ route('admin.users.parts-dealers.edit', $dealer) }}" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium bg-blue-100 text-blue-700 hover:bg-blue-200 transition-colors">
                            <svg class="w-4 h-4 {{ $isRtl ? 'ml-1' : 'mr-1' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            {{ t('admin.edit') }}
                        </a>
                        
                        @if(!$dealer->is_approved)
                            <form method="POST" action="{{ route('admin.users.parts-dealers.approve', $dealer) }}" class="inline">
                                @csrf
                                <button type="submit" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium bg-green-100 text-green-700 hover:bg-green-200 transition-colors">
                                    <svg class="w-4 h-4 {{ $isRtl ? 'ml-1' : 'mr-1' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ t('admin.approve') }}
                                </button>
                            </form>
                        @endif
                        
                        <form method="POST" action="{{ route('admin.users.parts-dealers.destroy', $dealer) }}" class="inline" onsubmit="return confirm('{{ t('admin.confirm_delete') }}')">
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
        <div class="p-12 text-center">
            <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ t('admin.no_parts_dealers') }}</h3>
            <p class="text-gray-600">{{ t('admin.no_parts_dealers_desc') }}</p>
        </div>
    @endif
</div>

@endsection