<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Track Your Tow Request - {{ $towRequest->request_code }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        .timeline-item {
            @apply flex items-center gap-4 p-4 rounded-lg transition-all duration-200;
        }
        .timeline-item.completed {
            @apply bg-green-50 border border-green-200;
        }
        .timeline-item.current {
            @apply bg-blue-50 border border-blue-200;
        }
        .timeline-item.upcoming {
            @apply bg-gray-50 border border-gray-200;
        }
        .pulse-dot {
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 py-6 max-w-4xl">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
            <div class="text-center">
                <div class="w-20 h-20 bg-blue-600 rounded-full flex items-center justify-center text-white mx-auto mb-4">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Track Your Tow Request</h1>
                <p class="text-gray-600">Request #{{ $towRequest->request_code }}</p>
            </div>
        </div>

        <!-- Current Status -->
        <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center">
                    <div class="w-3 h-3 bg-white rounded-full pulse-dot"></div>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Current Status</h2>
                    <p class="text-blue-600 font-medium" id="currentStatus">{{ $towRequest->status_badge['text'] }}</p>
                    <p class="text-gray-500 text-sm" id="lastUpdate">Last updated: {{ now()->format('M d, Y H:i') }}</p>
                </div>
            </div>
        </div>

        <!-- Vehicle & Service Info -->
        <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
            <h2 class="text-lg font-bold mb-4">Service Information</h2>
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <h3 class="font-medium text-gray-900 mb-2">Your Vehicle</h3>
                    <p class="text-gray-700">{{ $towRequest->claim->vehicle_plate_number ?: $towRequest->claim->chassis_number }}</p>
                    <p class="text-gray-600 text-sm">Claim: {{ $towRequest->claim->claim_number }}</p>
                </div>
                @if($providerInfo)
                <div>
                    <h3 class="font-medium text-gray-900 mb-2">Service Provider</h3>
                    <p class="text-gray-700">{{ $providerInfo['name'] }}</p>
                    <p class="text-gray-600 text-sm">{{ $providerInfo['phone'] }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Customer Verification (shows when driver arrives) -->
        @if($towRequest->status === 'arrived_at_pickup' && $towRequest->customer_verification_code)
        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6 mb-6">
            <h2 class="text-lg font-bold text-yellow-800 mb-4">🚛 Driver Has Arrived!</h2>
            <p class="text-yellow-700 mb-4">Your driver is at the pickup location. Please provide them with your verification code:</p>
            
            <div class="bg-white border-2 border-yellow-300 rounded-lg p-6 text-center mb-4">
                <p class="text-yellow-600 text-sm font-medium mb-2">Your Verification Code</p>
                <span class="text-4xl font-bold text-yellow-800 tracking-wider">{{ $towRequest->customer_verification_code }}</span>
            </div>
            
            <div class="bg-yellow-100 rounded-lg p-4">
                <p class="text-yellow-800 text-sm">
                    <strong>Instructions:</strong> Show this 5-digit code to your driver. They will enter it to confirm your identity before loading your vehicle.
                </p>
            </div>
        </div>
        @endif

        <!-- Live Tracking Map -->
        @if($latestTracking && in_array($towRequest->status, ['in_transit_to_pickup', 'in_transit_to_dropoff']))
        <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
            <h2 class="text-lg font-bold mb-4">🗺️ Live Tracking</h2>
            <div id="map" style="height: 300px;" class="rounded-lg border mb-4"></div>
            <div class="text-center">
                <button onclick="refreshLocation()" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors">
                    🔄 Refresh Location
                </button>
            </div>
        </div>
        @endif

        <!-- Timeline -->
        <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
            <h2 class="text-lg font-bold mb-6">Progress Timeline</h2>
            <div class="space-y-4">
                <!-- Request Accepted -->
                <div class="timeline-item completed">
                    <div class="w-10 h-10 bg-green-600 rounded-full flex items-center justify-center text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-medium text-gray-900">Request Accepted</h3>
                        <p class="text-gray-600 text-sm">Tow service provider assigned</p>
                    </div>
                </div>

                <!-- On the way to pickup -->
                <div class="timeline-item {{ in_array($towRequest->status, ['in_transit_to_pickup', 'arrived_at_pickup', 'customer_verified', 'vehicle_loaded', 'in_transit_to_dropoff', 'delivered']) ? 'completed' : ($towRequest->status === 'assigned' ? 'current' : 'upcoming') }}">
                    <div class="w-10 h-10 {{ in_array($towRequest->status, ['in_transit_to_pickup', 'arrived_at_pickup', 'customer_verified', 'vehicle_loaded', 'in_transit_to_dropoff', 'delivered']) ? 'bg-green-600' : ($towRequest->status === 'assigned' ? 'bg-blue-600' : 'bg-gray-400') }} rounded-full flex items-center justify-center text-white">
                        @if(in_array($towRequest->status, ['in_transit_to_pickup', 'arrived_at_pickup', 'customer_verified', 'vehicle_loaded', 'in_transit_to_dropoff', 'delivered']))
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        @elseif($towRequest->status === 'assigned')
                            <div class="w-3 h-3 bg-white rounded-full pulse-dot"></div>
                        @else
                            <span class="text-sm">2</span>
                        @endif
                    </div>
                    <div>
                        <h3 class="font-medium text-gray-900">Driver On The Way</h3>
                        <p class="text-gray-600 text-sm">Heading to your location</p>
                        @if($towRequest->estimated_pickup_time)
                            <p class="text-gray-500 text-xs">ETA: {{ $towRequest->estimated_pickup_time->format('H:i') }}</p>
                        @endif
                    </div>
                </div>

                <!-- Arrived at pickup -->
                <div class="timeline-item {{ in_array($towRequest->status, ['arrived_at_pickup', 'customer_verified', 'vehicle_loaded', 'in_transit_to_dropoff', 'delivered']) ? 'completed' : ($towRequest->status === 'in_transit_to_pickup' ? 'current' : 'upcoming') }}">
                    <div class="w-10 h-10 {{ in_array($towRequest->status, ['arrived_at_pickup', 'customer_verified', 'vehicle_loaded', 'in_transit_to_dropoff', 'delivered']) ? 'bg-green-600' : ($towRequest->status === 'in_transit_to_pickup' ? 'bg-blue-600' : 'bg-gray-400') }} rounded-full flex items-center justify-center text-white">
                        @if(in_array($towRequest->status, ['arrived_at_pickup', 'customer_verified', 'vehicle_loaded', 'in_transit_to_dropoff', 'delivered']))
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        @elseif($towRequest->status === 'in_transit_to_pickup')
                            <div class="w-3 h-3 bg-white rounded-full pulse-dot"></div>
                        @else
                            <span class="text-sm">3</span>
                        @endif
                    </div>
                    <div>
                        <h3 class="font-medium text-gray-900">Driver Arrived</h3>
                        <p class="text-gray-600 text-sm">Ready for vehicle pickup</p>
                    </div>
                </div>

                <!-- Vehicle loaded -->
                <div class="timeline-item {{ in_array($towRequest->status, ['vehicle_loaded', 'in_transit_to_dropoff', 'delivered']) ? 'completed' : (in_array($towRequest->status, ['arrived_at_pickup', 'customer_verified']) ? 'current' : 'upcoming') }}">
                    <div class="w-10 h-10 {{ in_array($towRequest->status, ['vehicle_loaded', 'in_transit_to_dropoff', 'delivered']) ? 'bg-green-600' : (in_array($towRequest->status, ['arrived_at_pickup', 'customer_verified']) ? 'bg-blue-600' : 'bg-gray-400') }} rounded-full flex items-center justify-center text-white">
                        @if(in_array($towRequest->status, ['vehicle_loaded', 'in_transit_to_dropoff', 'delivered']))
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        @elseif(in_array($towRequest->status, ['arrived_at_pickup', 'customer_verified']))
                            <div class="w-3 h-3 bg-white rounded-full pulse-dot"></div>
                        @else
                            <span class="text-sm">4</span>
                        @endif
                    </div>
                    <div>
                        <h3 class="font-medium text-gray-900">Vehicle Loaded</h3>
                        <p class="text-gray-600 text-sm">Your vehicle is secured on the tow truck</p>
                        @if($towRequest->actual_pickup_time)
                            <p class="text-gray-500 text-xs">Picked up: {{ $towRequest->actual_pickup_time->format('H:i') }}</p>
                        @endif
                    </div>
                </div>

                <!-- On the way to service center -->
                <div class="timeline-item {{ in_array($towRequest->status, ['in_transit_to_dropoff', 'delivered']) ? 'completed' : ($towRequest->status === 'vehicle_loaded' ? 'current' : 'upcoming') }}">
                    <div class="w-10 h-10 {{ in_array($towRequest->status, ['in_transit_to_dropoff', 'delivered']) ? 'bg-green-600' : ($towRequest->status === 'vehicle_loaded' ? 'bg-blue-600' : 'bg-gray-400') }} rounded-full flex items-center justify-center text-white">
                        @if(in_array($towRequest->status, ['in_transit_to_dropoff', 'delivered']))
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        @elseif($towRequest->status === 'vehicle_loaded')
                            <div class="w-3 h-3 bg-white rounded-full pulse-dot"></div>
                        @else
                            <span class="text-sm">5</span>
                        @endif
                    </div>
                    <div>
                        <h3 class="font-medium text-gray-900">Heading to Service Center</h3>
                        <p class="text-gray-600 text-sm">Vehicle being transported</p>
                    </div>
                </div>

                <!-- Delivered -->
                <div class="timeline-item {{ $towRequest->status === 'delivered' ? 'completed' : ($towRequest->status === 'in_transit_to_dropoff' ? 'current' : 'upcoming') }}">
                    <div class="w-10 h-10 {{ $towRequest->status === 'delivered' ? 'bg-green-600' : ($towRequest->status === 'in_transit_to_dropoff' ? 'bg-blue-600' : 'bg-gray-400') }} rounded-full flex items-center justify-center text-white">
                        @if($towRequest->status === 'delivered')
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        @elseif($towRequest->status === 'in_transit_to_dropoff')
                            <div class="w-3 h-3 bg-white rounded-full pulse-dot"></div>
                        @else
                            <span class="text-sm">6</span>
                        @endif
                    </div>
                    <div>
                        <h3 class="font-medium text-gray-900">Delivered to Service Center</h3>
                        <p class="text-gray-600 text-sm">Vehicle ready for repair</p>
                        @if($towRequest->actual_delivery_time)
                            <p class="text-gray-500 text-xs">Delivered: {{ $towRequest->actual_delivery_time->format('H:i') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Service Center Info -->
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <h2 class="text-lg font-bold mb-4">Service Center Details</h2>
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 bg-green-600 rounded-full flex items-center justify-center text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="font-medium text-gray-900">{{ $towRequest->claim->serviceCenter->legal_name }}</h3>
                    <p class="text-gray-600 text-sm">{{ $towRequest->claim->serviceCenter->center_address }}</p>
                    <p class="text-gray-600 text-sm">{{ $towRequest->claim->serviceCenter->formatted_phone }}</p>
                    @if($towRequest->claim->serviceCenter->center_location_lat)
                        <a href="https://maps.google.com/?q={{ $towRequest->claim->serviceCenter->center_location_lat }},{{ $towRequest->claim->serviceCenter->center_location_lng }}" 
                           target="_blank" 
                           class="inline-flex items-center gap-1 text-green-600 text-sm font-medium mt-2 hover:underline">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            </svg>
                            View Location
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        let map, driverMarker;
        const requestCode = '{{ $towRequest->request_code }}';

        // Initialize map
        function initMap() {
            if (!document.getElementById('map')) return;
            
            map = L.map('map').setView([30.0444, 31.2357], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            // Add pickup location marker
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

            // Add service center marker
            @if($towRequest->dropoff_location_lat)
            L.marker([{{ $towRequest->dropoff_location_lat }}, {{ $towRequest->dropoff_location_lng }}], {
                icon: L.divIcon({
                    className: 'custom-div-icon',
                    html: '<div style="background-color: #059669; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center;"><span style="color: white; font-size: 12px;">S</span></div>',
                    iconSize: [30, 30],
                    iconAnchor: [15, 15]
                })
            }).addTo(map).bindPopup('Service Center');
            @endif

            // Add driver location if available
            @if($latestTracking)
            updateDriverLocation({{ $latestTracking->driver_lat }}, {{ $latestTracking->driver_lng }});
            @endif
        }

        // Update driver location on map
        function updateDriverLocation(lat, lng) {
            if (!map) return;
            
            if (driverMarker) {
                map.removeLayer(driverMarker);
            }
            
            driverMarker = L.marker([lat, lng], {
                icon: L.divIcon({
                    className: 'custom-div-icon',
                    html: '<div style="background-color: #2563EB; width: 25px; height: 25px; border-radius: 50%; border: 3px solid white; box-shadow: 0 0 10px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center;"><span style="color: white; font-size: 10px;">🚛</span></div>',
                    iconSize: [25, 25],
                    iconAnchor: [12, 12]
                })
            }).addTo(map).bindPopup('Driver Location');
            
            map.setView([lat, lng], 15);
        }

        // Refresh driver location
        function refreshLocation() {
            fetch(`/track/customer/${requestCode}/data`)
            .then(response => response.json())
            .then(data => {
                if (data.latest_tracking) {
                    updateDriverLocation(data.latest_tracking.lat, data.latest_tracking.lng);
                    document.getElementById('lastUpdate').textContent = `Last updated: ${new Date().toLocaleString()}`;
                }
            })
            .catch(error => {
                console.error('Error refreshing location:', error);
            });
        }

        // Auto-refresh every 30 seconds
        setInterval(() => {
            const trackingStatuses = ['in_transit_to_pickup', 'in_transit_to_dropoff'];
            if (trackingStatuses.includes('{{ $towRequest->status }}')) {
                refreshLocation();
            }
        }, 30000);

        // Initialize map when page loads
        document.addEventListener('DOMContentLoaded', function() {
            initMap();
        });
    </script>
</body>
</html>