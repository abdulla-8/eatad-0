@extends('admin.layouts.app')

@section('title', isset($partsDealer) ? t('admin.edit_parts_dealer') : t('admin.add_parts_dealer'))

@section('content')

<!-- Page Header -->
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
    <div>
        <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-2">
            {{ isset($partsDealer) ? t('admin.edit_parts_dealer') : t('admin.add_parts_dealer') }}
        </h1>
        <nav class="flex text-sm">
            <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-gold-600">{{ t('admin.dashboard') }}</a>
            <span class="mx-2 text-gray-400">></span>
            <a href="{{ route('admin.users.parts-dealers.index') }}" class="text-gray-500 hover:text-gold-600">{{ t('admin.parts_dealers') }}</a>
            <span class="mx-2 text-gray-400">></span>
            <span class="text-gold-600 font-medium">
                {{ isset($partsDealer) ? t('admin.edit') : t('admin.add_new') }}
            </span>
        </nav>
    </div>
    <a href="{{ route('admin.users.parts-dealers.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium transition-colors mt-4 sm:mt-0">
        <svg class="w-4 h-4 inline {{ $isRtl ? 'ml-1' : 'mr-1' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        {{ t('admin.back_to_list') }}
    </a>
</div>

<!-- Form Container -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="bg-gold-500 text-dark-900 px-6 py-4">
        <h2 class="text-lg font-bold flex items-center">
            <svg class="w-5 h-5 {{ $isRtl ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ isset($partsDealer) ? 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z' : 'M12 4v16m8-8H4' }}"></path>
            </svg>
            {{ t('admin.dealer_details') }}
        </h2>
    </div>
    
    <form method="POST" 
          action="{{ isset($partsDealer) ? route('admin.users.parts-dealers.update', $partsDealer) : route('admin.users.parts-dealers.store') }}" 
          class="p-6"
          id="partsDealerForm">
        @csrf
        @if(isset($partsDealer))
            @method('PUT')
        @endif
        
        <!-- Basic Information -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b border-gray-200 pb-2">{{ t('admin.basic_information') }}</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Legal Name -->
                <div class="md:col-span-2">
                    <label for="legal_name" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ t('admin.legal_name') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="legal_name" 
                           name="legal_name" 
                           value="{{ old('legal_name', $partsDealer->legal_name ?? '') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gold-500 focus:border-gold-500 @error('legal_name') border-red-500 @enderror"
                           placeholder="{{ t('admin.legal_name_placeholder') }}"
                           required>
                    @error('legal_name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ t('admin.phone_number') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="phone" 
                           name="phone" 
                           value="{{ old('phone', $partsDealer->phone ?? '') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gold-500 focus:border-gold-500 @error('phone') border-red-500 @enderror"
                           placeholder="01234567890"
                           required>
                    @error('phone')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ t('admin.password') }} 
                        @if(!isset($partsDealer))
                            <span class="text-red-500">*</span>
                        @endif
                    </label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gold-500 focus:border-gold-500 @error('password') border-red-500 @enderror"
                           placeholder="••••••••"
                           {{ !isset($partsDealer) ? 'required' : '' }}>
                    @if(isset($partsDealer))
                        <p class="text-xs text-gray-500 mt-1">{{ t('admin.leave_empty_keep_current') }}</p>
                    @endif
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Commercial Register -->
                <div>
                    <label for="commercial_register" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ t('admin.commercial_register') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="commercial_register" 
                           name="commercial_register" 
                           value="{{ old('commercial_register', $partsDealer->commercial_register ?? '') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gold-500 focus:border-gold-500 @error('commercial_register') border-red-500 @enderror"
                           placeholder="CR123456789"
                           required>
                    @error('commercial_register')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Tax Number -->
                <div>
                    <label for="tax_number" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ t('admin.tax_number') }} <span class="text-gray-400">({{ t('admin.optional') }})</span>
                    </label>
                    <input type="text" 
                           id="tax_number" 
                           name="tax_number" 
                           value="{{ old('tax_number', $partsDealer->tax_number ?? '') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gold-500 focus:border-gold-500 @error('tax_number') border-red-500 @enderror"
                           placeholder="TX123456789">
                    @error('tax_number')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
        
        <!-- Business Information -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b border-gray-200 pb-2">{{ t('admin.business_information') }}</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Specialization -->
                <div>
                    <label for="specialization_id" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ t('admin.specialization') }}
                    </label>
                    <select name="specialization_id" 
                            id="specialization_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gold-500 focus:border-gold-500 @error('specialization_id') border-red-500 @enderror">
                        <option value="">{{ t('admin.select_specialization') }}</option>
                        @foreach($specializations as $specialization)
                            <option value="{{ $specialization->id }}" 
                                    {{ old('specialization_id', $partsDealer->specialization_id ?? '') == $specialization->id ? 'selected' : '' }}>
                                {{ $specialization->display_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('specialization_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Dealer Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ t('admin.dealer_type') }}
                    </label>
                    <div class="space-y-3">
                        <label class="inline-flex items-center">
                            <input type="radio" 
                                   name="is_scrapyard_owner" 
                                   value="0" 
                                   {{ old('is_scrapyard_owner', $partsDealer->is_scrapyard_owner ?? 0) == 0 ? 'checked' : '' }}
                                   class="form-radio text-gold-500 focus:ring-gold-500">
                            <span class="{{ $isRtl ? 'mr-2' : 'ml-2' }} text-sm text-gray-700">{{ t('admin.regular_dealer') }}</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" 
                                   name="is_scrapyard_owner" 
                                   value="1" 
                                   {{ old('is_scrapyard_owner', $partsDealer->is_scrapyard_owner ?? 0) == 1 ? 'checked' : '' }}
                                   class="form-radio text-gold-500 focus:ring-gold-500">
                            <span class="{{ $isRtl ? 'mr-2' : 'ml-2' }} text-sm text-gray-700">{{ t('admin.scrapyard_owner') }}</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Location Information -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b border-gray-200 pb-2">{{ t('admin.location_information') }}</h3>
            
            <div class="space-y-6">
                <!-- Address -->
                <div>
                    <label for="shop_address" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ t('admin.shop_address') }}
                    </label>
                    <textarea id="shop_address" 
                              name="shop_address" 
                              rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gold-500 focus:border-gold-500 @error('shop_address') border-red-500 @enderror"
                              placeholder="{{ t('admin.shop_address_placeholder') }}">{{ old('shop_address', $partsDealer->shop_address ?? '') }}</textarea>
                    @error('shop_address')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Map Location -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ t('admin.map_location') }}
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <input type="number" 
                                   id="shop_location_lat" 
                                   name="shop_location_lat" 
                                   step="any"
                                   value="{{ old('shop_location_lat', $partsDealer->shop_location_lat ?? '') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gold-500 focus:border-gold-500 @error('shop_location_lat') border-red-500 @enderror"
                                   placeholder="{{ t('admin.latitude') }}">
                            @error('shop_location_lat')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <input type="number" 
                                   id="shop_location_lng" 
                                   name="shop_location_lng" 
                                   step="any"
                                   value="{{ old('shop_location_lng', $partsDealer->shop_location_lng ?? '') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gold-500 focus:border-gold-500 @error('shop_location_lng') border-red-500 @enderror"
                                   placeholder="{{ t('admin.longitude') }}">
                            @error('shop_location_lng')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">{{ t('admin.map_location_help') }}</p>
                    
                    <!-- Get Current Location Button -->
                    <button type="button" 
                            onclick="getCurrentLocation()" 
                            class="mt-2 bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm transition-colors">
                        {{ t('admin.get_current_location') }}
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Status Settings -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b border-gray-200 pb-2">{{ t('admin.status_settings') }}</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Active Status -->
                <div>
                    <label class="flex items-center">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" 
                               name="is_active" 
                               value="1"
                               {{ old('is_active', $partsDealer->is_active ?? 1) ? 'checked' : '' }}
                               class="w-4 h-4 text-gold-500 bg-gray-100 border-gray-300 rounded focus:ring-gold-500">
                        <span class="{{ $isRtl ? 'mr-3' : 'ml-3' }} text-sm font-medium text-gray-700">{{ t('admin.active_account') }}</span>
                    </label>
                    <p class="text-xs text-gray-500 mt-1">{{ t('admin.active_account_help') }}</p>
                </div>
                
                <!-- Approved Status -->
                <div>
                    <label class="flex items-center">
                        <input type="hidden" name="is_approved" value="0">
                        <input type="checkbox" 
                               name="is_approved" 
                               value="1"
                               {{ old('is_approved', $partsDealer->is_approved ?? 0) ? 'checked' : '' }}
                               class="w-4 h-4 text-gold-500 bg-gray-100 border-gray-300 rounded focus:ring-gold-500">
                        <span class="{{ $isRtl ? 'mr-3' : 'ml-3' }} text-sm font-medium text-gray-700">{{ t('admin.approved_account') }}</span>
                    </label>
                    <p class="text-xs text-gray-500 mt-1">{{ t('admin.approved_account_help') }}</p>
                </div>
            </div>
        </div>
        
        <!-- Form Actions -->
        <div class="flex items-center justify-end space-x-3 {{ $isRtl ? 'space-x-reverse' : '' }} pt-6 border-t border-gray-200">
            <a href="{{ route('admin.users.parts-dealers.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                {{ t('admin.cancel') }}
            </a>
            <button type="submit" 
                    class="bg-gold-500 hover:bg-gold-600 text-dark-900 px-6 py-2 rounded-lg font-medium transition-colors flex items-center">
                <svg class="w-4 h-4 {{ $isRtl ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                {{ isset($partsDealer) ? t('admin.update') : t('admin.save') }}
            </button>
        </div>
    </form>
</div>

<script>
// Get current location using browser geolocation
function getCurrentLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            document.getElementById('shop_location_lat').value = position.coords.latitude.toFixed(8);
            document.getElementById('shop_location_lng').value = position.coords.longitude.toFixed(8);
            
            // Show success message
            const message = document.createElement('div');
            message.className = 'fixed top-4 {{ $isRtl ? "left-4" : "right-4" }} bg-green-500 text-white px-3 py-1 rounded text-sm z-50';
            message.textContent = '{{ t("admin.location_updated") }}';
            document.body.appendChild(message);
            
            setTimeout(() => {
                if (message.parentNode) {
                    message.parentNode.removeChild(message);
                }
            }, 3000);
        }, function(error) {
            alert('{{ t("admin.location_error") }}: ' + error.message);
        });
    } else {
        alert('{{ t("admin.geolocation_not_supported") }}');
    }
}

// Phone number formatting
document.getElementById('phone').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length > 11) {
        value = value.substring(0, 11);
    }
    e.target.value = value;
});
</script>

@endsection