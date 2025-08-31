@extends('insurance.layouts.app')

@section('title', t('insurance_company' . '.claims_management'))

@section('content')
<div class="space-y-6">
    <!-- Header with Stats -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ t('insurance_company' . '.claims_management') }}</h1>
            <p class="text-gray-600 mt-1">{{ t('insurance_company' . '.manage_all_claims') }}</p>
        </div>
        
        <div class="flex items-center gap-4">
            <a href="{{ route('insurance.claims.create', $company->company_slug) }}" class="px-6 py-2.5 text-white rounded-lg font-medium hover:opacity-90 transition-opacity" style="background: {{ $company->primary_color }};">
                {{ t('insurance_company' . '.create_claim') }}
            </a>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
        <div class="bg-white rounded-lg border px-4 py-3 text-center shadow-sm">
            <div class="text-2xl font-bold text-blue-600">{{ $stats['total'] }}</div>
            <div class="text-xs text-gray-600">{{ t('insurance_company' . '.total') }}</div>
        </div>
        <div class="bg-white rounded-lg border px-4 py-3 text-center shadow-sm">
            <div class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</div>
            <div class="text-xs text-gray-600">{{ t('insurance_company' . '.pending') }}</div>
        </div>
        <div class="bg-white rounded-lg border px-4 py-3 text-center shadow-sm">
            <div class="text-2xl font-bold text-green-600">{{ $stats['approved'] }}</div>
            <div class="text-xs text-gray-600">{{ t('insurance_company' . '.approved') }}</div>
        </div>
        <div class="bg-white rounded-lg border px-4 py-3 text-center shadow-sm">
            <div class="text-2xl font-bold text-red-600">{{ $stats['rejected'] }}</div>
            <div class="text-xs text-gray-600">{{ t('insurance_company' . '.rejected') }}</div>
        </div>
    </div>

    <!-- Filters Card -->
    <div class="bg-white rounded-xl shadow-sm border">
        <div class="p-6">
            <form method="GET" class="flex flex-col lg:flex-row gap-4">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ t('insurance_company' . '.search') }}</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="{{ t('insurance_company' . '.search_placeholder') }}"
                           class="w-full border-gray-300 rounded-lg focus:ring-2 focus:border-transparent px-4 py-2.5"
                           style="focus:ring-color: {{ $company->primary_color }};">
                </div>
                
                <div class="lg:w-48">
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ t('insurance_company' . '.status') }}</label>
                    <select name="status" class="w-full border-gray-300 rounded-lg focus:ring-2 focus:border-transparent px-4 py-2.5"
                            style="focus:ring-color: {{ $company->primary_color }};">
                        <option value="">{{ t('insurance_company' . '.all_status') }}</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>{{ t('insurance_company' . '.pending') }}</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>{{ t('insurance_company' . '.approved') }}</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>{{ t('insurance_company' . '.rejected') }}</option>
                    </select>
                </div>
                
                <div class="flex gap-2 lg:items-end">
                    <button type="submit" class="px-6 py-2.5 text-white rounded-lg font-medium hover:opacity-90 transition-opacity"
                            style="background: {{ $company->primary_color }};">
                        {{ t('insurance_company' . '.filter') }}
                    </button>
                    
                    @if(request()->hasAny(['status', 'search']))
                        <a href="{{ route('insurance.claims.index', $company->company_slug) }}" 
                           class="px-6 py-2.5 bg-gray-500 text-white rounded-lg font-medium hover:opacity-90 transition-opacity">
                            {{ t('insurance_company' . '.clear') }}
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
                                {{ t('insurance_company' . '.' . $claim->status) }}
                            </span>
                            
                            @if($claim->status === 'pending')
                                <div class="flex gap-2">
                                    <button onclick="approveModal({{ $claim->id }})" 
                                            class="px-4 py-2 bg-green-500 text-white rounded-lg text-sm font-medium hover:bg-green-600 transition-colors">
                                        {{ t('insurance_company' . '.approve_claim') }}
                                    </button>
                                    <button onclick="rejectModal({{ $claim->id }})" 
                                            class="px-4 py-2 bg-red-500 text-white rounded-lg text-sm font-medium hover:bg-red-600 transition-colors">
                                        {{ t('insurance_company' . '.reject_claim') }}
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Content Grid -->
                    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 mb-4">
                        <div class="space-y-2">
                            <h4 class="font-medium text-gray-900">{{ t('insurance_company' . '.user_info') }}</h4>
                            <div class="space-y-1 text-sm text-gray-600">
                                <div>{{ $claim->insuranceUser->full_name }}</div>
                                <div>{{ $claim->insuranceUser->formatted_phone }}</div>
                            </div>
                        </div>
                        
                        <div class="space-y-2">
                            <h4 class="font-medium text-gray-900">{{ t('insurance_company' . '.vehicle_info') }}</h4>
                            <div class="space-y-1 text-sm text-gray-600">
                                <div>{{ $claim->vehicle_plate_number ?: $claim->chassis_number }}</div>
                                <div class="{{ $claim->is_vehicle_working ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $claim->is_vehicle_working ? t('insurance_company' . '.working') : t('insurance_company' . '.not_working') }}
                                </div>
                            </div>
                        </div>
                        @if(!empty($claim->vehicle_location))
                        <div class="space-y-2">
                            <h4 class="font-medium text-gray-900">{{ t('insurance_company' . '.location') }}</h4>
                            <div class="text-sm text-gray-600">
                                {{ Str::limit($claim->vehicle_location, 40) }}
                            </div>
                        </div>
                        @endif
                        <div class="space-y-2">
                            <h4 class="font-medium text-gray-900">{{ t('insurance_company' . '.dates') }}</h4>
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
                    <div class="flex justify-end gap-2">
                        <a href="{{ route('insurance.claims.edit', [$company->company_slug, $claim->id]) }}" 
                           class="inline-flex items-center gap-2 px-4 py-2 rounded-lg font-medium transition-colors bg-gray-200 text-gray-700 hover:bg-gray-300">
                            {{ t('insurance_company' . '.edit') }}
                        </a>
                        <a href="{{ route('insurance.claims.show', [$company->company_slug, $claim->id]) }}" 
                           class="inline-flex items-center gap-2 px-4 py-2 rounded-lg font-medium transition-colors"
                           style="background: {{ $company->primary_color }}20; color: {{ $company->primary_color }};">
                            {{ t('insurance_company' . '.view_details') }}
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>

                    <!-- Service Center Response -->
                    @if($claim->status === 'approved' && !empty($claim->service_center_note))
                        <div class="bg-green-50 border border-green-200 rounded-xl p-4 my-4">
                            <div class="font-bold text-green-800 mb-2">{{ t('insurance.service_center_accepted') }}</div>
                            <div class="text-green-700">{{ $claim->service_center_note }}</div>
                        </div>
                    @elseif($claim->status === 'pending' && !empty($claim->service_center_note))
                        <div class="bg-red-50 border border-red-200 rounded-xl p-4 my-4">
                            <div class="font-bold text-red-800 mb-2">{{ t('insurance.service_center_rejected') }}</div>
                            <div class="text-red-700">{{ $claim->service_center_note }}</div>
                        </div>
                    @endif
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
                <h3 class="text-lg font-medium text-gray-900 mb-2">{{ t('insurance_company' . '.no_claims_found') }}</h3>
                <p class="text-gray-600">{{ t('insurance_company' . '.no_claims_description') }}</p>
            </div>
        </div>
    @endif
</div>

<!-- Approve Modal -->
<div id="approveModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b">
            <h3 class="text-xl font-bold">{{ t('insurance_company' . '.approve_claim') }}</h3>
        </div>
        
        <form id="approveForm" method="POST" class="p-6 space-y-6">
            @csrf
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">{{ t('insurance_company' . '.select_service_center') }} *</label>
                <select name="service_center_id" id="serviceCenterSelect" required 
                        class="w-full border-gray-300 rounded-lg focus:ring-2 focus:border-transparent px-4 py-2.5"
                        style="focus:ring-color: {{ $company->primary_color }};">
                    <option value="">{{ t('insurance_company' . '.loading') }}</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">{{ t('insurance_company' . '.notes_optional') }}</label>
                <textarea name="notes" rows="4" 
                          class="w-full border-gray-300 rounded-lg focus:ring-2 focus:border-transparent px-4 py-2.5"
                          style="focus:ring-color: {{ $company->primary_color }};"
                          placeholder="{{ t('insurance_company' . '.additional_notes') }}"></textarea>
            </div>
            
            <div class="flex gap-4 pt-4">
                <button type="submit" class="flex-1 py-3 text-white rounded-lg font-medium hover:opacity-90 transition-opacity"
                        style="background: {{ $company->primary_color }};">
                    {{ t('insurance_company' . '.approve_claim') }}
                </button>
                <button type="button" onclick="closeModal('approveModal')" 
                        class="flex-1 py-3 bg-gray-500 text-white rounded-lg font-medium hover:opacity-90 transition-opacity">
                    {{ t('insurance_company' . '.cancel') }}
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl max-w-md w-full">
        <div class="p-6 border-b">
            <h3 class="text-xl font-bold">{{ t('insurance_company' . '.reject_claim') }}</h3>
        </div>
        
        <form id="rejectForm" method="POST" class="p-6 space-y-6">
            @csrf
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">{{ t('insurance_company' . '.rejection_reason') }} *</label>
                <textarea name="rejection_reason" rows="4" required 
                          class="w-full border-gray-300 rounded-lg focus:ring-2 focus:border-transparent px-4 py-2.5"
                          style="focus:ring-color: {{ $company->primary_color }};"
                          placeholder="{{ t('insurance_company' . '.explain_rejection_reason') }}"></textarea>
            </div>
            
            <div class="flex gap-4 pt-4">
                <button type="submit" class="flex-1 py-3 bg-red-500 text-white rounded-lg font-medium hover:bg-red-600 transition-colors">
                    {{ t('insurance_company' . '.reject_claim') }}
                </button>
                <button type="button" onclick="closeModal('rejectModal')" 
                        class="flex-1 py-3 bg-gray-500 text-white rounded-lg font-medium hover:opacity-90 transition-opacity">
                    {{ t('insurance_company' . '.cancel') }}
                </button>
            </div>
        </form>
    </div>
</div>

<script>
let serviceCenters = [];

// Load service centers with accepted claims count
fetch('{{ route("insurance.claims.service-centers", $company->company_slug) }}')
    .then(response => response.json())
    .then(data => {
        console.log('Service Centers Data:', data); // للتأكد من البيانات
        
        const select = document.getElementById('serviceCenterSelect');
        select.innerHTML = '<option value="">{{ t('insurance_company' . ".select_service_center") }}</option>';
        
        data.forEach(center => {
            let claimsCount = center.accepted_claims_count ?? 0;
            let claimsText = claimsCount > 0 
                ? `(${claimsCount} {{ t('insurance_company' . ".accepted_claims") }})`
                : `({{ t('insurance_company' . ".no_accepted_claims") }})`;
            let areaText = center.area ? ` - ${center.area}` : '';
            
            // إضافة مؤشر مفصل لنوع مركز الصيانة
            let typeText = '';
            if (center.center_type === 'owned_by_current_company') {
                typeText = ' - ✓ {{ t('insurance_company' . ".your_company_center") }}';
            } else if (center.center_type === 'independent') {
                typeText = ' - {{ t('insurance_company' . ".independent_center") }}';
            } else if (center.center_type === 'owned_by_other_company' && center.owner_company) {
                typeText = ` - {{ t('insurance_company' . ".owned_by") }} ${center.owner_company}`;
            }
            
            // إضافة style مميز للمراكز التابعة للشركة الحالية
            let optionClass = center.center_type === 'owned_by_current_company' ? 'font-weight: bold; color: #059669;' : '';
            
            select.innerHTML += `<option value="${center.id}" style="${optionClass}">${center.name}${areaText}${typeText} ${claimsText}</option>`;
        });
    })
    .catch(error => {
        console.error('Error loading service centers:', error);
        const select = document.getElementById('serviceCenterSelect');
        select.innerHTML = '<option value="">{{ t('insurance_company' . ".error_loading") }}</option>';
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
