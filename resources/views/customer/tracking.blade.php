<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Track Your Tow Request - {{ $towRequest->request_code }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link
        href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
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
        * {
            font-family: 'Inter', sans-serif;
        }
    .creative-timeline {
        position: relative;
        padding: 2rem 0;
    }

    .timeline-step {
        position: relative;
        margin-bottom: 3rem;
        opacity: 0;
        animation: slideInFromLeft 0.8s ease-out forwards;
    }

    .timeline-step:nth-child(even) {
        animation: slideInFromRight 0.8s ease-out forwards;
    }

    .timeline-step:nth-child(1) { animation-delay: 0.1s; }
    .timeline-step:nth-child(2) { animation-delay: 0.3s; }
    .timeline-step:nth-child(3) { animation-delay: 0.5s; }
    .timeline-step:nth-child(4) { animation-delay: 0.7s; }
    .timeline-step:nth-child(5) { animation-delay: 0.9s; }

    @keyframes slideInFromLeft {
        from {
            opacity: 0;
            transform: translateX(-100px) rotate(-10deg);
        }
        to {
            opacity: 1;
            transform: translateX(0) rotate(0deg);
        }
    }

    @keyframes slideInFromRight {
        from {
            opacity: 0;
            transform: translateX(100px) rotate(10deg);
        }
        to {
            opacity: 1;
            transform: translateX(0) rotate(0deg);
        }
    }

    .step-card {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.9) 0%, rgba(248, 250, 252, 0.9) 100%);
        backdrop-filter: blur(15px);
        border-radius: 20px;
        padding: 1.5rem;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        border: 2px solid transparent;
        transition: all 0.4s ease;
        position: relative;
        overflow: hidden;
    }

    .step-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1), transparent);
        transform: translateX(-100%);
        transition: transform 0.6s;
    }

    .step-card:hover::before {
        transform: translateX(100%);
    }

    .step-card.completed {
        border-color: #038A00;
        background: linear-gradient(135deg, rgba(3, 138, 0, 0.1) 0%, rgba(5, 150, 105, 0.05) 100%);
        transform: scale(1.02);
    }

    .step-card.current {
        border-color: #3C8DBC;
        background: linear-gradient(135deg, rgba(60, 141, 188, 0.1) 0%, rgba(41, 128, 185, 0.05) 100%);
        transform: scale(1.05);
        box-shadow: 0 12px 40px rgba(60, 141, 188, 0.2);
    }

    .step-card.upcoming {
        border-color: #e5e7eb;
        opacity: 0.7;
    }

    .step-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        position: relative;
        transition: all 0.4s ease;
    }

    .step-icon.completed {
        background: linear-gradient(135deg, #038A00 0%, #059669 100%);
        box-shadow: 0 8px 25px rgba(3, 138, 0, 0.3);
    }

    .step-icon.current {
        background: linear-gradient(135deg, #3C8DBC 0%, #2980b9 100%);
        box-shadow: 0 8px 25px rgba(60, 141, 188, 0.4);
        animation: currentPulse 2s infinite;
    }

    .step-icon.upcoming {
        background: linear-gradient(135deg, #9ca3af 0%, #6b7280 100%);
    }

    @keyframes currentPulse {
        0%, 100% {
            transform: scale(1);
            box-shadow: 0 8px 25px rgba(60, 141, 188, 0.4);
        }
        50% {
            transform: scale(1.1);
            box-shadow: 0 12px 35px rgba(60, 141, 188, 0.6);
        }
    }

    .connecting-line {
        position: absolute;
        top: 50%;
        left: 50%;
        width: 200px;
        height: 4px;
        background: linear-gradient(90deg, transparent, #e5e7eb, transparent);
        transform: translateX(-50%) translateY(-50%);
        z-index: -1;
    }

    .connecting-line.completed {
        background: linear-gradient(90deg, transparent, #038A00, transparent);
    }

    .floating-emoji {
        font-size: 2.5rem;
        animation: float 3s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-10px) rotate(5deg); }
    }

    .pulse-dot {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }

    .glass-card {
        backdrop-filter: blur(10px);
        background: rgba(255, 255, 255, 0.95);
        border: 1px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
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

    .icon-gradient-gold { background: linear-gradient(135deg, #FFDD57 0%, #e6c64d 100%); }
    .icon-gradient-info { background: linear-gradient(135deg, #3C8DBC 0%, #2980b9 100%); }
    .icon-gradient-success { background: linear-gradient(135deg, #038A00 0%, #059669 100%); }
    .icon-gradient-danger { background: linear-gradient(135deg, #DB3B21 0%, #dc2626 100%); }
    .icon-gradient-dark { background: linear-gradient(135deg, #2d2d2d 0%, #3a3a3a 100%); }

    .verification-card {
        background: linear-gradient(135deg, rgba(255, 221, 87, 0.1) 0%, rgba(255, 221, 87, 0.05) 100%);
        border: 2px solid #FFDD57;
        box-shadow: 0 4px 20px rgba(255, 221, 87, 0.2);
    }

    .floating-animation {
        animation: float 3s ease-in-out infinite;
    }

    .zigzag-timeline {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
        max-width: 800px;
        margin: 0 auto;
    }

    .zigzag-timeline .timeline-step:nth-child(even) {
        grid-column: 2;
        margin-top: 2rem;
    }

    .zigzag-timeline .timeline-step:nth-child(odd) {
        grid-column: 1;
    }

    @media (max-width: 768px) {
        .zigzag-timeline {
            grid-template-columns: 1fr;
        }
        
        .zigzag-timeline .timeline-step:nth-child(even) {
            grid-column: 1;
            margin-top: 0;
        }
    }
</style>
</head> <body class="page-bg">
    <!-- Header Section -->
<div class="gradient-header text-white py-6 mb-6">
    <div class="container mx-auto px-4 max-w-5xl">
        <div class="text-center">
            <div class="w-16 h-16 bg-gold-500/20 backdrop-blur-lg rounded-full flex items-center justify-center text-gold-400 mx-auto mb-4 floating-animation border-2 border-gold-400/30">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold mb-2 text-gold-400">Track Your Tow Request</h1>
            <p class="text-white/80">Request #{{ $towRequest->request_code }}</p>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 pb-6 max-w-5xl">
    
    <!-- Current Status -->
    <div class="glass-card rounded-2xl p-6 mb-6 card-hover">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 icon-gradient-info rounded-full flex items-center justify-center">
                <div class="w-3 h-3 bg-white rounded-full pulse-dot"></div>
            </div>
            <div>
                <h2 class="text-lg font-bold text-dark-800">Current Status</h2>
                <p class="text-info font-semibold" id="currentStatus">{{ $towRequest->status_badge['text'] }}</p>
                <p class="text-gray-500 text-sm" id="lastUpdate">Last updated: {{ now()->format('M d, Y H:i') }}</p>
            </div>
        </div>
    </div>

    <!-- Vehicle & Service Info -->
    <div class="glass-card rounded-2xl p-6 mb-6 card-hover">
        <h2 class="text-lg font-bold text-dark-800 mb-4 flex items-center gap-3">
            <div class="w-8 h-8 icon-gradient-dark rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            Service Information
        </h2>
        <div class="grid md:grid-cols-2 gap-6">
            <div>
                <h3 class="font-semibold text-dark-800 mb-2">Your Vehicle</h3>
                <p class="text-gray-700 font-medium">{{ $towRequest->claim->vehicle_plate_number ?: $towRequest->claim->chassis_number }}</p>
                <p class="text-gray-600 text-sm">Claim: {{ $towRequest->claim->claim_number }}</p>
            </div>
            @if($providerInfo)
            <div>
                <h3 class="font-semibold text-dark-800 mb-2">Service Provider</h3>
                <p class="text-gray-700 font-medium">{{ $providerInfo['name'] }}</p>
                <p class="text-gray-600 text-sm">{{ $providerInfo['phone'] }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Customer Verification -->
    @if($towRequest->status === 'arrived_at_pickup' && $towRequest->customer_verification_code)
    <div class="verification-card rounded-2xl p-6 mb-6 card-hover">
        <div class="flex items-center gap-4 mb-4">
            <div class="w-12 h-12 icon-gradient-gold rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
            </div>
            <div>
                <h2 class="text-lg font-bold text-dark-800">🚛 Driver Has Arrived!</h2>
                <p class="text-gray-700">Your driver is at the pickup location</p>
            </div>
        </div>
        
        <div class="bg-white border-2 border-gold-400 rounded-xl p-6 text-center mb-4">
            <p class="text-gold-600 text-sm font-semibold mb-2">Your Verification Code</p>
            <span class="text-4xl font-bold text-gold-600 tracking-wider">{{ $towRequest->customer_verification_code }}</span>
        </div>
        
        <div class="bg-gold-50 rounded-xl p-4">
            <p class="text-dark-800 text-sm">
                <strong>Instructions:</strong> Show this 5-digit code to your driver. They will enter it to confirm your identity before loading your vehicle.
            </p>
        </div>
    </div>
    @endif

    <!-- Live Tracking Map -->
    @if($latestTracking && in_array($towRequest->status, ['in_transit_to_pickup', 'in_transit_to_dropoff']))
    <div class="glass-card rounded-2xl p-6 mb-6 card-hover">
        <h2 class="text-lg font-bold text-dark-800 mb-4 flex items-center gap-3">
            <div class="w-8 h-8 icon-gradient-info rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                </svg>
            </div>
            🗺️ Live Tracking
        </h2>
        <div id="map" style="height: 300px;" class="rounded-xl border-2 border-gray-200 mb-4 overflow-hidden"></div>
        <div class="text-center">
            <button onclick="refreshLocation()" 
                    class="px-6 py-3 bg-info text-white rounded-xl font-semibold hover:bg-blue-600 transition-colors flex items-center gap-2 mx-auto">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Refresh Location
            </button>
        </div>
    </div>
    @endif

    <!-- Creative Timeline -->
    <div class="glass-card rounded-2xl p-8 mb-6 card-hover">
        <h2 class="text-lg font-bold text-dark-800 mb-8 flex items-center gap-3">
            <div class="w-8 h-8 icon-gradient-gold rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            Progress Timeline
        </h2>
        
        <div class="creative-timeline">
            <div class="zigzag-timeline">
                
                <!-- Step 1: Request Accepted -->
                <div class="timeline-step">
                    <div class="step-card completed">
                        <div class="step-icon completed">
                            <span class="floating-emoji">✅</span>
                        </div>
                        <div class="text-center">
                            <h3 class="font-bold text-dark-800 mb-2">Request Accepted</h3>
                            <p class="text-gray-600 text-sm">Tow service provider assigned</p>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Driver On The Way -->
                <div class="timeline-step">
                    <div class="step-card {{ in_array($towRequest->status, ['in_transit_to_pickup', 'arrived_at_pickup', 'customer_verified', 'vehicle_loaded', 'in_transit_to_dropoff', 'delivered']) ? 'completed' : ($towRequest->status === 'assigned' ? 'current' : 'upcoming') }}">
                        <div class="step-icon {{ in_array($towRequest->status, ['in_transit_to_pickup', 'arrived_at_pickup', 'customer_verified', 'vehicle_loaded', 'in_transit_to_dropoff', 'delivered']) ? 'completed' : ($towRequest->status === 'assigned' ? 'current' : 'upcoming') }}">
                            <span class="floating-emoji">🚗</span>
                        </div>
                        <div class="text-center">
                            <h3 class="font-bold text-dark-800 mb-2">Driver On The Way</h3>
                            <p class="text-gray-600 text-sm">Heading to your location</p>
                            @if($towRequest->estimated_pickup_time)
                                <p class="text-gray-500 text-xs mt-1">ETA: {{ $towRequest->estimated_pickup_time->format('H:i') }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Step 3: Driver Arrived -->
                <div class="timeline-step">
                    <div class="step-card {{ in_array($towRequest->status, ['arrived_at_pickup', 'customer_verified', 'vehicle_loaded', 'in_transit_to_dropoff', 'delivered']) ? 'completed' : ($towRequest->status === 'in_transit_to_pickup' ? 'current' : 'upcoming') }}">
                        <div class="step-icon {{ in_array($towRequest->status, ['arrived_at_pickup', 'customer_verified', 'vehicle_loaded', 'in_transit_to_dropoff', 'delivered']) ? 'completed' : ($towRequest->status === 'in_transit_to_pickup' ? 'current' : 'upcoming') }}">
                            <span class="floating-emoji">📍</span>
                        </div>
                        <div class="text-center">
                            <h3 class="font-bold text-dark-800 mb-2">Driver Arrived</h3>
                            <p class="text-gray-600 text-sm">Ready for vehicle pickup</p>
                        </div>
                    </div>
                </div>

                <!-- Step 4: Vehicle Loaded -->
                <div class="timeline-step">
                    <div class="step-card {{ in_array($towRequest->status, ['vehicle_loaded', 'in_transit_to_dropoff', 'delivered']) ? 'completed' : (in_array($towRequest->status, ['arrived_at_pickup', 'customer_verified']) ? 'current' : 'upcoming') }}">
                        <div class="step-icon {{ in_array($towRequest->status, ['vehicle_loaded', 'in_transit_to_dropoff', 'delivered']) ? 'completed' : (in_array($towRequest->status, ['arrived_at_pickup', 'customer_verified']) ? 'current' : 'upcoming') }}">
                            <span class="floating-emoji">🔧</span>
                        </div>
                        <div class="text-center">
                            <h3 class="font-bold text-dark-800 mb-2">Vehicle Loaded</h3>
                            <p class="text-gray-600 text-sm">Secured on the tow truck</p>
                            @if($towRequest->actual_pickup_time)
                                <p class="text-gray-500 text-xs mt-1">Picked up: {{ $towRequest->actual_pickup_time->format('H:i') }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Step 5: Heading to Service Center -->
                <div class="timeline-step">
                    <div class="step-card {{ in_array($towRequest->status, ['in_transit_to_dropoff', 'delivered']) ? 'completed' : ($towRequest->status === 'vehicle_loaded' ? 'current' : 'upcoming') }}">
                        <div class="step-icon {{ in_array($towRequest->status, ['in_transit_to_dropoff', 'delivered']) ? 'completed' : ($towRequest->status === 'vehicle_loaded' ? 'current' : 'upcoming') }}">
                            <span class="floating-emoji">🚛</span>
                        </div>
                        <div class="text-center">
                            <h3 class="font-bold text-dark-800 mb-2">Heading to Service Center</h3>
                            <p class="text-gray-600 text-sm">Vehicle being transported</p>
                        </div>
                    </div>
                </div>

                <!-- Step 6: Delivered -->
                <div class="timeline-step">
                    <div class="step-card {{ $towRequest->status === 'delivered' ? 'completed' : ($towRequest->status === 'in_transit_to_dropoff' ? 'current' : 'upcoming') }}">
                        <div class="step-icon {{ $towRequest->status === 'delivered' ? 'completed' : ($towRequest->status === 'in_transit_to_dropoff' ? 'current' : 'upcoming') }}">
                            <span class="floating-emoji">🏁</span>
                        </div>
                        <div class="text-center">
                            <h3 class="font-bold text-dark-800 mb-2">Delivered to Service Center</h3>
                            <p class="text-gray-600 text-sm">Vehicle ready for repair</p>
                            @if($towRequest->actual_delivery_time)
                                <p class="text-gray-500 text-xs mt-1">Delivered: {{ $towRequest->actual_delivery_time->format('H:i') }}</p>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Service Center Info -->
    <div class="glass-card rounded-2xl p-6 card-hover">
        <h2 class="text-lg font-bold text-dark-800 mb-4 flex items-center gap-3">
            <div class="w-8 h-8 icon-gradient-success rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
            Service Center Details
        </h2>
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 icon-gradient-success rounded-full flex items-center justify-center text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
            <div class="flex-1">
                <h3 class="font-semibold text-dark-800">{{ $towRequest->claim->serviceCenter->legal_name }}</h3>
                <p class="text-gray-600 text-sm">{{ $towRequest->claim->serviceCenter->center_address }}</p>
                <p class="text-gray-600 text-sm">{{ $towRequest->claim->serviceCenter->formatted_phone }}</p>
                @if($towRequest->claim->serviceCenter->center_location_lat)
                    <a href="https://maps.google.com/?q={{ $towRequest->claim->serviceCenter->center_location_lat }},{{ $towRequest->claim->serviceCenter->center_location_lng }}" 
                       target="_blank" 
                       class="inline-flex items-center gap-2 text-success text-sm font-semibold mt-2 hover:underline">
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
                html: '<div style="background: linear-gradient(135deg, #DB3B21, #dc2626); width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 3px solid white; box-shadow: 0 4px 15px rgba(219, 59, 33, 0.3);"><span style="color: white; font-size: 12px; font-weight: bold;">P</span></div>',
                iconSize: ,
                iconAnchor: 
            })
        }).addTo(map).bindPopup('<div style="text-align: center; font-weight: bold;">Pickup Location</div>');
        @endif

        // Add service center marker
        @if($towRequest->dropoff_location_lat)
        L.marker([{{ $towRequest->dropoff_location_lat }}, {{ $towRequest->dropoff_location_lng }}], {
            icon: L.divIcon({
                className: 'custom-div-icon',
                html: '<div style="background: linear-gradient(135deg, #038A00, #059669); width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 3px solid white; box-shadow: 0 4px 15px rgba(3, 138, 0, 0.3);"><span style="color: white; font-size: 12px; font-weight: bold;">S</span></div>',
                iconSize: ,
                iconAnchor: 
            })
        }).addTo(map).bindPopup('<div style="text-align: center; font-weight: bold;">Service Center</div>');
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
                html: '<div style="background: linear-gradient(135deg, #3C8DBC, #2980b9); width: 30px; height: 30px; border-radius: 50%; border: 3px solid white; box-shadow: 0 4px 15px rgba(60, 141, 188, 0.4); display: flex; align-items: center; justify-content: center; animation: pulse 2s infinite;"><span style="color: white; font-size: 12px;">🚛</span></div>',
                iconSize: ,
                iconAnchor: 
            })
        }).addTo(map).bindPopup('<div style="text-align: center; font-weight: bold;">Driver Location</div>');
        
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
</body> </html>