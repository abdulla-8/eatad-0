@extends('insurance-user.layouts.app')

@section('title', t($company->translation_group . '.claim_details'))

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
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

    <!-- Progress Tracker -->
    @if($claim->status !== 'rejected')
    <div class="bg-white rounded-xl shadow-sm border p-6">
        <h3 class="text-lg font-bold mb-4 flex items-center gap-2">
            <svg class="w-5 h-5" style="color: {{ $company->primary_color }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            {{ t($company->translation_group . '.claim_progress') }}
        </h3>
        
        <div class="flex items-center justify-between mb-4">
            <!-- Step 1: Submitted -->
            <div class="flex flex-col items-center flex-1">
                <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold mb-2" 
                     style="background: {{ $company->primary_color }};">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <span class="text-xs font-medium text-center">{{ t($company->translation_group . '.submitted') }}</span>
                <span class="text-xs text-gray-500">{{ $claim->created_at->format('M d') }}</span>
            </div>
            
            <!-- Connector -->
            <div class="flex-1 h-0.5 mx-2" 
                 style="background: {{ in_array($claim->status, ['approved', 'in_progress', 'completed']) || $claim->vehicle_arrived_at_center ? $company->primary_color : '#e5e7eb' }};"></div>
            
            <!-- Step 2: Approved -->
            <div class="flex flex-col items-center flex-1">
                <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold mb-2
                            {{ in_array($claim->status, ['approved', 'in_progress', 'completed']) ? 'text-white' : 'text-gray-400 bg-gray-200' }}"
                     style="{{ in_array($claim->status, ['approved', 'in_progress', 'completed']) ? 'background: ' . $company->primary_color : '' }}">
                    @if(in_array($claim->status, ['approved', 'in_progress', 'completed']))
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    @else
                        2
                    @endif
                </div>
                <span class="text-xs font-medium text-center">{{ t($company->translation_group . '.approved') }}</span>
                <span class="text-xs text-gray-500">
                    @if($claim->status !== 'pending')
                        {{ t($company->translation_group . '.done') }}
                    @else
                        {{ t($company->translation_group . '.pending') }}
                    @endif
                </span>
            </div>
            
            <!-- Connector -->
            <div class="flex-1 h-0.5 mx-2" 
                 style="background: {{ $claim->vehicle_arrived_at_center || in_array($claim->status, ['in_progress', 'completed']) ? $company->primary_color : '#e5e7eb' }};"></div>
            
            <!-- Step 3: Vehicle Received -->
            <div class="flex flex-col items-center flex-1">
                <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold mb-2
                            {{ $claim->vehicle_arrived_at_center || in_array($claim->status, ['in_progress', 'completed']) ? 'text-white' : 'text-gray-400 bg-gray-200' }}"
                     style="{{ $claim->vehicle_arrived_at_center || in_array($claim->status, ['in_progress', 'completed']) ? 'background: ' . $company->primary_color : '' }}">
                    @if($claim->vehicle_arrived_at_center || in_array($claim->status, ['in_progress', 'completed']))
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    @else
                        3
                    @endif
                </div>
                <span class="text-xs font-medium text-center">{{ t($company->translation_group . '.vehicle_received') }}</span>
                <span class="text-xs text-gray-500">
                    @if($claim->vehicle_arrived_at_center)
                        {{ $claim->vehicle_arrived_at_center->format('M d') }}
                    @elseif($claim->status === 'approved')
                        {{ t($company->translation_group . '.pending') }}
                    @else
                        {{ t($company->translation_group . '.waiting') }}
                    @endif
                </span>
            </div>
            
            <!-- Connector -->
            <div class="flex-1 h-0.5 mx-2" 
                 style="background: {{ in_array($claim->status, ['in_progress', 'completed']) ? $company->primary_color : '#e5e7eb' }};"></div>
            
            <!-- Step 4: Repair In Progress -->
            <div class="flex flex-col items-center flex-1">
                <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold mb-2
                            {{ in_array($claim->status, ['in_progress', 'completed']) ? 'text-white' : 'text-gray-400 bg-gray-200' }}"
                     style="{{ in_array($claim->status, ['in_progress', 'completed']) ? 'background: ' . $company->primary_color : '' }}">
                    @if(in_array($claim->status, ['in_progress', 'completed']))
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            @if($claim->status === 'completed')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            @else
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            @endif
                        </svg>
                    @else
                        4
                    @endif
                </div>
                <span class="text-xs font-medium text-center">{{ t($company->translation_group . '.repair_in_progress') }}</span>
                <span class="text-xs text-gray-500">
                    @if($claim->status === 'completed')
                        {{ t($company->translation_group . '.completed') }}
                    @elseif($claim->status === 'in_progress')
                        {{ t($company->translation_group . '.in_progress') }}
                    @else
                        {{ t($company->translation_group . '.waiting') }}
                    @endif
                </span>
            </div>
            
            <!-- Connector -->
            <div class="flex-1 h-0.5 mx-2" 
                 style="background: {{ $claim->status === 'completed' ? $company->primary_color : '#e5e7eb' }};"></div>
            
            <!-- Step 5: Ready for Pickup -->
            <div class="flex flex-col items-center flex-1">
                <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold mb-2
                            {{ $claim->status === 'completed' ? 'text-white' : 'text-gray-400 bg-gray-200' }}"
                     style="{{ $claim->status === 'completed' ? 'background: ' . $company->primary_color : '' }}">
                    @if($claim->status === 'completed')
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    @else
                        5
                    @endif
                </div>
                <span class="text-xs font-medium text-center">{{ t($company->translation_group . '.ready_for_pickup') }}</span>
                <span class="text-xs text-gray-500">
                    @if($claim->status === 'completed')
                        {{ t($company->translation_group . '.ready') }}
                    @else
                        {{ t($company->translation_group . '.waiting') }}
                    @endif
                </span>
            </div>
        </div>
    </div>
    @endif

    {{-- Main Status Alert --}}
    @if($claim->status === 'completed')
        <div class="bg-green-50 border border-green-200 rounded-xl p-6">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="font-bold text-green-800 mb-2 text-lg">🎉 {{ t($company->translation_group . '.congratulations_repair_completed') }}</h3>
                    <p class="text-green-700 mb-3">{{ t($company->translation_group . '.vehicle_ready_for_pickup_message') }}</p>
                    
                    <div class="bg-white border border-green-200 rounded-lg p-4 mb-4">
                        <h4 class="font-bold text-green-800 mb-2">{{ t($company->translation_group . '.next_steps') }}:</h4>
                        <ul class="list-disc list-inside text-green-700 space-y-1">
                            <li>{{ t($company->translation_group . '.contact_service_center_to_schedule_pickup') }}</li>
                            <li>{{ t($company->translation_group . '.bring_id_and_policy_documents') }}</li>
                            <li>{{ t($company->translation_group . '.inspect_vehicle_before_leaving') }}</li>
                        </ul>
                    </div>
                    
                    <div class="flex gap-3">
                        <a href="tel:{{ $claim->serviceCenter->phone }}" 
                           class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            {{ t($company->translation_group . '.call_service_center') }}
                        </a>
                        
                        @if($claim->serviceCenter->center_location_lat)
                            <a href="{{ $claim->serviceCenter->location_url }}" target="_blank" 
                               class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                </svg>
                                {{ t($company->translation_group . '.get_directions') }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @elseif($claim->status === 'rejected')
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
    @elseif($claim->status === 'in_progress')
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-blue-800 mb-2">{{ t($company->translation_group . '.repair_in_progress') }}</h3>
                    <p class="text-blue-700">{{ t($company->translation_group . '.vehicle_being_repaired_message') }}</p>
                    <p class="text-blue-700 text-sm mt-2">{{ t($company->translation_group . '.will_notify_when_ready') }}</p>
                </div>
            </div>
        </div>
    @elseif($claim->vehicle_arrived_at_center)
        <div class="bg-green-50 border border-green-200 rounded-xl p-6">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-green-800 mb-2">{{ t($company->translation_group . '.vehicle_received_by_service_center') }}</h3>
                    <p class="text-green-700">{{ t($company->translation_group . '.vehicle_now_under_inspection') }}</p>
                    <p class="text-green-700 text-sm mt-1">{{ t($company->translation_group . '.received_at') }}: {{ $claim->vehicle_arrived_at_center->format('M d, Y H:i') }}</p>
                </div>
            </div>
        </div>
    @elseif($claim->status === 'approved')
        {{-- Other approved status alerts like tow service, delivery codes etc. --}}
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
        @elseif($claim->tow_service_accepted === true && $claim->tow_request_id)
            {{-- Tow service tracking section --}}
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
                                            <h4 class="font-medium text-yellow-800 mb-2">{{ t($company->translation_group . '.driver_arrived') }}</h4>
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
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @elseif($claim->customer_delivery_code)
            {{-- Customer delivery code section --}}
            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6">
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-full bg-yellow-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        @if($claim->is_vehicle_working)
                            <h3 class="font-bold text-yellow-800 mb-2">{{ t($company->translation_group . '.vehicle_is_working') }}</h3>
                            <p class="text-yellow-700 mb-4">{{ t($company->translation_group . '.drive_to_service_center') }}</p>
                        @else
                            <h3 class="font-bold text-yellow-800 mb-2">{{ t($company->translation_group . '.deliver_vehicle_yourself') }}</h3>
                            <p class="text-yellow-700 mb-4">{{ t($company->translation_group . '.take_vehicle_to_service_center') }}</p>
                        @endif
                        
                        <div class="bg-white border-2 border-yellow-300 rounded-lg p-4 mb-4">
                            <h4 class="font-bold text-yellow-800 mb-2">{{ t($company->translation_group . '.delivery_verification_code') }}</h4>
                            <div class="text-center">
                                <span class="text-3xl font-bold text-yellow-800 tracking-wider font-mono">{{ $claim->customer_delivery_code }}</span>
                            </div>
                            <p class="text-yellow-700 text-sm mt-2 text-center">{{ t($company->translation_group . '.show_this_code_to_service_center') }}</p>
                        </div>
                        
                        <div class="bg-yellow-100 rounded-lg p-3">
                            <h5 class="font-medium text-yellow-800 mb-2">{{ t($company->translation_group . '.service_center_details') }}</h5>
                            <div class="space-y-1 text-sm text-yellow-700">
                                <div><strong>{{ t($company->translation_group . '.name') }}:</strong> {{ $claim->serviceCenter->legal_name }}</div>
                                <div><strong>{{ t($company->translation_group . '.phone') }}:</strong> {{ $claim->serviceCenter->formatted_phone }}</div>
                                @if($claim->serviceCenter->center_address)
                                    <div><strong>{{ t($company->translation_group . '.address') }}:</strong> {{ $claim->serviceCenter->center_address }}</div>
                                @endif
                                @if($claim->serviceCenter->center_location_lat)
                                    <div class="mt-2">
                                        <a href="{{ $claim->serviceCenter->location_url }}" target="_blank" 
                                           class="inline-flex items-center gap-1 text-yellow-800 hover:text-yellow-900 font-medium">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            </svg>
                                            {{ t($company->translation_group . '.view_on_map') }}
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endif

    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            {{-- Enhanced Claim Information Section --}}
            <div class="bg-white rounded-xl shadow-sm border">
                <div class="p-6 border-b bg-gradient-to-r from-gray-50 to-white">
                    <h3 class="text-lg font-bold flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background: {{ $company->primary_color }}20;">
                            <svg class="w-5 h-5" style="color: {{ $company->primary_color }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        {{ t($company->translation_group . '.claim_information') }}
                    </h3>
                </div>
                
                <div class="p-6">
                    {{-- Basic Information Section --}}
                    <div class="mb-8">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-6 h-6 rounded-full flex items-center justify-center" style="background: {{ $company->primary_color }};">
                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <h4 class="text-md font-semibold text-gray-900">{{ t($company->translation_group . '.basic_information') }}</h4>
                        </div>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div class="bg-gray-50 rounded-lg p-4 border-l-4" style="border-color: {{ $company->primary_color }};">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <span class="text-sm text-gray-600">{{ t($company->translation_group . '.policy_number') }}</span>
                                    </div>
                                    <span class="font-semibold text-gray-900">{{ $claim->policy_number }}</span>
                                </div>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-4 border-l-4" style="border-color: {{ $company->primary_color }};">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-4 12v-6m6 6H6a2 2 0 01-2-2v-6a2 2 0 012-2h12a2 2 0 012 2v6a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <span class="text-sm text-gray-600">{{ t($company->translation_group . '.submitted') }}</span>
                                    </div>
                                    <span class="font-semibold text-gray-900">{{ $claim->created_at->format('M d, Y H:i') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Vehicle Information Section --}}
                    <div class="mb-8">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-6 h-6 rounded-full flex items-center justify-center" style="background: {{ $company->primary_color }};">
                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                            <h4 class="text-md font-semibold text-gray-900">{{ t($company->translation_group . '.vehicle_information') }}</h4>
                        </div>
                        <div class="grid md:grid-cols-2 gap-4">
                            @if($claim->vehicle_plate_number || $claim->chassis_number)
                            <div class="bg-gray-50 rounded-lg p-4 border-l-4" style="border-color: {{ $company->primary_color }};">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                        </svg>
                                        <span class="text-sm text-gray-600">{{ t($company->translation_group . '.vehicle_identification') }}</span>
                                    </div>
                                    <span class="font-semibold text-gray-900">{{ $claim->vehicle_plate_number ?: $claim->chassis_number }}</span>
                                </div>
                            </div>
                            @endif
                            
                            @if($claim->vehicle_brand)
                            <div class="bg-gray-50 rounded-lg p-4 border-l-4" style="border-color: {{ $company->primary_color }};">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                        <span class="text-sm text-gray-600">{{ t($company->translation_group . '.vehicle_brand') }}</span>
                                    </div>
                                    <span class="font-semibold text-gray-900">{{ $claim->vehicle_brand }}</span>
                                </div>
                            </div>
                            @endif
                            
                            @if($claim->vehicle_type)
                            <div class="bg-gray-50 rounded-lg p-4 border-l-4" style="border-color: {{ $company->primary_color }};">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                        </svg>
                                        <span class="text-sm text-gray-600">{{ t($company->translation_group . '.vehicle_type') }}</span>
                                    </div>
                                    <span class="font-semibold text-gray-900">{{ $claim->vehicle_type }}</span>
                                </div>
                            </div>
                            @endif
                            
                            @if($claim->vehicle_model)
                            <div class="bg-gray-50 rounded-lg p-4 border-l-4" style="border-color: {{ $company->primary_color }};">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"></path>
                                        </svg>
                                        <span class="text-sm text-gray-600">{{ t($company->translation_group . '.vehicle_model') }}</span>
                                    </div>
                                    <span class="font-semibold text-gray-900">{{ $claim->vehicle_model }}</span>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Vehicle Status Section --}}
                    <div class="mb-8">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-6 h-6 rounded-full flex items-center justify-center" style="background: {{ $company->primary_color }};">
                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h4 class="text-md font-semibold text-gray-900">{{ t($company->translation_group . '.vehicle_status') }}</h4>
                        </div>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div class="bg-gray-50 rounded-lg p-4 border-l-4" style="border-color: {{ $claim->is_vehicle_working ? '#10b981' : '#ef4444' }};">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 {{ $claim->is_vehicle_working ? 'text-green-500' : 'text-red-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $claim->is_vehicle_working ? 'M5 13l4 4L19 7' : 'M6 18L18 6M6 6l12 12' }}"></path>
                                        </svg>
                                        <span class="text-sm text-gray-600">{{ t($company->translation_group . '.vehicle_working') }}</span>
                                    </div>
                                    <span class="font-semibold {{ $claim->is_vehicle_working ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $claim->is_vehicle_working ? t($company->translation_group . '.yes') : t($company->translation_group . '.no') }}
                                    </span>
                                </div>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-4 border-l-4" style="border-color: {{ $claim->repair_receipt_ready ? '#10b981' : '#ef4444' }};">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 {{ $claim->repair_receipt_ready ? 'text-green-500' : 'text-red-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <span class="text-sm text-gray-600">{{ t($company->translation_group . '.receipt_ready') }}</span>
                                    </div>
                                    <span class="font-semibold {{ $claim->repair_receipt_ready ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $claim->repair_receipt_ready ? t($company->translation_group . '.yes') : t($company->translation_group . '.no') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Service Information Section --}}
                    @if($claim->tow_service_offered || $claim->customer_delivery_code || $claim->vehicle_arrived_at_center)
                    <div>
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-6 h-6 rounded-full flex items-center justify-center" style="background: {{ $company->primary_color }};">
                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                            <h4 class="text-md font-semibold text-gray-900">{{ t($company->translation_group . '.service_information') }}</h4>
                        </div>
                        <div class="grid md:grid-cols-2 gap-4">
                            @if($claim->tow_service_offered)
                                <div class="bg-gray-50 rounded-lg p-4 border-l-4" style="border-color: {{ is_null($claim->tow_service_accepted) ? '#f59e0b' : ($claim->tow_service_accepted ? '#10b981' : '#ef4444') }};">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path>
                                            </svg>
                                            <span class="text-sm text-gray-600">{{ t($company->translation_group . '.tow_service') }}</span>
                                        </div>
                                        <span class="font-semibold {{ is_null($claim->tow_service_accepted) ? 'text-yellow-600' : ($claim->tow_service_accepted ? 'text-green-600' : 'text-red-600') }}">
                                            @if(is_null($claim->tow_service_accepted))
                                                {{ t($company->translation_group . '.pending_response') }}
                                            @elseif($claim->tow_service_accepted)
                                                {{ t($company->translation_group . '.accepted') }}
                                            @else
                                                {{ t($company->translation_group . '.declined') }}
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            @endif
                            
                            @if($claim->customer_delivery_code && !$claim->vehicle_arrived_at_center)
                                <div class="bg-gray-50 rounded-lg p-4 border-l-4" style="border-color: {{ $company->primary_color }};">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                                            </svg>
                                            <span class="text-sm text-gray-600">{{ t($company->translation_group . '.delivery_code') }}</span>
                                        </div>
                                        <span class="font-semibold font-mono text-lg" style="color: {{ $company->primary_color }};">{{ $claim->customer_delivery_code }}</span>
                                    </div>
                                </div>
                            @endif
                            
                            @if($claim->vehicle_arrived_at_center)
                                <div class="bg-gray-50 rounded-lg p-4 border-l-4 border-green-500">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            <span class="text-sm text-gray-600">{{ t($company->translation_group . '.arrived_at_center') }}</span>
                                        </div>
                                        <span class="font-semibold text-green-600">{{ $claim->vehicle_arrived_at_center->format('M d, Y H:i') }}</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            @if(!empty($claim->vehicle_location))
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
            @endif

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

        <div class="space-y-6">
            @if($claim->service_center_id)
            <div class="bg-white rounded-xl shadow-sm border">
                <div class="p-6 border-b">
                    <h3 class="text-lg