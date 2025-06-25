@extends('admin.layouts.app')

@section('title', isset($insuranceCompany) ? t('admin.edit_insurance_company') : t('admin.add_insurance_company'))

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

<div class="flex justify-between items-center mb-4">
    <div>
        <h1 class="text-xl font-bold text-gray-900">
            {{ isset($insuranceCompany) ? t('admin.edit_insurance_company') : t('admin.add_insurance_company') }}
        </h1>
        <nav class="text-xs text-gray-600">
            <a href="{{ route('admin.dashboard') }}">{{ t('admin.dashboard') }}</a> > 
            <a href="{{ route('admin.users.insurance-companies.index') }}">{{ t('admin.insurance_companies') }}</a> > 
            <span class="text-gold-600">{{ isset($insuranceCompany) ? t('admin.edit') : t('admin.add_new') }}</span>
        </nav>
    </div>
    <a href="{{ route('admin.users.insurance-companies.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-2 rounded text-sm">
        ← {{ t('admin.back_to_list') }}
    </a>
</div>

<div class="bg-white rounded-lg shadow-sm border compact-form">
    <div class="bg-gold-500 text-dark-900 px-4 py-3 rounded-t-lg">
        <h2 class="font-semibold">{{ t('admin.company_details') }}</h2>
    </div>
    
    <form method="POST" 
          action="{{ isset($insuranceCompany) ? route('admin.users.insurance-companies.update', $insuranceCompany) : route('admin.users.insurance-companies.store') }}" 
          class="p-4" id="insuranceCompanyForm" enctype="multipart/form-data">
        @csrf
        @if(isset($insuranceCompany)) @method('PUT') @endif
        
        <!-- Basic Info -->
        <h3 class="section-title font-semibold text-gray-900">{{ t('admin.basic_information') }}</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4">
            <!-- Legal Name -->
            <div class="md:col-span-2">
                <label for="legal_name" class="block font-medium text-gray-700 mb-1">
                    {{ t('admin.legal_name') }} <span class="text-red-500">*</span>
                </label>
                <input type="text" id="legal_name" name="legal_name" 
                       value="{{ old('legal_name', $insuranceCompany->legal_name ?? '') }}"
                       class="w-full border border-gray-300 rounded focus:ring-2 focus:ring-gold-500 @error('legal_name') border-red-500 @enderror"
                       placeholder="{{ t('admin.company_legal_name_placeholder') }}" required>
                @error('legal_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Company Slug -->
            <div>
                <label for="company_slug" class="block font-medium text-gray-700 mb-1">
                    Company Route <span class="text-red-500">*</span>
                </label>
                <input type="text" id="company_slug" name="company_slug" 
                       value="{{ old('company_slug', $insuranceCompany->company_slug ?? '') }}"
                       class="w-full border border-gray-300 rounded focus:ring-2 focus:ring-gold-500 @error('company_slug') border-red-500 @enderror"
                       placeholder="misr" required>
                <p class="text-xs text-gray-500 mt-1">www.example.com/<strong id="slugPreview">{{ old('company_slug', $insuranceCompany->company_slug ?? 'company') }}</strong>/login</p>
                @error('company_slug')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            
            <!-- Phone -->
            <div>
                <label for="phone" class="block font-medium text-gray-700 mb-1">
                    {{ t('admin.primary_phone') }} <span class="text-red-500">*</span>
                </label>
                <input type="text" id="phone" name="phone" 
                       value="{{ old('phone', $insuranceCompany->phone ?? '') }}"
                       class="w-full border border-gray-300 rounded focus:ring-2 focus:ring-gold-500 @error('phone') border-red-500 @enderror"
                       placeholder="01234567890" required>
                @error('phone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            
            <!-- Password -->
            <div>
                <label for="password" class="block font-medium text-gray-700 mb-1">
                    {{ t('admin.password') }} @if(!isset($insuranceCompany))<span class="text-red-500">*</span>@endif
                </label>
                <input type="password" id="password" name="password" 
                       class="w-full border border-gray-300 rounded focus:ring-2 focus:ring-gold-500 @error('password') border-red-500 @enderror"
                       placeholder="••••••••" {{ !isset($insuranceCompany) ? 'required' : '' }}>
                @if(isset($insuranceCompany))<p class="text-xs text-gray-500 mt-1">{{ t('admin.leave_empty_keep_current') }}</p>@endif
                @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            
            <!-- Commercial Register -->
            <div>
                <label for="commercial_register" class="block font-medium text-gray-700 mb-1">
                    {{ t('admin.commercial_register') }} <span class="text-red-500">*</span>
                </label>
                <input type="text" id="commercial_register" name="commercial_register" 
                       value="{{ old('commercial_register', $insuranceCompany->commercial_register ?? '') }}"
                       class="w-full border border-gray-300 rounded focus:ring-2 focus:ring-gold-500 @error('commercial_register') border-red-500 @enderror"
                       placeholder="CR123456789" required>
                @error('commercial_register')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            
            <!-- Tax Number -->
            <div>
                <label for="tax_number" class="block font-medium text-gray-700 mb-1">
                    {{ t('admin.tax_number') }} <span class="text-gray-400">({{ t('admin.optional') }})</span>
                </label>
                <input type="text" id="tax_number" name="tax_number" 
                       value="{{ old('tax_number', $insuranceCompany->tax_number ?? '') }}"
                       class="w-full border border-gray-300 rounded focus:ring-2 focus:ring-gold-500 @error('tax_number') border-red-500 @enderror"
                       placeholder="TX123456789">
                @error('tax_number')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            
            <!-- Employee Count -->
            <div>
                <label for="employee_count" class="block font-medium text-gray-700 mb-1">
                    {{ t('admin.employee_count') }} <span class="text-gray-400">({{ t('admin.optional') }})</span>
                </label>
                <input type="number" id="employee_count" name="employee_count" min="1"
                       value="{{ old('employee_count', $insuranceCompany->employee_count ?? '') }}"
                       class="w-full border border-gray-300 rounded focus:ring-2 focus:ring-gold-500 @error('employee_count') border-red-500 @enderror"
                       placeholder="100">
                @error('employee_count')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            
            <!-- Insured Cars -->
            <div>
                <label for="insured_cars_count" class="block font-medium text-gray-700 mb-1">
                    {{ t('admin.insured_cars_count') }} <span class="text-gray-400">({{ t('admin.optional') }})</span>
                </label>
                <input type="number" id="insured_cars_count" name="insured_cars_count" min="0"
                       value="{{ old('insured_cars_count', $insuranceCompany->insured_cars_count ?? '') }}"
                       class="w-full border border-gray-300 rounded focus:ring-2 focus:ring-gold-500 @error('insured_cars_count') border-red-500 @enderror"
                       placeholder="25000">
                @error('insured_cars_count')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <!-- Company Branding -->
        <h3 class="section-title font-semibold text-gray-900">Company Branding</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4">
            <!-- Company Logo -->
            <div>
                <label for="company_logo" class="block font-medium text-gray-700 mb-1">
                    Company Logo <span class="text-gray-400">({{ t('admin.optional') }})</span>
                </label>
                @if(isset($insuranceCompany) && $insuranceCompany->company_logo)
                    <div class="mb-2">
                        <img src="{{ $insuranceCompany->logo_url }}" alt="Current Logo" class="w-16 h-16 object-contain border rounded">
                        <p class="text-xs text-gray-500">Current Logo</p>
                    </div>
                @endif
                <input type="file" id="company_logo" name="company_logo" accept="image/*"
                       class="w-full border border-gray-300 rounded focus:ring-2 focus:ring-gold-500 @error('company_logo') border-red-500 @enderror">
                <p class="text-xs text-gray-500 mt-1">PNG, JPG, GIF up to 2MB</p>
                @error('company_logo')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Primary Color -->
            <div>
                <label for="primary_color" class="block font-medium text-gray-700 mb-1">
                    Primary Color <span class="text-gray-400">({{ t('admin.optional') }})</span>
                </label>
                <input type="color" id="primary_color" name="primary_color" 
                       value="{{ old('primary_color', $insuranceCompany->primary_color ?? '#10B981') }}"
                       class="w-full h-10 border border-gray-300 rounded focus:ring-2 focus:ring-gold-500 @error('primary_color') border-red-500 @enderror">
                @error('primary_color')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Secondary Color -->
            <div>
                <label for="secondary_color" class="block font-medium text-gray-700 mb-1">
                    Secondary Color <span class="text-gray-400">({{ t('admin.optional') }})</span>
                </label>
                <input type="color" id="secondary_color" name="secondary_color" 
                       value="{{ old('secondary_color', $insuranceCompany->secondary_color ?? '#059669') }}"
                       class="w-full h-10 border border-gray-300 rounded focus:ring-2 focus:ring-gold-500 @error('secondary_color') border-red-500 @enderror">
                @error('secondary_color')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>
        
        <!-- Additional Phones -->
        <h3 class="section-title font-semibold text-gray-900">{{ t('admin.additional_phone_numbers') }}</h3>
        <div id="phoneNumbers" class="mb-3">
            @if(isset($insuranceCompany) && $insuranceCompany->additionalPhones->count() > 0)
                @foreach($insuranceCompany->additionalPhones->where('is_primary', false) as $additionalPhone)
                    <div class="phone-group grid grid-cols-1 md:grid-cols-3 gap-2 mb-2">
                        <input type="text" name="additional_phones[]" value="{{ $additionalPhone->phone }}"
                               class="border border-gray-300 rounded focus:ring-2 focus:ring-gold-500" placeholder="{{ t('admin.phone_number') }}">
                        <input type="text" name="phone_labels[]" value="{{ $additionalPhone->label }}"
                               class="border border-gray-300 rounded focus:ring-2 focus:ring-gold-500" placeholder="{{ t('admin.phone_label_placeholder') }}">
                        <button type="button" onclick="removePhone(this)" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs">{{ t('admin.delete') }}</button>
                    </div>
                @endforeach
            @else
                <div class="phone-group grid grid-cols-1 md:grid-cols-3 gap-2 mb-2">
                    <input type="text" name="additional_phones[]" class="border border-gray-300 rounded focus:ring-2 focus:ring-gold-500" placeholder="{{ t('admin.phone_number') }}">
                    <input type="text" name="phone_labels[]" class="border border-gray-300 rounded focus:ring-2 focus:ring-gold-500" placeholder="{{ t('admin.phone_label_placeholder') }}">
                    <button type="button" onclick="removePhone(this)" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs">{{ t('admin.delete') }}</button>
                </div>
            @endif
        </div>
        <button type="button" onclick="addPhone()" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs mb-4">
            + {{ t('admin.add_phone_number') }}
        </button>
        
        <!-- Location -->
        <h3 class="section-title font-semibold text-gray-900">{{ t('admin.location_information') }}</h3>
        
        <!-- Address -->
        <div class="mb-3">
            <label for="office_address" class="block font-medium text-gray-700 mb-1">{{ t('admin.office_address') }}</label>
            <textarea id="office_address" name="office_address" rows="2"
                      class="w-full border border-gray-300 rounded focus:ring-2 focus:ring-gold-500 @error('office_address') border-red-500 @enderror"
                      placeholder="{{ t('admin.office_address_placeholder') }}">{{ old('office_address', $insuranceCompany->office_address ?? '') }}</textarea>
            @error('office_address')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        
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
        
        <!-- Status Settings -->
        <h3 class="section-title font-semibold text-gray-900">{{ t('admin.status_settings') }}</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <label class="flex items-center">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $insuranceCompany->is_active ?? 1) ? 'checked' : '' }}
                       class="w-4 h-4 text-gold-500 bg-gray-100 border-gray-300 rounded focus:ring-gold-500">
                <span class="{{ $isRtl ? 'mr-3' : 'ml-3' }} text-sm">{{ t('admin.active_account') }}</span>
            </label>
            
            <label class="flex items-center">
                <input type="hidden" name="is_approved" value="0">
                <input type="checkbox" name="is_approved" value="1" {{ old('is_approved', $insuranceCompany->is_approved ?? 0) ? 'checked' : '' }}
                       class="w-4 h-4 text-gold-500 bg-gray-100 border-gray-300 rounded focus:ring-gold-500">
                <span class="{{ $isRtl ? 'mr-3' : 'ml-3' }} text-sm">{{ t('admin.approved_account') }}</span>
            </label>
        </div>
        
        <!-- Actions -->
        <div class="flex items-center justify-end gap-2 pt-3 border-t border-gray-200">
            <a href="{{ route('admin.users.insurance-companies.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded text-sm">{{ t('admin.cancel') }}</a>
            <button type="submit" class="bg-gold-500 hover:bg-gold-600 text-dark-900 px-4 py-2 rounded text-sm">
                ✓ {{ isset($insuranceCompany) ? t('admin.update') : t('admin.save') }}
            </button>
        </div>
    </form>
</div>

@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
let leafletMap, leafletMarker, isMapInitialized = false;

// Slug Preview
document.getElementById('company_slug').addEventListener('input', function(e) {
    document.getElementById('slugPreview').textContent = e.target.value || 'company';
});

// Phone Functions
function addPhone() {
    const container = document.getElementById('phoneNumbers');
    const div = document.createElement('div');
    div.className = 'phone-group grid grid-cols-1 md:grid-cols-3 gap-2 mb-2';
    div.innerHTML = `
        <input type="text" name="additional_phones[]" class="border border-gray-300 rounded focus:ring-2 focus:ring-gold-500" placeholder="{{ t('admin.phone_number') }}">
        <input type="text" name="phone_labels[]" class="border border-gray-300 rounded focus:ring-2 focus:ring-gold-500" placeholder="{{ t('admin.phone_label_placeholder') }}">
        <button type="button" onclick="removePhone(this)" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs">{{ t('admin.delete') }}</button>
    `;
    container.appendChild(div);
}

function removePhone(btn) {
    btn.closest('.phone-group').remove();
}

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

// Form validation
document.getElementById('insuranceCompanyForm').addEventListener('submit', function(e) {
    const required = ['legal_name', 'phone', 'commercial_register', 'company_slug'];
    for (let field of required) {
        if (!document.getElementById(field).value.trim()) {
            e.preventDefault();
            showNotification('Please fill all required fields', 'error');
            return false;
        }
    }
    
    const phone = document.getElementById('phone').value.trim();
    if (!/^01[0-9]{9}$/.test(phone)) {
        e.preventDefault();
        showNotification('Invalid phone format', 'error');
        return false;
    }

    const slug = document.getElementById('company_slug').value.trim();
    if (!/^[a-z0-9\-]+$/.test(slug)) {
        e.preventDefault();
        showNotification('Company route can only contain lowercase letters, numbers and hyphens', 'error');
        return false;
    }
});
</script>
@endpush