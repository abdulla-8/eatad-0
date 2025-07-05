<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Driver Tracking - {{ $towRequest->request_code }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'cairo': ['Cairo', 'sans-serif'],
                        'inter': ['Inter', 'sans-serif'],
                    },
                    colors: {
                        gold: {
                            400: '#FFDD57',
                            500: '#FFDD57',
                            600: '#e6c64d',
                        },
                        dark: {
                            900: '#191919',
                            800: '#2d2d2d',
                            700: '#3a3a3a'
                        },
                        success: '#038A00',
                        danger: '#DB3B21',
                        info: '#3C8DBC'
                    }
                }
            }
        }
    </script>
    <style>
        * { font-family: 'Inter', sans-serif; }
        
        .status-button {
            @apply px-4 py-3 rounded-lg font-semibold text-white transition-all duration-200 border-2 shadow-md;
            min-height: 50px;
        }
        
        .status-button.current {
            @apply bg-info hover:bg-blue-600 border-info hover:border-blue-600 hover:shadow-lg cursor-pointer;
            box-shadow: 0 4px 12px rgba(60, 141, 188, 0.3);
        }
        
        .status-button.completed {
            @apply bg-success border-success cursor-not-allowed;
            box-shadow: 0 2px 8px rgba(3, 138, 0, 0.2);
        }
        
        .status-button.upcoming {
            @apply bg-gray-400 border-gray-400 cursor-not-allowed opacity-70;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
        }

        .glass-card {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        }

        .pulse-dot {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        .gradient-header {
            background: linear-gradient(135deg, #191919 0%, #2d2d2d 50%, #3a3a3a 100%);
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .page-bg {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            min-height: 100vh;
        }

        .success-message {
            background: linear-gradient(135deg, #038A00 0%, #059669 100%);
            animation: slideIn 0.3s ease-out;
        }

        .error-message {
            background: linear-gradient(135deg, #DB3B21 0%, #dc2626 100%);
            animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        .icon-gradient-gold { background: linear-gradient(135deg, #FFDD57 0%, #e6c64d 100%); }
        .icon-gradient-info { background: linear-gradient(135deg, #3C8DBC 0%, #2980b9 100%); }
        .icon-gradient-success { background: linear-gradient(135deg, #038A00 0%, #059669 100%); }
        .icon-gradient-danger { background: linear-gradient(135deg, #DB3B21 0%, #dc2626 100%); }
        .icon-gradient-dark { background: linear-gradient(135deg, #2d2d2d 0%, #3a3a3a 100%); }

        .location-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.9) 0%, rgba(248, 250, 252, 0.9) 100%);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .verification-input {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 2px solid #FFDD57;
            box-shadow: 0 0 15px rgba(255, 221, 87, 0.2);
        }

        .verification-input:focus {
            border-color: #e6c64d;
            box-shadow: 0 0 20px rgba(255, 221, 87, 0.4);
        }
    </style>
</head>
<body class="page-bg">
    
    <!-- Header Section -->
    <div class="gradient-header text-white py-6 mb-6">
        <div class="container mx-auto px-4 max-w-5xl">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-gold-500/20 backdrop-blur-lg rounded-2xl flex items-center justify-center border border-gold-400/30">
                        <svg class="w-7 h-7 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold mb-1 text-gold-400">Request #{{ $towRequest->request_code }}</h1>
                        <p class="text-white/80 text-sm">Driver Tracking Dashboard</p>
                    </div>
                </div>
                
                <!-- Current Status Badge -->
                <div class="bg-white/10 backdrop-blur-lg rounded-xl px-4 py-2 border border-white/20">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 bg-gold-400 rounded-full pulse-dot"></div>
                        <span class="font-semibold text-sm text-gold-400" id="currentStatus">{{ $towRequest->status_badge['text'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 pb-6 max-w-5xl">
        
        <!-- Trip Information Cards -->
        <div class="grid lg:grid-cols-2 gap-6 mb-6">
            <!-- Customer Info -->
            <div class="glass-card rounded-2xl p-6 card-hover">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 icon-gradient-info rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h2 class="text-lg font-bold text-dark-800">Customer Information</h2>
                </div>
                <div class="space-y-3">
                    <div>
                        <p class="text-gray-600 text-xs font-medium mb-1 uppercase tracking-wide">Full Name</p>
                        <p class="text-dark-800 font-semibold">{{ $towRequest->claim->insuranceUser->full_name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-xs font-medium mb-1 uppercase tracking-wide">Phone Number</p>
                        <p class="text-dark-800 font-semibold">{{ $towRequest->claim->insuranceUser->formatted_phone }}</p>
                    </div>
                </div>
            </div>

            <!-- Vehicle Info -->
            <div class="glass-card rounded-2xl p-6 card-hover">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 icon-gradient-dark rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </div>
                    <h2 class="text-lg font-bold text-dark-800">Vehicle Information</h2>
                </div>
                <div class="space-y-3">
                    <div>
                        <p class="text-gray-600 text-xs font-medium mb-1 uppercase tracking-wide">Plate Number</p>
                        <p class="text-dark-800 font-semibold">{{ $towRequest->claim->vehicle_plate_number ?: $towRequest->claim->chassis_number }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-xs font-medium mb-1 uppercase tracking-wide">Claim Number</p>
                        <p class="text-dark-800 font-semibold">{{ $towRequest->claim->claim_number }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Locations Section -->
        <div class="glass-card rounded-2xl p-6 mb-6 card-hover">
            <h2 class="text-lg font-bold text-dark-800 mb-4 flex items-center gap-3">
                <div class="w-8 h-8 icon-gradient-gold rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    </svg>
                </div>
                Trip Locations
            </h2>
            
            <div class="grid lg:grid-cols-2 gap-4">
                <!-- Pickup Location -->
                <div class="location-card rounded-xl p-4 border-l-4 border-danger">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 icon-gradient-danger rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-bold text-dark-800 mb-1">Pickup Location</h3>
                            <p class="text-gray-700 text-sm mb-2">{{ $towRequest->pickup_location_address }}</p>
                            @if($towRequest->pickup_location_lat)
                                <a href="https://maps.google.com/?q={{ $towRequest->pickup_location_lat }},{{ $towRequest->pickup_location_lng }}" 
                                   target="_blank" 
                                   class="inline-flex items-center gap-1 bg-danger text-white px-3 py-1 rounded-lg text-xs font-medium hover:bg-red-700 transition-colors">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                    </svg>
                                    Open in Maps
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Delivery Location -->
                <div class="location-card rounded-xl p-4 border-l-4 border-success">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 icon-gradient-success rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-bold text-dark-800 mb-1">Delivery Location</h3>
                            <p class="text-gray-700 text-sm mb-2">{{ $towRequest->dropoff_location_address }}</p>
                            @if($towRequest->dropoff_location_lat)
                                <a href="https://maps.google.com/?q={{ $towRequest->dropoff_location_lat }},{{ $towRequest->dropoff_location_lng }}" 
                                   target="_blank" 
                                   class="inline-flex items-center gap-1 bg-success text-white px-3 py-1 rounded-lg text-xs font-medium hover:bg-green-700 transition-colors">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                    </svg>
                                    Open in Maps
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Updates Section -->
        <div class="glass-card rounded-2xl p-6 mb-6 card-hover">
            <h2 class="text-lg font-bold text-dark-800 mb-4 flex items-center gap-3">
                <div class="w-8 h-8 icon-gradient-info rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                Update Trip Status
            </h2>
            
            <div class="space-y-3">
                <button onclick="updateStatus('in_transit_to_pickup')" 
                        class="w-full status-button {{ $towRequest->status === 'assigned' ? 'current' : ($towRequest->status === 'in_transit_to_pickup' ? 'completed' : 'upcoming') }}" 
                        {{ in_array($towRequest->status, ['in_transit_to_pickup', 'arrived_at_pickup', 'customer_verified', 'vehicle_loaded', 'in_transit_to_dropoff', 'delivered']) ? 'disabled' : '' }}>
                    <div class="flex items-center justify-center gap-2">
                        <span class="text-lg">🚗</span>
                        <span>On the way to pickup location</span>
                    </div>
                </button>
                
                <button onclick="updateStatus('arrived_at_pickup')" 
                        class="w-full status-button {{ $towRequest->status === 'in_transit_to_pickup' ? 'current' : (in_array($towRequest->status, ['arrived_at_pickup', 'customer_verified', 'vehicle_loaded', 'in_transit_to_dropoff', 'delivered']) ? 'completed' : 'upcoming') }}" 
                        {{ !in_array($towRequest->status, ['in_transit_to_pickup']) ? 'disabled' : '' }}>
                    <div class="flex items-center justify-center gap-2">
                        <span class="text-lg">📍</span>
                        <span>Arrived at pickup location</span>
                    </div>
                </button>
                
                <button onclick="updateStatus('vehicle_loaded')" 
                        class="w-full status-button {{ $towRequest->status === 'customer_verified' ? 'current' : (in_array($towRequest->status, ['vehicle_loaded', 'in_transit_to_dropoff', 'delivered']) ? 'completed' : 'upcoming') }}" 
                        {{ !in_array($towRequest->status, ['customer_verified']) ? 'disabled' : '' }}>
                    <div class="flex items-center justify-center gap-2">
                        <span class="text-lg">🔧</span>
                        <span>Vehicle loaded on tow truck</span>
                    </div>
                </button>
                
                <button onclick="updateStatus('in_transit_to_dropoff')" 
                        class="w-full status-button {{ $towRequest->status === 'vehicle_loaded' ? 'current' : (in_array($towRequest->status, ['in_transit_to_dropoff', 'delivered']) ? 'completed' : 'upcoming') }}" 
                        {{ !in_array($towRequest->status, ['vehicle_loaded']) ? 'disabled' : '' }}>
                    <div class="flex items-center justify-center gap-2">
                        <span class="text-lg">🚛</span>
                        <span>On the way to service center</span>
                    </div>
                </button>
                
                <button onclick="updateStatus('delivered')" 
                        class="w-full status-button {{ $towRequest->status === 'in_transit_to_dropoff' ? 'current' : ($towRequest->status === 'delivered' ? 'completed' : 'upcoming') }}" 
                        {{ !in_array($towRequest->status, ['in_transit_to_dropoff']) ? 'disabled' : '' }}>
                    <div class="flex items-center justify-center gap-2">
                        <span class="text-lg">✅</span>
                        <span>Delivered to service center</span>
                    </div>
                </button>
            </div>
        </div>

        <!-- Customer Verification -->
        @if($towRequest->status === 'arrived_at_pickup')
        <div class="glass-card rounded-2xl p-6 mb-6 border-l-4 border-gold-400 card-hover">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 icon-gradient-gold rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-dark-800">Customer Verification Required</h2>
                    <p class="text-gray-600 text-sm">Ask the customer for their 5-digit verification code</p>
                </div>
            </div>
            
            <div class="flex gap-3">
                <input type="text" id="customerCode" placeholder="Enter 5-digit code" maxlength="5" 
                       class="flex-1 px-4 py-3 verification-input rounded-lg focus:outline-none font-semibold text-center tracking-widest">
                <button onclick="verifyCustomerCode()" 
                        class="px-6 py-3 bg-gold-500 text-dark-800 rounded-lg font-semibold hover:bg-gold-600 transition-colors">
                    Verify Customer
                </button>
            </div>
        </div>
        @endif

        <!-- Service Center Code -->
        @if(in_array($towRequest->status, ['customer_verified', 'vehicle_loaded', 'in_transit_to_dropoff', 'delivered']))
        <div class="glass-card rounded-2xl p-6 mb-6 border-l-4 border-success card-hover">
            <div class="text-center">
                <div class="w-12 h-12 icon-gradient-success rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-dark-800 mb-2">Service Center Delivery Code</h2>
                <p class="text-gray-600 text-sm mb-4">Give this code to the service center when delivering</p>
                <div class="bg-gradient-to-r from-success/10 to-green-100 border-2 border-success rounded-2xl p-6">
                    <span class="text-3xl font-bold text-success tracking-wider">{{ $towRequest->service_center_verification_code }}</span>
                </div>
            </div>
        </div>
        @endif

        <!-- Live Location Tracking -->
        <div class="glass-card rounded-2xl p-6 card-hover">
            <h2 class="text-lg font-bold text-dark-800 mb-4 flex items-center gap-3">
                <div class="w-8 h-8 icon-gradient-info rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    </svg>
                </div>
                Live Location Tracking
            </h2>
            
            <div class="space-y-4">
                <button onclick="getCurrentLocation()" 
                        class="w-full px-4 py-3 bg-info text-white rounded-lg font-semibold hover:bg-blue-600 transition-colors flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    </svg>
                    Update My Location
                </button>
                
                <div id="map" style="height: 300px;" class="rounded-xl overflow-hidden border"></div>
                <div id="locationStatus" class="text-center text-gray-600 bg-gray-50 rounded-lg p-3 text-sm"></div>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    <div id="messageContainer" class="fixed top-4 right-4 z-50 space-y-2"></div>

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
                    html: '<div style="background: linear-gradient(135deg, #DB3B21, #dc2626); width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 2px solid white; box-shadow: 0 2px 8px rgba(219, 59, 33, 0.3);"><span style="color: white; font-size: 12px; font-weight: bold;">P</span></div>',
                    iconSize: [30, 30],
                    iconAnchor: [15, 15]
                })
            }).addTo(map).bindPopup('<div style="text-align: center; font-weight: bold;">Pickup Location</div>');
            @endif

            @if($towRequest->dropoff_location_lat)
            L.marker([{{ $towRequest->dropoff_location_lat }}, {{ $towRequest->dropoff_location_lng }}], {
                icon: L.divIcon({
                    className: 'custom-div-icon',
                    html: '<div style="background: linear-gradient(135deg, #038A00, #059669); width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 2px solid white; box-shadow: 0 2px 8px rgba(3, 138, 0, 0.3);"><span style="color: white; font-size: 12px; font-weight: bold;">D</span></div>',
                    iconSize: [30, 30],
                    iconAnchor: [15, 15]
                })
            }).addTo(map).bindPopup('<div style="text-align: center; font-weight: bold;">Delivery Location</div>');
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
                    html: '<div style="background: linear-gradient(135deg, #3C8DBC, #2980b9); width: 20px; height: 20px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 8px rgba(60, 141, 188, 0.4); animation: pulse 2s infinite;"></div>',
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
            div.className = `px-4 py-3 rounded-lg shadow-lg text-white font-semibold transform transition-all duration-300 ${
                type === 'success' ? 'success-message' : 'error-message'
            }`;
            div.textContent = message;
            container.appendChild(div);
            
            setTimeout(() => {
                div.style.transform = 'translateX(100%)';
                setTimeout(() => div.remove(), 300);
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
