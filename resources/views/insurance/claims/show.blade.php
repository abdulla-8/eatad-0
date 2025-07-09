@extends('insurance.layouts.app')

@section('title', t($company->translation_group . '.claim_details'))

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('insurance.claims.index', $company->company_slug) }}" 
               class="w-10 h-10 rounded-lg border flex items-center justify-center hover:bg-gray-50 transition-colors">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $claim->claim_number }}</h1>
                <p class="text-gray-600">{{ t($company->translation_group . '.claim_details') }}</p>
            </div>
        </div>
        
        <div class="flex items-center gap-4">
            <span class="px-4 py-2 rounded-full text-sm font-medium {{ $claim->status_badge['class'] }}">
                {{ t($company->translation_group . '.' . $claim->status) }}
            </span>
            
            @if($claim->status === 'pending')
                <div class="flex gap-3">
                    <button onclick="approveModal()" 
                            class="px-6 py-2.5 bg-green-500 text-white rounded-lg font-medium hover:bg-green-600 transition-colors">
                        {{ t($company->translation_group . '.approve') }}
                    </button>
                    <button onclick="rejectModal()" 
                            class="px-6 py-2.5 bg-red-500 text-white rounded-lg font-medium hover:bg-red-600 transition-colors">
                        {{ t($company->translation_group . '.reject') }}
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- Parts Pricing Alert -->
    @if($claim->inspection && $claim->inspection->pricing_status === 'sent_to_insurance')
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="font-bold text-blue-800 mb-2">Parts Pricing Available for Review</h3>
                    <p class="text-blue-700 mb-4">A detailed parts pricing has been submitted for this claim. Please review and respond.</p>
                    <div class="flex gap-3">
                        <a href="{{ route('insurance.parts-quotes.show', [$company->company_slug, $claim->inspection->id]) }}" 
                           class="px-4 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors">
                            Review Pricing Details
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @elseif($claim->inspection && $claim->inspection->insurance_response === 'approved')
        <div class="bg-green-50 border border-green-200 rounded-xl p-6">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="font-bold text-green-800 mb-2">Parts Pricing Approved</h3>
                    <p class="text-green-700 mb-2">You have approved the parts pricing for {{ number_format($claim->inspection->total_amount, 2) }} SAR</p>
                    @if($claim->inspection->insurance_notes)
                        <p class="text-green-600 text-sm">{{ $claim->inspection->insurance_notes }}</p>
                    @endif
                </div>
            </div>
        </div>
    @elseif($claim->inspection && $claim->inspection->insurance_response === 'rejected')
        <div class="bg-red-50 border border-red-200 rounded-xl p-6">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="font-bold text-red-800 mb-2">Parts Pricing Rejected</h3>
                    <p class="text-red-700 mb-2">You have rejected the parts pricing. Reason: {{ $claim->inspection->rejection_reason }}</p>
                    @if($claim->inspection->insurance_notes)
                        <p class="text-red-600 text-sm">Additional notes: {{ $claim->inspection->insurance_notes }}</p>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <div class="grid lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- User Information -->
            <div class="bg-white rounded-xl shadow-sm border">
                <div class="p-6 border-b">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center text-white font-bold"
                             style="background: {{ $company->primary_color }};">
                            {{ substr($claim->insuranceUser->full_name, 0, 2) }}
                        </div>
                        <div>
                            <h3 class="text-lg font-bold">{{ t($company->translation_group . '.user_information') }}</h3>
                            <p class="text-gray-600 text-sm">{{ t($company->translation_group . '.contact_details') }}</p>
                        </div>
                    </div>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid md:grid-cols-2 gap-4">
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ t($company->translation_group . '.name') }}</span>
                            <span class="font-medium">{{ $claim->insuranceUser->full_name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ t($company->translation_group . '.phone') }}</span>
                            <span class="font-medium">{{ $claim->insuranceUser->formatted_phone }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ t($company->translation_group . '.national_id') }}</span>
                            <span class="font-medium font-mono text-sm">{{ $claim->insuranceUser->formatted_national_id }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ t($company->translation_group . '.user_policy') }}</span>
                            <span class="font-medium">{{ $claim->insuranceUser->policy_number }}</span>
                        </div>
                        <!-- Vehicle Information -->
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ t($company->translation_group . '.vehicle_brand') }}</span>
                            <span class="font-medium">{{ $claim->vehicle_brand }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ t($company->translation_group . '.vehicle_type') }}</span>
                            <span class="font-medium">{{ $claim->vehicle_type }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ t($company->translation_group . '.vehicle_model') }}</span>
                            <span class="font-medium">{{ $claim->vehicle_model }}</span>
                        </div>
                    </div>
                </div>
            </div>

<!-- Claim Information -->
            <div class="bg-white rounded-xl shadow-sm border">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-bold">{{ t($company->translation_group . '.claim_information') }}</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid md:grid-cols-2 gap-4">
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ t($company->translation_group . '.policy_number') }}</span>
                            <span class="font-medium">{{ $claim->policy_number }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ t($company->translation_group . '.vehicle') }}</span>
                            <span class="font-medium">{{ $claim->vehicle_plate_number ?: $claim->chassis_number }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ t($company->translation_group . '.vehicle_working') }}</span>
                            <span class="font-medium {{ $claim->is_vehicle_working ? 'text-green-600' : 'text-red-600' }}">
                                {{ $claim->is_vehicle_working ? t($company->translation_group . '.yes') : t($company->translation_group . '.no') }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ t($company->translation_group . '.receipt_ready') }}</span>
                            <span class="font-medium">{{ $claim->repair_receipt_ready ? t($company->translation_group . '.yes') : t($company->translation_group . '.no') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ t($company->translation_group . '.submitted') }}</span>
                            <span class="font-medium">{{ $claim->created_at->format('M d, Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Vehicle Location -->
            @if(!empty($claim->vehicle_location))
            <div class="bg-white rounded-xl shadow-sm border">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-bold">{{ t($company->translation_group . '.vehicle_location') }}</h3>
                </div>
                <div class="p-6">
                    <p class="text-gray-700 mb-4">{{ $claim->vehicle_location }}</p>
                    @if($claim->vehicle_location_lat)
                        <a href="{{ $claim->vehicle_location_url }}" target="_blank" 
                           class="inline-flex items-center gap-2 px-4 py-2 rounded-lg font-medium text-white transition-colors"
                           style="background: {{ $company->primary_color }};">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            {{ t($company->translation_group . '.view_on_map') }}
                        </a>
                    @endif
                </div>
            </div>
            @endif

            <!-- Rejection Reason -->
            @if($claim->status === 'rejected' && $claim->rejection_reason)
            <div class="bg-red-50 border border-red-200 rounded-xl">
                <div class="p-6 border-b border-red-200">
                    <h3 class="text-lg font-bold text-red-800">{{ t($company->translation_group . '.rejection_reason') }}</h3>
                </div>
                <div class="p-6">
                    <p class="text-red-700">{{ $claim->rejection_reason }}</p>
                </div>
            </div>
            @endif

            <!-- Service Center Response -->
            @if($claim->status === 'service_center_accepted')
                <div class="bg-green-50 border border-green-200 rounded-xl p-4 my-4">
                    <div class="font-bold text-green-800 mb-2">{{ t('insurance.service_center_accepted') }}</div>
                    <div class="text-green-700">{{ $claim->service_center_note }}</div>
                </div>
            @elseif($claim->status === 'service_center_rejected')
                <div class="bg-red-50 border border-red-200 rounded-xl p-4 my-4">
                    <div class="font-bold text-red-800 mb-2">{{ t('insurance.service_center_rejected') }}</div>
                    <div class="text-red-700">{{ $claim->service_center_note }}</div>
                </div>
            @endif

            <!-- Notes -->
            @if($claim->notes)
            <div class="bg-white rounded-xl shadow-sm border">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-bold">{{ t($company->translation_group . '.notes') }}</h3>
                </div>
                <div class="p-6">
                    <p class="text-gray-700">{{ $claim->notes }}</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Service Center -->
            @if($claim->service_center_id)
            <div class="bg-white rounded-xl shadow-sm border">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-bold">{{ t($company->translation_group . '.assigned_service_center') }}</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <h4 class="font-bold text-gray-900">{{ $claim->serviceCenter->legal_name }}</h4>
                        @if($claim->serviceCenter->center_address)
                            <p class="text-gray-600 text-sm mt-1">{{ $claim->serviceCenter->center_address }}</p>
                        @endif
                    </div>
                    
                    <div class="text-sm text-gray-600">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            {{ $claim->serviceCenter->formatted_phone }}
                        </div>
                    </div>
                    
                    @if($claim->serviceCenter->center_location_lat)
                        <a href="{{ $claim->serviceCenter->location_url }}" target="_blank" 
                           class="inline-flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium text-white transition-colors"
                           style="background: {{ $company->primary_color }};">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            {{ t($company->translation_group . '.view_on_map') }}
                        </a>
                    @endif
                </div>
            </div>
            @endif

            <!-- Parts Pricing Summary -->
            @if($claim->inspection && $claim->inspection->hasPricing())
            <div class="bg-white rounded-xl shadow-sm border">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-bold">Parts Pricing Summary</h3>
                </div>
                <div class="p-6 space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Parts Total:</span>
                        <span class="font-medium">{{ number_format($claim->inspection->parts_total, 2) }} SAR</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Service Fees:</span>
                        <span class="font-medium">{{ number_format($claim->inspection->service_center_fees, 2) }} SAR</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Tax ({{ $claim->inspection->tax_percentage }}%):</span>
                        <span class="font-medium">{{ number_format($claim->inspection->tax_amount, 2) }} SAR</span>
                    </div>
                    <div class="border-t pt-2 flex justify-between font-bold">
                        <span>Total Amount:</span>
                        <span class="text-lg" style="color: {{ $company->primary_color }};">{{ number_format($claim->inspection->total_amount, 2) }} SAR</span>
                    </div>
                    
                    @if($claim->inspection->pricing_status === 'sent_to_insurance')
                        <div class="pt-3">
                            <a href="{{ route('insurance.parts-quotes.show', [$company->company_slug, $claim->inspection->id]) }}" 
                               class="w-full px-4 py-2 rounded-lg text-sm font-medium text-white text-center block"
                               style="background: {{ $company->primary_color }};">
                                Review & Respond
                            </a>
                        </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Tow Service Status -->
            @if($claim->tow_service_offered)
            <div class="bg-white rounded-xl shadow-sm border">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-bold">{{ t($company->translation_group . '.tow_service') }}</h3>
                </div>
                <div class="p-6 space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">{{ t($company->translation_group . '.offered') }}</span>
                        <span class="font-medium text-blue-600">{{ t($company->translation_group . '.yes') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">{{ t($company->translation_group . '.status') }}</span>
                        <span class="font-medium {{ is_null($claim->tow_service_accepted) ? 'text-yellow-600' : ($claim->tow_service_accepted ? 'text-green-600' : 'text-red-600') }}">
                            @if(is_null($claim->tow_service_accepted))
                                {{ t($company->translation_group . '.waiting_response') }}
                            @elseif($claim->tow_service_accepted)
                                {{ t($company->translation_group . '.accepted') }}
                            @else
                                {{ t($company->translation_group . '.declined') }}
                            @endif
                        </span>
                    </div>
                </div>
            </div>
            @endif

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm border">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-bold">{{ t($company->translation_group . '.quick_actions') }}</h3>
                </div>
                <div class="p-6 space-y-3">
                    @if($claim->status === 'pending')
                        <button onclick="approveModal()" 
                                class="w-full px-4 py-3 bg-green-500 text-white rounded-lg font-medium hover:bg-green-600 transition-colors">
                            {{ t($company->translation_group . '.approve_claim') }}
                        </button>
                        <button onclick="rejectModal()" 
                                class="w-full px-4 py-3 bg-red-500 text-white rounded-lg font-medium hover:bg-red-600 transition-colors">
                            {{ t($company->translation_group . '.reject_claim') }}
                        </button>
                    @endif
                    
                    @if($claim->inspection && $claim->inspection->pricing_status === 'sent_to_insurance')
                        <a href="{{ route('insurance.parts-quotes.show', [$company->company_slug, $claim->inspection->id]) }}" 
                           class="w-full px-4 py-3 rounded-lg font-medium text-white text-center block"
                           style="background: {{ $company->primary_color }};">
                            Review Parts Pricing
                        </a>
                    @endif
                    
                    <a href="{{ route('insurance.claims.index', $company->company_slug) }}" 
                       class="w-full px-4 py-3 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition-colors text-center block">
                        {{ t($company->translation_group . '.back_to_claims') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Attachments -->
    @if($claim->attachments->count())
    <div class="bg-white rounded-xl shadow-sm border">
        <div class="p-6 border-b">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold">{{ t($company->translation_group . '.attachments') }}</h3>
                <span class="px-3 py-1 rounded-full text-sm font-medium"
                      style="background: {{ $company->primary_color }}20; color: {{ $company->primary_color }};">
                    {{ $claim->attachments->count() }} {{ t($company->translation_group . '.files') }}
                </span>
            </div>
        </div>
        <div class="p-6">
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($claim->attachments->groupBy('type') as $type => $attachments)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h4 class="font-medium text-gray-900 mb-3">{{ t($company->translation_group . '.' . $type) }}</h4>
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
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
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

<!-- Approve Modal -->
@if($claim->status === 'pending')
<div id="approveModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b">
            <h3 class="text-xl font-bold">{{ t($company->translation_group . '.approve_claim') }} {{ $claim->claim_number }}</h3>
        </div>
        
        <form method="POST" action="{{ route('insurance.claims.approve', [$company->company_slug, $claim->id]) }}" class="p-6 space-y-6">
            @csrf
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">{{ t($company->translation_group . '.select_service_center') }} *</label>
                <select name="service_center_id" id="serviceCenterSelect" required 
                        class="w-full border-gray-300 rounded-lg focus:ring-2 focus:border-transparent px-4 py-2.5"
                        style="focus:ring-color: {{ $company->primary_color }};">
                    <option value="">{{ t($company->translation_group . '.loading') }}</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">{{ t($company->translation_group . '.notes_optional') }}</label>
                <textarea name="notes" rows="4" 
                          class="w-full border-gray-300 rounded-lg focus:ring-2 focus:border-transparent px-4 py-2.5"
                          style="focus:ring-color: {{ $company->primary_color }};"
                          placeholder="{{ t($company->translation_group . '.additional_notes') }}"></textarea>
            </div>
            
            <div class="flex gap-4 pt-4">
                <button type="submit" class="flex-1 py-3 text-white rounded-lg font-medium hover:opacity-90 transition-opacity"
                        style="background: {{ $company->primary_color }};">
                    {{ t($company->translation_group . '.approve_claim') }}
                </button>
                <button type="button" onclick="closeModal('approveModal')" 
                        class="flex-1 py-3 bg-gray-500 text-white rounded-lg font-medium hover:opacity-90 transition-opacity">
                    {{ t($company->translation_group . '.cancel') }}
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl max-w-md w-full">
        <div class="p-6 border-b">
            <h3 class="text-xl font-bold">{{ t($company->translation_group . '.reject_claim') }} {{ $claim->claim_number }}</h3>
        </div>
        
        <form method="POST" action="{{ route('insurance.claims.reject', [$company->company_slug, $claim->id]) }}" class="p-6 space-y-6">
            @csrf
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">{{ t($company->translation_group . '.rejection_reason') }} *</label>
                <textarea name="rejection_reason" rows="4" required 
                          class="w-full border-gray-300 rounded-lg focus:ring-2 focus:border-transparent px-4 py-2.5"
                          style="focus:ring-color: {{ $company->primary_color }};"
                          placeholder="{{ t($company->translation_group . '.explain_rejection_reason') }}"></textarea>
            </div>
            
            <div class="flex gap-4 pt-4">
                <button type="submit" class="flex-1 py-3 bg-red-500 text-white rounded-lg font-medium hover:bg-red-600 transition-colors">
                    {{ t($company->translation_group . '.reject_claim') }}
                </button>
                <button type="button" onclick="closeModal('rejectModal')" 
                        class="flex-1 py-3 bg-gray-500 text-white rounded-lg font-medium hover:opacity-90 transition-opacity">
                    {{ t($company->translation_group . '.cancel') }}
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Load service centers with accepted claims count
fetch('{{ route("insurance.claims.service-centers", $company->company_slug) }}')
    .then(response => response.json())
    .then(data => {
        const select = document.getElementById('serviceCenterSelect');
        select.innerHTML = '<option value="">{{ t($company->translation_group . ".select_service_center") }}</option>';
        data.forEach(center => {
            let claimsCount = center.accepted_claims_count ?? 0;
            let claimsText = claimsCount > 0 
                ? `(${claimsCount} {{ t($company->translation_group . ".accepted_claims") }})`
                : `({{ t($company->translation_group . ".no_accepted_claims") }})`;
            let areaText = center.area ? ` - ${center.area}` : '';
            select.innerHTML += `<option value="${center.id}">${center.name}${areaText} ${claimsText}</option>`;
        });
    })
    .catch(error => {
        const select = document.getElementById('serviceCenterSelect');
        select.innerHTML = '<option value="">{{ t($company->translation_group . ".error_loading") }}</option>';
    });

function approveModal() {
    document.getElementById('approveModal').classList.remove('hidden');
}

function rejectModal() {
    document.getElementById('rejectModal').classList.remove('hidden');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

// Close modal on outside click
document.getElementById('approveModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal('approveModal');
});

document.getElementById('rejectModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal('rejectModal');
});
</script>
@endif
@endsection