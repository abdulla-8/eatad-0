<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ t($locationRequest->claim->insuranceCompany->translation_group . '.vehicle_location_form') ?? 'Vehicle Location Form' }}</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Leaflet CSS for map -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <style>
        .form-input {
            @apply w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200;
        }
        
        .btn-primary {
            @apply bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-200 transform hover:scale-105;
        }
        
        .btn-secondary {
            @apply bg-gray-500 hover:bg-gray-600 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-200;
        }
        
        .error-message {
            @apply text-red-600 text-sm mt-1;
        }
        
        .success-message {
            @apply text-green-600 text-sm mt-1;
        }
        
        .map-container {
            height: 400px;
            border-radius: 0.5rem;
            overflow: hidden;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-8 max-w-2xl">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="w-20 h-20 mx-auto mb-4 bg-blue-100 rounded-full flex items-center justify-center">
                <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">
                {{ t($locationRequest->claim->insuranceCompany->translation_group . '.vehicle_location_form') ?? 'Vehicle Location Form' }}
            </h1>
            <p class="text-gray-600">
                {{ t($locationRequest->claim->insuranceCompany->translation_group . '.please_provide_vehicle_location') ?? 'Please provide the location of your vehicle for towing service' }}
            </p>
        </div>

        <!-- Claim Information -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">
                {{ t($locationRequest->claim->insuranceCompany->translation_group . '.claim_information') ?? 'Claim Information' }}
            </h2>
            <div class="grid md:grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="font-medium text-gray-700">{{ t($locationRequest->claim->insuranceCompany->translation_group . '.policy_number') ?? 'Policy Number' }}:</span>
                    <span class="text-gray-900">{{ $locationRequest->claim->policy_number }}</span>
                </div>
                <div>
                    <span class="font-medium text-gray-700">{{ t($locationRequest->claim->insuranceCompany->translation_group . '.vehicle_plate') ?? 'Vehicle Plate' }}:</span>
                    <span class="text-gray-900">{{ $locationRequest->claim->vehicle_plate_number ?: 'N/A' }}</span>
                </div>
                <div>
                    <span class="font-medium text-gray-700">{{ t($locationRequest->claim->insuranceCompany->translation_group . '.vehicle_brand') ?? 'Vehicle Brand' }}:</span>
                    <span class="text-gray-900">{{ $locationRequest->claim->vehicle_brand ?: 'N/A' }}</span>
                </div>
                <div>
                    <span class="font-medium text-gray-700">{{ t($locationRequest->claim->insuranceCompany->translation_group . '.vehicle_model') ?? 'Vehicle Model' }}:</span>
                    <span class="text-gray-900">{{ $locationRequest->claim->vehicle_model ?: 'N/A' }}</span>
                </div>
            </div>
        </div>

        <!-- Location Form -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <form method="POST" action="{{ route('vehicle.location.submit', $locationRequest->public_hash) }}" class="space-y-6">
                @csrf
                
                @if($errors->any())
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">
                                    {{ t($locationRequest->claim->insuranceCompany->translation_group . '.please_correct_errors') ?? 'Please correct the following errors' }}
                                </h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <ul class="list-disc pl-5 space-y-1">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- City -->
                <div>
                    <label for="city" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ t($locationRequest->claim->insuranceCompany->translation_group . '.city') ?? 'City' }}
                        <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="city" name="city" value="{{ old('city') }}" 
                           class="form-input @error('city') border-red-500 @enderror" 
                           placeholder="{{ t($locationRequest->claim->insuranceCompany->translation_group . '.enter_city') ?? 'Enter city name' }}" required>
                    @error('city')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                <!-- District -->
                <div>
                    <label for="district" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ t($locationRequest->claim->insuranceCompany->translation_group . '.district') ?? 'District' }}
                        <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="district" name="district" value="{{ old('district') }}" 
                           class="form-input @error('district') border-red-500 @enderror" 
                           placeholder="{{ t($locationRequest->claim->insuranceCompany->translation_group . '.enter_district') ?? 'Enter district name' }}" required>
                    @error('district')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Notes -->
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ t($locationRequest->claim->insuranceCompany->translation_group . '.additional_notes') ?? 'Additional Notes' }}
                    </label>
                    <textarea id="notes" name="notes" rows="3" 
                              class="form-input @error('notes') border-red-500 @enderror" 
                              placeholder="{{ t($locationRequest->claim->insuranceCompany->translation_group . '.enter_additional_notes') ?? 'Enter any additional details about the location' }}">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Map for GPS Coordinates -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ t($locationRequest->claim->insuranceCompany->translation_group . '.location_on_map') ?? 'Location on Map (Optional)' }}
                    </label>
                    <div class="map-container" id="map"></div>
                    <p class="text-xs text-gray-500 mt-2">
                        {{ t($locationRequest->claim->insuranceCompany->translation_group . '.click_map_to_set_location') ?? 'Click on the map to set the exact location coordinates' }}
                    </p>
                    
                    <!-- Hidden inputs for coordinates -->
                    <input type="hidden" name="location_lat" id="location_lat" value="{{ old('location_lat') }}">
                    <input type="hidden" name="location_lng" id="location_lng" value="{{ old('location_lng') }}">
                    
                    <!-- Current Location Button -->
                    <button type="button" onclick="getCurrentLocation()" 
                            class="mt-3 bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        {{ t($locationRequest->claim->insuranceCompany->translation_group . '.use_current_location') ?? 'Use Current Location' }}
                    </button>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end space-x-4">
                    <button type="submit" class="btn-primary">
                        {{ t($locationRequest->claim->insuranceCompany->translation_group . '.submit_location') ?? 'Submit Location' }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <script>
        let map, marker;
        let currentLat = 24.7136; // Default to Saudi Arabia center
        let currentLng = 46.6753;

        // Initialize map
        document.addEventListener('DOMContentLoaded', function() {
            initMap();
        });

        function initMap() {
            // Initialize the map
            map = L.map('map').setView([currentLat, currentLng], 10);
            
            // Add OpenStreetMap tiles
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            // Add click event to map
            map.on('click', function(e) {
                setMarker(e.latlng.lat, e.latlng.lng);
            });

            // Try to get current location on page load
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        currentLat = position.coords.latitude;
                        currentLng = position.coords.longitude;
                        map.setView([currentLat, currentLng], 15);
                        setMarker(currentLat, currentLng);
                    },
                    function(error) {
                        console.log('Error getting location:', error);
                        // Keep default location
                    }
                );
            }
        }

        function setMarker(lat, lng) {
            // Remove existing marker
            if (marker) {
                map.removeLayer(marker);
            }

            // Add new marker
            marker = L.marker([lat, lng]).addTo(map);
            
            // Update hidden inputs
            document.getElementById('location_lat').value = lat;
            document.getElementById('location_lng').value = lng;
            
            // Add popup
            marker.bindPopup(`<b>Selected Location</b><br>Lat: ${lat.toFixed(6)}<br>Lng: ${lng.toFixed(6)}`).openPopup();
        }

        function getCurrentLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;
                        
                        map.setView([lat, lng], 15);
                        setMarker(lat, lng);
                        
                        // Show success message
                        showMessage('{{ t($locationRequest->claim->insuranceCompany->translation_group . ".location_updated") ?? "Location updated successfully!" }}', 'success');
                    },
                    function(error) {
                        let errorMessage = '{{ t($locationRequest->claim->insuranceCompany->translation_group . ".error_getting_location") ?? "Error getting location" }}';
                        
                        switch(error.code) {
                            case error.PERMISSION_DENIED:
                                errorMessage = '{{ t($locationRequest->claim->insuranceCompany->translation_group . ".location_permission_denied") ?? "Location permission denied" }}';
                                break;
                            case error.POSITION_UNAVAILABLE:
                                errorMessage = '{{ t($locationRequest->claim->insuranceCompany->translation_group . ".location_unavailable") ?? "Location information unavailable" }}';
                                break;
                            case error.TIMEOUT:
                                errorMessage = '{{ t($locationRequest->claim->insuranceCompany->translation_group . ".location_timeout") ?? "Location request timed out" }}';
                                break;
                        }
                        
                        showMessage(errorMessage, 'error');
                    }
                );
            } else {
                showMessage('{{ t($locationRequest->claim->insuranceCompany->translation_group . ".geolocation_not_supported") ?? "Geolocation is not supported by this browser" }}', 'error');
            }
        }

        function showMessage(message, type) {
            // Create message element
            const messageDiv = document.createElement('div');
            messageDiv.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
                type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
            }`;
            messageDiv.textContent = message;
            
            // Add to page
            document.body.appendChild(messageDiv);
            
            // Remove after 3 seconds
            setTimeout(() => {
                messageDiv.remove();
            }, 3000);
        }
    </script>
</body>
</html> 