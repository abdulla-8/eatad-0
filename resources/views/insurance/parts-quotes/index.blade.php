@extends('insurance.layouts.app')

@section('title', 'Parts Quotes Management')

@section('content')
<div class="space-y-6">
    <!-- Header with Stats -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Parts Quotes Management</h1>
            <p class="text-gray-600 mt-1">Review and respond to parts pricing requests</p>
        </div>
        
        <!-- Quick Stats -->
        <div class="grid grid-cols-2 lg:grid-cols-5 gap-3">
            <div class="bg-white rounded-lg border px-4 py-3 text-center shadow-sm">
                <div class="text-2xl font-bold text-blue-600">{{ $stats['total'] }}</div>
                <div class="text-xs text-gray-600">Total Quotes</div>
            </div>
            <div class="bg-white rounded-lg border px-4 py-3 text-center shadow-sm">
                <div class="text-2xl font-bold text-yellow-600">{{ $stats['awaiting_response'] }}</div>
                <div class="text-xs text-gray-600">Awaiting Response</div>
            </div>
            <div class="bg-white rounded-lg border px-4 py-3 text-center shadow-sm">
                <div class="text-2xl font-bold text-green-600">{{ $stats['approved'] }}</div>
                <div class="text-xs text-gray-600">Approved</div>
            </div>
            <div class="bg-white rounded-lg border px-4 py-3 text-center shadow-sm">
                <div class="text-2xl font-bold text-red-600">{{ $stats['rejected'] }}</div>
                <div class="text-xs text-gray-600">Rejected</div>
            </div>
            <div class="bg-white rounded-lg border px-4 py-3 text-center shadow-sm">
                <div class="text-lg font-bold" style="color: {{ $company->primary_color }};">{{ number_format($stats['total_approved_amount'], 0) }}</div>
                <div class="text-xs text-gray-600">Total Approved (SAR)</div>
            </div>
        </div>
    </div>

    <!-- Filters Card -->
    <div class="bg-white rounded-xl shadow-sm border">
        <div class="p-6">
            <form method="GET" class="flex flex-col lg:flex-row gap-4">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Search by claim number, plate, or chassis..."
                           class="w-full border-gray-300 rounded-lg focus:ring-2 focus:border-transparent px-4 py-2.5"
                           style="focus:ring-color: {{ $company->primary_color }};">
                </div>
                
                <div class="lg:w-48">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full border-gray-300 rounded-lg focus:ring-2 focus:border-transparent px-4 py-2.5"
                            style="focus:ring-color: {{ $company->primary_color }};">
                        <option value="">All Status</option>
                        <option value="awaiting_response" {{ request('status') === 'awaiting_response' ? 'selected' : '' }}>Awaiting Response</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                
                <div class="flex gap-2 lg:items-end">
                    <button type="submit" class="px-6 py-2.5 text-white rounded-lg font-medium hover:opacity-90 transition-opacity"
                            style="background: {{ $company->primary_color }};">
                        Filter
                    </button>
                    
                    @if(request()->hasAny(['status', 'search']))
                        <a href="{{ route('insurance.parts-quotes.index', $company->company_slug) }}" 
                           class="px-6 py-2.5 bg-gray-500 text-white rounded-lg font-medium hover:opacity-90 transition-opacity">
                            Clear
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Parts Quotes List -->
    @if($inspections->count())
        <div class="space-y-4">
            @foreach($inspections as $inspection)
            <div class="bg-white rounded-xl shadow-sm border hover:shadow-md transition-shadow">
                <div class="p-6">
                    <!-- Header -->
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-lg flex items-center justify-center text-white font-bold"
                                 style="background: {{ $company->primary_color }};">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-lg">{{ $inspection->claim->claim_number }}</h3>
                                <p class="text-gray-600 text-sm">{{ $inspection->claim->policy_number }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-3">
                            <span class="px-3 py-1.5 rounded-full text-sm font-medium {{ $inspection->pricing_status_badge['class'] }}">
                                {{ $inspection->pricing_status_badge['text'] }}
                            </span>
                            
                            @if($inspection->pricing_status === 'sent_to_insurance')
                                <span class="px-3 py-1.5 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                    Action Required
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Content Grid -->
                    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 mb-4">
                        <div class="space-y-2">
                            <h4 class="font-medium text-gray-900">Customer Info</h4>
                            <div class="space-y-1 text-sm text-gray-600">
                                <div>{{ $inspection->claim->insuranceUser->full_name }}</div>
                                <div>{{ $inspection->claim->insuranceUser->formatted_phone }}</div>
                            </div>
                        </div>
                        
                        <div class="space-y-2">
                            <h4 class="font-medium text-gray-900">Vehicle Info</h4>
                            <div class="space-y-1 text-sm text-gray-600">
                                <div>{{ $inspection->vehicle_brand }} {{ $inspection->vehicle_model }}</div>
                                <div>{{ $inspection->vehicle_year }} - {{ $inspection->claim->vehicle_plate_number ?: $inspection->chassis_number }}</div>
                            </div>
                        </div>
                        
                        <div class="space-y-2">
                            <h4 class="font-medium text-gray-900">Service Center</h4>
                            <div class="space-y-1 text-sm text-gray-600">
                                <div>{{ Str::limit($inspection->serviceCenter->legal_name, 25) }}</div>
                                <div>{{ $inspection->serviceCenter->formatted_phone }}</div>
                            </div>
                        </div>
                        
                        <div class="space-y-2">
                            <h4 class="font-medium text-gray-900">Pricing Details</h4>
                            <div class="space-y-1 text-sm">
                                <div class="font-bold text-lg" style="color: {{ $company->primary_color }};">
                                    {{ number_format($inspection->total_amount, 2) }} SAR
                                </div>
                                <div class="text-gray-600">{{ count($inspection->parts_with_pricing) }} parts</div>
                                @if($inspection->sent_to_insurance_at)
                                    <div class="text-xs text-gray-500">
                                        Sent: {{ $inspection->sent_to_insurance_at->format('M d, Y') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Response Status -->
                    @if($inspection->hasInsuranceResponse())
                        <div class="mb-4">
                            @if($inspection->insurance_response === 'approved')
                                <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span class="font-medium text-green-800">Approved on {{ $inspection->insurance_responded_at->format('M d, Y H:i') }}</span>
                                    </div>
                                    @if($inspection->insurance_notes)
                                        <p class="text-green-700 text-sm mt-1">{{ Str::limit($inspection->insurance_notes, 100) }}</p>
                                    @endif
                                </div>
                            @elseif($inspection->insurance_response === 'rejected')
                                <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                                    <div class="flex items-center gap-2 mb-1">
                                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        <span class="font-medium text-red-800">Rejected on {{ $inspection->insurance_responded_at->format('M d, Y H:i') }}</span>
                                    </div>
                                    <p class="text-red-700 text-sm">{{ Str::limit($inspection->rejection_reason, 100) }}</p>
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- Action -->
                    <div class="flex justify-end gap-3">
                        @if($inspection->pricing_status === 'sent_to_insurance')
                            <button onclick="quickApprove({{ $inspection->id }})" 
                                    class="px-4 py-2 bg-green-500 text-white rounded-lg font-medium hover:bg-green-600 transition-colors text-sm">
                                Quick Approve
                            </button>
                            <button onclick="quickReject({{ $inspection->id }})" 
                                    class="px-4 py-2 bg-red-500 text-white rounded-lg font-medium hover:bg-red-600 transition-colors text-sm">
                                Quick Reject
                            </button>
                        @endif
                        
                        <a href="{{ route('insurance.parts-quotes.show', [$company->company_slug, $inspection->id]) }}" 
                           class="inline-flex items-center gap-2 px-4 py-2 rounded-lg font-medium transition-colors text-sm"
                           style="background: {{ $company->primary_color }}20; color: {{ $company->primary_color }};">
                            View Details
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
            {{ $inspections->withQueryString()->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-xl shadow-sm border">
            <div class="p-12 text-center">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full flex items-center justify-center"
                     style="background: {{ $company->primary_color }}20;">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: {{ $company->primary_color }};">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No Parts Quotes Found</h3>
                <p class="text-gray-600">No parts pricing requests have been submitted yet.</p>
            </div>
        </div>
    @endif
</div>

<!-- Quick Approve Modal -->
<div id="quickApproveModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl max-w-md w-full">
        <div class="p-6 border-b">
            <h3 class="text-xl font-bold">Quick Approve</h3>
        </div>
        
        <form id="quickApproveForm" method="POST" class="p-6 space-y-4">
            @csrf
            
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-center">
                <p class="text-green-800 font-medium">Approve this parts pricing?</p>
                <p class="text-2xl font-bold text-green-700 mt-2" id="approveAmount">0.00 SAR</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                <textarea name="insurance_notes" rows="3" 
                          class="w-full border-gray-300 rounded-lg focus:ring-2 focus:border-transparent px-4 py-2.5"
                          placeholder="Any additional notes..."></textarea>
            </div>
            
            <div class="flex gap-4">
                <button type="submit" class="flex-1 py-3 bg-green-500 text-white rounded-lg font-medium hover:bg-green-600 transition-colors">
                    Approve
                </button>
                <button type="button" onclick="closeModal('quickApproveModal')" 
                        class="flex-1 py-3 bg-gray-500 text-white rounded-lg font-medium hover:bg-gray-600 transition-colors">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Quick Reject Modal -->
<div id="quickRejectModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl max-w-md w-full">
        <div class="p-6 border-b">
            <h3 class="text-xl font-bold">Quick Reject</h3>
        </div>
        
        <form id="quickRejectForm" method="POST" class="p-6 space-y-4">
            @csrf
            
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-center">
                <p class="text-red-800 font-medium">Reject this parts pricing?</p>
                <p class="text-2xl font-bold text-red-700 mt-2" id="rejectAmount">0.00 SAR</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Rejection Reason *</label>
                <textarea name="rejection_reason" rows="3" required 
                          class="w-full border-gray-300 rounded-lg focus:ring-2 focus:border-transparent px-4 py-2.5"
                          placeholder="Please explain why you are rejecting..."></textarea>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                <textarea name="insurance_notes" rows="2" 
                          class="w-full border-gray-300 rounded-lg focus:ring-2 focus:border-transparent px-4 py-2.5"
                          placeholder="Additional feedback..."></textarea>
            </div>
            
            <div class="flex gap-4">
                <button type="submit" class="flex-1 py-3 bg-red-500 text-white rounded-lg font-medium hover:bg-red-600 transition-colors">
                    Reject
                </button>
                <button type="button" onclick="closeModal('quickRejectModal')" 
                        class="flex-1 py-3 bg-gray-500 text-white rounded-lg font-medium hover:bg-gray-600 transition-colors">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Store inspection data for quick actions
const inspectionData = @json($inspections->map(function($inspection) {
    return [
        'id' => $inspection->id,
        'total_amount' => $inspection->total_amount,
        'claim_number' => $inspection->claim->claim_number
    ];
}));

function quickApprove(inspectionId) {
    const inspection = inspectionData.find(i => i.id === inspectionId);
    if (inspection) {
        document.getElementById('approveAmount').textContent = new Intl.NumberFormat().format(inspection.total_amount) + ' SAR';
        document.getElementById('quickApproveForm').action = `{{ route('insurance.parts-quotes.approve', [$company->company_slug, '__ID__']) }}`.replace('__ID__', inspectionId);
        document.getElementById('quickApproveModal').classList.remove('hidden');
    }
}

function quickReject(inspectionId) {
    const inspection = inspectionData.find(i => i.id === inspectionId);
    if (inspection) {
        document.getElementById('rejectAmount').textContent = new Intl.NumberFormat().format(inspection.total_amount) + ' SAR';
        document.getElementById('quickRejectForm').action = `{{ route('insurance.parts-quotes.reject', [$company->company_slug, '__ID__']) }}`.replace('__ID__', inspectionId);
        document.getElementById('quickRejectModal').classList.remove('hidden');
    }
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

// Close modal on outside click
document.getElementById('quickApproveModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal('quickApproveModal');
});

document.getElementById('quickRejectModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal('quickRejectModal');
});
</script>

@endsection