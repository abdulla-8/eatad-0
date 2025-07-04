@extends('insurance-user.layouts.app')

@section('title', t($company->translation_group . '.claim_details'))

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('insurance.user.claims.index', $company->company_slug) }}" 
               class="w-10 h-10 rounded-lg border flex items-center justify-center hover:bg-gray-50 transition-colors">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center text-white font-bold"
                     style="background: {{ $company->primary_color }};">
                    {{ substr($claim->claim_number, -2) }}
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $claim->claim_number }}</h1>
                    <p class="text-gray-600">{{ t($company->translation_group . '.claim_details') }}</p>
                </div>
            </div>
        </div>
        
        <div class="flex items-center gap-4">
            <span class="px-4 py-2 rounded-full text-sm font-medium {{ $claim->status_badge['class'] }}">
                {{ t($company->translation_group . '.' . $claim->status) }}
            </span>
            
            @if($claim->status === 'rejected')
                <a href="{{ route('insurance.user.claims.edit', [$company->company_slug, $claim->id]) }}" 
                   class="px-6 py-2.5 bg-blue-500 text-white rounded-lg font-medium hover:bg-blue-600 transition-colors">
                    {{ t($company->translation_group . '.edit_resubmit') }}
                </a>
            @endif
        </div>
    </div>

    <!-- Status Messages -->
    @if($claim->status === 'rejected')
        <div class="bg-red-50 border border-red-200 rounded-xl p-6">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="font-bold text-red-800 mb-2">{{ t($company->translation_group . '.claim_rejected') }}</h3>
                    <p class="text-red-700 mb-3">{{ $claim->rejection_reason }}</p>
                    <a href="{{ route('insurance.user.claims.edit', [$company->company_slug, $claim->id]) }}" 
                       class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        {{ t($company->translation_group . '.edit_resubmit') }}
                    </a>
                </div>
            </div>
        </div>
    @endif

     @if($claim->tow_service_offered && is_null($claim->tow_service_accepted))
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="font-bold text-blue-800 mb-2">{{ t($company->translation_group . '.tow_service_offered') }}</h3>
                    <p class="text-blue-700 mb-4">{{ t($company->translation_group . '.tow_service_description') }}</p>
                    <form method="POST" action="{{ route('insurance.user.claims.tow-service', [$company->company_slug, $claim->id]) }}" 
                          class="flex gap-3">
                        @csrf
                        <button type="submit" name="tow_service_accepted" value="1" 
                                class="px-4 py-2 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition-colors">
                            {{ t($company->translation_group . '.accept_tow_service') }}
                        </button>
                        <button type="submit" name="tow_service_accepted" value="0" 
                                class="px-4 py-2 bg-gray-600 text-white rounded-lg font-medium hover:bg-gray-700 transition-colors">
                            {{ t($company->translation_group . '.decline_go_myself') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endif

@if($claim->tow_service_accepted === true && $claim->tow_request_id)
        <div class="bg-green-50 border border-green-200 rounded-xl p-6">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="font-bold text-green-800 mb-2">{{ t($company->translation_group . '.tow_service_requested') }}</h3>
                    <p class="text-green-700 mb-3">{{ t($company->translation_group . '.tow_request_sent_to_providers') }}</p>
                    
                    @if($towRequestDetails)
                        <div class="bg-white border border-green-200 rounded-lg p-4 space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="font-medium text-green-800">{{ t($company->translation_group . '.request_status') }}:</span>
                                <span class="px-3 py-1 rounded-full text-sm font-medium {{ $towRequestDetails['status_badge']['class'] ?? 'bg-blue-100 text-blue-800' }}">
                                    {{ $towRequestDetails['status_badge']['text'] ?? $towRequestDetails['status'] }}
                                </span>
                            </div>
                            
                            @if($towRequestDetails['provider_info'])
                                <div class="border-t border-green-100 pt-3">
                                    <h4 class="font-medium text-green-800 mb-2">{{ t($company->translation_group . '.service_provider') }}</h4>
                                    <div class="space-y-1 text-sm">
                                        <div><strong>{{ t($company->translation_group . '.name') }}:</strong> {{ $towRequestDetails['provider_info']['name'] }}</div>
                                        <div><strong>{{ t($company->translation_group . '.phone') }}:</strong> {{ $towRequestDetails['provider_info']['phone'] }}</div>
                                        <div><strong>{{ t($company->translation_group . '.type') }}:</strong> {{ t($company->translation_group . '.' . $towRequestDetails['provider_info']['type']) }}</div>
                                    </div>
                                </div>
                            @endif
                            
                            @if($towRequestDetails['customer_verification_code'] && in_array($towRequestDetails['status'], ['arrived_at_pickup']))
                                <div class="border-t border-green-100 pt-3">
                                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                                        <h4 class="font-medium text-yellow-800 mb-2">🚛 {{ t($company->translation_group . '.driver_arrived') }}</h4>
                                        <p class="text-yellow-700 text-sm mb-2">{{ t($company->translation_group . '.provide_verification_code') }}</p>
                                        <div class="bg-white border-2 border-yellow-300 rounded-lg p-3 text-center">
                                            <span class="text-2xl font-bold text-yellow-800 tracking-wider">{{ $towRequestDetails['customer_verification_code'] }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            
                            <div class="border-t border-green-100 pt-3">
                                <a href="{{ $towRequestDetails['tracking_url'] }}" 
                                   target="_blank"
                                   class="inline-flex items-center gap-2 px-4 py-2 rounded-lg font-medium text-white transition-colors w-full justify-center"
                                   style="background: {{ $company->primary_color }};">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    </svg>
                                    {{ t($company->translation_group . '.track_tow_service') }}
                                </a>
                            </div>
                            
                            @if($towRequestDetails['estimated_pickup_time'])
                                <div class="text-sm text-green-700">
                                    <strong>{{ t($company->translation_group . '.estimated_pickup') }}:</strong> 
                                    {{ \Carbon\Carbon::parse($towRequestDetails['estimated_pickup_time'])->format('M d, Y H:i') }}
                                </div>
                            @endif
                            
                            @if($towRequestDetails['actual_pickup_time'])
                                <div class="text-sm text-green-700">
                                    <strong>{{ t($company->translation_group . '.actual_pickup') }}:</strong> 
                                    {{ \Carbon\Carbon::parse($towRequestDetails['actual_pickup_time'])->format('M d, Y H:i') }}
                                </div>
                            @endif
                            
                            @if($towRequestDetails['actual_delivery_time'])
                                <div class="text-sm text-green-700">
                                    <strong>{{ t($company->translation_group . '.delivered_at') }}:</strong> 
                                    {{ \Carbon\Carbon::parse($towRequestDetails['actual_delivery_time'])->format('M d, Y H:i') }}
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <div class="grid lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Claim Information -->
            <div class="bg-white rounded-xl shadow-sm border">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-bold flex items-center gap-2">
                        <svg class="w-5 h-5" style="color: {{ $company->primary_color }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        {{ t($company->translation_group . '.claim_information') }}
                    </h3>
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
                        @if($claim->tow_service_offered)
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ t($company->translation_group . '.tow_service') }}</span>
                                <span class="font-medium {{ is_null($claim->tow_service_accepted) ? 'text-yellow-600' : ($claim->tow_service_accepted ? 'text-green-600' : 'text-red-600') }}">
                                    @if(is_null($claim->tow_service_accepted))
                                        {{ t($company->translation_group . '.pending_response') }}
                                    @elseif($claim->tow_service_accepted)
                                        {{ t($company->translation_group . '.accepted') }}
                                    @else
                                        {{ t($company->translation_group . '.declined') }}
                                    @endif
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Vehicle Location -->
            <div class="bg-white rounded-xl shadow-sm border">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-bold flex items-center gap-2">
                        <svg class="w-5 h-5" style="color: {{ $company->primary_color }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        {{ t($company->translation_group . '.vehicle_location') }}
                    </h3>
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

            <!-- Notes -->
            @if($claim->notes)
            <div class="bg-white rounded-xl shadow-sm border">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-bold flex items-center gap-2">
                        <svg class="w-5 h-5" style="color: {{ $company->primary_color }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        {{ t($company->translation_group . '.additional_notes') }}
                    </h3>
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
                    <h3 class="text-lg font-bold flex items-center gap-2">
                        <svg class="w-5 h-5" style="color: {{ $company->primary_color }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        {{ t($company->translation_group . '.assigned_service_center') }}
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <h4 class="font-bold text-gray-900">{{ $claim->serviceCenter->legal_name }}</h4>
                        @if($claim->serviceCenter->center_address)
                            <p class="text-gray-600 text-sm mt-1">{{ $claim->serviceCenter->center_address }}</p>
                        @endif
                    </div>
                    
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        {{ $claim->serviceCenter->formatted_phone }}
                    </div>
                    
                    @if($claim->serviceCenter->center_location_lat)
                        <a href="{{ $claim->serviceCenter->location_url }}" target="_blank" 
                           class="inline-flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium text-white transition-colors w-full justify-center"
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

            <!-- Company Information -->
            <div class="bg-white rounded-xl shadow-sm border">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-bold flex items-center gap-2">
                        <svg class="w-5 h-5" style="color: {{ $company->primary_color }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                        {{ t($company->translation_group . '.insurance_company') }}
                    </h3>
                </div>
                <div class="p-6 space-y-3">
                    <div>
                        <h4 class="font-bold text-gray-900">{{ $claim->insuranceCompany->legal_name }}</h4>
                        @if($claim->insuranceCompany->office_address)
                            <p class="text-gray-600 text-sm mt-1">{{ $claim->insuranceCompany->office_address }}</p>
                        @endif
                    </div>
                    
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        {{ $claim->insuranceCompany->formatted_phone }}
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm border">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-bold">{{ t($company->translation_group . '.quick_actions') }}</h3>
                </div>
                <div class="p-6 space-y-3">
                    @if($claim->status === 'rejected')
                        <a href="{{ route('insurance.user.claims.edit', [$company->company_slug, $claim->id]) }}" 
                           class="w-full px-4 py-3 bg-blue-500 text-white rounded-lg font-medium hover:bg-blue-600 transition-colors text-center block">
                            {{ t($company->translation_group . '.edit_resubmit') }}
                        </a>
                    @endif
                    
                    <a href="{{ route('insurance.user.claims.index', $company->company_slug) }}" 
                       class="w-full px-4 py-3 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition-colors text-center block">
                        {{ t($company->translation_group . '.back_to_claims') }}
                    </a>
                    
                    <a href="{{ route('insurance.user.claims.create', $company->company_slug) }}" 
                       class="w-full px-4 py-3 rounded-lg font-medium text-white hover:opacity-90 transition-opacity text-center block"
                       style="background: {{ $company->primary_color }};">
                        {{ t($company->translation_group . '.new_claim') }}
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
                <h3 class="text-lg font-bold flex items-center gap-2">
                    <svg class="w-5 h-5" style="color: {{ $company->primary_color }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                    </svg>
                    {{ t($company->translation_group . '.attachments') }}
                </h3>
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
                                           class="block text-sm font-medium text-gray-900 hover:underline truncate"
                                           style="color: {{ $company->primary_color }};">
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
@endsection