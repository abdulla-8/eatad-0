<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Driver Tracking - {{ $towRequest->request_code }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        .status-button {
            @apply px-4 py-3 rounded-lg font-medium text-white transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed;
        }
        .status-button.current {
            @apply bg-blue-600 hover:bg-blue-700;
        }
        .status-button.completed {
            @apply bg-green-600 cursor-not-allowed;
        }
        .status-button.upcoming {
            @apply bg-gray-400 cursor-not-allowed;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 py-6 max-w-4xl">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-16 h-16 bg-blue-600 rounded-xl flex items-center justify-center text-white">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Tow Request #{{ $towRequest->request_code }}</h1>
                    <p class="text-gray-600">Driver Tracking Panel</p>
                </div>
            </div>
            
            <!-- Current Status -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 bg-blue-600 rounded-full animate-pulse"></div>
                    <span class="font-medium text-blue-800">Current Status: <span id="currentStatus">{{ $towRequest->status_badge['text'] }}</span></span>
                </div>
            </div>
        </div>

        <!-- Trip Information -->
        <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
            <h2 class="text-lg font-bold mb-4">Trip Information</h2>
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <h3 class="font-medium text-gray-900 mb-2">Customer</h3>
                    <p class="text-gray-700">{{ $towRequest->claim->insuranceUser->full_name }}</p>
                    <p class="text-gray-600 text-sm">{{ $towRequest->claim->insuranceUser->formatted_phone }}</p>
                </div>
                <div>
                    <h3 class="font-medium text-gray-900 mb-2">Vehicle</h3>
                    <p class="text-gray-700">{{ $towRequest->claim->vehicle_plate_number ?: $towRequest->claim->chassis_number }}</p>
                    <p class="text-gray-600 text-sm">Claim: {{ $towRequest->claim->claim_number }}</p>
                </div>
            </div>
        </div>

        <!-- Locations -->
        <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
            <h2 class="text-lg font-bold mb-4">Locations</h2>
            <div class="space-y-4">
                <!-- Pickup Location -->
                <div class="flex items-start gap-3 p-4 bg-red-50 rounded-lg">
                    <div class="w-8 h-8 bg-red-600 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-medium text-gray-900">Pickup Location</h3>
                        <p class="text-gray-700 text-sm">{{ $towRequest->pickup_location_address }}</p>
                        @if($towRequest->pickup_location_lat)
                            <a href="https://maps.google.com/?q={{ $towRequest->pickup_location_lat }},{{ $towRequest->pickup_location_lng }}" 
                               target="_blank" 
                               class="inline-flex items-center gap-1 text-red-600 text-sm font-medium mt-1 hover:underline">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                </svg>
                                Open in Maps
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Delivery Location -->
                <div class="flex items-start gap-3 p-4 bg-green-50 rounded-lg">
                    <div class="w-8 h-8 bg-green-600 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-medium text-gray-900">Delivery Location</h3>
                        <p class="text-gray-700 text-sm">{{ $towRequest->dropoff_location_address }}</p>
                        @if($towRequest->dropoff_location_lat)
                            <a href="https://maps.google.com/?q={{ $towRequest->dropoff_location_lat }},{{ $towRequest->dropoff_location_lng }}" 
                               target="_blank" 
                               class="inline-flex items-center gap-1 text-green-600 text-sm font-medium mt-1 hover:underline">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                </svg>
                                Open in Maps
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Updates -->
        <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
            <h2 class="text-lg font-bold mb-4">Update Status</h2>
            <div class="grid gap-3">
                <button onclick="updateStatus('in_transit_to_pickup')" 
                        class="status-button {{ $towRequest->status === 'assigned' ? 'current' : ($towRequest->status === 'in_transit_to_pickup' ? 'completed' : 'upcoming') }}" 
                        {{ in_array($towRequest->status, ['in_transit_to_pickup', 'arrived_at_pickup', 'customer_verified', 'vehicle_loaded', 'in_transit_to_dropoff', 'delivered']) ? 'disabled' : '' }}>
                    🚗 On the way to pickup location
                </button>
                
                <button onclick="updateStatus('arrived_at_pickup')" 
                        class="status-button {{ $towRequest->status === 'in_transit_to_pickup' ? 'current' : (in_array($towRequest->status, ['arrived_at_pickup', 'customer_verified', 'vehicle_loaded', 'in_transit_to_dropoff', 'delivered']) ? 'completed' : 'upcoming') }}" 
                        {{ !in_array($towRequest->status, ['in_transit_to_pickup']) ? 'disabled' : '' }}>
                    📍 Arrived at pickup location
                </button>
                
                <button onclick="updateStatus('vehicle_loaded')" 
                        class="status-button {{ $towRequest->status === 'customer_verified' ? 'current' : (in_array($towRequest->status, ['vehicle_loaded', 'in_transit_to_dropoff', 'delivered']) ? 'completed' : 'upcoming') }}" 
                        {{ !in_array($towRequest->status, ['customer_verified']) ? 'disabled' : '' }}>
                    🔧 Vehicle loaded on tow truck
                </button>
                
                <button onclick="updateStatus('in_transit_to_dropoff')" 
                        class="status-button {{ $towRequest->status === 'vehicle_loaded' ? 'current' : (in_array($towRequest->status, ['in_transit_to_dropoff', 'delivered']) ? 'completed' : 'upcoming') }}" 
                        {{ !in_array($towRequest->status, ['vehicle_loaded']) ? 'disabled' : '' }}>
                    🚛 On the way to service center
                </button>
                
                <button onclick="updateStatus('delivered')" 
                        class="status-button {{ $towRequest->status === 'in_transit_to_dropoff' ? 'current' : ($towRequest->status === 'delivered' ? 'completed' : 'upcoming') }}" 
                        {{ !in_array($towRequest->status, ['in_transit_to_dropoff']) ? 'disabled' : '' }}>
                    ✅ Delivered to service center
                </button>
            </div>
        </div>

        <!-- Customer Verification (shows when arrived at pickup) -->
        @if($towRequest->status === 'arrived_at_pickup')
        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6 mb-6">
            <h2 class="text-lg font-bold text-yellow-800 mb-4">Customer Verification Required</h2>
            <p class="text-yellow-700 mb-4">Ask the customer for their 5-digit verification code:</p>
            <div class="flex gap-3">
                <input type="text" id="customerCode" placeholder="Enter 5-digit code" maxlength="5" 
                       class="flex-1 px-4 py-3 border border-yellow-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                <button onclick="verifyCustomerCode()" 
                        class="px-6 py-3 bg-yellow-600 text-white rounded-lg font-medium hover:bg-yellow-700 transition-colors">
                    Verify Customer
                </button>
            </div>
        </div>
        @endif

        <!-- Service Center Code (shows after customer verification) -->
        @if(in_array($towRequest->status, ['customer_verified', 'vehicle_loaded', 'in_transit_to_dropoff', 'delivered']))
        <div class="bg-green-50 border border-green-200 rounded-xl p-6 mb-6">
            <h2 class="text-lg font-bold text-green-800 mb-2">Service Center Delivery Code</h2>
            <p class="text-green-700 mb-3">Give this code to the service center when delivering:</p>
            <div class="bg-white border-2 border-green-300 rounded-lg p-4 text-center">
                <span class="text-3xl font-bold text-green-800 tracking-wider">{{ $towRequest->service_center_verification_code }}</span>
            </div>
        </div>
        @endif

        <!-- Live Location Tracking -->
        <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
            <h2 class="text-lg font-bold mb-4">Live Location Tracking</h2>
            <div class="space-y-4">
                <button onclick="getCurrentLocation()" 
                        class="w-full px-4 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors">
                    📍 Update My Location
                </button>
                <div id="map" style="height: 300px;" class="rounded-lg border"></div>
                <div id="locationStatus" class="text-sm text-gray-600 text-center"></div>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    <div id="messageContainer" class="fixed top-4 right-4 z-50"></div>

    <script>
        let map, marker;
        let currentLocation = null;
        const token = '{{ $towRequest->driver_tracking_token }}';

        // Initialize map
        function initMap() {
            map = L.map('map').setView([30.0444, 31.2357], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            // Add pickup and delivery markers
            @if($towRequest->pickup_location_lat)
            L.marker([{{ $towRequest->pickup_location_lat }}, {{ $towRequest->pickup_location_lng }}], {
                icon: L.divIcon({
                    className: 'custom-div-icon',
                    html: '<div style="background-color: #DC2626; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center;"><span style="color: white; font-size: 12px;">P</span></div>',
                    iconSize: [30, 30],
                    iconAnchor: [15, 15]
                })
            }).addTo(map).bindPopup('Pickup Location');
            @endif

            @if($towRequest->dropoff_location_lat)
            L.marker([{{ $towRequest->dropoff_location_lat }}, {{ $towRequest->dropoff_location_lng }}], {
                icon: L.divIcon({
                    className: 'custom-div-icon',
                    html: '<div style="background-color: #059669; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center;"><span style="color: white; font-size: 12px;">D</span></div>',
                    iconSize: [30, 30],
                    iconAnchor: [15, 15]
                })
            }).addTo(map).bindPopup('Delivery Location');
            @endif
        }

        // Get current location
        function getCurrentLocation() {
            if (navigator.geolocation) {
                document.getElementById('locationStatus').textContent = 'Getting your location...';
                
                navigator.geolocation.getCurrentPosition(function(position) {
                    currentLocation = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };
                    
                    updateLocationOnMap(currentLocation.lat, currentLocation.lng);
                    sendLocationUpdate();
                }, function(error) {
                    showMessage('Failed to get location: ' + error.message, 'error');
                    document.getElementById('locationStatus').textContent = 'Failed to get location';
                });
            } else {
                showMessage('Geolocation is not supported by this browser', 'error');
            }
        }

        // Update location on map
        function updateLocationOnMap(lat, lng) {
            if (marker) {
                map.removeLayer(marker);
            }
            
            marker = L.marker([lat, lng], {
                icon: L.divIcon({
                    className: 'custom-div-icon',
                    html: '<div style="background-color: #2563EB; width: 20px; height: 20px; border-radius: 50%; border: 3px solid white; box-shadow: 0 0 10px rgba(0,0,0,0.3);"></div>',
                    iconSize: [20, 20],
                    iconAnchor: [10, 10]
                })
            }).addTo(map);
            
            map.setView([lat, lng], 15);
            document.getElementById('locationStatus').textContent = `Location updated: ${lat.toFixed(6)}, ${lng.toFixed(6)}`;
        }

        // Send location update to server
        function sendLocationUpdate() {
            if (!currentLocation) return;
            
            fetch(`/driver/track/${token}/location`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    lat: currentLocation.lat,
                    lng: currentLocation.lng
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showMessage('Location updated successfully', 'success');
                } else {
                    showMessage('Failed to update location', 'error');
                }
            })
            .catch(error => {
                showMessage('Error updating location', 'error');
            });
        }

        // Update status
        function updateStatus(status) {
            fetch(`/driver/track/${token}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    status: status,
                    lat: currentLocation?.lat,
                    lng: currentLocation?.lng
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showMessage(data.message, 'success');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showMessage(data.error || 'Failed to update status', 'error');
                }
            })
            .catch(error => {
                showMessage('Error updating status', 'error');
            });
        }

        // Verify customer code
        function verifyCustomerCode() {
            const code = document.getElementById('customerCode').value;
            if (code.length !== 5) {
                showMessage('Please enter a 5-digit code', 'error');
                return;
            }

            fetch(`/driver/track/${token}/verify-customer`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    verification_code: code
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showMessage(data.message, 'success');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showMessage(data.error || 'Invalid verification code', 'error');
                }
            })
            .catch(error => {
                showMessage('Error verifying code', 'error');
            });
        }

        // Show message
        function showMessage(message, type) {
            const container = document.getElementById('messageContainer');
            const div = document.createElement('div');
            div.className = `px-4 py-3 rounded-lg shadow-lg text-white font-medium mb-2 ${
                type === 'success' ? 'bg-green-600' : 'bg-red-600'
            }`;
            div.textContent = message;
            container.appendChild(div);
            
            setTimeout(() => {
                div.remove();
            }, 5000);
        }

        // Auto-update location every 30 seconds when moving
        setInterval(() => {
            const movingStatuses = ['in_transit_to_pickup', 'in_transit_to_dropoff'];
            if (movingStatuses.includes('{{ $towRequest->status }}')) {
                getCurrentLocation();
            }
        }, 30000);

        // Initialize map when page loads
        document.addEventListener('DOMContentLoaded', function() {
            initMap();
            getCurrentLocation();
        });
    </script>
</body>
</html>