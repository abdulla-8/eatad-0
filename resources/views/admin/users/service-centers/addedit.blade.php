@extends('admin.layouts.app')

@section('title', isset($serviceCenter) ? t('admin.edit_service_center') : t('admin.add_service_center'))

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
            {{ isset($serviceCenter) ? t('admin.edit_service_center') : t('admin.add_service_center') }}
        </h1>
        <nav class="text-xs text-gray-600">
            <a href="{{ route('admin.dashboard') }}">{{ t('admin.dashboard') }}</a> > 
            <a href="{{ route('admin.users.service-centers.index') }}">{{ t('admin.service_centers') }}</a> > 
            <span class="text-gold-600">{{ isset($serviceCenter) ? t('admin.edit') : t('admin.add_new') }}</span>
        </nav>
    </div>
    <a href="{{ route('admin.users.service-centers.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-2 rounded text-sm">
        ← {{ t('admin.back_to_list') }}
    </a>
</div>

<div class="bg-white rounded-lg shadow-sm border compact-form">
    <div class="bg-gold-500 text-dark-900 px-4 py-3 rounded-t-lg">
        <h2 class="font-semibold">{{ t('admin.service_center_details') }}</h2>
    </div>
    
    <form method="POST" 
          action="{{ isset($serviceCenter) ? route('admin.users.service-centers.update', $serviceCenter) : route('admin.users.service-centers.store') }}" 
          class="p-4" id="serviceCenterForm" enctype="multipart/form-data">
        @csrf
        @if(isset($serviceCenter)) @method('PUT') @endif
        
        <!-- Basic Info -->
        <h3 class="section-title font-semibold text-gray-900">{{ t('admin.basic_information') }}</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4">
            <!-- Legal Name -->
            <div class="md:col-span-2">
                <label for="legal_name" class="block font-medium text-gray-700 mb-1">
                    {{ t('admin.legal_name') }} <span class="text-red-500">*</span>
                </label>
                <input type="text" id="legal_name" name="legal_name" 
                       value="{{ old('legal_name', $serviceCenter->legal_name ?? '') }}"
                       class="w-full border border-gray-300 rounded focus:ring-2 focus:ring-gold-500 @error('legal_name') border-red-500 @enderror"
                       placeholder="مركز النور للصيانة الشاملة" required>
                @error('legal_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Center Slug -->
            <div>
                <label for="center_slug" class="block font-medium text-gray-700 mb-1">
                    Center Route <span class="text-red-500">*</span>
                </label>
                <input type="text" id="center_slug" name="center_slug" 
                       value="{{ old('center_slug', $serviceCenter->center_slug ?? '') }}"
                       class="w-full border border-gray-300 rounded focus:ring-2 focus:ring-gold-500 @error('center_slug') border-red-500 @enderror"
                       placeholder="alnour" {{ isset($serviceCenter) ? '' : 'required' }}>
                <p class="text-xs text-gray-500 mt-1">www.example.com/service-center/<strong id="slugPreview">{{ old('center_slug', $serviceCenter->center_slug ?? 'center') }}</strong>/login</p>
                @error('center_slug')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            
            <!-- Phone -->
            <div>
                <label for="phone" class="block font-medium text-gray-700 mb-1">
                    {{ t('admin.primary_phone') }} <span class="text-red-500">*</span>
                </label>
                <input type="text" id="phone" name="phone" 
                       value="{{ old('phone', $serviceCenter->phone ?? '') }}"
                       class="w-full border border-gray-300 rounded focus:ring-2 focus:ring-gold-500 @error('phone') border-red-500 @enderror"
                       placeholder="01234567890" required>
                @error('phone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            
            <!-- Password -->
            <div>
                <label for="password" class="block font-medium text-gray-700 mb-1">
                    {{ t('admin.password') }} @if(!isset($serviceCenter))<span class="text-red-500">*</span>@endif
                </label>
                <input type="password" id="password" name="password" 
                       class="w-full border border-gray-300 rounded focus:ring-2 focus:ring-gold-500 @error('password') border-red-500 @enderror"
                       placeholder="••••••••" {{ !isset($serviceCenter) ? 'required' : '' }}>
                @if(isset($serviceCenter))<p class="text-xs text-gray-500 mt-1">{{ t('admin.leave_empty_keep_current') }}</p>@endif
                @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            
            <!-- Commercial Register -->
            <div>
                <label for="commercial_register" class="block font-medium text-gray-700 mb-1">
                    {{ t('admin.commercial_register') }} <span class="text-red-500">*</span>
                </label>
                <input type="text" id="commercial_register" name="commercial_register" 
                       value="{{ old('commercial_register', $serviceCenter->commercial_register ?? '') }}"
                       class="w-full border border-gray-300 rounded focus:ring-2 focus:ring-gold-500 @error('commercial_register') border-red-500 @enderror"
                       placeholder="SR123456789" required>
                @error('commercial_register')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            
            <!-- Tax Number -->
            <div>
                <label for="tax_number" class="block font-medium text-gray-700 mb-1">
                    {{ t('admin.tax_number') }} <span class="text-gray-400">({{ t('admin.optional') }})</span>
                </label>
                <input type="text" id="tax_number" name="tax_number" 
                       value="{{ old('tax_number', $serviceCenter->tax_number ?? '') }}"
                       class="w-full border border-gray-300 rounded focus:ring-2 focus:ring-gold-500 @error('tax_number') border-red-500 @enderror"
                       placeholder="TX123456789">
                @error('tax_number')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <!-- Location & Specialization -->
        <h3 class="section-title font-semibold text-gray-900">{{ t('admin.location_specialization') }}</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4">
            <!-- Industrial Area -->
            <div>
                <label for="industrial_area_id" class="block font-medium text-gray-700 mb-1">
                    الصناعية
                </label>
                <select name="industrial_area_id" id="industrial_area_id"
                        class="w-full border border-gray-300 rounded focus:ring-2 focus:ring-gold-500 @error('industrial_area_id') border-red-500 @enderror">
                    <option value="">اختر الصناعية</option>
                    @foreach($industrialAreas as $area)
                        <option value="{{ $area->id }}" 
                                {{ old('industrial_area_id', $serviceCenter->industrial_area_id ?? '') == $area->id ? 'selected' : '' }}>
                            {{ $area->display_name }}
                        </option>
                    @endforeach
                </select>
                @error('industrial_area_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Service Specialization -->
            <div>
                <label for="service_specialization_id" class="block font-medium text-gray-700 mb-1">
                    التخصص
                </label>
                <select name="service_specialization_id" id="service_specialization_id"
                        class="w-full border border-gray-300 rounded focus:ring-2 focus:ring-gold-500 @error('service_specialization_id') border-red-500 @enderror">
                    <option value="">اختر التخصص</option>
                    @foreach($serviceSpecializations as $specialization)
                        <option value="{{ $specialization->id }}" 
                                {{ old('service_specialization_id', $serviceCenter->service_specialization_id ?? '') == $specialization->id ? 'selected' : '' }}>
                            {{ $specialization->display_name }}
                        </option>
                    @endforeach
                </select>
                @error('service_specialization_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Center Area -->
            <div>
                <label for="center_area_sqm" class="block font-medium text-gray-700 mb-1">
                    مساحة المركز (م²) <span class="text-gray-400">({{ t('admin.optional') }})</span>
                </label>
                <input type="number" id="center_area_sqm" name="center_area_sqm" step="0.01" min="0"
                       value="{{ old('center_area_sqm', $serviceCenter->center_area_sqm ?? '') }}"
                       class="w-full border border-gray-300 rounded focus:ring-2 focus:ring-gold-500 @error('center_area_sqm') border-red-500 @enderror"
                       placeholder="500">
                @error('center_area_sqm')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <!-- Technicians -->
        <h3 class="section-title font-semibold text-gray-900">عدد الفنيين</h3>
        <div class="grid grid-cols-2 md:grid-cols-5 gap-3 mb-4">
            <!-- Body Work Technicians -->
            <div>
                <label for="body_work_technicians" class="block font-medium text-gray-700 mb-1">
                    السمكرين
                </label>
                <input type="number" id="body_work_technicians" name="body_work_technicians" min="0"
                       value="{{ old('body_work_technicians', $serviceCenter->body_work_technicians ?? 0) }}"
                       class="w-full border border-gray-300 rounded focus:ring-2 focus:ring-gold-500 @error('body_work_technicians') border-red-500 @enderror"
                       placeholder="0">
                @error('body_work_technicians')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Mechanical Technicians -->
            <div>
                <label for="mechanical_technicians" class="block font-medium text-gray-700 mb-1">
                    الميكانيكا
                </label>
                <input type="number" id="mechanical_technicians" name="mechanical_technicians" min="0"
                       value="{{ old('mechanical_technicians', $serviceCenter->mechanical_technicians ?? 0) }}"
                       class="w-full border border-gray-300 rounded focus:ring-2 focus:ring-gold-500 @error('mechanical_technicians') border-red-500 @enderror"
                       placeholder="0">
                @error('mechanical_technicians')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Painting Technicians -->
            <div>
                <label for="painting_technicians" class="block font-medium text-gray-700 mb-1">
                    الدهانين
                </label>
                <input type="number" id="painting_technicians" name="painting_technicians" min="0"
                       value="{{ old('painting_technicians', $serviceCenter->painting_technicians ?? 0) }}"
                       class="w-full border border-gray-300 rounded focus:ring-2 focus:ring-gold-500 @error('painting_technicians') border-red-500 @enderror"
                       placeholder="0">
                @error('painting_technicians')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Electrical Technicians -->
            <div>
                <label for="electrical_technicians" class="block font-medium text-gray-700 mb-1">
                    الكهربائيين
                </label>
                <input type="number" id="electrical_technicians" name="electrical_technicians" min="0"
                       value="{{ old('electrical_technicians', $serviceCenter->electrical_technicians ?? 0) }}"
                       class="w-full border border-gray-300 rounded focus:ring-2 focus:ring-gold-500 @error('electrical_technicians') border-red-500 @enderror"
                       placeholder="0">
                @error('electrical_technicians')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Other Technicians -->
            <div>
                <label for="other_technicians" class="block font-medium text-gray-700 mb-1">
                    أخرى
                </label>
                <input type="number" id="other_technicians" name="other_technicians" min="0"
                       value="{{ old('other_technicians', $serviceCenter->other_technicians ?? 0) }}"
                       class="w-full border border-gray-300 rounded focus:ring-2 focus:ring-gold-500 @error('other_technicians') border-red-500 @enderror"
                       placeholder="0">
                @error('other_technicians')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <!-- Total Display -->
        <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded">
            <span class="text-blue-800 font-medium">إجمالي الفنيين: </span>
            <span id="totalTechnicians" class="text-blue-900 font-bold">0</span>
        </div>

        <!-- Additional Phones -->
        <h3 class="section-title font-semibold text-gray-900">{{ t('admin.additional_phone_numbers') }}</h3>
        <div id="phoneNumbers" class="mb-3">
            @if(isset($serviceCenter) && $serviceCenter->additionalPhones->count() > 0)
                @foreach($serviceCenter->additionalPhones->where('is_primary', false) as $additionalPhone)
                    <div class="phone-group grid grid-cols-1 md:grid-cols-3 gap-2 mb-2">
                        <input type="text" name="additional_phones[]" value="{{ $additionalPhone->phone }}"
                               class="border border-gray-300 rounded focus:ring-2 focus:ring-gold-500" placeholder="رقم الهاتف">
                        <input type="text" name="phone_labels[]" value="{{ $additionalPhone->label }}"
                               class="border border-gray-300 rounded focus:ring-2 focus:ring-gold-500" placeholder="اسم الخط (مثال: خدمة العملاء)">
                        <button type="button" onclick="removePhone(this)" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs">حذف</button>
                    </div>
                @endforeach
            @else
                <div class="phone-group grid grid-cols-1 md:grid-cols-3 gap-2 mb-2">
                    <input type="text" name="additional_phones[]" class="border border-gray-300 rounded focus:ring-2 focus:ring-gold-500" placeholder="رقم الهاتف">
                    <input type="text" name="phone_labels[]" class="border border-gray-300 rounded focus:ring-2 focus:ring-gold-500" placeholder="اسم الخط (مثال: خدمة العملاء)">
                    <button type="button" onclick="removePhone(this)" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs">حذف</button>
                </div>
            @endif
        </div>
        <button type="button" onclick="addPhone()" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs mb-4">
            + إضافة رقم هاتف
        </button>
        
        <!-- Location -->
        <h3 class="section-title font-semibold text-gray-900">{{ t('admin.location_information') }}</h3>
        
        <!-- Address -->
        <div class="mb-3">
            <label for="center_address" class="block font-medium text-gray-700 mb-1">عنوان المركز</label>
            <textarea id="center_address" name="center_address" rows="2"
                      class="w-full border border-gray-300 rounded focus:ring-2 focus:ring-gold-500 @error('center_address') border-red-500 @enderror"
                      placeholder="العنوان الكامل للمركز">{{ old('center_address', $serviceCenter->center_address ?? '') }}</textarea>
            @error('center_address')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        
        <!-- Map Section -->
        <div class="mb-4">
            <label class="block font-medium text-gray-700 mb-1">{{ t('admin.map_location') }}</label>
            
            <!-- Coordinates -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mb-2">
                <input type="number" id="center_location_lat" name="center_location_lat" step="any"
                       value="{{ old('center_location_lat', $serviceCenter->center_location_lat ?? '') }}"
                       class="border border-gray-300 rounded focus:ring-2 focus:ring-gold-500 @error('center_location_lat') border-red-500 @enderror"
                       placeholder="{{ t('admin.latitude') }}" readonly>
                <input type="number" id="center_location_lng" name="center_location_lng" step="any"
                       value="{{ old('center_location_lng', $serviceCenter->center_location_lng ?? '') }}"
                       class="border border-gray-300 rounded focus:ring-2 focus:ring-gold-500 @error('center_location_lng') border-red-500 @enderror"
                       placeholder="{{ t('admin.longitude') }}" readonly>
            </div>
            
            <!-- Map Controls -->
            <div class="flex gap-2 flex-wrap mb-2">
                <button type="button" onclick="openMap()" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs">
                    📍 فتح الخريطة
                </button>
                <button type="button" onclick="getCurrentLocation()" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-xs">
                    📌 الموقع الحالي
                </button>
                <button type="button" onclick="clearLocation()" class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-1 rounded text-xs">
                    🗑️ مسح الموقع
                </button>
            </div>
            
            <!-- Map Container -->
            <div id="mapContainer" class="hidden">
                <div id="leafletMap" class="leaflet-container border border-gray-300 mb-2"></div>
                <div class="flex justify-between items-center text-xs">
                    <span class="text-gray-600">اضغط على الخريطة لتحديد الموقع</span>
                    <button type="button" onclick="closeMap()" class="bg-gray-500 hover:bg-gray-600 text-white px-2 py-1 rounded">إغلاق</button>
                </div>
            </div>
            
            <!-- Location Display -->
            <div id="locationInfo" class="hidden mt-2 p-2 bg-green-50 border border-green-200 rounded text-xs">
                <span class="text-green-800">الموقع محدد: </span>
                <span id="locationDetails" class="text-green-600"></span>
            </div>
        </div>
        
        <!-- Status Settings -->
        <h3 class="section-title font-semibold text-gray-900">{{ t('admin.status_settings') }}</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <label class="flex items-center">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $serviceCenter->is_active ?? 1) ? 'checked' : '' }}
                       class="w-4 h-4 text-gold-500 bg-gray-100 border-gray-300 rounded focus:ring-gold-500">
                <span class="{{ $isRtl ? 'mr-3' : 'ml-3' }} text-sm">{{ t('admin.active_account') }}</span>
            </label>
            
            <label class="flex items-center">
                <input type="hidden" name="is_approved" value="0">
                <input type="checkbox" name="is_approved" value="1" {{ old('is_approved', $serviceCenter->is_approved ?? 0) ? 'checked' : '' }}
                       class="w-4 h-4 text-gold-500 bg-gray-100 border-gray-300 rounded focus:ring-gold-500">
                <span class="{{ $isRtl ? 'mr-3' : 'ml-3' }} text-sm">{{ t('admin.approved_account') }}</span>
            </label>
        </div>
        
        <!-- Actions -->
        <div class="flex items-center justify-end gap-2 pt-3 border-t border-gray-200">
            <a href="{{ route('admin.users.service-centers.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded text-sm">{{ t('admin.cancel') }}</a>
            <button type="submit" class="bg-gold-500 hover:bg-gold-600 text-dark-900 px-4 py-2 rounded text-sm">
                ✓ {{ isset($serviceCenter) ? t('admin.update') : t('admin.save') }}
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
if (document.getElementById('center_slug')) {
    document.getElementById('center_slug').addEventListener('input', function(e) {
        document.getElementById('slugPreview').textContent = e.target.value || 'center';
    });
}

// Calculate Total Technicians
function updateTotalTechnicians() {
    const fields = ['body_work_technicians', 'mechanical_technicians', 'painting_technicians', 'electrical_technicians', 'other_technicians'];
    let total = 0;
    
    fields.forEach(field => {
        const element = document.getElementById(field);
        if (element) {
            const value = parseInt(element.value) || 0;
            total += value;
        }
    });
    
    const totalElement = document.getElementById('totalTechnicians');
    if (totalElement) {
        totalElement.textContent = total;
    }
}

// Add event listeners for technician inputs
document.querySelectorAll('[id$="_technicians"]').forEach(input => {
    input.addEventListener('input', updateTotalTechnicians);
});

// Phone Functions
function addPhone() {
    const container = document.getElementById('phoneNumbers');
    const div = document.createElement('div');
    div.className = 'phone-group grid grid-cols-1 md:grid-cols-3 gap-2 mb-2';
    div.innerHTML = `
        <input type="text" name="additional_phones[]" class="border border-gray-300 rounded focus:ring-2 focus:ring-gold-500" placeholder="رقم الهاتف">
        <input type="text" name="phone_labels[]" class="border border-gray-300 rounded focus:ring-2 focus:ring-gold-500" placeholder="اسم الخط (مثال: خدمة العملاء)">
        <button type="button" onclick="removePhone(this)" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs">حذف</button>
    `;
    container.appendChild(div);
}

function removePhone(btn) {
    btn.closest('.phone-group').remove();
}

// Map Functions
function initMap() {
    const defaultLocation = [30.0444, 31.2357];
    const existingLat = parseFloat(document.getElementById('center_location_lat').value);
    const existingLng = parseFloat(document.getElementById('center_location_lng').value);
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
    document.getElementById('center_location_lat').value = lat.toFixed(8);
    document.getElementById('center_location_lng').value = lng.toFixed(8);
    updateLocationInfo({ lat, lng });
    showNotification('تم تحديث الموقع', 'success');
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
        showNotification('الموقع الجغرافي غير مدعوم', 'error');
        return;
    }

    showNotification('جاري الحصول على الموقع...', 'info');
    navigator.geolocation.getCurrentPosition(function(position) {
        const location = { lat: position.coords.latitude, lng: position.coords.longitude };
        document.getElementById('center_location_lat').value = location.lat.toFixed(8);
        document.getElementById('center_location_lng').value = location.lng.toFixed(8);
        updateLocationInfo(location);
        
        if (!document.getElementById('mapContainer').classList.contains('hidden') && isMapInitialized) {
            leafletMap.setView([location.lat, location.lng], 15);
            leafletMarker.setLatLng([location.lat, location.lng]);
        }
        
        showNotification('تم تحديد الموقع بنجاح', 'success');
    }, function(error) {
        const messages = {
            1: 'تم رفض الوصول للموقع',
            2: 'الموقع غير متاح',
            3: 'انتهت مهلة الحصول على الموقع'
        };
showNotification(messages[error.code] || 'خطأ في الموقع', 'error');
    });
}

function clearLocation() {
    document.getElementById('center_location_lat').value = '';
    document.getElementById('center_location_lng').value = '';
    document.getElementById('locationInfo').classList.add('hidden');
    
    if (!document.getElementById('mapContainer').classList.contains('hidden') && isMapInitialized) {
        const defaultLocation = [30.0444, 31.2357];
        leafletMap.setView(defaultLocation, 10);
        leafletMarker.setLatLng(defaultLocation);
    }
    
    showNotification('تم مسح الموقع', 'info');
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
    // Initialize total on page load
    updateTotalTechnicians();
    
    // Phone formatting
    const phoneField = document.getElementById('phone');
    if (phoneField) {
        phoneField.addEventListener('input', function(e) {
            e.target.value = e.target.value.replace(/\D/g, '').substring(0, 11);
        });
    }

    document.addEventListener('input', function(e) {
        if (e.target.name === 'additional_phones[]') {
            e.target.value = e.target.value.replace(/\D/g, '').substring(0, 11);
        }
    });

    // Show existing location
    const existingLat = document.getElementById('center_location_lat').value;
    const existingLng = document.getElementById('center_location_lng').value;
    if (existingLat && existingLng) {
        updateLocationInfo({ lat: parseFloat(existingLat), lng: parseFloat(existingLng) });
    }
});

// Form validation
document.getElementById('serviceCenterForm').addEventListener('submit', function(e) {
    const required = ['legal_name', 'phone', 'commercial_register'];
    for (let field of required) {
        const element = document.getElementById(field);
        if (!element || !element.value.trim()) {
            e.preventDefault();
            showNotification('يرجى ملء جميع الحقول المطلوبة', 'error');
            return false;
        }
    }
    
    const phone = document.getElementById('phone').value.trim();
    // if (!/^01[0-9]{9}$/.test(phone)) {
    //     e.preventDefault();
    //     showNotification('صيغة رقم الهاتف غير صحيحة', 'error');
    //     return false;
    // }

    const slugField = document.getElementById('center_slug');
    if (slugField && slugField.value.trim()) {
        const slug = slugField.value.trim();
        if (!/^[a-z0-9\-]+$/.test(slug)) {
            e.preventDefault();
            showNotification('رابط المركز يجب أن يحتوي على أحرف إنجليزية صغيرة وأرقام وشرطات فقط', 'error');
            return false;
        }
    }
});
</script>
@endpush