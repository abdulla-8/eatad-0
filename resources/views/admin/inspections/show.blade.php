@extends('admin.layouts.app')

@section('title', 'Inspection Details')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.inspections.index') }}" 
           class="w-10 h-10 rounded-lg border flex items-center justify-center hover:bg-gray-50 transition-colors">
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Inspection Details</h1>
            <p class="text-gray-600">{{ $inspection->claim->claim_number }}</p>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Vehicle Information -->
            <div class="bg-white rounded-xl shadow-sm border">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-bold">Vehicle Information</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid md:grid-cols-2 gap-4">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Brand:</span>
                            <span class="font-medium">{{ $inspection->vehicle_brand }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Model:</span>
                            <span class="font-medium">{{ $inspection->vehicle_model }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Year:</span>
                            <span class="font-medium">{{ $inspection->vehicle_year }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Chassis Number:</span>
                            <span class="font-medium">{{ $inspection->chassis_number }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Required Parts -->
            <div class="bg-white rounded-xl shadow-sm border">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-bold">Required Parts</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-2">
                        @foreach($inspection->required_parts as $index => $part)
                            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                                <span class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-bold">{{ $index + 1 }}</span>
                                <span class="font-medium">{{ $part }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Registration Image -->
            @if($inspection->registration_image_path)
            <div class="bg-white rounded-xl shadow-sm border">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-bold">Registration Document</h3>
                </div>
                <div class="p-6">
                    <div class="border rounded-lg p-4">
                        <img src="{{ asset('storage/' . $inspection->registration_image_path) }}" 
                             alt="Registration Document" 
                             class="max-w-full h-auto rounded-lg">
                    </div>
                </div>
            </div>
            @endif

            <!-- Inspection Notes -->
            @if($inspection->inspection_notes)
            <div class="bg-white rounded-xl shadow-sm border">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-bold">Inspection Notes</h3>
                </div>
                <div class="p-6">
                    <p class="text-gray-700 whitespace-pre-line">{{ $inspection->inspection_notes }}</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Claim Information -->
            <div class="bg-white rounded-xl shadow-sm border">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-bold">Claim Information</h3>
                </div>
                <div class="p-6 space-y-3">
                    <div>
                        <span class="text-gray-600">Claim Number:</span>
                        <p class="font-medium">{{ $inspection->claim->claim_number }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600">Policy Number:</span>
                        <p class="font-medium">{{ $inspection->claim->policy_number }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600">Vehicle Plate:</span>
                        <p class="font-medium">{{ $inspection->claim->vehicle_plate_number ?: 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Customer Information -->
            <div class="bg-white rounded-xl shadow-sm border">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-bold">Customer Information</h3>
                </div>
                <div class="p-6 space-y-3">
                    <div>
                        <span class="text-gray-600">Name:</span>
                        <p class="font-medium">{{ $inspection->claim->insuranceUser->full_name }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600">Phone:</span>
                        <p class="font-medium">{{ $inspection->claim->insuranceUser->formatted_phone }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600">National ID:</span>
                        <p class="font-medium">{{ $inspection->claim->insuranceUser->formatted_national_id }}</p>
                    </div>
                </div>
            </div>

            <!-- Service Center Information -->
            <div class="bg-white rounded-xl shadow-sm border">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-bold">Service Center</h3>
                </div>
                <div class="p-6 space-y-3">
                    <div>
                        <span class="text-gray-600">Name:</span>
                        <p class="font-medium">{{ $inspection->serviceCenter->legal_name }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600">Phone:</span>
                        <p class="font-medium">{{ $inspection->serviceCenter->formatted_phone }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600">Inspection Date:</span>
                        <p class="font-medium">{{ $inspection->created_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Insurance Company -->
            <div class="bg-white rounded-xl shadow-sm border">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-bold">Insurance Company</h3>
                </div>
                <div class="p-6 space-y-3">
                    <div>
                        <span class="text-gray-600">Company:</span>
                        <p class="font-medium">{{ $inspection->claim->insuranceCompany->legal_name }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600">Phone:</span>
                        <p class="font-medium">{{ $inspection->claim->insuranceCompany->formatted_phone }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection