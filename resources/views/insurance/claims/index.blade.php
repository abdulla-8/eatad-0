@extends('insurance.layouts.app')

@section('title', t($company->translation_group . '.claims_management'))

@section('content')
<div class="space-y-6">
    <!-- Header with Stats -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ t($company->translation_group . '.claims_management') }}</h1>
            <p class="text-gray-600 mt-1">{{ t($company->translation_group . '.manage_all_claims') }}</p>
        </div>
        
        <!-- Quick Stats -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
            <div class="bg-white rounded-lg border px-4 py-3 text-center shadow-sm">
                <div class="text-2xl font-bold text-blue-600">{{ $stats['total'] }}</div>
                <div class="text-xs text-gray-600">{{ t($company->translation_group . '.total') }}</div>
            </div>
            <div class="bg-white rounded-lg border px-4 py-3 text-center shadow-sm">
                <div class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</div>
                <div class="text-xs text-gray-600">{{ t($company->translation_group . '.pending') }}</div>
            </div>
            <div class="bg-white rounded-lg border px-4 py-3 text-center shadow-sm">
                <div class="text-2xl font-bold text-green-600">{{ $stats['approved'] }}</div>
                <div class="text-xs text-gray-600">{{ t($company->translation_group . '.approved') }}</div>
            </div>
            <div class="bg-white rounded-lg border px-4 py-3 text-center shadow-sm">
                <div class="text-2xl font-bold text-red-600">{{ $stats['rejected'] }}</div>
                <div class="text-xs text-gray-600">{{ t($company->translation_group . '.rejected') }}</div>
            </div>
        </div>
    </div>

    <!-- Filters Card -->
    <div class="bg-white rounded-xl shadow-sm border">
        <div class="p-6">
            <form method="GET" class="flex flex-col lg:flex-row gap-4">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ t($company->translation_group . '.search') }}</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="{{ t($company->translation_group . '.search_placeholder') }}"
                           class="w-full border-gray-300 rounded-lg focus:ring-2 focus:border-transparent px-4 py-2.5"
                           style="focus:ring-color: {{ $company->primary_color }};">
                </div>
                
                <div class="lg:w-48">
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ t($company->translation_group . '.status') }}</label>
                    <select name="status" class="w-full border-gray-300 rounded-lg focus:ring-2 focus:border-transparent px-4 py-2.5"
                            style="focus:ring-color: {{ $company->primary_color }};">
                        <option value="">{{ t($company->translation_group . '.all_status') }}</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>{{ t($company->translation_group . '.pending') }}</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>{{ t($company->translation_group . '.approved') }}</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>{{ t($company->translation_group . '.rejected') }}</option>
                    </select>
                </div>
                
                <div class="flex gap-2 lg:items-end">
                    <button type="submit" class="px-6 py-2.5 text-white rounded-lg font-medium hover:opacity-90 transition-opacity"
                            style="background: {{ $company->primary_color }};">
                        {{ t($company->translation_group . '.filter') }}
                    </button>
                    
                    @if(request()->hasAny(['status', 'search']))
                        <a href="{{ route('insurance.claims.index', $company->company_slug) }}" 
                           class="px-6 py-2.5 bg-gray-500 text-white rounded-lg font-medium hover:opacity-90 transition-opacity">
                            {{ t($company->translation_group . '.clear') }}
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Claims List -->
    @if($claims->count())
        <div class="space-y-4">
            @foreach($claims as $claim)
            <div class="bg-white rounded-xl shadow-sm border hover:shadow-md transition-shadow">
                <div class="p-6">
                    <!-- Header -->
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-lg flex items-center justify-center text-white font-bold"
                                 style="background: {{ $company->primary_color }};">
                                {{ substr($claim->claim_number, -2) }}
                            </div>
                            <div>
                                <h3 class="font-bold text-lg">{{ $claim->claim_number }}</h3>
                                <p class="text-gray-600 text-sm">{{ $claim->policy_number }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-3">
                            <span class="px-3 py-1.5 rounded-full text-sm font-medium {{ $claim->status_badge['class'] }}">
                                {{ t($company->translation_group . '.' . $claim->status) }}
                            </span>
                            
                            @if($claim->status === 'pending')
                                <div class="flex gap-2">
                                    <button onclick="approveModal({{ $claim->id }})" 
                                            class="px-4 py-2 bg-green-500 text-white rounded-lg text-sm font-medium hover:bg-green-600 transition-colors">
                                        {{ t($company->translation_group . '.approve') }}
                                    </button>
                                    <button onclick="rejectModal({{ $claim->id }})" 
                                            class="px-4 py-2 bg-red-500 text-white rounded-lg text-sm font-medium hover:bg-red-600 transition-colors">
                                        {{ t($company->translation_group . '.reject') }}
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Content Grid -->
                    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 mb-4">
                        <div class="space-y-2">
                            <h4 class="font-medium text-gray-900">{{ t($company->translation_group . '.user_info') }}</h4>
                            <div class="space-y-1 text-sm text-gray-600">
                                <div>{{ $claim->insuranceUser->full_name }}</div>
                                <div>{{ $claim->insuranceUser->formatted_phone }}</div>
                            </div>
                        </div>
                        
                        <div class="space-y-2">
                            <h4 class="font-medium text-gray-900">{{ t($company->translation_group . '.vehicle_info') }}</h4>
                            <div class="space-y-1 text-sm text-gray-600">
                                <div>{{ $claim->vehicle_plate_number ?: $claim->chassis_number }}</div>
                                <div class="{{ $claim->is_vehicle_working ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $claim->is_vehicle_working ? t($company->translation_group . '.working') : t($company->translation_group . '.not_working') }}
                                </div>
                            </div>
                        </div>
                        
                        <div class="space-y-2">
                            <h4 class="font-medium text-gray-900">{{ t($company->translation_group . '.location') }}</h4>
                            <div class="text-sm text-gray-600">
                                {{ Str::limit($claim->vehicle_location, 40) }}
                            </div>
                        </div>
                        
                        <div class="space-y-2">
                            <h4 class="font-medium text-gray-900">{{ t($company->translation_group . '.dates') }}</h4>
                            <div class="space-y-1 text-sm text-gray-600">
                                <div>{{ $claim->created_at->format('M d, Y') }}</div>
                                @if($claim->service_center_id)
                                    <div class="font-medium" style="color: {{ $company->primary_color }};">
                                        {{ Str::limit($claim->serviceCenter->legal_name, 20) }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Action -->
                    <div class="flex justify-end">
                        <a href="{{ route('insurance.claims.show', [$company->company_slug, $claim->id]) }}" 
                           class="inline-flex items-center gap-2 px-4 py-2 rounded-lg font-medium transition-colors"
                           style="background: {{ $company->primary_color }}20; color: {{ $company->primary_color }};">
                            {{ t($company->translation_group . '.view_details') }}
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="flex justify-center">
            {{ $claims->withQueryString()->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-xl shadow-sm border">
            <div class="p-12 text-center">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full flex items-center justify-center"
                     style="background: {{ $company->primary_color }}20;">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: {{ $company->primary_color }};">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">{{ t($company->translation_group . '.no_claims_found') }}</h3>
                <p class="text-gray-600">{{ t($company->translation_group . '.no_claims_description') }}</p>
            </div>
        </div>
    @endif
</div>

<!-- Approve Modal -->
<div id="approveModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b">
            <h3 class="text-xl font-bold">{{ t($company->translation_group . '.approve_claim') }}</h3>
        </div>
        
        <form id="approveForm" method="POST" class="p-6 space-y-6">
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
            <h3 class="text-xl font-bold">{{ t($company->translation_group . '.reject_claim') }}</h3>
        </div>
        
        <form id="rejectForm" method="POST" class="p-6 space-y-6">
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
let serviceCenters = [];

// Load service centers
fetch('{{ route("insurance.claims.service-centers", $company->company_slug) }}')
    .then(response => response.json())
    .then(data => {
        serviceCenters = data;
        const select = document.getElementById('serviceCenterSelect');
        select.innerHTML = '<option value="">{{ t($company->translation_group . ".select_service_center") }}</option>';
        data.forEach(center => {
            select.innerHTML += `<option value="${center.id}">${center.name} - ${center.area || '{{ t($company->translation_group . ".no_area") }}'}</option>`;
        });
    });

function approveModal(claimId) {
    document.getElementById('approveForm').action = `{{ route('insurance.claims.approve', [$company->company_slug, '__ID__']) }}`.replace('__ID__', claimId);
    document.getElementById('approveModal').classList.remove('hidden');
}

function rejectModal(claimId) {
    document.getElementById('rejectForm').action = `{{ route('insurance.claims.reject', [$company->company_slug, '__ID__']) }}`.replace('__ID__', claimId);
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
@endsection