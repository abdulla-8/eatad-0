@extends('admin.layouts.app')

@section('title', isset($partsDealer) ? t('admin.edit_parts_dealer') : t('admin.add_parts_dealer'))
@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
.compact-form { font-size: 0.875rem; }
.compact-form input, .compact-form textarea, .compact-form select { padding: 0.375rem 0.75rem; }
.compact-form .form-group { margin-bottom: 0.75rem; }
.compact-form .section-title { font-size: 1rem; padding: 0.5rem 0; border-bottom: 1px solid #e5e7eb; margin-bottom: 0.75rem; }
.leaflet-container { height: 250px; border-radius: 0.375rem; }
</style>
@endpush
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
         <!-- Map Section -->
        <div class="mb-4">
            <label class="block font-medium text-gray-700 mb-1">{{ t('admin.map_location') }}</label>
            
            <!-- Coordinates -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mb-2">
                <input type="number" id="office_location_lat" name="office_location_lat" step="any"
                       value="{{ old('office_location_lat', $insuranceCompany->office_location_lat ?? '') }}"
                       class="border border-gray-300 rounded focus:ring-2 focus:ring-gold-500 @error('office_location_lat') border-red-500 @enderror"
                       placeholder="{{ t('admin.latitude') }}" readonly>
                <input type="number" id="office_location_lng" name="office_location_lng" step="any"
                       value="{{ old('office_location_lng', $insuranceCompany->office_location_lng ?? '') }}"
                       class="border border-gray-300 rounded focus:ring-2 focus:ring-gold-500 @error('office_location_lng') border-red-500 @enderror"
                       placeholder="{{ t('admin.longitude') }}" readonly>
            </div>
            
            <!-- Map Controls -->
            <div class="flex gap-2 flex-wrap mb-2">
                <button type="button" onclick="openMap()" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs">
                    📍 {{ t('admin.open_map') }}
                </button>
                <button type="button" onclick="getCurrentLocation()" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-xs">
                    📌 {{ t('admin.get_current_location') }}
                </button>
                <button type="button" onclick="clearLocation()" class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-1 rounded text-xs">
                    🗑️ {{ t('admin.clear_location') }}
                </button>
            </div>
            
            <!-- Map Container -->
            <div id="mapContainer" class="hidden">
                <div id="leafletMap" class="leaflet-container border border-gray-300 mb-2"></div>
                <div class="flex justify-between items-center text-xs">
                    <span class="text-gray-600">Click on map to set location</span>
                    <button type="button" onclick="closeMap()" class="bg-gray-500 hover:bg-gray-600 text-white px-2 py-1 rounded">Close</button>
                </div>
            </div>
            
            <!-- Location Display -->
            <div id="locationInfo" class="hidden mt-2 p-2 bg-green-50 border border-green-200 rounded text-xs">
                <span class="text-green-800">Location set: </span>
                <span id="locationDetails" class="text-green-600"></span>
            </div>
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

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
let leafletMap, leafletMarker, isMapInitialized = false;


// Map Functions
function initMap() {
    const defaultLocation = [30.0444, 31.2357];
    const existingLat = parseFloat(document.getElementById('office_location_lat').value);
    const existingLng = parseFloat(document.getElementById('office_location_lng').value);
    const initialLocation = (existingLat && existingLng) ? [existingLat, existingLng] : defaultLocation;

    leafletMap = L.map('leafletMap').setView(initialLocation, 15);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(leafletMap);

    leafletMarker = L.marker(initialLocation, { draggable: true }).addTo(leafletMap);

    leafletMap.on('click', function(e) {
        updateMarker(e.latlng);
    });

    leafletMarker.on('dragend', function(e) {
        updateMarker(e.target.getLatLng());
    });

    if (existingLat && existingLng) {
        updateLocationInfo({ lat: existingLat, lng: existingLng });
    }

    isMapInitialized = true;
}

function updateMarker(latlng) {
    const lat = latlng.lat, lng = latlng.lng;
    leafletMarker.setLatLng([lat, lng]);
    document.getElementById('office_location_lat').value = lat.toFixed(8);
    document.getElementById('office_location_lng').value = lng.toFixed(8);
    updateLocationInfo({ lat, lng });
    showNotification('Location updated', 'success');
}

function openMap() {
    document.getElementById('mapContainer').classList.remove('hidden');
    if (!isMapInitialized) {
        initMap();
    } else {
        setTimeout(() => leafletMap.invalidateSize(), 100);
    }
}

function closeMap() {
    document.getElementById('mapContainer').classList.add('hidden');
}

function getCurrentLocation() {
    if (!navigator.geolocation) {
        showNotification('Geolocation not supported', 'error');
        return;
    }

    showNotification('Getting location...', 'info');
    navigator.geolocation.getCurrentPosition(function(position) {
        const location = { lat: position.coords.latitude, lng: position.coords.longitude };
        document.getElementById('office_location_lat').value = location.lat.toFixed(8);
        document.getElementById('office_location_lng').value = location.lng.toFixed(8);
        updateLocationInfo(location);
        
        if (!document.getElementById('mapContainer').classList.contains('hidden') && isMapInitialized) {
            leafletMap.setView([location.lat, location.lng], 15);
            leafletMarker.setLatLng([location.lat, location.lng]);
        }
        
        showNotification('Location set successfully', 'success');
    }, function(error) {
        const messages = {
            1: 'Location access denied',
            2: 'Location unavailable',
            3: 'Location timeout'
        };
        showNotification(messages[error.code] || 'Location error', 'error');
    });
}

function clearLocation() {
    document.getElementById('office_location_lat').value = '';
    document.getElementById('office_location_lng').value = '';
    document.getElementById('locationInfo').classList.add('hidden');
    
    if (!document.getElementById('mapContainer').classList.contains('hidden') && isMapInitialized) {
        const defaultLocation = [30.0444, 31.2357];
        leafletMap.setView(defaultLocation, 10);
        leafletMarker.setLatLng(defaultLocation);
    }
    
    showNotification('Location cleared', 'info');
}

function updateLocationInfo(location) {
    document.getElementById('locationDetails').textContent = `${location.lat.toFixed(6)}, ${location.lng.toFixed(6)}`;
    document.getElementById('locationInfo').classList.remove('hidden');
}

function showNotification(message, type) {
    const colors = {
        success: 'bg-green-500',
        error: 'bg-red-500',
        info: 'bg-blue-500'
    };
    
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 ${colors[type]} text-white px-3 py-2 rounded text-sm z-50`;
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => notification.remove(), 3000);
}

// Form Events
document.addEventListener('DOMContentLoaded', function() {
    // Phone formatting
    document.getElementById('phone').addEventListener('input', function(e) {
        e.target.value = e.target.value.replace(/\D/g, '').substring(0, 11);
    });

    document.addEventListener('input', function(e) {
        if (e.target.name === 'additional_phones[]') {
            e.target.value = e.target.value.replace(/\D/g, '').substring(0, 11);
        }
    });

    // Show existing location
    const existingLat = document.getElementById('office_location_lat').value;
    const existingLng = document.getElementById('office_location_lng').value;
    if (existingLat && existingLng) {
        updateLocationInfo({ lat: parseFloat(existingLat), lng: parseFloat(existingLng) });
    }
});



// Phone number formatting
document.getElementById('phone').addEventListener('input', function(e) {
   let value = e.target.value.replace(/\D/g, '');
   // UAE: 9 digits, Saudi: 9-10 digits, Egypt: 11 digits
   if (value.length > 15) {
       value = value.substring(0, 15);
   }
   e.target.value = value;
});


</script>

@endsection