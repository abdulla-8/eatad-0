@extends('admin.layouts.app')

@section('title', t('admin.industrial_areas_management'))

@section('content')

<!-- Page Header -->
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
    <div>
        <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-2">{{ t('admin.industrial_areas_management') }}</h1>
        <nav class="flex text-sm">
            <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-gold-600">{{ t('admin.dashboard') }}</a>
            <span class="mx-2 text-gray-400">></span>
            <span class="text-gold-600 font-medium">{{ t('admin.industrial_areas') }}</span>
        </nav>
    </div>
    <div class="flex items-center space-x-3 {{ $isRtl ? 'space-x-reverse' : '' }} mt-4 sm:mt-0">
        <div class="bg-gray-50 px-3 py-2 rounded-lg border">
            <span class="text-sm text-gray-600">{{ t('admin.total') }}: </span>
            <span class="font-bold text-gold-600">{{ $industrialAreas->count() }}</span>
        </div>
        <a href="{{ route('admin.industrial-areas.create') }}" class="bg-gold-500 hover:bg-gold-600 text-dark-900 px-4 py-2 rounded-lg font-medium transition-colors">
            <svg class="w-4 h-4 inline {{ $isRtl ? 'ml-1' : 'mr-1' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            {{ t('admin.add_industrial_area') }}
        </a>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">{{ t('admin.total_areas') }}</p>
                <p class="text-3xl font-bold text-gray-900">{{ $industrialAreas->count() }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">{{ t('admin.active_areas') }}</p>
                <p class="text-3xl font-bold text-green-600">{{ $industrialAreas->where('is_active', true)->count() }}</p>
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
                <p class="text-sm font-medium text-gray-600">{{ t('admin.service_centers_using') }}</p>
                <p class="text-3xl font-bold text-purple-600">{{ $industrialAreas->sum(function($area) { return $area->serviceCenters->count(); }) }}</p>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Industrial Areas Container -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="bg-gold-500 text-dark-900 px-6 py-4">
        <h2 class="text-lg font-bold flex items-center">
            <svg class="w-5 h-5 {{ $isRtl ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
            {{ t('admin.industrial_areas') }}
        </h2>
    </div>
    
    @if($industrialAreas->count() > 0)
        <!-- Desktop Table -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-{{ $isRtl ? 'right' : 'left' }} text-xs font-semibold text-gray-600 uppercase">{{ t('admin.sort_order') }}</th>
                        <th class="px-6 py-3 text-{{ $isRtl ? 'right' : 'left' }} text-xs font-semibold text-gray-600 uppercase">{{ t('admin.area_name') }}</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">{{ t('admin.service_centers') }}</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">{{ t('admin.status') }}</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">{{ t('admin.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200" id="sortableAreas">
                    @foreach($industrialAreas as $area)
                        <tr class="hover:bg-gray-50" data-id="{{ $area->id }}">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-gray-400 cursor-move {{ $isRtl ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V6a2 2 0 012-2h2M4 16v2a2 2 0 002 2h2M20 8V6a2 2 0 00-2-2h-2M20 16v2a2 2 0 01-2 2h-2"></path>
                                    </svg>
                                    <span class="font-medium text-gray-900">{{ $area->sort_order }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div>
                                    <div class="font-semibold text-gray-900">{{ $area->name_ar }}</div>
                                    <div class="text-sm text-gray-500">{{ $area->name }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="bg-blue-100 text-blue-800 text-sm px-2 py-1 rounded">
                                    {{ $area->serviceCenters->count() }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $area->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    <div class="w-2 h-2 rounded-full {{ $area->is_active ? 'bg-green-400' : 'bg-red-400' }} {{ $isRtl ? 'ml-1' : 'mr-1' }}"></div>
                                    {{ $area->is_active ? t('admin.active') : t('admin.inactive') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center space-x-2 {{ $isRtl ? 'space-x-reverse' : '' }}">
                                    <a href="{{ route('admin.industrial-areas.edit', $area) }}" class="p-2 rounded-lg bg-blue-100 text-blue-600 hover:bg-blue-200 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    
                                    <form method="POST" action="{{ route('admin.industrial-areas.toggle', $area) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="p-2 rounded-lg {{ $area->is_active ? 'bg-orange-100 text-orange-600 hover:bg-orange-200' : 'bg-green-100 text-green-600 hover:bg-green-200' }} transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $area->is_active ? 'M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z' : 'M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h8m-10 5a9 9 0 1118 0 9 9 0 01-18 0z' }}"></path>
                                            </svg>
                                        </button>
                                    </form>
                                    
                                    <form method="POST" action="{{ route('admin.industrial-areas.destroy', $area) }}" class="inline" onsubmit="return confirm('{{ t('admin.confirm_delete') }}')">
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
            @foreach($industrialAreas as $area)
                <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex-1">
                            <h3 class="font-bold text-gray-900">{{ $area->name_ar }}</h3>
                            <p class="text-sm text-gray-600">{{ $area->name }}</p>
                            <div class="flex items-center space-x-2 {{ $isRtl ? 'space-x-reverse' : '' }} mt-2">
                                <span class="px-2 py-1 rounded-full text-xs font-medium {{ $area->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $area->is_active ? t('admin.active') : t('admin.inactive') }}
                                </span>
                                <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">
                                    {{ $area->serviceCenters->count() }} {{ t('admin.centers') }}
                                </span>
                            </div>
                        </div>
                        <div class="text-gray-500 text-sm">
                            #{{ $area->sort_order }}
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-center space-x-3 {{ $isRtl ? 'space-x-reverse' : '' }} pt-3 border-t border-gray-200">
                        <a href="{{ route('admin.industrial-areas.edit', $area) }}" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium bg-blue-100 text-blue-700 hover:bg-blue-200 transition-colors">
                            <svg class="w-4 h-4 {{ $isRtl ? 'ml-1' : 'mr-1' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            {{ t('admin.edit') }}
                        </a>
                        
                        <form method="POST" action="{{ route('admin.industrial-areas.toggle', $area) }}" class="inline">
                            @csrf
                            <button type="submit" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium {{ $area->is_active ? 'bg-orange-100 text-orange-700 hover:bg-orange-200' : 'bg-green-100 text-green-700 hover:bg-green-200' }} transition-colors">
                                <svg class="w-4 h-4 {{ $isRtl ? 'ml-1' : 'mr-1' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $area->is_active ? 'M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z' : 'M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h8m-10 5a9 9 0 1118 0 9 9 0 01-18 0z' }}"></path>
                                </svg>
                                {{ $area->is_active ? t('admin.deactivate') : t('admin.activate') }}
                            </button>
                        </form>
                        
                        <form method="POST" action="{{ route('admin.industrial-areas.destroy', $area) }}" class="inline" onsubmit="return confirm('{{ t('admin.confirm_delete') }}')">
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ t('admin.no_industrial_areas') }}</h3>
            <p class="text-gray-600">{{ t('admin.no_industrial_areas_desc') }}</p>
        </div>
    @endif
</div>

@endsection

@push('scripts')
<script>
// Sortable functionality for reordering
// You can implement drag & drop here if needed
</script>
@endpush