@extends('admin.layouts.app')

@section('title', isset($industrialArea) ? t('admin.edit_industrial_area') : t('admin.add_industrial_area'))

@section('content')

<!-- Page Header -->
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
    <div>
        <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-2">
            {{ isset($industrialArea) ? t('admin.edit_industrial_area') : t('admin.add_industrial_area') }}
        </h1>
        <nav class="flex text-sm">
            <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-gold-600">{{ t('admin.dashboard') }}</a>
            <span class="mx-2 text-gray-400">></span>
            <a href="{{ route('admin.industrial-areas.index') }}" class="text-gray-500 hover:text-gold-600">{{ t('admin.industrial_areas') }}</a>
            <span class="mx-2 text-gray-400">></span>
            <span class="text-gold-600 font-medium">
                {{ isset($industrialArea) ? t('admin.edit') : t('admin.add_new') }}
            </span>
        </nav>
    </div>
    <a href="{{ route('admin.industrial-areas.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium transition-colors mt-4 sm:mt-0">
        <svg class="w-4 h-4 inline {{ $isRtl ? 'ml-1' : 'mr-1' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        {{ t('admin.back_to_list') }}
    </a>
</div>

<!-- Form Container -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden max-w-2xl mx-auto">
    <div class="bg-gold-500 text-dark-900 px-6 py-4">
        <h2 class="text-lg font-bold flex items-center">
            <svg class="w-5 h-5 {{ $isRtl ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ isset($industrialArea) ? 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z' : 'M12 4v16m8-8H4' }}"></path>
            </svg>
            {{ t('admin.area_details') }}
        </h2>
    </div>
    
    <form method="POST" 
          action="{{ isset($industrialArea) ? route('admin.industrial-areas.update', $industrialArea) : route('admin.industrial-areas.store') }}" 
          class="p-6"
          id="industrialAreaForm">
        @csrf
        @if(isset($industrialArea))
            @method('PUT')
        @endif
        
        <!-- Area Information -->
        <div class="space-y-6">
            <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">{{ t('admin.area_information') }}</h3>
            
        <!-- Area Information -->
        <div class="space-y-6">
            <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">{{ t('admin.area_information') }}</h3>
            
            <div class="grid grid-cols-1 gap-6">
                <!-- Arabic Name -->
                <div>
                    <label for="name_ar" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ t('admin.arabic_name') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="name_ar" 
                           name="name_ar" 
                           value="{{ old('name_ar', $industrialArea->name_ar ?? '') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gold-500 focus:border-gold-500 @error('name_ar') border-red-500 @enderror"
                           placeholder="الصناعية القديمة"
                           required>
                    @error('name_ar')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- English Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ t('admin.english_name') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', $industrialArea->name ?? '') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gold-500 focus:border-gold-500 @error('name') border-red-500 @enderror"
                           placeholder="Old Industrial"
                           required>
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Sort Order -->
                <div>
                    <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ t('admin.sort_order') }}
                    </label>
                    <input type="number" 
                           id="sort_order" 
                           name="sort_order" 
                           min="0"
                           value="{{ old('sort_order', $industrialArea->sort_order ?? 0) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gold-500 focus:border-gold-500 @error('sort_order') border-red-500 @enderror"
                           placeholder="0">
                    <p class="text-xs text-gray-500 mt-1">{{ t('admin.sort_order_help') }}</p>
                    @error('sort_order')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
        
        <!-- Status Settings -->
        <div class="space-y-6">
            <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">{{ t('admin.status_settings') }}</h3>
            
            <div>
                <label class="flex items-center">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" 
                           name="is_active" 
                           value="1"
                           {{ old('is_active', $industrialArea->is_active ?? 1) ? 'checked' : '' }}
                           class="w-4 h-4 text-gold-500 bg-gray-100 border-gray-300 rounded focus:ring-gold-500">
                    <span class="{{ $isRtl ? 'mr-3' : 'ml-3' }} text-sm font-medium text-gray-700">{{ t('admin.active_area') }}</span>
                </label>
                <p class="text-xs text-gray-500 mt-1">{{ t('admin.active_area_help') }}</p>
            </div>
        </div>
        
        <!-- Form Actions -->
        <div class="flex items-center justify-end space-x-3 {{ $isRtl ? 'space-x-reverse' : '' }} pt-6 border-t border-gray-200">
            <a href="{{ route('admin.industrial-areas.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                {{ t('admin.cancel') }}
            </a>
            <button type="submit" 
                    class="bg-gold-500 hover:bg-gold-600 text-dark-900 px-6 py-2 rounded-lg font-medium transition-colors flex items-center">
                <svg class="w-4 h-4 {{ $isRtl ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                {{ isset($industrialArea) ? t('admin.update') : t('admin.save') }}
            </button>
        </div>
    </form>
</div>

@endsection

@push('scripts')
<script>
// Form validation
document.getElementById('industrialAreaForm').addEventListener('submit', function(e) {
    const required = ['name_ar', 'name'];
    for (let field of required) {
        if (!document.getElementById(field).value.trim()) {
            e.preventDefault();
            alert('Please fill all required fields');
            return false;
        }
    }
});
</script>
@endpush