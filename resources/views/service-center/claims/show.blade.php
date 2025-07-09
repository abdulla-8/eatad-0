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
                @if($claim->canStartWork() && $claim->status === 'approved')
                    <button onclick="markInProgress()" 
                            class="px-6 py-2.5 bg-orange-500 text-white rounded-lg font-medium hover:bg-orange-600 transition-colors">
                        {{ t('service_center.start_work') }}
                    </button>
                @endif
            @endif
        </div>
    </div>

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
                <div class="p-6 border-b">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold">
                            {{ substr($claim->insuranceUser->full_name, 0, 2) }}
                        </div>
                        <div>
                            <h3 class="text-lg font-bold">{{ t('service_center.customer_information') }}</h3>
                            <p class="text-gray-600 text-sm">{{ t('service_center.contact_details') }}</p>
                        </div>
                    </div>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid md:grid-cols-2 gap-4">
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ t('service_center.name') }}</span>
                            <span class="font-medium">{{ $claim->insuranceUser->full_name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ t('service_center.phone') }}</span>
                            <span class="font-medium">{{ $claim->insuranceUser->formatted_phone }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ t('service_center.national_id') }}</span>
                            <span class="font-medium font-mono text-sm">{{ $claim->insuranceUser->formatted_national_id }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ t('service_center.policy_number') }}</span>
                            <span class="font-medium">{{ $claim->insuranceUser->policy_number }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-bold">{{ t('service_center.vehicle_claim_information') }}</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid md:grid-cols-2 gap-4">
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ t('service_center.claim_policy_number') }}</span>
                            <span class="font-medium">{{ $claim->policy_number }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ t('service_center.vehicle') }}</span>
                            <span class="font-medium">{{ $claim->vehicle_plate_number ?: $claim->chassis_number }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ t('service_center.vehicle_working') }}</span>
                            <span class="font-medium {{ $claim->is_vehicle_working ? 'text-green-600' : 'text-red-600' }}">
                                {{ $claim->is_vehicle_working ? t('service_center.yes') : t('service_center.no') }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ t('service_center.receipt_ready') }}</span>
                            <span class="font-medium">{{ $claim->repair_receipt_ready ? t('service_center.yes') : t('service_center.no') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ t('service_center.submitted') }}</span>
                            <span class="font-medium">{{ $claim->created_at->format('M d, Y H:i') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ t('service_center.assigned') }}</span>
                            <span class="font-medium">{{ $claim->updated_at->format('M d, Y H:i') }}</span>
                        </div>
                            <!-- الحقول الجديدة -->
        <div class="flex justify-between">
            <span class="text-gray-600">{{ t('service_center.vehicle_brand') }}</span>
            <span class="font-medium">{{ $claim->vehicle_brand }}</span>
        </div>
        <div class="flex justify-between">
            <span class="text-gray-600">{{ t('service_center.vehicle_type') }}</span>
            <span class="font-medium">{{ $claim->vehicle_type }}</span>
        </div>
        <div class="flex justify-between">
            <span class="text-gray-600">{{ t('service_center.vehicle_model') }}</span>
            <span class="font-medium">{{ $claim->vehicle_model }}</span>
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
                    @if($claim->canStartWork() && $claim->status === 'approved')
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