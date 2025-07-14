@extends('service-center.layouts.app')

@section('title', t('service_center.claim_details'))

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('service-center.claims.index') }}" 
               class="w-10 h-10 rounded-lg border flex items-center justify-center hover:bg-gray-50 transition-colors">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $claim->claim_number }}</h1>
                <p class="text-gray-600">{{ t('service_center.claim_details') }}</p>
            </div>
        </div>
        
        <div class="flex items-center gap-4">
            @if($claim->shouldShowDeliveryVerificationButton())
                <button onclick="showCustomerDeliveryModal()" 
                        class="px-6 py-2.5 bg-blue-500 text-white rounded-lg font-medium hover:bg-blue-600 transition-colors">
                    {{ t('service_center.verify_customer_delivery') }}
                </button>
            @elseif($claim->shouldShowMarkArrivedButton())
                <button onclick="markVehicleArrived()" 
                        class="px-6 py-2.5 bg-green-500 text-white rounded-lg font-medium hover:bg-green-600 transition-colors">
                    {{ t('service_center.mark_vehicle_arrived') }}
                </button>
            @elseif($claim->canStartInspection())
                <span class="px-4 py-2 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                    {{ t('service_center.vehicle_arrived') }}
                </span>
                <button onclick="showInspectionModal()" 
                        class="px-6 py-2.5 bg-yellow-500 text-white rounded-lg font-medium hover:bg-yellow-600 transition-colors">
                    {{ t('service_center.start_inspection') }}
                </button>
            @elseif($claim->inspection_status === 'in_progress')
                <span class="px-4 py-2 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                    {{ t('service_center.inspection_in_progress') }}
                </span>
            @elseif($claim->inspection_status === 'completed')
                <span class="px-4 py-2 rounded-full text-sm font-medium bg-green-100 text-green-800">
                    {{ t('service_center.inspection_completed') }}
                </span>
                @if($claim->shouldShowConfirmPartsButton())
                    <button onclick="showConfirmPartsModal()" 
                            class="px-6 py-2.5 bg-purple-500 text-white rounded-lg font-medium hover:bg-purple-600 transition-colors">
                        {{ t('service_center.confirm_parts_received') }}
                    </button>
                @elseif($claim->canStartWork())
                    <button onclick="markInProgress()" 
                            class="px-6 py-2.5 bg-orange-500 text-white rounded-lg font-medium hover:bg-orange-600 transition-colors">
                        {{ t('service_center.start_work') }}
                    </button>
                @endif
            @endif
        </div>
    </div>

    <!-- Parts Status Alerts -->
    @if($claim->inspection && $claim->inspection->insurance_response === 'approved' && !$claim->parts_received_at)
        <div class="bg-purple-50 border border-purple-200 rounded-xl p-6">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="font-bold text-purple-800 mb-2">{{ t('service_center.parts_pricing_approved') }}</h3>
                    <p class="text-purple-700 mb-3">{{ t('service_center.insurance_approved_parts_pricing') }}</p>
                    <p class="text-purple-600 text-sm mb-4">{{ t('service_center.please_confirm_when_parts_arrive') }}</p>
                    <div class="bg-white border-2 border-purple-300 rounded-lg p-3 text-center">
                        <span class="text-lg font-bold text-purple-800">{{ number_format($claim->inspection->total_amount, 2) }} SAR</span>
                        <p class="text-purple-600 text-sm">{{ t('service_center.approved_amount') }}</p>
                    </div>
                </div>
            </div>
        </div>
    @elseif($claim->parts_received_at)
        <div class="bg-green-50 border border-green-200 rounded-xl p-6">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-green-800 mb-2">{{ t('service_center.parts_received_confirmed') }}</h3>
                    <p class="text-green-700">{{ t('service_center.received_at') }}: {{ $claim->parts_received_at->format('M d, Y H:i') }}</p>
                    @if($claim->parts_received_notes)
                        <p class="text-green-600 text-sm mt-1">{{ $claim->parts_received_notes }}</p>
                    @endif
                </div>
            </div>
        </div>
    @endif

    @if($claim->shouldShowCustomerDeliveryCode())
        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-full bg-yellow-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="font-bold text-yellow-800 mb-2">{{ t('service_center.customer_has_delivery_code') }}</h3>
                    <p class="text-yellow-700">{{ t('service_center.customer_will_provide_code_on_arrival') }}</p>
                    <div class="bg-white border-2 border-yellow-300 rounded-lg p-3 mt-3 text-center">
                        <span class="text-xl font-bold text-yellow-800 tracking-wider font-mono">{{ $claim->customer_delivery_code }}</span>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($claim->vehicle_arrived_at_center)
        <div class="bg-green-50 border border-green-200 rounded-xl p-6">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-green-800 mb-2">{{ t('service_center.vehicle_arrived') }}</h3>
                    <p class="text-green-700">{{ t('service_center.arrived_at') }}: {{ $claim->vehicle_arrived_at_center->format('M d, Y H:i') }}</p>
                </div>
            </div>
        </div>
    @endif

    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
         <div class="bg-white rounded-xl shadow-sm border">
    <div class="p-6 border-b bg-gradient-to-r from-gray-50 to-white">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-full flex items-center justify-center text-white font-bold" style="background: #2563eb;">
                {{ substr($claim->insuranceUser->full_name, 0, 2) }}
            </div>
            <div>
                <h3 class="text-lg font-bold">{{ t('service_center.customer_information') }}</h3>
                <p class="text-gray-600 text-sm">{{ t('service_center.contact_details') }}</p>
            </div>
        </div>
    </div>
    <div class="p-6 space-y-4">
        <div class="grid md:grid-cols-2 gap-6">
            <div class="bg-gray-50 rounded-lg p-4 border-l-4 border-blue-600">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <!-- أيقونة المستخدم -->
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A7 7 0 0112 15a7 7 0 016.879 2.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span class="text-gray-600 text-sm">{{ t('service_center.name') }}</span>
                    </div>
                    <span class="font-medium">{{ $claim->insuranceUser->full_name }}</span>
                </div>
            </div>

            <div class="bg-gray-50 rounded-lg p-4 border-l-4 border-blue-600">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <!-- أيقونة الهاتف -->
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h2l3.6 7.59a1 1 0 01-.36 1.41l-2.6 1.54a11 11 0 005.6 5.6l1.54-2.6a1 1 0 011.41-.36L19 19v2a2 2 0 01-2 2H5a2 2 0 01-2-2V5z" />
                        </svg>
                        <span class="text-gray-600 text-sm">{{ t('service_center.phone') }}</span>
                    </div>
                    <span class="font-medium">{{ $claim->insuranceUser->formatted_phone }}</span>
                </div>
            </div>

            <div class="bg-gray-50 rounded-lg p-4 border-l-4 border-blue-600">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <!-- أيقونة الهوية الوطنية -->
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2zM15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span class="text-gray-600 text-sm">{{ t('service_center.national_id') }}</span>
                    </div>
                    <span class="font-medium font-mono text-sm">{{ $claim->insuranceUser->formatted_national_id }}</span>
                </div>
            </div>

            <div class="bg-gray-50 rounded-lg p-4 border-l-4 border-blue-600">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <!-- أيقونة رقم الوثيقة -->
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span class="text-gray-600 text-sm">{{ t('service_center.policy_number') }}</span>
                    </div>
                    <span class="font-medium">{{ $claim->insuranceUser->policy_number }}</span>
                </div>
            </div>
        </div>
    </div>
</div>


          <div class="bg-white rounded-xl shadow-sm border">
    <div class="p-6 border-b bg-gradient-to-r from-gray-50 to-white">
        <h3 class="text-lg font-bold flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background: #2563eb;">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            {{ t('service_center.vehicle_claim_information') }}
        </h3>
    </div>
    <div class="p-6 space-y-4">
        <div class="grid md:grid-cols-2 gap-6">

            <div class="bg-gray-50 rounded-lg p-4 border-l-4 border-blue-600">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <!-- أيقونة رقم بوليصة -->
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span class="text-gray-600 text-sm">{{ t('service_center.claim_policy_number') }}</span>
                    </div>
                    <span class="font-medium">{{ $claim->policy_number }}</span>
                </div>
            </div>

            <div class="bg-gray-50 rounded-lg p-4 border-l-4 border-blue-600">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <!-- أيقونة سيارة -->
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 13l1.5-4.5a1 1 0 011-1h13a1 1 0 011 1l1.5 4.5M5 16h14v2a2 2 0 01-2 2H7a2 2 0 01-2-2v-2z" />
                        </svg>
                        <span class="text-gray-600 text-sm">{{ t('service_center.vehicle_plate_number') }}</span>
                    </div>
                    <span class="font-medium">{{ $claim->vehicle_plate_number ?: $claim->chassis_number }}</span>
                </div>
            </div>

            <div class="bg-gray-50 rounded-lg p-4 border-l-4" style="border-color: {{ $claim->is_vehicle_working ? '#10b981' : '#ef4444' }};">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <!-- أيقونة حالة السيارة -->
                        <svg class="w-5 h-5 {{ $claim->is_vehicle_working ? 'text-green-500' : 'text-red-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $claim->is_vehicle_working ? 'M5 13l4 4L19 7' : 'M6 18L18 6M6 6l12 12' }}" />
                        </svg>
                        <span class="text-gray-600 text-sm">{{ t('service_center.vehicle_working') }}</span>
                    </div>
                    <span class="font-semibold {{ $claim->is_vehicle_working ? 'text-green-600' : 'text-red-600' }}">
                        {{ $claim->is_vehicle_working ? t('service_center.yes') : t('service_center.no') }}
                    </span>
                </div>
            </div>

            <div class="bg-gray-50 rounded-lg p-4 border-l-4" style="border-color: {{ $claim->repair_receipt_ready ? '#10b981' : '#ef4444' }};">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <!-- أيقونة إيصال الإصلاح -->
                        <svg class="w-5 h-5 {{ $claim->repair_receipt_ready ? 'text-green-500' : 'text-red-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span class="text-gray-600 text-sm">{{ t('service_center.receipt_ready') }}</span>
                    </div>
                    <span class="font-semibold {{ $claim->repair_receipt_ready ? 'text-green-600' : 'text-red-600' }}">
                        {{ $claim->repair_receipt_ready ? t('service_center.yes') : t('service_center.no') }}
                    </span>
                </div>
            </div>

            <div class="bg-gray-50 rounded-lg p-4 border-l-4 border-blue-600">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <!-- أيقونة تاريخ التقديم -->
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-4 12v-6m6 6H6a2 2 0 01-2-2v-6a2 2 0 012-2h12a2 2 0 012 2v6a2 2 0 01-2 2z" />
                        </svg>
                        <span class="text-gray-600 text-sm">{{ t('service_center.submitted') }}</span>
                    </div>
                    <span class="font-medium">{{ $claim->created_at->format('M d, Y H:i') }}</span>
                </div>
            </div>

            <div class="bg-gray-50 rounded-lg p-4 border-l-4 border-blue-600">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <!-- أيقونة تاريخ التعيين -->
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8h18M3 12h18M3 16h18M3 20h18" />
                        </svg>
                        <span class="text-gray-600 text-sm">{{ t('service_center.assigned') }}</span>
                    </div>
                    <span class="font-medium">{{ $claim->updated_at->format('M d, Y H:i') }}</span>
                </div>
            </div>

            <div class="bg-gray-50 rounded-lg p-4 border-l-4 border-blue-600">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <!-- أيقونة ماركة السيارة -->
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 13l1.5-4.5a1 1 0 011-1h13a1 1 0 011 1l1.5 4.5M5 16h14v2a2 2 0 01-2 2H7a2 2 0 01-2-2v-2z" />
                        </svg>
                        <span class="text-gray-600 text-sm">{{ t('service_center.vehicle_brand') }}</span>
                    </div>
                    <span class="font-medium">{{ $claim->vehicle_brand }}</span>
                </div>
            </div>

            <div class="bg-gray-50 rounded-lg p-4 border-l-4 border-blue-600">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <!-- أيقونة نوع السيارة -->
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h-2v-6h2m-1-4h.01" />
                        </svg>
                        <span class="text-gray-600 text-sm">{{ t('service_center.vehicle_type') }}</span>
                    </div>
                    <span class="font-medium">{{ $claim->vehicle_type }}</span>
                </div>
            </div>

            <div class="bg-gray-50 rounded-lg p-4 border-l-4 border-blue-600">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <!-- أيقونة موديل السيارة -->
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        <span class="text-gray-600 text-sm">{{ t('service_center.vehicle_model') }}</span>
                    </div>
                    <span class="font-medium">{{ $claim->vehicle_model }}</span>
                </div>
            </div>

        </div>
    </div>
</div>


            @if(!empty($claim->vehicle_location))
            <div class="bg-white rounded-xl shadow-sm border">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-bold">{{ t('service_center.vehicle_location') }}</h3>
                </div>
                <div class="p-6">
                    <p class="text-gray-700 mb-4">{{ $claim->vehicle_location }}</p>
                    @if($claim->vehicle_location_lat)
                        <a href="{{ $claim->vehicle_location_url }}" target="_blank" 
                           class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            {{ t('service_center.view_on_map') }}
                        </a>
                    @endif
                </div>
            </div>
            @endif

            @if($claim->notes)
            <div class="bg-white rounded-xl shadow-sm border">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-bold">{{ t('service_center.notes') }}</h3>
                </div>
                <div class="p-6">
                    <p class="text-gray-700 whitespace-pre-line">{{ $claim->notes }}</p>
                </div>
            </div>
            @endif
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-sm border">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-bold">{{ t('service_center.insurance_company') }}</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <h4 class="font-bold text-gray-900">{{ $claim->insuranceCompany->legal_name }}</h4>
                        @if($claim->insuranceCompany->office_address)
                            <p class="text-gray-600 text-sm mt-1">{{ $claim->insuranceCompany->office_address }}</p>
                        @endif
                    </div>
                    
                    <div class="text-sm text-gray-600">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            {{ $claim->insuranceCompany->formatted_phone }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Parts Pricing Summary -->
            @if($claim->inspection && $claim->inspection->hasPricing())
            <div class="bg-white rounded-xl shadow-sm border">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-bold">{{ t('service_center.parts_pricing_summary') }}</h3>
                </div>
                <div class="p-6 space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">{{ t('service_center.parts_total') }}:</span>
                        <span class="font-medium">{{ number_format($claim->inspection->parts_total, 2) }} SAR</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">{{ t('service_center.service_fees') }}:</span>
                        <span class="font-medium">{{ number_format($claim->inspection->service_center_fees, 2) }} SAR</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">{{ t('service_center.tax') }} ({{ $claim->inspection->tax_percentage }}%):</span>
                        <span class="font-medium">{{ number_format($claim->inspection->tax_amount, 2) }} SAR</span>
                    </div>
                    <div class="border-t pt-2 flex justify-between font-bold">
                        <span>{{ t('service_center.total_amount') }}:</span>
                        <span class="text-lg text-blue-600">{{ number_format($claim->inspection->total_amount, 2) }} SAR</span>
                    </div>
                    
                    <div class="pt-2">
                        <span class="px-3 py-1.5 rounded-full text-sm font-medium {{ $claim->inspection->insurance_response_badge['class'] }}">
                            {{ $claim->inspection->insurance_response_badge['text'] }}
                        </span>
                    </div>
                </div>
            </div>
            @endif

            @if($claim->tow_service_offered)
            <div class="bg-white rounded-xl shadow-sm border">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-bold">{{ t('service_center.tow_service') }}</h3>
                </div>
                <div class="p-6 space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">{{ t('service_center.offered') }}</span>
                        <span class="font-medium text-blue-600">{{ t('service_center.yes') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">{{ t('service_center.status') }}</span>
                        <span class="font-medium {{ is_null($claim->tow_service_accepted) ? 'text-yellow-600' : ($claim->tow_service_accepted ? 'text-green-600' : 'text-red-600') }}">
                            @if(is_null($claim->tow_service_accepted))
                                {{ t('service_center.waiting_response') }}
                            @elseif($claim->tow_service_accepted)
                                {{ t('service_center.accepted') }}
                            @else
                                {{ t('service_center.declined') }}
                            @endif
                        </span>
                    </div>
                </div>
            </div>
            @endif

            <div class="bg-white rounded-xl shadow-sm border">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-bold">{{ t('service_center.quick_actions') }}</h3>
                </div>
                <div class="p-6 space-y-3">
                    @if($claim->shouldShowConfirmPartsButton())
                        <button onclick="showConfirmPartsModal()" 
                                class="w-full px-4 py-3 bg-purple-500 text-white rounded-lg font-medium hover:bg-purple-600 transition-colors">
                            {{ t('service_center.confirm_parts_received') }}
                        </button>
                    @elseif($claim->canStartWork())
                        <button onclick="markInProgress()" 
                                class="w-full px-4 py-3 bg-yellow-500 text-white rounded-lg font-medium hover:bg-yellow-600 transition-colors">
                            {{ t('service_center.start_work') }}
                        </button>
                    @elseif($claim->status === 'in_progress')
                        <button onclick="markCompleted()" 
                                class="w-full px-4 py-3 bg-green-500 text-white rounded-lg font-medium hover:bg-green-600 transition-colors">
                            {{ t('service_center.mark_completed') }}
                        </button>
                    @endif
                    
                    <button onclick="addNotesModal()" 
                            class="w-full px-4 py-3 bg-blue-100 text-blue-700 rounded-lg font-medium hover:bg-blue-200 transition-colors">
                        {{ t('service_center.add_notes') }}
                    </button>
                    
                    <a href="{{ route('service-center.claims.index') }}" 
                       class="w-full px-4 py-3 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition-colors text-center block">
                        {{ t('service_center.back_to_claims') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if($claim->attachments->count())
    <div class="bg-white rounded-xl shadow-sm border">
        <div class="p-6 border-b">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold">{{ t('service_center.attachments') }}</h3>
                <span class="px-3 py-1 bg-blue-100 text-blue-600 rounded-full text-sm font-medium">
                    {{ $claim->attachments->count() }} {{ t('service_center.files') }}
                </span>
            </div>
        </div>
        <div class="p-6">
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($claim->attachments->groupBy('type') as $type => $attachments)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h4 class="font-medium text-gray-900 mb-3">{{ t('service_center.' . $type) }}</h4>
                        <div class="space-y-2">
                            @foreach($attachments as $attachment)
                                <div class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-50 transition-colors">
                                    <div class="flex-shrink-0">
                                        @if($attachment->isImage())
                                            <div class="w-8 h-8 rounded bg-blue-100 flex items-center justify-center">
                                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
@elseif($attachment->isPdf())
                                            <div class="w-8 h-8 rounded bg-red-100 flex items-center justify-center">
                                                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                            </div>
                                        @else
                                            <div class="w-8 h-8 rounded bg-gray-100 flex items-center justify-center">
                                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656L9 8.586m6.586 6.586l-6.586 6.586a2 2 0 01-2.828-2.828l6.586-6.586a4 4 0 015.656 5.656L15.172 7z"></path>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <a href="{{ $attachment->file_url }}" target="_blank" 
                                           class="block text-sm font-medium text-gray-900 hover:text-blue-600 transition-colors truncate">
                                            {{ $attachment->file_name }}
                                        </a>
                                        <p class="text-xs text-gray-500">{{ $attachment->file_size_formatted }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Confirm Parts Received Modal -->
<div id="confirmPartsModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl max-w-md w-full">
        <div class="p-6 border-b">
            <h3 class="text-xl font-bold">{{ t('service_center.confirm_parts_received') }}</h3>
        </div>
        
        <div class="p-6 space-y-4">
            <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                <p class="text-purple-800 font-medium">{{ t('service_center.confirm_parts_delivery_message') }}</p>
                @if($claim->inspection && $claim->inspection->total_amount)
                    <p class="text-purple-700 text-sm mt-2">{{ t('service_center.approved_amount') }}: {{ number_format($claim->inspection->total_amount, 2) }} SAR</p>
                @endif
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">{{ t('service_center.parts_received_notes') }}</label>
                <textarea id="partsReceivedNotes" rows="4" 
                          class="w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent px-4 py-2.5"
                          placeholder="{{ t('service_center.parts_received_notes_placeholder') }}"></textarea>
            </div>
            
            <div class="flex gap-4">
                <button onclick="confirmPartsReceived()" 
                        class="flex-1 py-3 bg-purple-500 text-white rounded-lg font-medium hover:bg-purple-600 transition-colors">
                    {{ t('service_center.confirm_received') }}
                </button>
                <button type="button" onclick="closeModal('confirmPartsModal')" 
                        class="flex-1 py-3 bg-gray-500 text-white rounded-lg font-medium hover:bg-gray-600 transition-colors">
                    {{ t('service_center.cancel') }}
                </button>
            </div>
        </div>
    </div>
</div>

<!-- In Progress Modal -->
<div id="inProgressModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl max-w-md w-full">
        <div class="p-6 border-b">
            <h3 class="text-xl font-bold">{{ t('service_center.start_working_on_claim') }} {{ $claim->claim_number }}</h3>
        </div>
        
        <form method="POST" action="{{ route('service-center.claims.mark-progress', $claim->id) }}" class="p-6">
            @csrf
            
            <p class="text-gray-600 mb-6">{{ t('service_center.confirm_start_work_message') }}</p>
            
            <div class="flex gap-4">
                <button type="submit" class="flex-1 py-3 bg-yellow-500 text-white rounded-lg font-medium hover:bg-yellow-600 transition-colors">
                    {{ t('service_center.start_work') }}
                </button>
                <button type="button" onclick="closeModal('inProgressModal')" 
                        class="flex-1 py-3 bg-gray-500 text-white rounded-lg font-medium hover:bg-gray-600 transition-colors">
                    {{ t('service_center.cancel') }}
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Completed Modal -->
<div id="completedModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl max-w-md w-full">
        <div class="p-6 border-b">
            <h3 class="text-xl font-bold">{{ t('service_center.mark_claim_completed') }} {{ $claim->claim_number }}</h3>
        </div>
        
        <form method="POST" action="{{ route('service-center.claims.mark-completed', $claim->id) }}" class="p-6 space-y-4">
            @csrf
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">{{ t('service_center.completion_notes') }}</label>
                <textarea name="completion_notes" rows="4" 
                          class="w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent px-4 py-2.5"
                          placeholder="{{ t('service_center.completion_notes_placeholder') }}"></textarea>
            </div>
            
            <div class="flex gap-4">
                <button type="submit" class="flex-1 py-3 bg-green-500 text-white rounded-lg font-medium hover:bg-green-600 transition-colors">
                    {{ t('service_center.mark_completed') }}
                </button>
                <button type="button" onclick="closeModal('completedModal')" 
                        class="flex-1 py-3 bg-gray-500 text-white rounded-lg font-medium hover:bg-gray-600 transition-colors">
                    {{ t('service_center.cancel') }}
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Inspection Modal -->
<div id="inspectionModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b">
            <h3 class="text-xl font-bold">{{ t('service_center.vehicle_inspection') }}</h3>
        </div>
        
        <form id="inspectionForm" enctype="multipart/form-data" class="p-6 space-y-6">
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ t('service_center.vehicle_brand') }} *</label>
                    <input type="text" name="vehicle_brand" required 
                           class="w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent px-4 py-2.5">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ t('service_center.vehicle_model') }} *</label>
                    <input type="text" name="vehicle_model" required 
                           class="w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent px-4 py-2.5">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ t('service_center.vehicle_year') }} *</label>
                    <input type="number" name="vehicle_year" min="1900" max="{{ date('Y') + 1 }}" required 
                           class="w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent px-4 py-2.5">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ t('service_center.chassis_number') }} *</label>
                    <input type="text" name="chassis_number" required 
                           class="w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent px-4 py-2.5">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">{{ t('service_center.registration_image') }} *</label>
                <input type="file" name="registration_image" accept="image/*" required 
                       class="w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent px-4 py-2.5">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">{{ t('service_center.required_parts') }} *</label>
                <div id="partsContainer">
                    <div class="flex gap-2 mb-2">
                        <input type="text" name="required_parts[]" required 
                               class="flex-1 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent px-4 py-2.5"
                               placeholder="{{ t('service_center.part_name') }}">
                        <button type="button" onclick="addPartField()" 
                                class="px-4 py-2.5 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors">
                            +
                        </button>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">{{ t('service_center.inspection_notes') }}</label>
                <textarea name="inspection_notes" rows="4" 
                          class="w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent px-4 py-2.5"
                          placeholder="{{ t('service_center.additional_notes') }}"></textarea>
            </div>
            
            <div class="flex gap-4">
                <button type="submit" 
                        class="flex-1 py-3 bg-yellow-500 text-white rounded-lg font-medium hover:bg-yellow-600 transition-colors">
                    {{ t('service_center.submit_inspection') }}
                </button>
                <button type="button" onclick="closeModal('inspectionModal')" 
                        class="flex-1 py-3 bg-gray-500 text-white rounded-lg font-medium hover:bg-gray-600 transition-colors">
                    {{ t('service_center.cancel') }}
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Customer Delivery Modal -->
<div id="customerDeliveryModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl max-w-md w-full">
        <div class="p-6 border-b">
            <h3 class="text-xl font-bold">{{ t('service_center.verify_customer_delivery') }}</h3>
        </div>
        
        <div class="p-6 space-y-4">
            <p class="text-gray-600">{{ t('service_center.enter_6_digit_code_from_customer') }}</p>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">{{ t('service_center.delivery_code') }}</label>
                <input type="text" id="deliveryCode" maxlength="6" 
                       class="w-full px-4 py-3 text-lg font-mono border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-center tracking-widest"
                       placeholder="000000">
            </div>
            
            <div class="flex gap-4">
                <button onclick="verifyCustomerDelivery()" 
                        class="flex-1 py-3 bg-blue-500 text-white rounded-lg font-medium hover:bg-blue-600 transition-colors">
                    {{ t('service_center.verify') }}
                </button>
                <button type="button" onclick="closeModal('customerDeliveryModal')" 
                        class="flex-1 py-3 bg-gray-500 text-white rounded-lg font-medium hover:bg-gray-600 transition-colors">
                    {{ t('service_center.cancel') }}
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Add Notes Modal -->
<div id="addNotesModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl max-w-md w-full">
        <div class="p-6 border-b">
            <h3 class="text-xl font-bold">{{ t('service_center.add_notes') }}</h3>
        </div>
        
        <form method="POST" action="{{ route('service-center.claims.add-notes', $claim->id) }}" class="p-6 space-y-4">
            @csrf
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">{{ t('service_center.notes') }}</label>
                <textarea name="notes" rows="4" required 
                          class="w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent px-4 py-2.5"
                          placeholder="{{ t('service_center.add_notes_placeholder') }}"></textarea>
            </div>
            
            <div class="flex gap-4">
                <button type="submit" class="flex-1 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors">
                    {{ t('service_center.add_notes') }}
                </button>
                <button type="button" onclick="closeModal('addNotesModal')" 
                        class="flex-1 py-3 bg-gray-500 text-white rounded-lg font-medium hover:bg-gray-600 transition-colors">
                    {{ t('service_center.cancel') }}
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function showCustomerDeliveryModal() {
    document.getElementById('customerDeliveryModal').classList.remove('hidden');
}

function showInspectionModal() {
    fetch(`{{ route('service-center.claims.start-inspection', $claim->id) }}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('inspectionModal').classList.remove('hidden');
        } else {
            alert(data.error || 'Failed to start inspection');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('{{ t("service_center.error_occurred") }}');
    });
}

function showConfirmPartsModal() {
    document.getElementById('confirmPartsModal').classList.remove('hidden');
}

function confirmPartsReceived() {
    const notes = document.getElementById('partsReceivedNotes').value;
    
    fetch(`/service-center/claims/{{ $claim->id }}/confirm-parts-received`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            parts_received_notes: notes
        })
    })
    .then(response => {
        console.log('Response status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            location.reload();
        } else {
            alert(data.error || 'Failed to confirm parts receipt');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('حدث خطأ أثناء الاتصال بالخادم');
    });
}

function markVehicleArrived() {
    if (!confirm('{{ t("service_center.confirm_vehicle_arrived") }}')) return;
    
    fetch(`{{ route('service-center.claims.mark-arrived', $claim->id) }}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.error);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('{{ t("service_center.error_occurred") }}');
    });
}

function verifyCustomerDelivery() {
    const code = document.getElementById('deliveryCode').value;
    
    if (code.length !== 6) {
        alert('{{ t("service_center.code_must_be_6_digits") }}');
        return;
    }
    
    fetch(`{{ route('service-center.claims.verify-delivery', $claim->id) }}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ delivery_code: code })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.error);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('{{ t("service_center.error_occurred") }}');
    });
}

function addPartField() {
    const container = document.getElementById('partsContainer');
    const div = document.createElement('div');
    div.className = 'flex gap-2 mb-2';
    div.innerHTML = `
        <input type="text" name="required_parts[]" required 
               class="flex-1 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent px-4 py-2.5"
               placeholder="{{ t('service_center.part_name') }}">
        <button type="button" onclick="this.parentElement.remove()" 
                class="px-4 py-2.5 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
            -
        </button>
    `;
    container.appendChild(div);
}

document.getElementById('inspectionForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch(`{{ route('service-center.claims.submit-inspection', $claim->id) }}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.error);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('{{ t("service_center.error_occurred") }}');
    });
});

document.getElementById('deliveryCode').addEventListener('input', function(e) {
    e.target.value = e.target.value.replace(/\D/g, '');
});

function markInProgress() {
    document.getElementById('inProgressModal').classList.remove('hidden');
}

function markCompleted() {
    document.getElementById('completedModal').classList.remove('hidden');
}

function addNotesModal() {
    document.getElementById('addNotesModal').classList.remove('hidden');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

// Event listeners for outside clicks
document.getElementById('confirmPartsModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal('confirmPartsModal');
});

document.getElementById('inProgressModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal('inProgressModal');
});

document.getElementById('completedModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal('completedModal');
});

document.getElementById('addNotesModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal('addNotesModal');
});

document.getElementById('inspectionModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal('inspectionModal');
});

document.getElementById('customerDeliveryModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal('customerDeliveryModal');
});
</script>
@endsection