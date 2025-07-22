@extends('insurance-user.layouts.app')

@section('title', t($company->translation_group . '.my_claims'))

@push('styles')
<style>
    /* إصلاح شامل للتوافق مع RTL و LTR */
    .claims-container {
        overflow-x: hidden;
        width: 100%;
        max-width: 100vw;
        box-sizing: border-box;
    }
    
    /* توجيه مرن للعناصر */
    .flex-direction-responsive {
        flex-direction: column;
    }
    
    @media (min-width: 1024px) {
        .flex-direction-responsive {
            flex-direction: row;
        }
    }
    
    /* محاذاة للنصوص والعناصر */
    .text-align-responsive {
        text-align: center;
    }
    
    @media (min-width: 768px) {
        .text-align-responsive {
            text-align: inherit;
        }
    }
    
    /* مسافات مرنة */
    .gap-responsive {
        gap: 0.75rem;
    }
    
    @media (min-width: 768px) {
        .gap-responsive {
            gap: 1rem;
        }
    }
    
    @media (min-width: 1024px) {
        .gap-responsive {
            gap: 1.5rem;
        }
    }
    
    /* تحسين الأزرار */
    .btn-responsive {
        width: 100%;
        justify-content: center;
    }
    
    @media (min-width: 640px) {
        .btn-responsive {
            width: auto;
            justify-content: flex-start;
        }
    }
    
    /* تحسين النصوص */
    .text-responsive {
        font-size: 0.875rem;
        line-height: 1.25rem;
    }
    
    @media (min-width: 640px) {
        .text-responsive {
            font-size: 1rem;
            line-height: 1.5rem;
        }
    }
    
    /* تحسين العناوين */
    .heading-responsive {
        font-size: 1.5rem;
        line-height: 2rem;
    }
    
    @media (min-width: 640px) {
        .heading-responsive {
            font-size: 1.875rem;
            line-height: 2.25rem;
        }
    }
</style>
@endpush

@section('content')
<div class="claims-container px-3 py-4 sm:px-4 lg:px-6 xl:px-8 space-y-6">
    <!-- Header -->
    <div class="flex flex-direction-responsive lg:items-center lg:justify-between gap-responsive">
        <div class="min-w-0 flex-1">
            <h1 class="heading-responsive font-bold text-gray-900 break-words">{{ t($company->translation_group . '.my_claims') }}</h1>
            <p class="text-gray-600 mt-1 text-responsive break-words">{{ t($company->translation_group . '.track_your_claims') }}</p>
        </div>
        <a href="{{ route('insurance.user.claims.create', $company->company_slug) }}" 
           class="inline-flex items-center gap-2 px-4 py-3 sm:px-6 text-white rounded-lg font-medium hover:opacity-90 transition-opacity btn-responsive"
           style="background: {{ $company->primary_color }};">
            <svg class="w-4 h-4 sm:w-5 sm:h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            <span class="text-sm sm:text-base truncate">{{ t($company->translation_group . '.new_claim') }}</span>
        </a>
    </div>

    @if($claims->count())
        <!-- Claims Grid -->
        <div class="grid gap-4 sm:gap-6">
            @foreach($claims as $claim)
            <div class="bg-white rounded-xl shadow-sm border hover:shadow-md transition-all duration-300 overflow-hidden">
                <div class="p-4 sm:p-6">
                    <!-- Header -->
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-4">
                        <div class="flex items-center gap-3 sm:gap-4 min-w-0 flex-1">
                            <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-xl flex items-center justify-center text-white font-bold text-base sm:text-lg flex-shrink-0"
                                 style="background: {{ $company->primary_color }};">
                                {{ substr($claim->claim_number, -2) }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <h3 class="text-lg sm:text-xl font-bold text-gray-900 break-words">{{ $claim->claim_number }}</h3>
                                <p class="text-gray-600 text-sm sm:text-base break-words">{{ t($company->translation_group . '.policy') }}: {{ $claim->policy_number }}</p>
                            </div>
                        </div>
                        
                        <div class="flex flex-row sm:flex-col items-start sm:items-end justify-between sm:justify-start gap-2 flex-shrink-0">
                            <span class="px-3 py-1.5 rounded-full text-xs sm:text-sm font-medium {{ $claim->user_status_badge['class'] }} break-words">
                                {{ t($company->translation_group . '.' . $claim->user_status) }}
                            </span>
                            <span class="text-xs sm:text-sm text-gray-500 whitespace-nowrap">{{ $claim->created_at->format('M d, Y') }}</span>
                        </div>
                    </div>

                    <!-- Status Progress Indicator -->
                    @if($claim->status !== 'rejected')
                    <div class="mb-6">
                        <div class="flex items-center justify-between text-xs text-gray-500 mb-2">
                            <span class="break-words">{{ t($company->translation_group . '.claim_progress') }}</span>
                            <span class="whitespace-nowrap">
                                @switch($claim->status)
                                    @case('pending')
                                        {{ t($company->translation_group . '.step') }} 1/5
                                        @break
                                    @case('approved')
                                        @if($claim->vehicle_arrived_at_center)
                                            {{ t($company->translation_group . '.step') }} 3/5
                                        @else
                                            {{ t($company->translation_group . '.step') }} 2/5
                                        @endif
                                        @break
                                    @case('in_progress')
                                        {{ t($company->translation_group . '.step') }} 4/5
                                        @break
                                    @case('completed')
                                        {{ t($company->translation_group . '.step') }} 5/5
                                        @break
                                    @default
                                        {{ t($company->translation_group . '.step') }} 1/5
                                @endswitch
                            </span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                            <div class="h-2 rounded-full transition-all duration-500" 
                                 style="background: {{ $company->primary_color }}; width: 
                                 @switch($claim->status)
                                     @case('pending') 20% @break
                                     @case('approved')
                                         @if($claim->vehicle_arrived_at_center) 60% @else 40% @endif
                                         @break
                                     @case('in_progress') 80% @break
                                     @case('completed') 100% @break
                                     @default 20%
                                 @endswitch">
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Status Alerts -->
                    @if($claim->status === 'completed')
                        <div class="bg-green-50 border border-green-200 rounded-lg p-3 sm:p-4 mb-4 overflow-hidden">
                            <div class="flex items-start gap-3">
                                <div class="w-6 h-6 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h4 class="font-bold text-green-800 mb-1 text-sm sm:text-base break-words">{{ t($company->translation_group . '.repair_completed') }}</h4>
                                    <p class="text-green-700 text-xs sm:text-sm break-words">{{ t($company->translation_group . '.vehicle_ready_for_pickup') }}</p>
                                    <p class="text-green-700 text-xs sm:text-sm font-medium mt-1 break-words">{{ t($company->translation_group . '.contact_service_center_to_collect') }}</p>
                                </div>
                            </div>
                        </div>
                    @elseif($claim->status === 'in_progress')
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 sm:p-4 mb-4 overflow-hidden">
                            <div class="flex items-start gap-3">
                                <div class="w-6 h-6 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h4 class="font-bold text-blue-800 mb-1 text-sm sm:text-base break-words">{{ t($company->translation_group . '.repair_in_progress') }}</h4>
                                    <p class="text-blue-700 text-xs sm:text-sm break-words">{{ t($company->translation_group . '.vehicle_being_repaired') }}</p>
                                </div>
                            </div>
                        </div>
                    @elseif($claim->vehicle_arrived_at_center)
                        <div class="bg-green-50 border border-green-200 rounded-lg p-3 sm:p-4 mb-4 overflow-hidden">
                            <div class="flex items-start gap-3">
                                <div class="w-6 h-6 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h4 class="font-bold text-green-800 mb-1 text-sm sm:text-base break-words">{{ t($company->translation_group . '.vehicle_received_by_service_center') }}</h4>
                                    <p class="text-green-700 text-xs sm:text-sm break-words">{{ t($company->translation_group . '.vehicle_under_inspection') }}</p>
                                </div>
                            </div>
                        </div>
                    @elseif($claim->status === 'approved' && $claim->tow_service_offered && is_null($claim->tow_service_accepted))
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 sm:p-4 mb-4 overflow-hidden">
                            <div class="flex items-start gap-3">
                                <div class="w-6 h-6 rounded-full bg-yellow-100 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.19 2.5 1.732 2.5z"></path>
                                    </svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h4 class="font-bold text-yellow-800 mb-1 text-sm sm:text-base break-words">{{ t($company->translation_group . '.action_required') }}</h4>
                                    <p class="text-yellow-700 text-xs sm:text-sm break-words">{{ t($company->translation_group . '.tow_service_decision_needed') }}</p>
                                </div>
                            </div>
                        </div>
                    @elseif($claim->customer_delivery_code && !$claim->vehicle_arrived_at_center)
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 sm:p-4 mb-4 overflow-hidden">
                            <div class="flex items-start gap-3">
                                <div class="w-6 h-6 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                                    </svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h4 class="font-bold text-blue-800 mb-1 text-sm sm:text-base break-words">{{ t($company->translation_group . '.delivery_required') }}</h4>
                                    <p class="text-blue-700 text-xs sm:text-sm break-words">
                                        {{ t($company->translation_group . '.bring_vehicle_with_code') }}: 
                                        <span class="font-mono font-bold break-all">{{ $claim->customer_delivery_code }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Content -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 sm:gap-6 mb-6">
                        <div class="space-y-2 min-w-0">
                            <h4 class="font-medium text-gray-900 flex items-center gap-2 text-sm sm:text-base">
                                <svg class="w-4 h-4 flex-shrink-0" style="color: {{ $company->primary_color }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                <span class="break-words">{{ t($company->translation_group . '.vehicle_info') }}</span>
                            </h4>
                            <div class="text-xs sm:text-sm text-gray-600">
                                <div class="break-words">{{ $claim->vehicle_plate_number ?: $claim->chassis_number }}</div>
                                <div class="{{ $claim->is_vehicle_working ? 'text-green-600' : 'text-red-600' }} break-words">
                                    {{ $claim->is_vehicle_working ? t($company->translation_group . '.working') : t($company->translation_group . '.not_working') }}
                                </div>
                            </div>
                        </div>
                        
                        @if(!empty($claim->vehicle_location))
                        <div class="space-y-2 min-w-0">
                            <h4 class="font-medium text-gray-900 flex items-center gap-2 text-sm sm:text-base">
                                <svg class="w-4 h-4 flex-shrink-0" style="color: {{ $company->primary_color }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span class="break-words">{{ t($company->translation_group . '.location') }}</span>
                            </h4>
                            <div class="text-xs sm:text-sm text-gray-600 break-words">
                                {{ Str::limit($claim->vehicle_location, 50) }}
                            </div>
                        </div>
                        @endif
                        
                        @if($claim->service_center_id)
                        <div class="space-y-2 min-w-0">
                            <h4 class="font-medium text-gray-900 flex items-center gap-2 text-sm sm:text-base">
                                <svg class="w-4 h-4 flex-shrink-0" style="color: {{ $company->primary_color }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                <span class="break-words">{{ t($company->translation_group . '.service_center') }}</span>
                            </h4>
                            <div class="text-xs sm:text-sm text-gray-600 break-words">
                                {{ Str::limit($claim->serviceCenter->legal_name, 30) }}
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pt-4 border-t border-gray-100">
                        <div class="flex flex-col sm:flex-row gap-3 min-w-0 flex-1">
                       <a href="{{ route('insurance.user.claims.show', [$company->company_slug, $claim->id]) }}" 
   class="inline-flex items-center gap-2 px-4 py-2 rounded-lg font-medium text-white transition-colors text-sm sm:text-base justify-center sm:justify-start"
   style="background: {{ $company->primary_color }};">
    <span class="break-words">{{ t($company->translation_group . '.view_details') }}</span>
    <svg class="w-4 h-4 flex-shrink-0 {{ $isRtl ? '' : '' }}" 
         fill="none" 
         stroke="currentColor" 
         viewBox="0 0 24 24"
         style="{{ $isRtl ? 'transform: scaleX(-1);' : '' }}">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
    </svg>
</a>

                            
                            @if($claim->status === 'rejected')
                                <a href="{{ route('insurance.user.claims.edit', [$company->company_slug, $claim->id]) }}" 
                                   class="inline-flex items-center gap-2 px-4 py-2 bg-blue-500 text-white rounded-lg font-medium hover:bg-blue-600 transition-colors text-sm sm:text-base justify-center sm:justify-start">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    <span class="break-words">{{ t($company->translation_group . '.edit_resubmit') }}</span>
                                </a>
                            @elseif($claim->status === 'completed')
                                <a href="tel:{{ $claim->serviceCenter->phone }}" 
                                   class="inline-flex items-center gap-2 px-4 py-2 bg-green-500 text-white rounded-lg font-medium hover:bg-green-600 transition-colors text-sm sm:text-base justify-center sm:justify-start">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    <span class="break-words">{{ t($company->translation_group . '.call_service_center') }}</span>
                                </a>
                            @endif
                        </div>

                        <div class="text-xs sm:text-sm text-gray-500 whitespace-nowrap text-align-responsive sm:text-right">
                            {{ t($company->translation_group . '.submitted') }}: {{ $claim->created_at->format('H:i') }}
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="flex justify-center overflow-x-auto">
            {{ $claims->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
            <div class="p-8 sm:p-12 text-center">
                <div class="w-16 h-16 sm:w-20 sm:h-20 mx-auto mb-6 rounded-full flex items-center justify-center"
                     style="background: {{ $company->primary_color }}20;">
                    <svg class="w-8 h-8 sm:w-10 sm:h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: {{ $company->primary_color }};">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-2 break-words">{{ t($company->translation_group . '.no_claims_yet') }}</h3>
                <p class="text-gray-600 mb-6 text-sm sm:text-base break-words px-2">{{ t($company->translation_group . '.no_claims_description') }}</p>
                <a href="{{ route('insurance.user.claims.create', $company->company_slug) }}" 
                   class="inline-flex items-center gap-2 px-4 sm:px-6 py-3 text-white rounded-lg font-medium hover:opacity-90 transition-opacity w-full sm:w-auto justify-center"
                   style="background: {{ $company->primary_color }};">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <span class="text-sm sm:text-base truncate">{{ t($company->translation_group . '.submit_first_claim') }}</span>
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
