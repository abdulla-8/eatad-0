@extends('insurance-user.layouts.app')

@section('title', t($company->translation_group . '.new_claim'))

@push('styles')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ route('insurance.user.claims.index', $company->company_slug) }}" 
           class="w-10 h-10 rounded-lg border flex items-center justify-center hover:bg-gray-50 transition-colors">
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ t($company->translation_group . '.submit_new_claim') }}</h1>
            <p class="text-gray-600">{{ t($company->translation_group . '.fill_claim_details') }}</p>
        </div>
    </div>

    <form method="POST" action="{{ route('insurance.user.claims.store', $company->company_slug) }}" 
          enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Basic Information -->
        <div class="bg-white rounded-xl shadow-sm border">
            <div class="p-6 border-b">
                <h2 class="text-lg font-bold flex items-center gap-2">
                    <svg class="w-5 h-5" style="color: {{ $company->primary_color }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    {{ t($company->translation_group . '.basic_information') }}
                </h2>
                <p class="text-gray-600 text-sm mt-1">{{ t($company->translation_group . '.basic_info_description') }}</p>
            </div>
            
            <div class="p-6 space-y-6">
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ t($company->translation_group . '.policy_number') }} *</label>
                        <input type="text" name="policy_number" value="{{ old('policy_number', $user->policy_number) }}" 
                               class="w-full border-gray-300 rounded-lg focus:ring-2 focus:border-transparent px-4 py-2.5 @error('policy_number') border-red-500 @enderror" 
                               style="focus:ring-color: {{ $company->primary_color }};" required>
                        @error('policy_number')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ t($company->translation_group . '.vehicle_working') }} *</label>
                        <select name="is_vehicle_working" class="w-full border-gray-300 rounded-lg focus:ring-2 focus:border-transparent px-4 py-2.5"
                                style="focus:ring-color: {{ $company->primary_color }};" required>
                            <option value="1" {{ old('is_vehicle_working') == '1' ? 'selected' : '' }}>{{ t($company->translation_group . '.yes') }}</option>
                            <option value="0" {{ old('is_vehicle_working') == '0' ? 'selected' : '' }}>{{ t($company->translation_group . '.no') }}</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ t($company->translation_group . '.vehicle_plate_number') }}</label>
                        <input type="text" name="vehicle_plate_number" value="{{ old('vehicle_plate_number') }}" 
                               class="w-full border-gray-300 rounded-lg focus:ring-2 focus:border-transparent px-4 py-2.5"
                               style="focus:ring-color: {{ $company->primary_color }};"
                               placeholder="{{ t($company->translation_group . '.enter_plate_number') }}">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ t($company->translation_group . '.chassis_number') }}</label>
                        <input type="text" name="chassis_number" value="{{ old('chassis_number') }}" 
                               class="w-full border-gray-300 rounded-lg focus:ring-2 focus:border-transparent px-4 py-2.5"
                               style="focus:ring-color: {{ $company->primary_color }};"
                               placeholder="{{ t($company->translation_group . '.enter_chassis_number') }}">
                    </div>
                </div>

                @error('vehicle_info')
                    <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                        <p class="text-red-700 text-sm">{{ $message }}</p>
                    </div>
                @enderror

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ t($company->translation_group . '.repair_receipt_ready') }} *</label>
                    <select name="repair_receipt_ready" class="w-full border-gray-300 rounded-lg focus:ring-2 focus:border-transparent px-4 py-2.5"
                            style="focus:ring-color: {{ $company->primary_color }};" required>
                        <option value="1" {{ old('repair_receipt_ready') == '1' ? 'selected' : '' }}>{{ t($company->translation_group . '.ready_now') }}</option>
                        <option value="0" {{ old('repair_receipt_ready') == '0' ? 'selected' : '' }}>{{ t($company->translation_group . '.will_add_later') }}</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Vehicle Location -->
        <div class="bg-white rounded-xl shadow-sm border">
            <div class="p-6 border-b">
                <h2 class="text-lg font-bold flex items-center gap-2">
                    <svg class="w-5 h-5" style="color: {{ $company->primary_color }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    {{ t($company->translation_group . '.vehicle_location') }}
                </h2>
                <p class="text-gray-600 text-sm mt-1">{{ t($company->translation_group . '.location_description') }}</p>
            </div>
            
            <div class="p-6 space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ t($company->translation_group . '.location_description') }} *</label>
                    <textarea name="vehicle_location" rows="3" 
                              class="w-full border-gray-300 rounded-lg focus:ring-2 focus:border-transparent px-4 py-2.5" 
                              style="focus:ring-color: {{ $company->primary_color }};"
                              placeholder="{{ t($company->translation_group . '.describe_vehicle_location') }}" required>{{ old('vehicle_location') }}</textarea>
                </div>

                <div class="grid md:grid-cols-2 gap-4">
                    <input type="hidden" name="vehicle_location_lat" id="lat">
                    <input type="hidden" name="vehicle_location_lng" id="lng">
                    
                    <button type="button" onclick="getLocation()" 
                            class="flex items-center justify-center gap-2 px-4 py-3 bg-blue-500 text-white rounded-lg font-medium hover:bg-blue-600 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        {{ t($company->translation_group . '.use_current_location') }}
                    </button>
                    
                    <button type="button" onclick="openMap()" 
                            class="flex items-center justify-center gap-2 px-4 py-3 bg-green-500 text-white rounded-lg font-medium hover:bg-green-600 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                        </svg>
                        {{ t($company->translation_group . '.pick_on_map') }}
                    </button>
                </div>

                <div id="map" style="height: 300px; display: none;" class="border rounded-lg"></div>
                <div id="location-info" class="hidden bg-green-50 border border-green-200 rounded-lg p-3">
                    <p class="text-green-700 text-sm"></p>
                </div>
            </div>
        </div>

        <!-- Required Documents -->
        <div class="bg-white rounded-xl shadow-sm border">
            <div class="p-6 border-b">
                <h2 class="text-lg font-bold flex items-center gap-2">
                    <svg class="w-5 h-5" style="color: {{ $company->primary_color }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                    </svg>
                    {{ t($company->translation_group . '.required_documents') }}
                </h2>
                <p class="text-gray-600 text-sm mt-1">{{ t($company->translation_group . '.upload_required_files') }}</p>
            </div>
            
            <div class="p-6 space-y-6">
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            {{ t($company->translation_group . '.policy_image') }} 
                            <span class="text-gray-500">({{ t($company->translation_group . '.optional') }})</span>
                        </label>
                        <input type="file" name="policy_image[]" multiple accept="image/*,.pdf" 
                               class="w-full border-gray-300 rounded-lg focus:ring-2 focus:border-transparent px-4 py-2.5"
                               style="focus:ring-color: {{ $company->primary_color }};">
                        <p class="text-xs text-gray-500 mt-1">{{ t($company->translation_group . '.file_types_supported') }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            {{ t($company->translation_group . '.registration_form') }}
                            <span class="text-yellow-600 text-xs">({{ t($company->translation_group . '.conditional') }})</span>
                        </label>
                        <input type="file" name="registration_form[]" multiple accept="image/*,.pdf" 
                               class="w-full border-gray-300 rounded-lg focus:ring-2 focus:border-transparent px-4 py-2.5"
                               style="focus:ring-color: {{ $company->primary_color }};">
                        <p class="text-xs text-gray-500 mt-1">{{ t($company->translation_group . '.required_if_no_plate_chassis') }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            {{ t($company->translation_group . '.damage_report') }} 
                            <span class="text-red-600">*</span>
                        </label>
                        <input type="file" name="damage_report[]" multiple accept="image/*,.pdf" 
                               class="w-full border-gray-300 rounded-lg focus:ring-2 focus:border-transparent px-4 py-2.5 @error('damage_report.*') border-red-500 @enderror"
                               style="focus:ring-color: {{ $company->primary_color }};" required>
                        <p class="text-xs text-gray-500 mt-1">{{ t($company->translation_group . '.najm_report_required') }}</p>
                        @error('damage_report.*')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            {{ t($company->translation_group . '.estimation_report') }} 
                            <span class="text-red-600">*</span>
                        </label>
                        <input type="file" name="estimation_report[]" multiple accept="image/*,.pdf" 
                               class="w-full border-gray-300 rounded-lg focus:ring-2 focus:border-transparent px-4 py-2.5 @error('estimation_report.*') border-red-500 @enderror"
                               style="focus:ring-color: {{ $company->primary_color }};" required>
                        <p class="text-xs text-gray-500 mt-1">{{ t($company->translation_group . '.cost_estimation_required') }}</p>
                        @error('estimation_report.*')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div id="repair-receipt-section" style="display: none;">
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ t($company->translation_group . '.repair_receipt') }}</label>
                    <input type="file" name="repair_receipt[]" multiple accept="image/*,.pdf" 
                           class="w-full border-gray-300 rounded-lg focus:ring-2 focus:border-transparent px-4 py-2.5"
                           style="focus:ring-color: {{ $company->primary_color }};">
                    <p class="text-xs text-gray-500 mt-1">{{ t($company->translation_group . '.repair_receipt_description') }}</p>
                </div>

                <!-- File Upload Info -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <h4 class="font-medium text-blue-800 mb-1">{{ t($company->translation_group . '.file_upload_tips') }}</h4>
                            <ul class="text-sm text-blue-700 space-y-1">
                                <li>• {{ t($company->translation_group . '.max_file_size_5mb') }}</li>
                                <li>• {{ t($company->translation_group . '.supported_formats') }}: JPEG, PNG, PDF</li>
                                <li>• {{ t($company->translation_group . '.multiple_files_allowed') }}</li>
                                <li>• {{ t($company->translation_group . '.clear_readable_images') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Notes -->
        <div class="bg-white rounded-xl shadow-sm border">
            <div class="p-6 border-b">
                <h2 class="text-lg font-bold flex items-center gap-2">
                    <svg class="w-5 h-5" style="color: {{ $company->primary_color }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    {{ t($company->translation_group . '.additional_notes') }}
                </h2>
                <p class="text-gray-600 text-sm mt-1">{{ t($company->translation_group . '.notes_optional_description') }}</p>
            </div>
            <div class="p-6">
                <textarea name="notes" rows="4" 
                          class="w-full border-gray-300 rounded-lg focus:ring-2 focus:border-transparent px-4 py-2.5"
                          style="focus:ring-color: {{ $company->primary_color }};"
                          placeholder="{{ t($company->translation_group . '.any_additional_information') }}">{{ old('notes') }}</textarea>
            </div>
        </div>

        <!-- Summary & Submit Actions -->
        <div class="bg-white rounded-xl shadow-sm border">
            <div class="p-6 border-b">
                <h2 class="text-lg font-bold flex items-center gap-2">
                    <svg class="w-5 h-5" style="color: {{ $company->primary_color }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ t($company->translation_group . '.review_submit') }}
                </h2>
                <p class="text-gray-600 text-sm mt-1">{{ t($company->translation_group . '.review_before_submit') }}</p>
            </div>
            <div class="p-6 space-y-4">
                <!-- Important Notes -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-yellow-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.94-.833-2.664 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        <div>
                            <h4 class="font-medium text-yellow-800 mb-2">{{ t($company->translation_group . '.important_notes') }}</h4>
                            <ul class="text-sm text-yellow-700 space-y-1">
                                <li>• {{ t($company->translation_group . '.ensure_all_info_correct') }}</li>
                                <li>• {{ t($company->translation_group . '.required_files_uploaded') }}</li>
                                <li>• {{ t($company->translation_group . '.claim_review_time') }}</li>
                                <li>• {{ t($company->translation_group . '.notification_updates') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 pt-4">
                    <button type="submit" 
                            class="flex-1 flex items-center justify-center gap-2 px-6 py-3 text-white rounded-lg font-medium hover:opacity-90 transition-opacity"
                            style="background: {{ $company->primary_color }};">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                        {{ t($company->translation_group . '.submit_claim') }}
                    </button>
                    <a href="{{ route('insurance.user.claims.index', $company->company_slug) }}" 
                       class="flex-1 flex items-center justify-center gap-2 px-6 py-3 bg-gray-500 text-white rounded-lg font-medium hover:opacity-90 transition-opacity">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        {{ t($company->translation_group . '.cancel') }}
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
let map, marker;

function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            document.getElementById('lat').value = position.coords.latitude;
            document.getElementById('lng').value = position.coords.longitude;
            
            const locationInfo = document.getElementById('location-info');
            locationInfo.innerHTML = `<p class="text-green-700 text-sm">${'{{ t($company->translation_group . ".location_set") }}'}: ${position.coords.latitude.toFixed(6)}, ${position.coords.longitude.toFixed(6)}</p>`;
            locationInfo.classList.remove('hidden');
        }, function(error) {
            alert('{{ t($company->translation_group . ".location_error") }}');
        });
    } else {
        alert('{{ t($company->translation_group . ".geolocation_not_supported") }}');
    }
}

function openMap() {
    document.getElementById('map').style.display = 'block';
    if (!map) {
        map = L.map('map').setView([30.0444, 31.2357], 10);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);
        
        map.on('click', function(e) {
            if (marker) map.removeLayer(marker);
            marker = L.marker([e.latlng.lat, e.latlng.lng]).addTo(map);
            document.getElementById('lat').value = e.latlng.lat;
            document.getElementById('lng').value = e.latlng.lng;
            
            const locationInfo = document.getElementById('location-info');
            locationInfo.innerHTML = `<p class="text-green-700 text-sm">${'{{ t($company->translation_group . ".location_set") }}'}: ${e.latlng.lat.toFixed(6)}, ${e.latlng.lng.toFixed(6)}</p>`;
            locationInfo.classList.remove('hidden');
        });
    }
    setTimeout(() => map.invalidateSize(), 100);
}

// Show/hide repair receipt section
document.querySelector('[name="repair_receipt_ready"]').addEventListener('change', function() {
    document.getElementById('repair-receipt-section').style.display = 
        this.value === '1' ? 'block' : 'none';
});

// Trigger initial state
document.querySelector('[name="repair_receipt_ready"]').dispatchEvent(new Event('change'));

// Form validation before submit
document.querySelector('form').addEventListener('submit', function(e) {
    const plateNumber = document.querySelector('[name="vehicle_plate_number"]').value;
    const chassisNumber = document.querySelector('[name="chassis_number"]').value;
    
    if (!plateNumber && !chassisNumber) {
        e.preventDefault();
        alert('{{ t($company->translation_group . ".vehicle_info_required") }}');
        return false;
    }
});
</script>
@endsection