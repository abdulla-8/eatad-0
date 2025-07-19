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
                        success: '#10B981',
                        danger: '#EF4444',
                        info: '#3B82F6',
                        warning: '#F59E0B'
                    }
                }
            }
        }
    </script>
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        .page-bg {
            background: linear-gradient(135deg, #1e293b 0%, #334155 50%, #475569 100%);
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }

        .page-bg::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1), transparent);

        }

        .glass-card {
            backdrop-filter: blur(20px);
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 8px 32px rgba(31, 38, 135, 0.37);
        }

        .gradient-header {
            background: linear-gradient(135deg, #1e293b 0%, #334155 50%, #475569 100%);
            position: relative;
            overflow: hidden;
        }

        .gradient-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transform: translateX(-100%);
            animation: shimmer 3s infinite;
        }

        @keyframes shimmer {
            100% {
                transform: translateX(100%);
            }
        }

   /* تحسين Timeline الأفقي */
        .horizontal-timeline {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            position: relative;
            margin: 3rem 0;
            padding: 0 2rem;
        }

        .timeline-progress-bar {
            position: absolute;
            top: 40px; /* تحديد موقع الخط تحت الدوائر */
            left: 10%;
            right: 10%;
            height: 4px;
            background: #e5e7eb;
            border-radius: 2px;
            z-index: 1;
        }

        .timeline-progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #10B981, #059669);
            border-radius: 2px;
            transition: width 1s ease-in-out;
            position: relative;
        }

        .timeline-progress-fill::after {
            content: '';
            position: absolute;
            right: 0;
            top: 50%;
            width: 12px;
            height: 12px;
            background: #10B981;
            border-radius: 50%;
            transform: translateY(-50%);
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.3);
            animation: pulse 2s infinite;
        }

        .timeline-step {
            position: relative;
            z-index: 2;
            text-align: center;
            flex: 1;
            max-width: 140px;
            min-width: 120px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .step-circle {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin-bottom: 20px; /* مسافة بين الدائرة والخط */
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .step-circle.completed {
            background: linear-gradient(135deg, #10B981, #059669);
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
            animation: bounce 0.6s ease-out;
        }

        .step-circle.current {
            background: linear-gradient(135deg, #3B82F6, #1D4ED8);
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.5);
            animation: currentPulse 2s infinite;
        }

        .step-circle.upcoming {
            background: linear-gradient(135deg, #9CA3AF, #6B7280);
            box-shadow: 0 4px 15px rgba(156, 163, 175, 0.3);
        }

        .step-circle::before {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            border-radius: 50%;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .step-circle.current::before {
            opacity: 1;
            animation: rotate 2s linear infinite;
        }

        .step-content {
            margin-top: 30px; /* مسافة من الخط للكلام */
            text-align: center;
        }

        .step-label {
            font-weight: 700;
            font-size: 0.95rem;
            color: #374151;
            margin-bottom: 6px;
            line-height: 1.3;
        }

        .step-time {
            font-size: 0.8rem;
            color: #6B7280;
            font-weight: 500;
        }

        .step-time.completed {
            color: #10B981;
            font-weight: 600;
        }

        .step-time.current {
            color: #3B82F6;
            font-weight: 600;
        }

        @keyframes currentPulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }

        @keyframes bounce {
            0%, 20%, 53%, 80%, 100% {
                transform: translate3d(0,0,0);
            }
            40%, 43% {
                transform: translate3d(0,-8px,0);
            }
            70% {
                transform: translate3d(0,-4px,0);
            }
            90% {
                transform: translate3d(0,-2px,0);
            }
        }

        @keyframes rotate {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }

        @keyframes pulse {
            0%, 100% {
                opacity: 1;
                transform: translateY(-50%) scale(1);
            }
            50% {
                opacity: 0.8;
                transform: translateY(-50%) scale(1.1);
            }
        }

        /* تحسين الاستجابة للموبايل */
        @media (max-width: 768px) {
            .horizontal-timeline {
                flex-direction: column;
                gap: 3rem;
                padding: 0 1rem;
            }

            .timeline-progress-bar {
                top: 0;
                left: 50%;
                right: auto;
                width: 4px;
                height: 100%;
                transform: translateX(-50%);
            }

            .timeline-progress-fill {
                width: 100% !important;
            }

            .timeline-step {
                max-width: none;
                min-width: auto;
                display: flex;
                flex-direction: row;
                align-items: center;
                gap: 2rem;
                text-align: left;
                position: relative;
                padding-left: 2rem;
            }

            .step-circle {
                margin: 0;
                width: 70px;
                height: 70px;
                font-size: 1.8rem;
                flex-shrink: 0;
            }

            .step-content {
                margin-top: 0;
                text-align: left;
                flex: 1;
            }

            .step-label {
                font-size: 1rem;
            }

            .step-time {
                font-size: 0.85rem;
            }
        }

        @media (max-width: 480px) {
            .timeline-step {
                gap: 1.5rem;
                padding-left: 1.5rem;
            }

            .step-circle {
                width: 60px;
                height: 60px;
                font-size: 1.5rem;
            }

            .step-label {
                font-size: 0.9rem;
            }

            .step-time {
                font-size: 0.8rem;
            }
        }
        /* تحسين الخريطة */
        .map-container {
            position: relative;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .map-overlay {
            position: absolute;
            top: 16px;
            left: 16px;
            right: 16px;
            z-index: 1000;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .map-controls {
            display: flex;
            gap: 8px;
        }

        .map-control-btn {
            width: 44px;
            height: 44px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .map-control-btn:hover {
            background: rgba(255, 255, 255, 1);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }

        .status-indicator {
            background: rgba(16, 185, 129, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 12px;
            padding: 8px 16px;
            color: white;
            font-weight: 600;
            font-size: 0.875rem;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        /* تحسين كارت التحقق */
        .verification-card {
            background: linear-gradient(135deg, 
                rgba(255, 221, 87, 0.1) 0%, 
                rgba(255, 193, 7, 0.05) 100%);
            border: 2px solid #FFDD57;
            border-radius: 24px;
            position: relative;
            overflow: hidden;
        }

        .verification-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, 
                transparent, 
                rgba(255, 221, 87, 0.1), 
                transparent);
            animation: sweep 2s ease-in-out infinite;
        }

        @keyframes sweep {
            0% { left: -100%; }
            50% { left: 100%; }
            100% { left: 100%; }
        }

        .verification-code {
            background: linear-gradient(135deg, #FFDD57, #F59E0B);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 800;
            font-size: 3rem;
            letter-spacing: 0.5rem;
            text-shadow: 0 4px 8px rgba(245, 158, 11, 0.3);
        }

        /* تحسين الأزرار */
        .btn-primary {
            background: linear-gradient(135deg, #3B82F6, #1D4ED8);
            border: none;
            border-radius: 16px;
            padding: 14px 28px;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, 
                transparent, 
                rgba(255, 255, 255, 0.2), 
                transparent);
            transition: left 0.5s;
        }

        .btn-primary:hover::before {
            left: 100%;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #1D4ED8, #1E40AF);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.4);
        }

        /* تحسين الأيقونات */
        .floating-icon {
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-10px);
            }
        }

        /* تحسين الاستجابة */
        @media (max-width: 768px) {
            .horizontal-timeline {
                flex-direction: column;
                gap: 2rem;
            }

            .timeline-progress-bar {
                top: 0;
                left: 50%;
                right: auto;
                width: 4px;
                height: 100%;
                transform: translateX(-50%);
            }

            .timeline-progress-fill {
                width: 100% !important;
            }

            .timeline-step {
                max-width: none;
                display: flex;
                align-items: center;
                gap: 1rem;
                text-align: left;
            }

            .step-circle {
                margin: 0;
                width: 60px;
                height: 60px;
                font-size: 1.5rem;
            }

            .verification-code {
                font-size: 2.5rem;
                letter-spacing: 0.3rem;
            }
        }

        /* تأثيرات loading */
        .loading-shimmer {
            background: linear-gradient(90deg, 
                rgba(255, 255, 255, 0.1) 25%, 
                rgba(255, 255, 255, 0.3) 50%, 
                rgba(255, 255, 255, 0.1) 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
        }

        @keyframes shimmer {
            0% {
                background-position: -200% 0;
            }
            100% {
                background-position: 200% 0;
            }
        }

        /* Custom Leaflet Styles */
        .leaflet-control-container {
            display: none;
        }

        .custom-marker {
            border-radius: 50%;
            border: 3px solid white;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }
    </style>
</head>

<body class="page-bg">
    <!-- Header Section -->
    <div class="gradient-header text-white py-8 mb-8 relative z-10">
        <div class="container mx-auto px-4 max-w-6xl">
            <div class="text-center">
                <div class="w-20 h-20 bg-gradient-to-br from-gold-400 to-warning rounded-full flex items-center justify-center text-white mx-auto mb-6 floating-icon shadow-2xl">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                    </svg>
                </div>
                <h1 class="text-4xl font-bold mb-3 text-transparent bg-clip-text bg-gradient-to-r from-white to-gray-300">
                    Track Your Vehicle
                </h1>
                <p class="text-xl text-gray-300 mb-2">Request #{{ $towRequest->request_code }}</p>
                <div class="inline-flex items-center gap-2 bg-white/20 backdrop-blur-lg rounded-full px-4 py-2 mt-4">
                    <div class="w-3 h-3 bg-green-400 rounded-full animate-pulse"></div>
                    <span class="text-sm font-medium">Live Tracking Active</span>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 pb-8 max-w-6xl relative z-10 ">
        
        <!-- Live Map Section -->
        <div class="glass-card rounded-3xl p-8 mb-8 relative overflow-hidden">
            <div class="absolute top-4 right-4 z-10">
                <div class="bg-gradient-to-r from-green-500 to-emerald-600 text-white px-4 py-2 rounded-full text-sm font-semibold shadow-lg">
                    🗺️ Live Location
                </div>
            </div>
            
            <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-3">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                    </svg>
                </div>
                Real-time Driver Location
            </h2>

            <div class="map-container relative">
                <div class="map-overlay">
                    <div class="map-controls">
                        <button class="map-control-btn" onclick="refreshLocation()" title="Refresh Location">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                        </button>
                        <button class="map-control-btn" onclick="centerMap()" title="Center Map">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="status-indicator" id="mapStatus">
                        📍 Live Tracking
                    </div>
                </div>
                <div id="map" style="height: 400px;" class="rounded-2xl overflow-hidden"></div>
            </div>

            <div class="mt-6 flex flex-wrap gap-4 justify-center">
                <div class="flex items-center gap-2 bg-red-50 px-4 py-2 rounded-full border border-red-200">
                    <div class="w-4 h-4 bg-red-500 rounded-full"></div>
                    <span class="text-sm font-medium text-red-700">Pickup Location</span>
                </div>
                <div class="flex items-center gap-2 bg-blue-50 px-4 py-2 rounded-full border border-blue-200">
                    <div class="w-4 h-4 bg-blue-500 rounded-full animate-pulse"></div>
                    <span class="text-sm font-medium text-blue-700">Driver Location</span>
                </div>
                <div class="flex items-center gap-2 bg-green-50 px-4 py-2 rounded-full border border-green-200">
                    <div class="w-4 h-4 bg-green-500 rounded-full"></div>
                    <span class="text-sm font-medium text-green-700">Service Center</span>
                </div>
            </div>
        </div>

        <!-- Enhanced Horizontal Timeline -->
        <div class="glass-card rounded-3xl p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-8 flex items-center gap-3">
                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                Delivery Progress
            </h2>
<div class="horizontal-timeline">
                <!-- Progress Bar -->
                <div class="timeline-progress-bar">
                    <div class="timeline-progress-fill" id="progressFill" style="width: {{ $progressPercentage ?? 20 }}%"></div>
                </div>

                <!-- Step 1: Request Accepted -->
                <div class="timeline-step">
                    <div class="step-circle completed">
                        🎯
                    </div>
                    <div class="step-content">
                        <div class="step-label">Request Accepted</div>
                        <div class="step-time completed">✓ Completed</div>
                    </div>
                </div>

                <!-- Step 2: Driver Assigned -->
                <div class="timeline-step">
                    <div class="step-circle {{ in_array($towRequest->status, ['assigned', 'in_transit_to_pickup', 'arrived_at_pickup', 'customer_verified', 'vehicle_loaded', 'in_transit_to_dropoff', 'delivered']) ? 'completed' : 'current' }}">
                        👨‍🔧
                    </div>
                    <div class="step-content">
                        <div class="step-label">Driver Assigned</div>
                        <div class="step-time {{ in_array($towRequest->status, ['assigned', 'in_transit_to_pickup', 'arrived_at_pickup', 'customer_verified', 'vehicle_loaded', 'in_transit_to_dropoff', 'delivered']) ? 'completed' : 'current' }}">
                            @if(in_array($towRequest->status, ['assigned', 'in_transit_to_pickup', 'arrived_at_pickup', 'customer_verified', 'vehicle_loaded', 'in_transit_to_dropoff', 'delivered']))
                                ✓ Completed
                            @elseif($towRequest->estimated_pickup_time)
                                ETA: {{ $towRequest->estimated_pickup_time->format('H:i') }}
                            @else
                                In Progress
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Step 3: On The Way -->
                <div class="timeline-step">
                    <div class="step-circle {{ in_array($towRequest->status, ['in_transit_to_pickup', 'arrived_at_pickup', 'customer_verified', 'vehicle_loaded', 'in_transit_to_dropoff', 'delivered']) ? 'completed' : ($towRequest->status === 'assigned' ? 'current' : 'upcoming') }}">
                        🚗
                    </div>
                    <div class="step-content">
                        <div class="step-label">On The Way</div>
                        <div class="step-time {{ in_array($towRequest->status, ['in_transit_to_pickup', 'arrived_at_pickup', 'customer_verified', 'vehicle_loaded', 'in_transit_to_dropoff', 'delivered']) ? 'completed' : ($towRequest->status === 'assigned' ? 'current' : '') }}">
                            @if(in_array($towRequest->status, ['in_transit_to_pickup', 'arrived_at_pickup', 'customer_verified', 'vehicle_loaded', 'in_transit_to_dropoff', 'delivered']))
                                ✓ Completed
                            @elseif($towRequest->status === 'assigned')
                                Current
                            @else
                                Pending
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Step 4: Driver Arrived -->
                <div class="timeline-step">
                    <div class="step-circle {{ in_array($towRequest->status, ['arrived_at_pickup', 'customer_verified', 'vehicle_loaded', 'in_transit_to_dropoff', 'delivered']) ? 'completed' : ($towRequest->status === 'in_transit_to_pickup' ? 'current' : 'upcoming') }}">
                        📍
                    </div>
                    <div class="step-content">
                        <div class="step-label">Driver Arrived</div>
                        <div class="step-time {{ in_array($towRequest->status, ['arrived_at_pickup', 'customer_verified', 'vehicle_loaded', 'in_transit_to_dropoff', 'delivered']) ? 'completed' : ($towRequest->status === 'in_transit_to_pickup' ? 'current' : '') }}">
                            @if(in_array($towRequest->status, ['customer_verified', 'vehicle_loaded', 'in_transit_to_dropoff', 'delivered']))
                                ✓ Completed
                            @elseif($towRequest->status === 'arrived_at_pickup')
                                🔴 Waiting for verification
                            @elseif($towRequest->status === 'in_transit_to_pickup')
                                Current
                            @else
                                Pending
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Step 5: Vehicle Loaded -->
                <div class="timeline-step">
                    <div class="step-circle {{ in_array($towRequest->status, ['vehicle_loaded', 'in_transit_to_dropoff', 'delivered']) ? 'completed' : (in_array($towRequest->status, ['arrived_at_pickup', 'customer_verified']) ? 'current' : 'upcoming') }}">
                        🔧
                    </div>
                    <div class="step-content">
                        <div class="step-label">Vehicle Loaded</div>
                        <div class="step-time {{ in_array($towRequest->status, ['vehicle_loaded', 'in_transit_to_dropoff', 'delivered']) ? 'completed' : (in_array($towRequest->status, ['arrived_at_pickup', 'customer_verified']) ? 'current' : '') }}">
                            @if($towRequest->actual_pickup_time)
                                {{ $towRequest->actual_pickup_time->format('H:i') }}
                            @elseif(in_array($towRequest->status, ['vehicle_loaded', 'in_transit_to_dropoff', 'delivered']))
                                ✓ Completed
                            @elseif(in_array($towRequest->status, ['arrived_at_pickup', 'customer_verified']))
                                Current
                            @else
                                Pending
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Step 6: Delivered -->
                <div class="timeline-step">
                    <div class="step-circle {{ $towRequest->status === 'delivered' ? 'completed' : (in_array($towRequest->status, ['vehicle_loaded', 'in_transit_to_dropoff']) ? 'current' : 'upcoming') }}">
                        🏁
                    </div>
                    <div class="step-content">
                        <div class="step-label">Delivered</div>
                        <div class="step-time {{ $towRequest->status === 'delivered' ? 'completed' : (in_array($towRequest->status, ['vehicle_loaded', 'in_transit_to_dropoff']) ? 'current' : '') }}">
                            @if($towRequest->actual_delivery_time)
                                {{ $towRequest->actual_delivery_time->format('H:i') }}
                            @elseif($towRequest->status === 'delivered')
                                ✓ Completed
                            @elseif(in_array($towRequest->status, ['vehicle_loaded', 'in_transit_to_dropoff']))
                                Current
                            @else
                                Pending
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer Verification -->
        @if($towRequest->status === 'arrived_at_pickup' && $towRequest->customer_verification_code)
        <div class="verification-card p-8 mb-8 relative">
            <div class="text-center relative z-10">
                <div class="w-20 h-20 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-full flex items-center justify-center text-white mx-auto mb-6 floating-icon shadow-2xl">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
                
                <h2 class="text-3xl font-bold text-gray-800 mb-4">🚛 Driver Has Arrived!</h2>
                <p class="text-gray-600 text-lg mb-8">Your driver is waiting at the pickup location</p>
                
                <div class="bg-white rounded-2xl p-8 shadow-2xl border-4 border-yellow-300 mb-6">
                    <p class="text-yellow-600 text-sm font-semibold mb-4 uppercase tracking-wider">Your Verification Code</p>
                    <div class="verification-code">{{ $towRequest->customer_verification_code }}</div>
                </div>
                
                <div class="bg-gradient-to-br from-yellow-50 to-orange-50 rounded-2xl p-6 border border-yellow-200">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-full flex items-center justify-center text-white flex-shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-800 mb-2">📋 Instructions</h4>
                            <p class="text-gray-700 text-sm leading-relaxed">
                                <strong>Show this 5-digit code to your driver.</strong> They will enter it to confirm your identity before loading your vehicle onto the tow truck.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Vehicle & Service Information -->
        <div class="grid md:grid-cols-2 gap-8 mb-8">
            <!-- Vehicle Information -->
            <div class="glass-card rounded-3xl p-8">
                <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    🚗 Your Vehicle
                </h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 bg-blue-50 rounded-xl border border-blue-200">
                        <span class="text-gray-600 font-medium">Plate Number</span>
                        <span class="font-bold text-blue-700">{{ $towRequest->claim->vehicle_plate_number ?: 'N/A' }}</span>
                    </div>
                    <div class="flex items-center justify-between p-4 bg-blue-50 rounded-xl border border-blue-200">
                        <span class="text-gray-600 font-medium">Chassis Number</span>
                        <span class="font-bold text-blue-700">{{ $towRequest->claim->chassis_number ?: 'N/A' }}</span>
                    </div>
                    <div class="flex items-center justify-between p-4 bg-blue-50 rounded-xl border border-blue-200">
                        <span class="text-gray-600 font-medium">Claim Number</span>
                        <span class="font-bold text-blue-700">{{ $towRequest->claim->claim_number }}</span>
                    </div>
                </div>
            </div>

            <!-- Service Provider Information -->
            <div class="glass-card rounded-3xl p-8">
                <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    👨‍🔧 Service Provider
                </h3>
                @if($providerInfo)
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 bg-green-50 rounded-xl border border-green-200">
                        <span class="text-gray-600 font-medium">Provider Name</span>
                        <span class="font-bold text-green-700">{{ $providerInfo['name'] }}</span>
                    </div>
                    <div class="flex items-center justify-between p-4 bg-green-50 rounded-xl border border-green-200">
                        <span class="text-gray-600 font-medium">Contact Number</span>
                        <a href="tel:{{ $providerInfo['phone'] }}" class="font-bold text-green-700 hover:text-green-800 transition-colors">
                            {{ $providerInfo['phone'] }}
                        </a>
                    </div>
                    <div class="text-center mt-6">
                        <a href="tel:{{ $providerInfo['phone'] }}" class="btn-primary inline-flex items-center gap-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            Call Driver
                        </a>
                    </div>
                </div>
                @else
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.94-.833-2.664 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <p class="text-gray-500">Driver information will appear once assigned</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Service Center Details -->
        <div class="glass-card rounded-3xl p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-3">
                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                🏢 Destination Service Center
            </h2>
            
            <div class="grid md:grid-cols-2 gap-8">
                <div class="space-y-4">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center text-white flex-shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg text-gray-800 mb-2">{{ $towRequest->claim->serviceCenter->legal_name }}</h3>
                            <p class="text-gray-600 mb-3">{{ $towRequest->claim->serviceCenter->center_address }}</p>
                            <a href="tel:{{ $towRequest->claim->serviceCenter->phone }}" 
                               class="inline-flex items-center gap-2 text-purple-600 font-semibold hover:text-purple-700 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                {{ $towRequest->claim->serviceCenter->phone }}
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center justify-center">
                    @if($towRequest->claim->serviceCenter->center_location_lat)
                        <a href="https://maps.google.com/?q={{ $towRequest->claim->serviceCenter->center_location_lat }},{{ $towRequest->claim->serviceCenter->center_location_lng }}" 
                           target="_blank" 
                           class="btn-primary inline-flex items-center gap-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            View on Google Maps
                        </a>
                    @else
                        <div class="text-center py-4">
                            <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                </svg>
                            </div>
                            <p class="text-gray-500 text-sm">Location not available</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Status Updates & Refresh -->
        <div class="glass-card rounded-3xl p-8 text-center">
            <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center justify-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl flex items-center justify-center text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                </div>
                📱 Live Updates
            </h3>
            
            <div class="grid md:grid-cols-3 gap-6 mb-8">
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-6 rounded-2xl border border-blue-200">
                    <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h4 class="font-bold text-blue-800 mb-2">Current Status</h4>
                    <p class="text-blue-600 font-semibold" id="currentStatusText">{{ $towRequest->status_badge['text'] ?? 'Processing' }}</p>
                </div>
                
                <div class="bg-gradient-to-br from-green-50 to-green-100 p-6 rounded-2xl border border-green-200">
                    <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h4 class="font-bold text-green-800 mb-2">Last Update</h4>
                    <p class="text-green-600 text-sm" id="lastUpdateTime">{{ now()->format('M d, Y H:i') }}</p>
                </div>
                
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-6 rounded-2xl border border-purple-200">
                    <div class="w-12 h-12 bg-purple-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        </svg>
                    </div>
                    <h4 class="font-bold text-purple-800 mb-2">Auto Refresh</h4>
                    <p class="text-purple-600 text-sm">Every 30 seconds</p>
                </div>
            </div>

            <button onclick="refreshData()" 
                    class="btn-primary inline-flex items-center gap-3 mx-auto text-lg px-8 py-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Refresh Status
            </button>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        let map, driverMarker, pickupMarker, serviceMarker;
        const requestCode = '{{ $towRequest->request_code }}';
        let refreshInterval;

        // Initialize map
        function initMap() {
            // تعيين الإحداثيات الافتراضية (القاهرة)
            const defaultLat = 30.0444;
            const defaultLng = 31.2357;
            
            map = L.map('map', {
                zoomControl: false,
                attributionControl: false
            }).setView([defaultLat, defaultLng], 12);
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            // إضافة علامة موقع الاستلام
            @if($towRequest->pickup_location_lat && $towRequest->pickup_location_lng)
            pickupMarker = L.marker([{{ $towRequest->pickup_location_lat }}, {{ $towRequest->pickup_location_lng }}], {
                icon: L.divIcon({
                    className: 'custom-marker',
                    html: '<div style="background: linear-gradient(135deg, #EF4444, #DC2626); width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 3px solid white; box-shadow: 0 4px 15px rgba(239, 68, 68, 0.4); color: white; font-weight: bold; font-size: 12px;">P</div>',
                    iconSize: [40, 40],
                    iconAnchor: [20, 20]
                })
            }).addTo(map).bindPopup('<div style="text-align: center; font-weight: bold; color: #EF4444;">📍 Pickup Location</div>');
            @endif

            // إضافة علامة مركز الخدمة
            @if($towRequest->dropoff_location_lat && $towRequest->dropoff_location_lng)
            serviceMarker = L.marker([{{ $towRequest->dropoff_location_lat }}, {{ $towRequest->dropoff_location_lng }}], {
                icon: L.divIcon({
                    className: 'custom-marker',
                    html: '<div style="background: linear-gradient(135deg, #10B981, #059669); width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 3px solid white; box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4); color: white; font-weight: bold; font-size: 12px;">S</div>',
                    iconSize: [40, 40],
                    iconAnchor: [20, 20]
                })
            }).addTo(map).bindPopup('<div style="text-align: center; font-weight: bold; color: #10B981;">🏢 Service Center</div>');
            @endif

            // إضافة موقع السائق إذا كان متاحاً
            @if($latestTracking)
            updateDriverLocation({{ $latestTracking->driver_lat }}, {{ $latestTracking->driver_lng }});
            @endif

            // تحديد نطاق الخريطة لتشمل جميع المواضع
            fitMapBounds();
        }

        // تحديث موقع السائق
        function updateDriverLocation(lat, lng) {
            if (!map) return;
            
            if (driverMarker) {
                map.removeLayer(driverMarker);
            }
            
            driverMarker = L.marker([lat, lng], {
                icon: L.divIcon({
                    className: 'custom-marker',
                    html: '<div style="background: linear-gradient(135deg, #3B82F6, #1D4ED8); width: 35px; height: 35px; border-radius: 50%; border: 3px solid white; box-shadow: 0 4px 15px rgba(59, 130, 246, 0.5); display: flex; align-items: center; justify-content: center; animation: pulse 2s infinite;">🚛</div>',
                    iconSize: [35, 35],
                    iconAnchor: [17, 17]
                })
            }).addTo(map).bindPopup('<div style="text-align: center; font-weight: bold; color: #3B82F6;">🚛 Driver Location</div>');
            
            // تحديث status indicator
            document.getElementById('mapStatus').textContent = '📍 Driver Located';
        }

        // تحديد نطاق الخريطة
        function fitMapBounds() {
            const markers = [];
            if (pickupMarker) markers.push(pickupMarker);
            if (serviceMarker) markers.push(serviceMarker);
            if (driverMarker) markers.push(driverMarker);
            
            if (markers.length > 1) {
                const group = new L.featureGroup(markers);
                map.fitBounds(group.getBounds().pad(0.1));
            }
        }

        // توسيط الخريطة
        function centerMap() {
            if (driverMarker) {
                map.setView(driverMarker.getLatLng(), 15);
            } else {
                fitMapBounds();
            }
        }

        // تحديث موقع السائق
        function refreshLocation() {
            document.getElementById('mapStatus').innerHTML = '🔄 Updating...';
            
            fetch(`/track/customer/${requestCode}/data`)
            .then(response => response.json())
            .then(data => {
                if (data.latest_tracking) {
                    updateDriverLocation(data.latest_tracking.lat, data.latest_tracking.lng);
                    document.getElementById('lastUpdateTime').textContent = new Date().toLocaleString();
                    
                    // تحديث شريط التقدم
                    updateProgress(data.tow_request.status);
                } else {
                    document.getElementById('mapStatus').textContent = '❌ No tracking data';
                }
            })
            .catch(error => {
                console.error('Error refreshing location:', error);
                document.getElementById('mapStatus').textContent = '❌ Update failed';
            });
        }

        // تحديث جميع البيانات
        function refreshData() {
            const button = event.target.closest('button');
            const originalText = button.innerHTML;
            
            button.innerHTML = '<svg class="w-6 h-6 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg> Updating...';
            
            fetch(`/track/customer/${requestCode}/data`)
            .then(response => response.json())
            .then(data => {
                // تحديث الحالة
                if (data.tow_request) {
                    document.getElementById('currentStatusText').textContent = data.tow_request.status_badge?.text || 'Processing';
                    updateProgress(data.tow_request.status);
                }
                
                // تحديث الموقع
                if (data.latest_tracking) {
                    updateDriverLocation(data.latest_tracking.lat, data.latest_tracking.lng);
                }
                
                document.getElementById('lastUpdateTime').textContent = new Date().toLocaleString();
                
                // إظهار رسالة نجاح
                showNotification('✅ Status updated successfully!', 'success');
            })
            .catch(error => {
                console.error('Error refreshing data:', error);
                showNotification('❌ Failed to update status', 'error');
            })
            .finally(() => {
                setTimeout(() => {
                    button.innerHTML = originalText;
                }, 1000);
            });
        }

        // تحديث شريط التقدم
        function updateProgress(status) {
            const progressFill = document.getElementById('progressFill');
            let percentage = 20;
            
            switch(status) {
                case 'assigned': percentage = 33; break;
                case 'in_transit_to_pickup': percentage = 50; break;
                case 'arrived_at_pickup': percentage = 66; break;
                case 'customer_verified':
                case 'vehicle_loaded': percentage = 83; break;
                case 'in_transit_to_dropoff': percentage = 90; break;
                case 'delivered': percentage = 100; break;
            }
            
            progressFill.style.width = percentage + '%';
        }

        // إظهار الإشعارات
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 ${type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500'} text-white`;
            notification.textContent = message;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.style.opacity = '0';
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }

        // التحديث التلقائي كل 30 ثانية
        function startAutoRefresh() {
            refreshInterval = setInterval(() => {
                const trackingStatuses = ['assigned', 'in_transit_to_pickup', 'in_transit_to_dropoff'];
                const currentStatus = '{{ $towRequest->status }}';
                
                if (trackingStatuses.includes(currentStatus)) {
                    refreshLocation();
                }
            }, 30000);
        }

        // إيقاف التحديث التلقائي
        function stopAutoRefresh() {
            if (refreshInterval) {
                clearInterval(refreshInterval);
            }
        }

        // تهيئة الصفحة
        document.addEventListener('DOMContentLoaded', function() {
            initMap();
            startAutoRefresh();
            
            // إضافة مستمع لإيقاف التحديث عند مغادرة الصفحة
            window.addEventListener('beforeunload', stopAutoRefresh);
        });

        // تحديث التقدم عند تحميل الصفحة
        document.addEventListener('DOMContentLoaded', function() {
            updateProgress('{{ $towRequest->status }}');
        });
    </script>
</body>

</html>