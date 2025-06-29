@extends('insurance.layouts.app')

@section('title', 'Claims Management')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Claims Management</h1>
    
    {{-- Stats --}}
    <div class="flex gap-4 text-sm">
        <div class="bg-blue-100 px-3 py-1 rounded">Total: {{ $stats['total'] }}</div>
        <div class="bg-yellow-100 px-3 py-1 rounded">Pending: {{ $stats['pending'] }}</div>
        <div class="bg-green-100 px-3 py-1 rounded">Approved: {{ $stats['approved'] }}</div>
        <div class="bg-red-100 px-3 py-1 rounded">Rejected: {{ $stats['rejected'] }}</div>
    </div>
</div>

{{-- Filters --}}
<div class="bg-white rounded-lg border p-4 mb-6">
    <form method="GET" class="flex gap-4">
        <select name="status" class="border rounded px-3 py-2">
            <option value="">All Status</option>
            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
        </select>
        
        <input type="text" name="search" value="{{ request('search') }}" 
               placeholder="Search by policy, vehicle, or user..." 
               class="border rounded px-3 py-2 flex-1">
        
        <button type="submit" class="bg-primary text-white px-4 py-2 rounded hover:opacity-90">
            Filter
        </button>
        
        @if(request()->hasAny(['status', 'search']))
            <a href="{{ route('insurance.claims.index', $company->company_slug) }}" 
               class="bg-gray-500 text-white px-4 py-2 rounded hover:opacity-90">
                Clear
            </a>
        @endif
    </form>
</div>

{{-- Claims List --}}
@if($claims->count())
    <div class="space-y-4">
        @foreach($claims as $claim)
        <div class="bg-white rounded-lg border p-4">
            <div class="flex justify-between items-start">
                <div class="flex-1">
                    <div class="flex justify-between items-center mb-2">
                        <h3 class="font-bold">{{ $claim->claim_number }}</h3>
                        <span class="px-2 py-1 rounded text-xs {{ $claim->status_badge['class'] }}">
                            {{ $claim->status_badge['text'] }}
                        </span>
                    </div>
                    
                    <div class="grid md:grid-cols-4 gap-4 text-sm">
                        <div>
                            <span class="font-medium">User:</span> {{ $claim->insuranceUser->full_name }}<br>
                            <span class="font-medium">Phone:</span> {{ $claim->insuranceUser->formatted_phone }}
                        </div>
                        <div>
                            <span class="font-medium">Policy:</span> {{ $claim->policy_number }}<br>
                            <span class="font-medium">Vehicle:</span> {{ $claim->vehicle_plate_number ?: $claim->chassis_number }}
                        </div>
                        <div>
                            <span class="font-medium">Working:</span> {{ $claim->is_vehicle_working ? 'Yes' : 'No' }}<br>
                            <span class="font-medium">Location:</span> {{ Str::limit($claim->vehicle_location, 30) }}
                        </div>
                        <div>
                            <span class="font-medium">Submitted:</span> {{ $claim->created_at->format('M d, Y') }}<br>
                            @if($claim->service_center_id)
                                <span class="font-medium">Center:</span> {{ $claim->serviceCenter->legal_name }}
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="flex gap-2 ml-4">
                    <a href="{{ route('insurance.claims.show', [$company->company_slug, $claim->id]) }}" 
                       class="bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600">
                        View
                    </a>
                    
                    @if($claim->status === 'pending')
                        <button onclick="approveModal({{ $claim->id }})" 
                                class="bg-green-500 text-white px-3 py-1 rounded text-sm hover:bg-green-600">
                            Approve
                        </button>
                        <button onclick="rejectModal({{ $claim->id }})" 
                                class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600">
                            Reject
                        </button>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-6">
        {{ $claims->withQueryString()->links() }}
    </div>
@else
    <div class="text-center py-12">
        <p class="text-gray-500">No claims found</p>
    </div>
@endif

{{-- Approve Modal --}}
<div id="approveModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg p-6 w-full max-w-2xl">
            <h3 class="text-lg font-bold mb-4">Approve Claim</h3>
            
            <form id="approveForm" method="POST">
                @csrf
                
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Select Service Center *</label>
                    <select name="service_center_id" id="serviceCenterSelect" required 
                            class="w-full border rounded-lg px-3 py-2">
                        <option value="">Loading...</option>
                    </select>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Notes (Optional)</label>
                    <textarea name="notes" rows="3" class="w-full border rounded-lg px-3 py-2" 
                              placeholder="Additional notes..."></textarea>
                </div>
                
                <div class="flex gap-4">
                    <button type="submit" class="bg-green-500 text-white px-6 py-2 rounded hover:bg-green-600">
                        Approve Claim
                    </button>
                    <button type="button" onclick="closeModal('approveModal')" 
                            class="bg-gray-500 text-white px-6 py-2 rounded hover:bg-gray-600">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Reject Modal --}}
<div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <h3 class="text-lg font-bold mb-4">Reject Claim</h3>
            
            <form id="rejectForm" method="POST">
                @csrf
                
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Rejection Reason *</label>
                    <textarea name="rejection_reason" rows="4" required 
                              class="w-full border rounded-lg px-3 py-2" 
                              placeholder="Please explain why this claim is being rejected..."></textarea>
                </div>
                
                <div class="flex gap-4">
                    <button type="submit" class="bg-red-500 text-white px-6 py-2 rounded hover:bg-red-600">
                        Reject Claim
                    </button>
                    <button type="button" onclick="closeModal('rejectModal')" 
                            class="bg-gray-500 text-white px-6 py-2 rounded hover:bg-gray-600">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
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
        select.innerHTML = '<option value="">Select Service Center</option>';
        data.forEach(center => {
            select.innerHTML += `<option value="${center.id}">${center.name} - ${center.area || 'No Area'}</option>`;
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
</script>
@endsection