@extends('insurance-user.layouts.app')

@section('title', t($company->translation_group . '.claim_details'))

@section('content')
<div class=" space-y-6">
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
    
    <!-- Mobile: Vertical Timeline -->
    <div class="space-y-6 md:hidden">
        @php
            $steps = [
                [
                    'title' => t($company->translation_group . '.submitted'),
                    'date' => $claim->created_at->format('M d'),
                    'icon' => 'check',
                    'color' => $company->primary_color,
                    'active' => true
                ],
                [
                    'title' => t($company->translation_group . '.approved'),
                    'date' => $claim->status !== 'pending' ? t($company->translation_group . '.done') : t($company->translation_group . '.pending'),
                    'icon' => in_array($claim->status, ['approved', 'in_progress', 'completed']) ? 'check' : '2',
                    'color' => in_array($claim->status, ['approved', 'in_progress', 'completed']) ? $company->primary_color : '#e5e7eb',
                    'active' => in_array($claim->status, ['approved', 'in_progress', 'completed'])
                ],
                [
                    'title' => t($company->translation_group . '.vehicle_received'),
                    'date' => $claim->vehicle_arrived_at_center 
                              ? $claim->vehicle_arrived_at_center->format('M d') 
                              : ($claim->status === 'approved' ? t($company->translation_group . '.pending') : t($company->translation_group . '.waiting')),
                    'icon' => ($claim->vehicle_arrived_at_center || in_array($claim->status, ['in_progress', 'completed'])) ? 'check' : '3',
                    'color' => ($claim->vehicle_arrived_at_center || in_array($claim->status, ['in_progress', 'completed'])) ? $company->primary_color : '#e5e7eb',
                    'active' => ($claim->vehicle_arrived_at_center || in_array($claim->status, ['in_progress', 'completed']))
                ],
                [
                    'title' => t($company->translation_group . '.repair_in_progress'),
                    'date' => $claim->status === 'completed' 
                              ? t($company->translation_group . '.completed') 
                              : ($claim->status === 'in_progress' ? t($company->translation_group . '.in_progress') : t($company->translation_group . '.waiting')),
                    'icon' => in_array($claim->status, ['in_progress', 'completed']) ? ($claim->status === 'completed' ? 'check' : 'gear') : '4',
                    'color' => in_array($claim->status, ['in_progress', 'completed']) ? $company->primary_color : '#e5e7eb',
                    'active' => in_array($claim->status, ['in_progress', 'completed'])
                ],
                [
                    'title' => t($company->translation_group . '.ready_for_pickup'),
                    'date' => $claim->status === 'completed' ? t($company->translation_group . '.ready') : t($company->translation_group . '.waiting'),
                    'icon' => $claim->status === 'completed' ? 'check' : '5',
                    'color' => $claim->status === 'completed' ? $company->primary_color : '#e5e7eb',
                    'active' => $claim->status === 'completed'
                ]
            ];
        @endphp

        @foreach($steps as $step)
            <div class="flex items-start gap-4">
                <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold mt-1 flex-shrink-0
                          {{ $step['active'] ? 'text-white' : 'text-gray-400 bg-gray-200' }}"
                     style="{{ $step['active'] ? 'background: ' . $step['color'] : '' }}">
                    @if($step['icon'] === 'check')
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    @elseif($step['icon'] === 'gear')
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826 2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        </svg>
                    @else
                        {{ $step['icon'] }}
                    @endif
                </div>
                <div>
                    <div class="font-medium">{{ $step['title'] }}</div>
                    <div class="text-xs text-gray-500">{{ $step['date'] }}</div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Desktop: Horizontal Timeline -->
    <div class="hidden md:flex items-center justify-between mb-4">
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826 2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
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
                                {{ t($company->translation_group . '.decline_my_self') }}
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
    <div class="flex items-center p-4 mb-4 text-sm text-green-800 rounded-lg bg-gradient-to-r from-green-50 to-green-100 border border-green-200 shadow-md" role="alert">
        <svg class="shrink-0 inline w-5 h-5 me-3 text-green-600" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        <div class="flex items-center justify-between w-full">
            <span class="font-medium text-green-800">{{ t($company->translation_group . '.submitted') }}</span>
            <span class="font-semibold text-green-700 text-sm">{{ $claim->created_at->format('M d, Y H:i') }}</span>
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
@if($claim->vehicle_plate_number || $claim->chassis_number || $claim->policy_number)
<div class="bg-white rounded-xl p-4 md:p-6 border border-gray-200 shadow-sm hover:shadow-md transition-shadow duration-300">
    <!-- رأس السيكشن -->
    <div class="flex items-center gap-3 mb-6 pb-3 border-b" style="border-color: {{ $company->primary_color }};">
        <div class="p-2 rounded-lg shadow-sm" style="background-color: {{ $company->primary_color }}20;">
            <svg class="w-5 h-5 md:w-6 md:h-6" style="color: {{ $company->primary_color }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
            </svg>
        </div>
        <h3 class="text-lg md:text-xl font-bold text-gray-800">{{ t($company->translation_group . '.important_numbers') }}</h3>
    </div>
    
    @if($claim->vehicle_plate_number)
    <div class="mb-6">
        <!-- عنوان رقم اللوحة -->
        <div class="flex items-center gap-2 mb-4">
            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
            </svg>
            <span class="text-sm font-medium text-gray-600">{{ t($company->translation_group . '.vehicle_plate_number') }}</span>
        </div>
        
        <!-- تصميم اللوحة -->
        <div class="flex justify-center items-center">
            <div class="flex border border-gray-200 rounded-lg p-4 bg-white shadow-lg overflow-hidden {{ $isRtl ? 'flex-row-reverse' : 'flex-row-reverse' }}">
                <!-- محتوى اللوحة -->
                <div class="flex flex-col h-24 md:h-28">
                    <!-- الصف الأول - العربية -->
                    <div class="flex items-center justify-center h-1/2 border-b border-black">
                        <!-- خانة الحروف العربية -->
                        <div class="flex gap-1 bg-gray-50 rounded p-1 h-full items-center justify-center">
                            <div class="w-6 h-6 md:w-7 md:h-7 flex items-center justify-center bg-white rounded text-sm md:text-base font-bold" id="display-char-ar1">ر</div>
                            <div class="w-6 h-6 md:w-7 md:h-7 flex items-center justify-center bg-white rounded text-sm md:text-base font-bold" id="display-char-ar2">ج</div>
                            <div class="w-6 h-6 md:w-7 md:h-7 flex items-center justify-center bg-white rounded text-sm md:text-base font-bold" id="display-char-ar3">ب</div>
                        </div>
                        <!-- فاصل عمودي -->
                        <div class="w-0.5 bg-gray-200 mx-1 h-full"></div>
                        <!-- خانة الأرقام العربية -->
                        <div class="flex gap-1 bg-gray-50 rounded p-1 h-full items-center justify-center">
                            <div class="w-6 h-6 md:w-7 md:h-7 flex items-center justify-center bg-white rounded text-sm md:text-base font-bold" id="display-num-ar1">٧</div>
                            <div class="w-6 h-6 md:w-7 md:h-7 flex items-center justify-center bg-white rounded text-sm md:text-base font-bold" id="display-num-ar2">٩</div>
                            <div class="w-6 h-6 md:w-7 md:h-7 flex items-center justify-center bg-white rounded text-sm md:text-base font-bold" id="display-num-ar3">٢</div>
                            <div class="w-6 h-6 md:w-7 md:h-7 flex items-center justify-center bg-white rounded text-sm md:text-base font-bold" id="display-num-ar4">١</div>
                        </div>
                    </div>
                    
                    <!-- الصف الثاني - الإنجليزية -->
                    <div class="flex items-center justify-center h-1/2">
                        <!-- خانة الحروف الإنجليزية -->
                        <div class="flex gap-1 bg-gray-50 rounded p-1 h-full items-center justify-center">
                            <div class="w-6 h-6 md:w-7 md:h-7 flex items-center justify-center bg-white rounded text-sm md:text-base font-bold" id="display-char-en1">D</div>
                            <div class="w-6 h-6 md:w-7 md:h-7 flex items-center justify-center bg-white rounded text-sm md:text-base font-bold" id="display-char-en2">B</div>
                            <div class="w-6 h-6 md:w-7 md:h-7 flex items-center justify-center bg-white rounded text-sm md:text-base font-bold" id="display-char-en3">R</div>
                        </div>
                        <!-- فاصل عمودي -->
                        <div class="w-0.5 bg-gray-200 mx-1 h-full"></div>
                        <!-- خانة الأرقام الإنجليزية -->
                        <div class="flex gap-1 bg-gray-50 rounded p-1 h-full items-center justify-center">
                            <div class="w-6 h-6 md:w-7 md:h-7 flex items-center justify-center bg-white rounded text-sm md:text-base font-bold" id="display-num-en1">7</div>
                            <div class="w-6 h-6 md:w-7 md:h-7 flex items-center justify-center bg-white rounded text-sm md:text-base font-bold" id="display-num-en2">9</div>
                            <div class="w-6 h-6 md:w-7 md:h-7 flex items-center justify-center bg-white rounded text-sm md:text-base font-bold" id="display-num-en3">2</div>
                            <div class="w-6 h-6 md:w-7 md:h-7 flex items-center justify-center bg-white rounded text-sm md:text-base font-bold" id="display-num-en4">1</div>
                        </div>
                    </div>
                </div>
                
                <!-- قسم العلم السعودي -->
                <div class="flex flex-col items-center justify-center w-12 md:w-16 text-gray-800 {{ $isRtl ? 'border-l' : 'border-r' }} border-black px-1 py-2 h-24 md:h-28">
                    <div class="flex items-center mb-1">
                        <span class="text-xs md:text-sm">⚔️</span>
                        <span class="text-xs">🌴</span>
                    </div>
                    <div class="text-xs font-bold mb-1">السعودية</div>
                    <div class="flex flex-col items-center text-xs font-bold">
                        <div>K</div>
                        <div>S</div>
                        <div>A</div>
                    </div>
                    <div class="text-xs mt-1">●</div>
                </div>
            </div>
        </div>
    </div>
    @endif
    
    @if($claim->chassis_number)
    <div class="bg-white rounded-lg p-3 md:p-4 border border-gray-200 shadow-sm hover:shadow-md transition-shadow duration-200 mb-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span class="text-sm font-medium text-gray-600">{{ t($company->translation_group . '.chassis_number') }}</span>
            </div>
            <span class="font-mono text-sm md:text-base font-semibold text-gray-900 bg-gray-100 px-3 py-1 rounded-md break-all">{{ $claim->chassis_number }}</span>
        </div>
    </div>
    @endif
    
    @if($claim->policy_number)
    <div class="bg-white rounded-lg p-3 md:p-4 border border-gray-200 shadow-sm hover:shadow-md transition-shadow duration-200">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span class="text-sm font-medium text-gray-600">{{ t($company->translation_group . '.policy_number') }}</span>
            </div>
            <span class="font-mono text-sm md:text-base font-semibold text-gray-900 bg-gray-100 px-3 py-1 rounded-md break-all">{{ $claim->policy_number }}</span>
        </div>
    </div>
    @endif
</div>
@endif



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

              {{-- Help & Support Section --}}
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200">
                <div class="p-6">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-blue-900 mb-2">{{ t($company->translation_group . '.need_help') }}</h3>
                            <p class="text-blue-700 mb-4">{{ t($company->translation_group . '.support_description') }}</p>
                            
                            <div class="grid sm:grid-cols-2 gap-3">
                                <div class="bg-white rounded-lg p-3 border border-blue-200">
                                    <div class="flex items-center gap-2 text-blue-800">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                        </svg>
                                        <span class="font-medium">{{ t($company->translation_group . '.call_support') }}</span>
                                    </div>
                                    <p class="text-sm text-blue-600 mt-1">{{ $company->formatted_phone }}</p>
                                </div>
                                
                                <div class="bg-white rounded-lg p-3 border border-blue-200">
                                    <div class="flex items-center gap-2 text-blue-800">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                        </svg>
                                        <span class="font-medium">{{ t($company->translation_group . '.submit_complaint') }}</span>
                                    </div>
                                    <a href="{{ route('insurance.complaints.index', $company->company_slug) }}" 
                                       class="text-sm text-blue-600 hover:text-blue-800 mt-1 inline-block">
                                        {{ t($company->translation_group . '.file_complaint_here') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
        </div>

        

<div class="space-y-6">
            @if($claim->service_center_id)
           <div class="bg-white rounded-xl shadow-sm border">
    <div class="p-6 border-b bg-gradient-to-r from-gray-50 to-white">
        <h3 class="text-lg font-bold flex items-center gap-2">
            {{-- أيقونة الـ header --}}
            <div class="w-12 h-8 rounded-lg flex items-center justify-center" style="background: {{ $company->primary_color }}20;">
                <svg class="w-5 h-5" style="color: {{ $company->primary_color }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
            {{ t($company->translation_group . '.assigned_service_center') }}
        </h3>
    </div>
    
    <div class="p-6">
        <div class="flex items-start gap-4">
        {{-- أيقونة المركز (لوجو أو حروف) --}}
@if($claim->serviceCenter->center_logo)
    <img src="{{ $claim->serviceCenter->logo_url }}" 
         alt="{{ $claim->serviceCenter->legal_name }}" 
         class="w-16 h-16 rounded-full object-cover border-2"
         style="border-color: {{ $company->primary_color }}20;">
@else
    <div class="w-16 h-5 rounded-full flex items-center justify-center text-white font-bold text-lg self-start"
         style="background: {{ $company->primary_color }};">
        {{ substr($claim->serviceCenter->legal_name, 0, 2) }}
    </div>
@endif

            
            <div class="flex-1">
                <h4 class="text-xl font-bold text-gray-900 mb-2">{{ $claim->serviceCenter->legal_name }}</h4>
                
                <div class="space-y-3">
                    {{-- أيقونات التواصل الصغيرة --}}
                    @if($claim->serviceCenter->phone)
                    <div class="flex items-center gap-2 text-gray-600">
                        <div class="w-6 h-6 rounded-full flex items-center justify-center" style="background: {{ $company->primary_color }}20;">
                            <svg class="w-4 h-4" style="color: {{ $company->primary_color }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                        </div>
                        <span>{{ $claim->serviceCenter->formatted_phone }}</span>
                    </div>
                    @endif
                    
                    @if($claim->serviceCenter->center_address)
                    <div class="flex items-center gap-2 text-gray-600">
                        <div class="w-6 h-6 rounded-full flex items-center justify-center" style="background: {{ $company->primary_color }}20;">
                            <svg class="w-4 h-4" style="color: {{ $company->primary_color }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <span>{{ $claim->serviceCenter->center_address }}</span>
                    </div>
                    @endif
                    
                    @if($claim->serviceCenter->industrialArea)
                    <div class="flex items-center gap-2 text-gray-600">
                        <div class="w-6 h-6 rounded-full flex items-center justify-center" style="background: {{ $company->primary_color }}20;">
                            <svg class="w-4 h-4" style="color: {{ $company->primary_color }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <span>{{ $claim->serviceCenter->industrialArea->display_name }}</span>
                    </div>
                    @endif
                </div>
                
                {{-- أزرار التواصل --}}
                <div class="flex gap-3 mt-4">
                    <a href="tel:{{ $claim->serviceCenter->phone }}" 
                       class="inline-flex items-center gap-2 px-4 py-2 bg-green-500 text-white rounded-lg font-medium hover:bg-green-600 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        {{ t($company->translation_group . '.call') }}
                    </a>
                    
                    @if($claim->serviceCenter->center_location_lat)
                        <a href="{{ $claim->serviceCenter->location_url }}" target="_blank" 
                           class="inline-flex items-center gap-2 px-4 py-2 text-white rounded-lg font-medium hover:opacity-90 transition-opacity"
                           style="background: {{ $company->primary_color }};">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            </svg>
                            {{ t($company->translation_group . '.location') }}
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

            @endif

         
{{-- Claim Timeline Section --}}
<div class="bg-white rounded-xl shadow-sm border">
    <div class="p-6 border-b bg-gradient-to-r from-gray-50 to-white">
        <h3 class="text-lg font-bold flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background: {{ $company->primary_color }}20;">
                <svg class="w-5 h-5" style="color: {{ $company->primary_color }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            {{ t($company->translation_group . '.claim_timeline') }}
        </h3>
    </div>
    
    <div class="p-6">
        <div class="flow-root">
            <ul class="space-y-6 md:space-y-8">
                {{-- Claim Submitted --}}
                <li>
                    <div class="flex gap-4 md:gap-3">
                        <div class="flex flex-col items-center">
                            <span class="h-10 w-10 rounded-full flex items-center justify-center ring-8 ring-white"
                                  style="background: {{ $company->primary_color }};">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ t($company->translation_group . '.claim_submitted') }}</p>
                                <p class="mt-1 text-sm text-gray-500">{{ $claim->created_at->format('M d, Y \a\t H:i') }}</p>
                            </div>
                            <p class="mt-2 text-sm text-gray-700">{{ t($company->translation_group . '.claim_submitted_for_review') }}</p>
                        </div>
                    </div>
                </li>

                {{-- Service Center Assigned --}}
                @if($claim->service_center_id && $claim->status !== 'rejected')
                <li>
                    <div class="flex gap-4 md:gap-3">
                        <div class="flex flex-col items-center">
                            <span class="h-10 w-10 rounded-full flex items-center justify-center ring-8 ring-white"
                                  style="background: {{ $company->primary_color }};">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ t($company->translation_group . '.assigned_to_service_center') }}</p>
                                @if($claim->service_center_approved_at)
                                    <p class="mt-1 text-sm text-gray-500">{{ $claim->service_center_approved_at->format('M d, Y \a\t H:i') }}</p>
                                @endif
                            </div>
                            <p class="mt-2 text-sm text-gray-700">{{ t($company->translation_group . '.claim_sent_to') }} {{ $claim->serviceCenter->legal_name }}</p>
                        </div>
                    </div>
                </li>
                @endif

                {{-- Tow Service Decision --}}
                @if($claim->tow_service_offered && !is_null($claim->tow_service_accepted))
                <li>
                    <div class="flex gap-4 md:gap-3">
                        <div class="flex flex-col items-center">
                            <span class="h-10 w-10 rounded-full flex items-center justify-center ring-8 ring-white"
                                  style="background: {{ $claim->tow_service_accepted ? '#10b981' : '#f59e0b' }};">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    @if($claim->tow_service_accepted)
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    @else
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    @endif
                                </svg>
                            </span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <div>
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $claim->tow_service_accepted ? t($company->translation_group . '.tow_service_accepted') : t($company->translation_group . '.tow_service_declined') }}
                                </p>
                                <p class="mt-1 text-sm text-gray-500">{{ $claim->updated_at->format('M d, Y \a\t H:i') }}</p>
                            </div>
                            <p class="mt-2 text-sm text-gray-700">
                                @if($claim->tow_service_accepted)
                                    {{ t($company->translation_group . '.tow_service_requested') }}
                                @else
                                    {{ t($company->translation_group . '.customer_will_deliver_vehicle') }}
                                @endif
                            </p>
                        </div>
                    </div>
                </li>
                @endif

                {{-- Vehicle Arrived at Service Center --}}
                @if($claim->vehicle_arrived_at_center)
                <li>
                    <div class="flex gap-4 md:gap-3">
                        <div class="flex flex-col items-center">
                            <span class="h-10 w-10 rounded-full flex items-center justify-center ring-8 ring-white"
                                  style="background: #10b981;">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ t($company->translation_group . '.vehicle_received_by_service_center') }}</p>
                                <p class="mt-1 text-sm text-gray-500">{{ $claim->vehicle_arrived_at_center->format('M d, Y \a\t H:i') }}</p>
                            </div>
                            <p class="mt-2 text-sm text-gray-700">{{ t($company->translation_group . '.vehicle_inspection_started') }}</p>
                        </div>
                    </div>
                </li>
                @endif

                {{-- Parts Received/Repair Started --}}
                @if($claim->status === 'in_progress')
                <li>
                    <div class="flex gap-4 md:gap-3">
                        <div class="flex flex-col items-center">
                            <span class="h-10 w-10 rounded-full flex items-center justify-center ring-8 ring-white"
                                  style="background: {{ $company->primary_color }};">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826 2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ t($company->translation_group . '.repair_work_started') }}</p>
                                <p class="mt-1 text-sm text-gray-500">{{ t($company->translation_group . '.in_progress') }}</p>
                            </div>
                            <p class="mt-2 text-sm text-gray-700">{{ t($company->translation_group . '.vehicle_being_repaired_by_technicians') }}</p>
                        </div>
                    </div>
                </li>
                @endif

                {{-- Repair Completed --}}
                @if($claim->status === 'completed')
                <li>
                    <div class="flex gap-4 md:gap-3">
                        <div class="flex flex-col items-center">
                            <span class="h-10 w-10 rounded-full flex items-center justify-center ring-8 ring-white"
                                  style="background: #10b981;">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ t($company->translation_group . '.repair_completed') }}</p>
                                <p class="mt-1 text-sm text-gray-500">{{ t($company->translation_group . '.ready_for_pickup') }}</p>
                            </div>
                            <p class="mt-2 text-sm text-gray-700">{{ t($company->translation_group . '.vehicle_repaired_ready_collection') }}</p>
                        </div>
                    </div>
                </li>
                @endif

                {{-- Rejection Timeline (if rejected) --}}
                @if($claim->status === 'rejected')
                <li>
                    <div class="flex gap-4 md:gap-3">
                        <div class="flex flex-col items-center">
                            <span class="h-10 w-10 rounded-full flex items-center justify-center ring-8 ring-white"
                                  style="background: #ef4444;">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ t($company->translation_group . '.claim_rejected') }}</p>
                                <p class="mt-1 text-sm text-gray-500">{{ $claim->updated_at->format('M d, Y \a\t H:i') }}</p>
                            </div>
                            <p class="mt-2 text-sm text-gray-700 text-red-600">{{ $claim->rejection_reason }}</p>
                        </div>
                    </div>
                </li>
                @endif
            </ul>
        </div>
    </div>
</div>




            {{-- Action Buttons Section --}}
            <div class="bg-white rounded-xl shadow-sm border hidden">
                <div class="p-6">
                    <div class="flex flex-col sm:flex-row gap-4">
                        {{-- Back to Claims List --}}
                        <a href="{{ route('insurance.user.claims.index', $company->company_slug) }}" 
                           class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                            {{ t($company->translation_group . '.back_to_claims') }}
                        </a>

                        {{-- Action buttons based on status --}}
                        @if($claim->status === 'rejected')
                            <a href="{{ route('insurance.user.claims.edit', [$company->company_slug, $claim->id]) }}" 
                               class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-blue-500 text-white rounded-lg font-medium hover:bg-blue-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                {{ t($company->translation_group . '.edit_and_resubmit') }}
                            </a>
                        @elseif($claim->status === 'completed')
                            <a href="tel:{{ $claim->serviceCenter->phone }}" 
                               class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-green-500 text-white rounded-lg font-medium hover:bg-green-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                {{ t($company->translation_group . '.call_to_schedule_pickup') }}
                            </a>
                        @elseif($claim->serviceCenter && $claim->status !== 'pending')
                            <a href="tel:{{ $claim->serviceCenter->phone }}" 
                               class="inline-flex items-center justify-center gap-2 px-6 py-3 text-white rounded-lg font-medium hover:opacity-90 transition-opacity"
                               style="background: {{ $company->primary_color }};">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                {{ t($company->translation_group . '.contact_service_center') }}
                            </a>
                        @endif

                        {{-- Print/Share Options --}}
                        <div class="flex gap-2">
                            <button onclick="window.print()" 
                                    class="inline-flex items-center justify-center gap-2 px-4 py-3 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                </svg>
                                <span class="hidden sm:inline">{{ t($company->translation_group . '.print') }}</span>
                            </button>

                            <button onclick="shareClaimDetails()" 
                                    class="inline-flex items-center justify-center gap-2 px-4 py-3 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                                </svg>
                                <span class="hidden sm:inline">{{ t($company->translation_group . '.share') }}</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

          
        </div>
    </div>
</div>
   {{-- Claim Attachments Section --}}
            @if($claim->attachments->count())
            <div class="bg-white rounded-xl shadow-sm border mt-8">
                <div class="p-6 border-b bg-gradient-to-r from-gray-50 to-white">
                    <h3 class="text-lg font-bold flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background: {{ $company->primary_color }}20;">
                            <svg class="w-5 h-5" style="color: {{ $company->primary_color }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                            </svg>
                        </div>
                        {{ t($company->translation_group . '.uploaded_documents') }}
                    </h3>
                </div>
                
                <div class="p-6">
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($claim->attachments->groupBy('type') as $type => $attachments)
                        <div class="bg-gray-50 rounded-lg p-4 border-l-4" style="border-color: {{ $company->primary_color }};">
                            <h4 class="font-medium text-gray-900 mb-3 flex items-center gap-2">
                                <svg class="w-4 h-4" style="color: {{ $company->primary_color }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                {{ t($company->translation_group . '.' . $type) }}
                            </h4>
                            <div class="space-y-2">
                                @foreach($attachments as $attachment)
                                <div class="flex items-center justify-between bg-white rounded p-2 border">
                                    <div class="flex items-center gap-2 flex-1 min-w-0">
                                        <div class="w-8 h-8 rounded flex items-center justify-center flex-shrink-0"
                                             style="background: {{ $company->primary_color }}20;">
                                            @if($attachment->isImage())
                                                <svg class="w-4 h-4" style="color: {{ $company->primary_color }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4" style="color: {{ $company->primary_color }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                            @endif
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-sm font-medium text-gray-900 truncate">{{ $attachment->file_name }}</p>
                                            <p class="text-xs text-gray-500">{{ $attachment->file_size_formatted }}</p>
                                        </div>
                                    </div>
                                    <a href="{{ $attachment->file_url }}" target="_blank" 
                                       class="flex-shrink-0 w-8 h-8 rounded flex items-center justify-center hover:bg-gray-100 transition-colors">
                                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                        </svg>
                                    </a>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
{{-- Custom Scripts --}}
<script>
function shareClaimDetails() {
    if (navigator.share) {
        navigator.share({
            title: '{{ t($company->translation_group . ".claim_details") }} - {{ $claim->claim_number }}',
            text: '{{ t($company->translation_group . ".claim_number") }}: {{ $claim->claim_number }}\n{{ t($company->translation_group . ".status") }}: {{ t($company->translation_group . "." . $claim->status) }}',
            url: window.location.href
        }).catch(console.error);
    } else {
        // Fallback for browsers that don't support Web Share API
        navigator.clipboard.writeText(window.location.href).then(() => {
            alert('{{ t($company->translation_group . ".link_copied") }}');
        }).catch(() => {
            // Fallback for older browsers
            const textArea = document.createElement('textarea');
            textArea.value = window.location.href;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);
            alert('{{ t($company->translation_group . ".link_copied") }}');
        });
    }
}

// Auto-refresh page every 30 seconds if claim is in progress
@if(in_array($claim->status, ['approved', 'in_progress']) && !$claim->vehicle_arrived_at_center)
setInterval(function() {
    // Only refresh if the tab is active
    if (!document.hidden) {
        location.reload();
    }
}, 30000);
@endif

// Print styles
const printStyles = `
    <style media="print">
        .no-print { display: none !important; }
        body { font-size: 12px; }
        .bg-gradient-to-r { background: #f8f9fa !important; }
        .shadow-sm { box-shadow: none !important; }
        .border { border: 1px solid #dee2e6 !important; }
    </style>
`;
document.head.insertAdjacentHTML('beforeend', printStyles);
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const plateNumber = '{{ $claim->vehicle_plate_number }}';
    if (plateNumber) {
        const plateData = parsePlateNumber(plateNumber);
        if (plateData) {
            // تحديث الحروف العربية
            document.getElementById('display-char-ar1').textContent = plateData.ar1;
            document.getElementById('display-char-ar2').textContent = plateData.ar2;
            document.getElementById('display-char-ar3').textContent = plateData.ar3;
            
            // تحديث الأرقام العربية
            document.getElementById('display-num-ar1').textContent = plateData.arNum1;
            document.getElementById('display-num-ar2').textContent = plateData.arNum2;
            document.getElementById('display-num-ar3').textContent = plateData.arNum3;
            document.getElementById('display-num-ar4').textContent = plateData.arNum4;
            
            // تحديث الحروف الإنجليزية
            document.getElementById('display-char-en1').textContent = plateData.en1;
            document.getElementById('display-char-en2').textContent = plateData.en2;
            document.getElementById('display-char-en3').textContent = plateData.en3;
            
            // تحديث الأرقام الإنجليزية
            document.getElementById('display-num-en1').textContent = plateData.enNum1;
            document.getElementById('display-num-en2').textContent = plateData.enNum2;
            document.getElementById('display-num-en3').textContent = plateData.enNum3;
            document.getElementById('display-num-en4').textContent = plateData.enNum4;
        }
    }
});
</script>

{{-- Custom Print Styles --}}
<style>
@media print {
    .no-print {
        display: none !important;
    }
    
    .bg-gradient-to-r {
        background: #f8f9fa !important;
        -webkit-print-color-adjust: exact;
    }
    
    .shadow-sm, .shadow-md {
        box-shadow: none !important;
    }
    
    .border {
        border: 1px solid #dee2e6 !important;
    }
    
    .rounded-xl, .rounded-lg {
        border-radius: 8px !important;
    }
    
    .text-white {
        color: #000 !important;
    }
    
    .bg-blue-50, .bg-green-50, .bg-yellow-50, .bg-red-50 {
        background: #f8f9fa !important;
        -webkit-print-color-adjust: exact;
    }
}

/* Custom scrollbar for better UX */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb {
    background: {{ $company->primary_color }};
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: {{ $company->primary_color }}dd;
}

/* Smooth transitions */
.transition-all {
    transition: all 0.3s ease;
}

/* Enhanced hover effects */
.hover\:scale-105:hover {
    transform: scale(1.05);
}

/* Loading animation for auto-refresh */
.loading-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: .5;
    }
}
</style>

  <script>
        // Mobile menu toggle
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const sidebar = document.getElementById('sidebar');
        const mobileOverlay = document.getElementById('mobileOverlay');
        const isRtl = document.documentElement.dir === 'rtl';

        function toggleMobileMenu() {
            if (sidebar.classList.contains(isRtl ? 'translate-x-full' : '-translate-x-full')) {
                sidebar.classList.remove(isRtl ? 'translate-x-full' : '-translate-x-full');
                mobileOverlay.classList.remove('hidden');
            } else {
                sidebar.classList.add(isRtl ? 'translate-x-full' : '-translate-x-full');
                mobileOverlay.classList.add('hidden');
            }
        }

        if (mobileMenuBtn) {
            mobileMenuBtn.addEventListener('click', toggleMobileMenu);
        }

        if (mobileOverlay) {
            mobileOverlay.addEventListener('click', toggleMobileMenu);
        }

        document.querySelectorAll('#sidebar a').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 1024) {
                    sidebar.classList.add(isRtl ? 'translate-x-full' : '-translate-x-full');
                    mobileOverlay.classList.add('hidden');
                }
            });
        });

        // Language change function
        function changeLanguage(code) {
            window.location.href = '/language/' + code;
        }
    </script>

    <script>
        function parsePlateNumber(plateNumber) {
    // تحليل رقم اللوحة المحفوظ وإرجاع كائن يحتوي على الأجزاء المختلفة
    // يجب تعديل هذه الدالة حسب تنسيق حفظ البيانات
    if (!plateNumber) return null;
    
    // مثال على التحليل (يحتاج تعديل حسب طريقة حفظ البيانات)
    return {
        ar1: plateNumber.charAt(0) || '',
        ar2: plateNumber.charAt(1) || '',
        ar3: plateNumber.charAt(2) || '',
        arNum1: plateNumber.charAt(3) || '',
        arNum2: plateNumber.charAt(4) || '',
        arNum3: plateNumber.charAt(5) || '',
        arNum4: plateNumber.charAt(6) || '',
        en1: plateNumber.charAt(7) || '',
        en2: plateNumber.charAt(8) || '',
        en3: plateNumber.charAt(9) || '',
        enNum1: plateNumber.charAt(10) || '',
        enNum2: plateNumber.charAt(11) || '',
        enNum3: plateNumber.charAt(12) || '',
        enNum4: plateNumber.charAt(13) || ''
    };
}

        </script>
@endsection