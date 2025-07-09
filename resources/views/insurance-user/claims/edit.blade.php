@extends('insurance-user.layouts.app')

@section('title', t($company->translation_group . '.edit_claim'))

@push('styles')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ route('insurance.user.claims.show', [$company->company_slug, $claim->id]) }}" 
           class="w-10 h-10 rounded-lg border flex items-center justify-center hover:bg-gray-50 transition-colors">
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ t($company->translation_group . '.edit_claim') }} {{ $claim->claim_number }}</h1>
            <p class="text-gray-600">{{ t($company->translation_group . '.update_claim_details') }}</p>
        </div>
    </div>
    
    <!-- Rejection Reason Alert -->
    <div class="bg-red-50 border border-red-200 rounded-xl p-6">
        <div class="flex items-start gap-3">
            <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </div>
            <div>
                <h3 class="font-bold text-red-800 mb-2">{{ t($company->translation_group . '.rejection_reason') }}</h3>
                <p class="text-red-700">{{ $claim->rejection_reason }}</p>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('insurance.user.claims.update', [$company->company_slug, $claim->id]) }}" 
          enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Basic Information -->
        <div class="bg-white rounded-xl shadow-sm border">
            <div class="p-6 border-b">
                <h2 class="text-lg font-bold flex items-center gap-2">
                    <svg class="w-5 h-5" style="color: {{ $company->primary_color }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    {{ t($company->translation_group . '.basic_information') }}
                </h2>
            </div>
            
            <div class="p-6 space-y-6">
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ t($company->translation_group . '.policy_number') }} *</label>
                        <input type="text" name="policy_number" value="{{ old('policy_number', $claim->policy_number) }}" 
                               class="w-full border-gray-300 rounded-lg focus:ring-2 focus:border-transparent px-4 py-2.5 @error('policy_number') border-red-500 @enderror" 
                               style="focus:ring-color: {{ $company->primary_color }};" required>
                        @error('policy_number')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ t($company->translation_group . '.vehicle_working') }} *</label>
                        <select name="is_vehicle_working" id="is_vehicle_working" class="w-full border-gray-300 rounded-lg focus:ring-2 focus:border-transparent px-4 py-2.5"
                                style="focus:ring-color: {{ $company->primary_color }};" required>
                            <option value="1" {{ old('is_vehicle_working', $claim->is_vehicle_working) == '1' ? 'selected' : '' }}>{{ t($company->translation_group . '.yes') }}</option>
                            <option value="0" {{ old('is_vehicle_working', $claim->is_vehicle_working) == '0' ? 'selected' : '' }}>{{ t($company->translation_group . '.no') }}</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ t($company->translation_group . '.vehicle_plate_number') }}</label>
                        <input type="text" name="vehicle_plate_number" value="{{ old('vehicle_plate_number', $claim->vehicle_plate_number) }}" 
                               class="w-full border-gray-300 rounded-lg focus:ring-2 focus:border-transparent px-4 py-2.5"
                               style="focus:ring-color: {{ $company->primary_color }};">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ t($company->translation_group . '.chassis_number') }}</label>
                        <input type="text" name="chassis_number" value="{{ old('chassis_number', $claim->chassis_number) }}" 
                               class="w-full border-gray-300 rounded-lg focus:ring-2 focus:border-transparent px-4 py-2.5"
                               style="focus:ring-color: {{ $company->primary_color }};">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ t($company->translation_group . '.vehicle_brand') }} *</label>
                        <input type="text" name="vehicle_brand" value="{{ old('vehicle_brand', $claim->vehicle_brand) }}" 
                               class="w-full border-gray-300 rounded-lg focus:ring-2 focus:border-transparent px-4 py-2.5 @error('vehicle_brand') border-red-500 @enderror" 
                               style="focus:ring-color: {{ $company->primary_color }};" required>
                        @error('vehicle_brand')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ t($company->translation_group . '.vehicle_type') }} *</label>
                        <input type="text" name="vehicle_type" value="{{ old('vehicle_type', $claim->vehicle_type) }}" 
                               class="w-full border-gray-300 rounded-lg focus:ring-2 focus:border-transparent px-4 py-2.5 @error('vehicle_type') border-red-500 @enderror" 
                               style="focus:ring-color: {{ $company->primary_color }};" required>
                        @error('vehicle_type')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ t($company->translation_group . '.vehicle_model') }} *</label>
                        <input type="text" name="vehicle_model" value="{{ old('vehicle_model', $claim->vehicle_model) }}" 
                               class="w-full border-gray-300 rounded-lg focus:ring-2 focus:border-transparent px-4 py-2.5 @error('vehicle_model') border-red-500 @enderror" 
                               style="focus:ring-color: {{ $company->primary_color }};" required>
                        @error('vehicle_model')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ t($company->translation_group . '.repair_receipt_ready') }} *</label>
                    <select name="repair_receipt_ready" class="w-full border-gray-300 rounded-lg focus:ring-2 focus:border-transparent px-4 py-2.5"
                            style="focus:ring-color: {{ $company->primary_color }};" required>
                        <option value="1" {{ old('repair_receipt_ready', $claim->repair_receipt_ready) == '1' ? 'selected' : '' }}>{{ t($company->translation_group . '.ready_now') }}</option>
                        <option value="0" {{ old('repair_receipt_ready', $claim->repair_receipt_ready) == '0' ? 'selected' : '' }}>{{ t($company->translation_group . '.will_add_later') }}</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Vehicle Location -->

        <div class="bg-white rounded-xl shadow-sm border" id="vehicle-location-section">
            <div class="p-6 border-b">
                <h2 class="text-lg font-bold flex items-center gap-2">
                    <svg class="w-5 h-5" style="color: {{ $company->primary_color }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    {{ t($company->translation_group . '.vehicle_location') }}
                </h2>
            </div>
            
            <div class="p-6 space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ t($company->translation_group . '.location_description') }} *</label>
                    <textarea name="vehicle_location" rows="3" 
                              class="w-full border-gray-300 rounded-lg focus:ring-2 focus:border-transparent px-4 py-2.5" 
                              style="focus:ring-color: {{ $company->primary_color }};" required>{{ old('vehicle_location', $claim->vehicle_location) }}</textarea>
                </div>

                <div class="grid md:grid-cols-2 gap-4">
                    <input type="hidden" name="vehicle_location_lat" id="lat" value="{{ $claim->vehicle_location_lat }}">
                    <input type="hidden" name="vehicle_location_lng" id="lng" value="{{ $claim->vehicle_location_lng }}">
                    
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
                @if($claim->vehicle_location_lat)
                    <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                        <p class="text-green-700 text-sm">{{ t($company->translation_group . '.current_location') }}: {{ $claim->vehicle_location_lat }}, {{ $claim->vehicle_location_lng }}</p>
                    </div>
                @endif
            </div>
        </div>
        

        <!-- Current Attachments -->
        @if($claim->attachments->count())
        <div class="bg-white rounded-xl shadow-sm border">
            <div class="p-6 border-b">
                <h2 class="text-lg font-bold flex items-center gap-2">
                    <svg class="w-5 h-5" style="color: {{ $company->primary_color }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    {{ t($company->translation_group . '.current_attachments') }}
                </h2>
                <p class="text-gray-600 text-sm mt-1">{{ t($company->translation_group . '.manage_existing_files') }}</p>
            </div>
            <div class="p-6">
                <div class="grid md:grid-cols-2 gap-4">
                    @foreach($claim->attachments->groupBy('type') as $type => $attachments)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h3 class="font-medium text-sm mb-3">{{ t($company->translation_group . '.' . $type) }}</h3>
                            @foreach($attachments as $attachment)
                                <div class="flex items-center justify-between text-xs mb-2 p-2 bg-gray-50 rounded">
                                    <div class="flex items-center gap-2 flex-1 min-w-0">
                                        <svg class="w-4 h-4 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                        </svg>
                                        <a href="{{ $attachment->file_url }}" target="_blank" 
                                           class="text-blue-600 hover:underline truncate flex-1"
                                           style="color: {{ $company->primary_color }};">
                                            {{ Str::limit($attachment->file_name, 25) }}
                                        </a>
                                    </div>
                                    <form method="POST" action="{{ route('insurance.user.claims.attachments.delete', [$company->company_slug, $claim->id, $attachment->id]) }}" 
                                          class="inline ml-2" onsubmit="return confirm('{{ t($company->translation_group . '.confirm_delete_file') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 p-1 rounded hover:bg-red-50">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Add New Documents -->
        <div class="bg-white rounded-xl shadow-sm border">
            <div class="p-6 border-b">
                <h2 class="text-lg font-bold flex items-center gap-2">
                    <svg class="w-5 h-5" style="color: {{ $company->primary_color }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    {{ t($company->translation_group . '.add_new_documents') }}
                </h2>
                <p class="text-gray-600 text-sm mt-1">{{ t($company->translation_group . '.upload_additional_files') }}</p>
            </div>
            
            <div class="p-6 space-y-6">
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ t($company->translation_group . '.policy_image') }}</label>
                        <input type="file" name="policy_image[]" multiple accept="image/*,.pdf" 
                               class="w-full border-gray-300 rounded-lg focus:ring-2 focus:border-transparent px-4 py-2.5"
                               style="focus:ring-color: {{ $company->primary_color }};">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ t($company->translation_group . '.registration_form') }}</label>
                        <input type="file" name="registration_form[]" multiple accept="image/*,.pdf" 
                               class="w-full border-gray-300 rounded-lg focus:ring-2 focus:border-transparent px-4 py-2.5"
                               style="focus:ring-color: {{ $company->primary_color }};">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ t($company->translation_group . '.damage_report') }}</label>
                        <input type="file" name="damage_report[]" multiple accept="image/*,.pdf" 
                               class="w-full border-gray-300 rounded-lg focus:ring-2 focus:border-transparent px-4 py-2.5"
                               style="focus:ring-color: {{ $company->primary_color }};">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ t($company->translation_group . '.estimation_report') }}</label>
                        <input type="file" name="estimation_report[]" multiple accept="image/*,.pdf" 
                               class="w-full border-gray-300 rounded-lg focus:ring-2 focus:border-transparent px-4 py-2.5"
                               style="focus:ring-color: {{ $company->primary_color }};">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ t($company->translation_group . '.repair_receipt') }}</label>
                    <input type="file" name="repair_receipt[]" multiple accept="image/*,.pdf" 
                           class="w-full border-gray-300 rounded-lg focus:ring-2 focus:border-transparent px-4 py-2.5"
                           style="focus:ring-color: {{ $company->primary_color }};">
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
            </div>
            <div class="p-6">
                <textarea name="notes" rows="4" 
                          class="w-full border-gray-300 rounded-lg focus:ring-2 focus:border-transparent px-4 py-2.5"
                          style="focus:ring-color: {{ $company->primary_color }};">{{ old('notes', $claim->notes) }}</textarea>
            </div>
        </div>

        <!-- Submit Actions -->
        <div class="bg-white rounded-xl shadow-sm border">
            <div class="p-6">
                <div class="flex flex-col sm:flex-row gap-4">
                    <button type="submit" 
                            class="flex-1 px-6 py-3 text-white rounded-lg font-medium hover:opacity-90 transition-opacity"
                            style="background: {{ $company->primary_color }};">
                        {{ t($company->translation_group . '.update_resubmit_claim') }}
                    </button>
                    <a href="{{ route('insurance.user.claims.show', [$company->company_slug, $claim->id]) }}" 
                       class="flex-1 px-6 py-3 bg-gray-500 text-white rounded-lg font-medium hover:opacity-90 transition-opacity text-center">
                        {{ t($company->translation_group . '.cancel') }}
                    </a>
                </div>
            </div>
        </div>

        @if($errors->any())
    <div class="bg-red-100 text-red-700 p-2 my-2 rounded">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if(session('error'))
    <div class="bg-red-100 text-red-700 p-2 my-2 rounded">
        {{ session('error') }}
    </div>
@endif

    </form>
</div>

<script>
let map, marker;

function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            document.getElementById('lat').value = position.coords.latitude;
            document.getElementById('lng').value = position.coords.longitude;
        }, function(error) {
            alert('{{ t($company->translation_group . '.location_error') }}');
        });
    } else {
        alert('{{ t($company->translation_group . '.geolocation_not_supported') }}');
    }
}

function openMap() {
    document.getElementById('map').style.display = 'block';
    if (!map) {
        const lat = document.getElementById('lat').value || 30.0444;
        const lng = document.getElementById('lng').value || 31.2357;
        
        map = L.map('map').setView([lat, lng], 10);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);
        
        if (document.getElementById('lat').value) {
            marker = L.marker([lat, lng]).addTo(map);
        }
        
        map.on('click', function(e) {
            if (marker) map.removeLayer(marker);
            marker = L.marker([e.latlng.lat, e.latlng.lng]).addTo(map);
            document.getElementById('lat').value = e.latlng.lat;
            document.getElementById('lng').value = e.latlng.lng;
        });
    }
    setTimeout(() => map.invalidateSize(), 100);
}

// --- إظهار/إخفاء قسم اللوكيشن حسب حالة السيارة ---
function toggleLocationSection() {
    var working = document.getElementById('is_vehicle_working').value;
    var locationSection = document.getElementById('vehicle-location-section');
    if (working === '1') {
        locationSection.style.display = '';
    } else {
        locationSection.style.display = 'none';
        document.querySelector('[name="vehicle_location"]').value = '';
        document.getElementById('lat').value = '';
        document.getElementById('lng').value = '';
    }
}
document.getElementById('is_vehicle_working').addEventListener('change', toggleLocationSection);
window.addEventListener('DOMContentLoaded', toggleLocationSection);
</script>
@endsection
