@extends('insurance-user.layouts.app')

@section('title', 'New Claim')

@push('styles')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush

@section('content')
<div class="max-w-4xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">Submit New Claim</h1>

    <form method="POST" action="{{ route('insurance.user.claims.store', $company->company_slug) }}" 
          enctype="multipart/form-data" class="space-y-6">
        @csrf

        {{-- Basic Info --}}
        <div class="bg-white rounded-lg border p-6">
            <h2 class="text-lg font-bold mb-4">Basic Information</h2>
            
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Policy Number *</label>
                    <input type="text" name="policy_number" value="{{ old('policy_number', $user->policy_number) }}" 
                           class="w-full border rounded-lg px-3 py-2 @error('policy_number') border-red-500 @enderror" required>
                    @error('policy_number')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Vehicle Working? *</label>
                    <select name="is_vehicle_working" class="w-full border rounded-lg px-3 py-2" required>
                        <option value="1" {{ old('is_vehicle_working') == '1' ? 'selected' : '' }}>Yes</option>
                        <option value="0" {{ old('is_vehicle_working') == '0' ? 'selected' : '' }}>No</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Vehicle Plate Number</label>
                    <input type="text" name="vehicle_plate_number" value="{{ old('vehicle_plate_number') }}" 
                           class="w-full border rounded-lg px-3 py-2">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Chassis Number</label>
                    <input type="text" name="chassis_number" value="{{ old('chassis_number') }}" 
                           class="w-full border rounded-lg px-3 py-2">
                </div>
            </div>

            @error('vehicle_info')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror

            <div class="mt-4">
                <label class="block text-sm font-medium mb-1">Repair Receipt Ready? *</label>
                <select name="repair_receipt_ready" class="w-full border rounded-lg px-3 py-2" required>
                    <option value="1" {{ old('repair_receipt_ready') == '1' ? 'selected' : '' }}>Ready Now</option>
                    <option value="0" {{ old('repair_receipt_ready') == '0' ? 'selected' : '' }}>Will Add Later</option>
                </select>
            </div>
        </div>

        {{-- Location --}}
        <div class="bg-white rounded-lg border p-6">
            <h2 class="text-lg font-bold mb-4">Vehicle Location</h2>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Location Description *</label>
                    <textarea name="vehicle_location" rows="2" class="w-full border rounded-lg px-3 py-2" 
                              placeholder="Describe vehicle location..." required>{{ old('vehicle_location') }}</textarea>
                </div>

                <div class="grid md:grid-cols-2 gap-4">
                    <input type="hidden" name="vehicle_location_lat" id="lat">
                    <input type="hidden" name="vehicle_location_lng" id="lng">
                    <button type="button" onclick="getLocation()" class="bg-blue-500 text-white px-4 py-2 rounded">
                        📍 Use Current Location
                    </button>
                    <button type="button" onclick="openMap()" class="bg-green-500 text-white px-4 py-2 rounded">
                        🗺️ Pick on Map
                    </button>
                </div>

                <div id="map" style="height: 250px; display: none;" class="border rounded"></div>
                <div id="location-info" class="hidden text-sm text-green-600"></div>
            </div>
        </div>

        {{-- Files --}}
        <div class="bg-white rounded-lg border p-6">
            <h2 class="text-lg font-bold mb-4">Required Documents</h2>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Policy Image (Optional)</label>
                    <input type="file" name="policy_image[]" multiple accept="image/*,.pdf" 
                           class="w-full border rounded-lg px-3 py-2">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Registration Form / Chassis Image (Required if no plate/chassis number)</label>
                    <input type="file" name="registration_form[]" multiple accept="image/*,.pdf" 
                           class="w-full border rounded-lg px-3 py-2">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Damage Report (Najm) *</label>
                    <input type="file" name="damage_report[]" multiple accept="image/*,.pdf" 
                           class="w-full border rounded-lg px-3 py-2" required>
                    @error('damage_report.*')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Estimation Report *</label>
                    <input type="file" name="estimation_report[]" multiple accept="image/*,.pdf" 
                           class="w-full border rounded-lg px-3 py-2" required>
                    @error('estimation_report.*')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div id="repair-receipt-section" style="display: none;">
                    <label class="block text-sm font-medium mb-1">Repair Receipt</label>
                    <input type="file" name="repair_receipt[]" multiple accept="image/*,.pdf" 
                           class="w-full border rounded-lg px-3 py-2">
                </div>
            </div>
        </div>

        {{-- Notes --}}
        <div class="bg-white rounded-lg border p-6">
            <h2 class="text-lg font-bold mb-4">Additional Notes</h2>
            <textarea name="notes" rows="3" class="w-full border rounded-lg px-3 py-2" 
                      placeholder="Any additional information...">{{ old('notes') }}</textarea>
        </div>

        {{-- Submit --}}
        <div class="flex gap-4">
            <button type="submit" class="bg-primary text-white px-6 py-3 rounded-lg hover:opacity-90">
                Submit Claim
            </button>
            <a href="{{ route('insurance.user.claims.index', $company->company_slug) }}" 
               class="bg-gray-500 text-white px-6 py-3 rounded-lg hover:opacity-90">
                Cancel
            </a>
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
            document.getElementById('location-info').innerHTML = `Location set: ${position.coords.latitude.toFixed(6)}, ${position.coords.longitude.toFixed(6)}`;
            document.getElementById('location-info').classList.remove('hidden');
        });
    }
}

function openMap() {
    document.getElementById('map').style.display = 'block';
    if (!map) {
        map = L.map('map').setView([30.0444, 31.2357], 10);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
        
        map.on('click', function(e) {
            if (marker) map.removeLayer(marker);
            marker = L.marker([e.latlng.lat, e.latlng.lng]).addTo(map);
            document.getElementById('lat').value = e.latlng.lat;
            document.getElementById('lng').value = e.latlng.lng;
            document.getElementById('location-info').innerHTML = `Location set: ${e.latlng.lat.toFixed(6)}, ${e.latlng.lng.toFixed(6)}`;
            document.getElementById('location-info').classList.remove('hidden');
        });
    }
    setTimeout(() => map.invalidateSize(), 100);
}

// Show/hide repair receipt section
document.querySelector('[name="repair_receipt_ready"]').addEventListener('change', function() {
    document.getElementById('repair-receipt-section').style.display = 
        this.value === '1' ? 'block' : 'none';
});
</script>
@endsection