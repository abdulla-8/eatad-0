@extends('insurance-user.layouts.app')

@section('title', 'Edit Claim')

@push('styles')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush

@section('content')
<div class="max-w-4xl mx-auto">
    <h1 class="text-2xl font-bold mb-4">Edit Claim {{ $claim->claim_number }}</h1>
    
    <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
        <h3 class="font-bold text-red-800">Rejection Reason:</h3>
        <p class="text-red-700">{{ $claim->rejection_reason }}</p>
    </div>

    <form method="POST" action="{{ route('insurance.user.claims.update', [$company->company_slug, $claim->id]) }}" 
          enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Basic Info --}}
        <div class="bg-white rounded-lg border p-6">
            <h2 class="text-lg font-bold mb-4">Basic Information</h2>
            
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Policy Number *</label>
                    <input type="text" name="policy_number" value="{{ old('policy_number', $claim->policy_number) }}" 
                           class="w-full border rounded-lg px-3 py-2 @error('policy_number') border-red-500 @enderror" required>
                    @error('policy_number')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Vehicle Working? *</label>
                    <select name="is_vehicle_working" class="w-full border rounded-lg px-3 py-2" required>
                        <option value="1" {{ old('is_vehicle_working', $claim->is_vehicle_working) == '1' ? 'selected' : '' }}>Yes</option>
                        <option value="0" {{ old('is_vehicle_working', $claim->is_vehicle_working) == '0' ? 'selected' : '' }}>No</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Vehicle Plate Number</label>
                    <input type="text" name="vehicle_plate_number" value="{{ old('vehicle_plate_number', $claim->vehicle_plate_number) }}" 
                           class="w-full border rounded-lg px-3 py-2">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Chassis Number</label>
                    <input type="text" name="chassis_number" value="{{ old('chassis_number', $claim->chassis_number) }}" 
                           class="w-full border rounded-lg px-3 py-2">
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-sm font-medium mb-1">Repair Receipt Ready? *</label>
                <select name="repair_receipt_ready" class="w-full border rounded-lg px-3 py-2" required>
                    <option value="1" {{ old('repair_receipt_ready', $claim->repair_receipt_ready) == '1' ? 'selected' : '' }}>Ready Now</option>
                    <option value="0" {{ old('repair_receipt_ready', $claim->repair_receipt_ready) == '0' ? 'selected' : '' }}>Will Add Later</option>
                </select>
            </div>
        </div>

        {{-- Location --}}
        <div class="bg-white rounded-lg border p-6">
            <h2 class="text-lg font-bold mb-4">Vehicle Location</h2>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Location Description *</label>
                    <textarea name="vehicle_location" rows="2" class="w-full border rounded-lg px-3 py-2" required>{{ old('vehicle_location', $claim->vehicle_location) }}</textarea>
                </div>

                <div class="grid md:grid-cols-2 gap-4">
                    <input type="hidden" name="vehicle_location_lat" id="lat" value="{{ $claim->vehicle_location_lat }}">
                    <input type="hidden" name="vehicle_location_lng" id="lng" value="{{ $claim->vehicle_location_lng }}">
                    <button type="button" onclick="getLocation()" class="bg-blue-500 text-white px-4 py-2 rounded">
                        📍 Use Current Location
                    </button>
                    <button type="button" onclick="openMap()" class="bg-green-500 text-white px-4 py-2 rounded">
                        🗺️ Pick on Map
                    </button>
                </div>

                <div id="map" style="height: 250px; display: none;" class="border rounded"></div>
                @if($claim->vehicle_location_lat)
                    <div class="text-sm text-green-600">
                        Current location: {{ $claim->vehicle_location_lat }}, {{ $claim->vehicle_location_lng }}
                    </div>
                @endif
            </div>
        </div>

        {{-- Existing Files --}}
        @if($claim->attachments->count())
        <div class="bg-white rounded-lg border p-6">
            <h2 class="text-lg font-bold mb-4">Current Attachments</h2>
            <div class="grid md:grid-cols-2 gap-4">
                @foreach($claim->attachments->groupBy('type') as $type => $attachments)
                    <div class="border rounded p-3">
                        <h3 class="font-medium text-sm mb-2">{{ $attachments->first()->type_display_name }}</h3>
                        @foreach($attachments as $attachment)
                            <div class="flex items-center justify-between text-xs mb-1">
                                <a href="{{ $attachment->file_url }}" target="_blank" class="text-blue-600 hover:underline">
                                    {{ Str::limit($attachment->file_name, 20) }}
                                </a>
                                <form method="POST" action="{{ route('insurance.user.claims.attachments.delete', [$company->company_slug, $claim->id, $attachment->id]) }}" 
                                      class="inline" onsubmit="return confirm('Delete this file?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">🗑️</button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Add New Files --}}
        <div class="bg-white rounded-lg border p-6">
            <h2 class="text-lg font-bold mb-4">Add New Documents</h2>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Policy Image</label>
                    <input type="file" name="policy_image[]" multiple accept="image/*,.pdf" 
                           class="w-full border rounded-lg px-3 py-2">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Registration Form / Chassis Image</label>
                    <input type="file" name="registration_form[]" multiple accept="image/*,.pdf" 
                           class="w-full border rounded-lg px-3 py-2">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Damage Report (Najm)</label>
                    <input type="file" name="damage_report[]" multiple accept="image/*,.pdf" 
                           class="w-full border rounded-lg px-3 py-2">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Estimation Report</label>
                    <input type="file" name="estimation_report[]" multiple accept="image/*,.pdf" 
                           class="w-full border rounded-lg px-3 py-2">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Repair Receipt</label>
                    <input type="file" name="repair_receipt[]" multiple accept="image/*,.pdf" 
                           class="w-full border rounded-lg px-3 py-2">
                </div>
            </div>
        </div>

        {{-- Notes --}}
        <div class="bg-white rounded-lg border p-6">
            <h2 class="text-lg font-bold mb-4">Additional Notes</h2>
            <textarea name="notes" rows="3" class="w-full border rounded-lg px-3 py-2">{{ old('notes', $claim->notes) }}</textarea>
        </div>

        {{-- Submit --}}
        <div class="flex gap-4">
            <button type="submit" class="bg-primary text-white px-6 py-3 rounded-lg hover:opacity-90">
                Update & Resubmit Claim
            </button>
            <a href="{{ route('insurance.user.claims.show', [$company->company_slug, $claim->id]) }}" 
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
        });
    }
}

function openMap() {
    document.getElementById('map').style.display = 'block';
    if (!map) {
        const lat = document.getElementById('lat').value || 30.0444;
        const lng = document.getElementById('lng').value || 31.2357;
        
        map = L.map('map').setView([lat, lng], 10);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
        
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
</script>
@endsection