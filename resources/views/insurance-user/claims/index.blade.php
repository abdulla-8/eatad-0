@extends('insurance-user.layouts.app')

@section('title', t($company->translation_group . '.my_claims'))

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ t($company->translation_group . '.my_claims') }}</h1>
            <p class="text-gray-600 mt-1">{{ t($company->translation_group . '.track_your_claims') }}</p>
        </div>
        <a href="{{ route('insurance.user.claims.create', $company->company_slug) }}" 
           class="inline-flex items-center gap-2 px-6 py-3 text-white rounded-lg font-medium hover:opacity-90 transition-opacity"
           style="background: {{ $company->primary_color }};">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            {{ t($company->translation_group . '.new_claim') }}
        </a>
    </div>

    @if($claims->count())
        <!-- Claims Grid -->
        <div class="grid gap-6">
            @foreach($claims as $claim)
            <div class="bg-white rounded-xl shadow-sm border hover:shadow-md transition-all duration-300">
                <div class="p-6">
                    <!-- Header -->
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-xl flex items-center justify-center text-white font-bold text-lg"
                                 style="background: {{ $company->primary_color }};">
                                {{ substr($claim->claim_number, -2) }}
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">{{ $claim->claim_number }}</h3>
                                <p class="text-gray-600">{{ t($company->translation_group . '.policy') }}: {{ $claim->policy_number }}</p>
                            </div>
                        </div>
                        
                        <div class="flex flex-col items-end gap-2">
                      
<span class="px-3 py-1.5 rounded-full text-sm font-medium {{ $claim->user_status_badge['class'] }}">
    {{ t($company->translation_group . '.' . $claim->user_status) }}
</span>
                            <span class="text-sm text-gray-500">{{ $claim->created_at->format('M d, Y') }}</span>
                        </div>
                    </div>

                    <!-- Content -->
                    
                    <div class="grid md:grid-cols-3 gap-6 mb-6">
                        <div class="space-y-2">
                            <h4 class="font-medium text-gray-900 flex items-center gap-2">
                                <svg class="w-4 h-4" style="color: {{ $company->primary_color }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                {{ t($company->translation_group . '.vehicle_info') }}
                            </h4>
                            <div class="text-sm text-gray-600">
                                <div>{{ $claim->vehicle_plate_number ?: $claim->chassis_number }}</div>
                                <div class="{{ $claim->is_vehicle_working ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $claim->is_vehicle_working ? t($company->translation_group . '.working') : t($company->translation_group . '.not_working') }}
                                </div>
                            </div>
                        </div>
                        
                    @if(!empty($claim->vehicle_location))
    <div class="space-y-2 ">
        <h4 class="font-medium text-gray-900 flex items-center gap-2">
            <svg class="w-4 h-4" style="color: {{ $company->primary_color }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            {{ t($company->translation_group . '.location') }}
        </h4>
        <div class="text-sm text-gray-600">
            {{ Str::limit($claim->vehicle_location, 50) }}
        </div>
    </div>
@endif

                        
                        @if($claim->service_center_id)
                        <div class="space-y-2">
                            <h4 class="font-medium text-gray-900 flex items-center gap-2">
                                <svg class="w-4 h-4" style="color: {{ $company->primary_color }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                {{ t($company->translation_group . '.service_center') }}
                            </h4>
                            <div class="text-sm text-gray-600">
                                {{ Str::limit($claim->serviceCenter->legal_name, 30) }}
                            </div>
                        </div>
                        @endif
                    </div>


<!-- إضافة شرط عرض أزرار خدمة السحب -->
@if($claim->status === 'approved' && !$claim->is_vehicle_working)
    <div class="mt-4 flex gap-4">
        <form method="POST" action="{{ route('insurance.user.claims.tow-service', [$company->company_slug, $claim->id]) }}">
            @csrf
            <input type="hidden" name="accepted" value="1">
            <button type="submit" 
                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                {{ t($company->translation_group . '.accept_tow_service') }}
            </button>
        </form>

        <form method="POST" action="{{ route('insurance.user.claims.tow-service', [$company->company_slug, $claim->id]) }}">
            @csrf
            <input type="hidden" name="accepted" value="0">
            <button type="submit" 
                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                {{ t($company->translation_group . '.reject_tow_service') }}
            </button>
        </form>
    </div>
@endif



                    <!-- Actions -->
                    <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                        <div class="flex gap-3">
                            <a href="{{ route('insurance.user.claims.show', [$company->company_slug, $claim->id]) }}" 
                               class="inline-flex items-center gap-2 px-4 py-2 rounded-lg font-medium text-white transition-colors"
                               style="background: {{ $company->primary_color }};">
                                {{ t($company->translation_group . '.view_details') }}
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                            
                            @if($claim->status === 'rejected')
                                <a href="{{ route('insurance.user.claims.edit', [$company->company_slug, $claim->id]) }}" 
                                   class="inline-flex items-center gap-2 px-4 py-2 bg-blue-500 text-white rounded-lg font-medium hover:bg-blue-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    {{ t($company->translation_group . '.edit_resubmit') }}
                                </a>
                            @endif
                        </div>

                        <div class="text-sm text-gray-500">
                            {{ t($company->translation_group . '.submitted') }}: {{ $claim->created_at->format('H:i') }}
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="flex justify-center">
            {{ $claims->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-xl shadow-sm border">
            <div class="p-12 text-center">
                <div class="w-20 h-20 mx-auto mb-6 rounded-full flex items-center justify-center"
                     style="background: {{ $company->primary_color }}20;">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: {{ $company->primary_color }};">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ t($company->translation_group . '.no_claims_yet') }}</h3>
                <p class="text-gray-600 mb-6">{{ t($company->translation_group . '.no_claims_description') }}</p>
                <a href="{{ route('insurance.user.claims.create', $company->company_slug) }}" 
                   class="inline-flex items-center gap-2 px-6 py-3 text-white rounded-lg font-medium hover:opacity-90 transition-opacity"
                   style="background: {{ $company->primary_color }};">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    {{ t($company->translation_group . '.submit_first_claim') }}
                </a>
            </div>
        </div>
    @endif
</div>
@endsection