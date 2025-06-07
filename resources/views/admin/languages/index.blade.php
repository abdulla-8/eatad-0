@extends('admin.layouts.app')

@section('title', __('admin.language_management'))

@section('content')

<!-- Page Header -->
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
    <div>
        <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-2">{{ __('admin.language_management') }}</h1>
        <nav class="flex text-sm">
            <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-gold-600">{{ __('admin.dashboard') }}</a>
            <span class="mx-2 text-gray-400">></span>
            <span class="text-gold-600 font-medium">{{ __('admin.languages') }}</span>
        </nav>
    </div>
    <div class="flex items-center space-x-3 {{ $isRtl ? 'space-x-reverse' : '' }} mt-4 sm:mt-0">
        <div class="bg-gray-50 px-3 py-2 rounded-lg border">
            <span class="text-sm text-gray-600">{{ __('admin.total') }}: </span>
            <span class="font-bold text-gold-600">{{ $languages->count() }}</span>
        </div>
        <a href="{{ route('admin.translations.index') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg font-medium transition-colors">
            <svg class="w-4 h-4 inline {{ $isRtl ? 'ml-1' : 'mr-1' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            {{ __('admin.manage_translations') }}
        </a>
    </div>
</div>

<!-- Languages Container -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="bg-gold-500 text-dark-900 px-6 py-4">
        <h2 class="text-lg font-bold flex items-center">
            <svg class="w-5 h-5 {{ $isRtl ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path>
            </svg>
            {{ __('admin.languages') }}
        </h2>
    </div>
    
    @if($languages->count() > 0)
        <!-- Desktop Table -->
        <div class="hidden md:block">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-{{ $isRtl ? 'right' : 'left' }} text-xs font-semibold text-gray-600 uppercase">{{ __('admin.language_name') }}</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">{{ __('admin.language_code') }}</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">{{ __('admin.direction') }}</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">{{ __('admin.status') }}</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">{{ __('admin.default') }}</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">{{ __('admin.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($languages as $language)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-gold-500 rounded-lg flex items-center justify-center {{ $isRtl ? 'ml-3' : 'mr-3' }} text-white text-xs font-bold">
                                        {{ strtoupper($language->code) }}
                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-900">{{ $language->name }}</div>
                                        @if($language->code == app()->getLocale())
                                            <span class="bg-gold-100 text-gold-800 text-xs px-2 py-1 rounded-full">{{ __('admin.current') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="bg-gray-100 text-gray-800 text-sm font-mono px-2 py-1 rounded">{{ strtoupper($language->code) }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2 py-1 rounded-full text-xs font-medium {{ $language->direction == 'rtl' ? 'bg-orange-100 text-orange-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ strtoupper($language->direction) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $language->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    <div class="w-2 h-2 rounded-full {{ $language->is_active ? 'bg-green-400' : 'bg-gray-400' }} {{ $isRtl ? 'ml-1' : 'mr-1' }}"></div>
                                    {{ $language->is_active ? __('admin.active') : __('admin.inactive') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($language->is_default)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gold-100 text-gold-800">
                                        <svg class="w-3 h-3 {{ $isRtl ? 'ml-1' : 'mr-1' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                        {{ __('admin.default') }}
                                    </span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center space-x-2 {{ $isRtl ? 'space-x-reverse' : '' }}">
                                    <form method="POST" action="{{ route('admin.languages.toggle', $language->id) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="p-2 rounded-lg {{ $language->is_active ? 'bg-orange-100 text-orange-600 hover:bg-orange-200' : 'bg-green-100 text-green-600 hover:bg-green-200' }} transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $language->is_active ? 'M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z' : 'M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h8m-10 5a9 9 0 1118 0 9 9 0 01-18 0z' }}"></path>
                                            </svg>
                                        </button>
                                    </form>
                                    
                                    @if(!$language->is_default && $language->is_active)
                                        <form method="POST" action="{{ route('admin.languages.default', $language->id) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="p-2 rounded-lg bg-gold-100 text-gold-600 hover:bg-gold-200 transition-colors" onclick="return confirm('{{ __('admin.confirm_default') }}')">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                    
                                    @if($language->is_active && $language->code != app()->getLocale())
                                        <a href="{{ route('language.change', $language->code) }}" class="p-2 rounded-lg bg-blue-100 text-blue-600 hover:bg-blue-200 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                            </svg>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Mobile Cards -->
        <div class="md:hidden space-y-4 p-4">
            @foreach($languages as $language)
                <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gold-500 rounded-lg flex items-center justify-center {{ $isRtl ? 'ml-3' : 'mr-3' }} text-white text-sm font-bold">
                                {{ strtoupper($language->code) }}
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900">{{ $language->name }}</h3>
                                <div class="flex items-center space-x-2 {{ $isRtl ? 'space-x-reverse' : '' }} text-xs">
                                    <span class="px-2 py-1 rounded-full {{ $language->direction == 'rtl' ? 'bg-orange-100 text-orange-800' : 'bg-blue-100 text-blue-800' }}">
                                        {{ strtoupper($language->direction) }}
                                    </span>
                                    @if($language->code == app()->getLocale())
                                        <span class="bg-gold-100 text-gold-800 px-2 py-1 rounded-full">{{ __('admin.current') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-{{ $isRtl ? 'left' : 'right' }}">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $language->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                <div class="w-2 h-2 rounded-full {{ $language->is_active ? 'bg-green-400' : 'bg-gray-400' }} {{ $isRtl ? 'ml-1' : 'mr-1' }}"></div>
                                {{ $language->is_active ? __('admin.active') : __('admin.inactive') }}
                            </span>
                            @if($language->is_default)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gold-100 text-gold-800 mt-1">
                                    <svg class="w-3 h-3 {{ $isRtl ? 'ml-1' : 'mr-1' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                    {{ __('admin.default') }}
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-center space-x-3 {{ $isRtl ? 'space-x-reverse' : '' }} pt-3 border-t border-gray-200">
                        <form method="POST" action="{{ route('admin.languages.toggle', $language->id) }}" class="inline">
                            @csrf
                            <button type="submit" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium {{ $language->is_active ? 'bg-orange-100 text-orange-700 hover:bg-orange-200' : 'bg-green-100 text-green-700 hover:bg-green-200' }} transition-colors">
                                <svg class="w-4 h-4 {{ $isRtl ? 'ml-1' : 'mr-1' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $language->is_active ? 'M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z' : 'M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h8m-10 5a9 9 0 1118 0 9 9 0 01-18 0z' }}"></path>
                                </svg>
                                {{ $language->is_active ? __('admin.deactivate') : __('admin.activate') }}
                            </button>
                        </form>
                        
                        @if(!$language->is_default && $language->is_active)
                            <form method="POST" action="{{ route('admin.languages.default', $language->id) }}" class="inline">
                                @csrf
                                <button type="submit" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium bg-gold-100 text-gold-700 hover:bg-gold-200 transition-colors" onclick="return confirm('{{ __('admin.confirm_default') }}')">
                                    <svg class="w-4 h-4 {{ $isRtl ? 'ml-1' : 'mr-1' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                    </svg>
                                    {{ __('admin.set_default') }}
                                </button>
                            </form>
                        @endif
                        
                        @if($language->is_active && $language->code != app()->getLocale())
                            <a href="{{ route('language.change', $language->code) }}" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium bg-blue-100 text-blue-700 hover:bg-blue-200 transition-colors">
                                <svg class="w-4 h-4 {{ $isRtl ? 'ml-1' : 'mr-1' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                </svg>
                                {{ __('admin.switch_to') }} {{ $language->name }}
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="p-12 text-center">
            <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path>
            </svg>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ __('admin.no_languages') }}</h3>
            <p class="text-gray-600">{{ __('admin.no_languages_desc') }}</p>
        </div>
    @endif
</div>

<!-- Info Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
    <div class="bg-blue-50 rounded-xl p-6 border border-blue-200">
        <div class="flex items-center mb-3">
            <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center {{ $isRtl ? 'ml-3' : 'mr-3' }}">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h3 class="font-bold text-gray-900">{{ __('admin.language_info') }}</h3>
        </div>
        <p class="text-gray-700 text-sm">{{ __('admin.language_info_desc') }}</p>
    </div>
    
    <div class="bg-gold-50 rounded-xl p-6 border border-gold-200">
        <div class="flex items-center mb-3">
            <div class="w-10 h-10 bg-gold-500 rounded-lg flex items-center justify-center {{ $isRtl ? 'ml-3' : 'mr-3' }}">
                <svg class="w-5 h-5 text-dark-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                </svg>
            </div>
            <h3 class="font-bold text-gray-900">{{ __('admin.default_language') }}</h3>
        </div>
        <p class="text-gray-700 text-sm">{{ __('admin.default_language_desc') }}</p>
    </div>
    
    <div class="bg-green-50 rounded-xl p-6 border border-green-200">
        <div class="flex items-center mb-3">
            <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center {{ $isRtl ? 'ml-3' : 'mr-3' }}">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                </svg>
            </div>
            <h3 class="font-bold text-gray-900">{{ __('admin.switch_language') }}</h3>
        </div>
        <p class="text-gray-700 text-sm">{{ __('admin.switch_language_desc') }}</p>
    </div>
</div>

@endsection