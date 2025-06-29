@extends('insurance.layouts.app')

@section('title', 'Claim Details')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Claim {{ $claim->claim_number }}</h1>
        <div class="flex items-center gap-4">
            <span class="px-3 py-1 rounded {{ $claim->status_badge['class'] }}">
                {{ $claim->status_badge['text'] }}
            </span>
            
            @if($claim->status === 'pending')
                <div class="flex gap-2">
                    <button onclick="approveModal()" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                        Approve
                    </button>
                    <button onclick="rejectModal()" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                        Reject
                    </button>
                </div>
            @endif
        </div>
    </div>

    <div class="grid lg:grid-cols-2 gap-6">
        {{-- User Info --}}
        <div class="bg-white rounded-lg border p-6">
            <h2 class="text-lg font-bold mb-4">User Information</h2>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between"><span class="font-medium">Name:</span><span>{{ $claim->insuranceUser->full_name }}</span></div>
                <div class="flex justify-between"><span class="font-medium">Phone:</span><span>{{ $claim->insuranceUser->formatted_phone }}</span></div>
                <div class="flex justify-between"><span class="font-medium">National ID:</span><span>{{ $claim->insuranceUser->formatted_national_id }}</span></div>
                <div class="flex justify-between"><span class="font-medium">User Policy:</span><span>{{ $claim->insuranceUser->policy_number }}</span></div>
            </div>
        </div>

        {{-- Claim Info --}}
        <div class="bg-white rounded-lg border p-6">
            <h2 class="text-lg font-bold mb-4">Claim Information</h2>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between"><span class="font-medium">Policy Number:</span><span>{{ $claim->policy_number }}</span></div>
                <div class="flex justify-between"><span class="font-medium">Vehicle:</span><span>{{ $claim->vehicle_plate_number ?: $claim->chassis_number }}</span></div>
                <div class="flex justify-between"><span class="font-medium">Vehicle Working:</span><span class="{{ $claim->is_vehicle_working ? 'text-green-600' : 'text-red-600' }}">{{ $claim->is_vehicle_working ? 'Yes' : 'No' }}</span></div>
                <div class="flex justify-between"><span class="font-medium">Receipt Ready:</span><span>{{ $claim->repair_receipt_ready ? 'Yes' : 'No' }}</span></div>
                <div class="flex justify-between"><span class="font-medium">Submitted:</span><span>{{ $claim->created_at->format('M d, Y H:i') }}</span></div>
            </div>
        </div>

        {{-- Location --}}
        <div class="bg-white rounded-lg border p-6">
            <h2 class="text-lg font-bold mb-4">Vehicle Location</h2>
            <p class="text-sm text-gray-700 mb-2">{{ $claim->vehicle_location }}</p>
            @if($claim->vehicle_location_lat)
                <a href="{{ $claim->vehicle_location_url }}" target="_blank" 
                   class="inline-block bg-blue-500 text-white px-3 py-1 rounded text-xs hover:bg-blue-600">
                    📍 View on Map
                </a>
            @endif
        </div>

        {{-- Service Center --}}
        @if($claim->service_center_id)
        <div class="bg-white rounded-lg border p-6">
            <h2 class="text-lg font-bold mb-4">Assigned Service Center</h2>
            <div class="space-y-2 text-sm">
                <div class="font-medium">{{ $claim->serviceCenter->legal_name }}</div>
                @if($claim->serviceCenter->center_address)
                    <div class="text-gray-600">{{ $claim->serviceCenter->center_address }}</div>
                @endif
                <div class="text-gray-600">{{ $claim->serviceCenter->formatted_phone }}</div>
                @if($claim->serviceCenter->center_location_lat)
                    <a href="{{ $claim->serviceCenter->location_url }}" target="_blank" 
                       class="inline-block mt-2 bg-green-500 text-white px-3 py-1 rounded text-xs hover:bg-green-600">
                        📍 View on Map
                    </a>
                @endif
            </div>
        </div>
        @endif

        {{-- Tow Service Status --}}
        @if($claim->tow_service_offered)
        <div class="bg-white rounded-lg border p-6">
            <h2 class="text-lg font-bold mb-4">Tow Service</h2>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="font-medium">Offered:</span>
                    <span class="text-blue-600">Yes</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-medium">Status:</span>
                    <span class="{{ is_null($claim->tow_service_accepted) ? 'text-yellow-600' : ($claim->tow_service_accepted ? 'text-green-600' : 'text-red-600') }}">
                        @if(is_null($claim->tow_service_accepted))
                            Waiting for Response
                        @elseif($claim->tow_service_accepted)
                            Accepted
                        @else
                            Declined
                        @endif
                    </span>
                </div>
            </div>
        </div>
        @endif

        {{-- Notes --}}
        @if($claim->notes)
        <div class="bg-white rounded-lg border p-6 lg:col-span-2">
            <h2 class="text-lg font-bold mb-4">Notes</h2>
            <p class="text-sm text-gray-700">{{ $claim->notes }}</p>
        </div>
        @endif

        {{-- Rejection Reason --}}
        @if($claim->status === 'rejected' && $claim->rejection_reason)
        <div class="bg-red-50 border border-red-200 rounded-lg p-6 lg:col-span-2">
            <h2 class="text-lg font-bold mb-4 text-red-800">Rejection Reason</h2>
            <p class="text-sm text-red-700">{{ $claim->rejection_reason }}</p>
        </div>
        @endif
    </div>

    {{-- Attachments --}}
    @if($claim->attachments->count())
    <div class="bg-white rounded-lg border p-6 mt-6">
        <h2 class="text-lg font-bold mb-4">Attachments ({{ $claim->attachments->count() }} files)</h2>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($claim->attachments->groupBy('type') as $type => $attachments)
                <div class="border rounded-lg p-3">
                    <h3 class="font-medium text-sm mb-2">{{ $attachments->first()->type_display_name }}</h3>
                    @foreach($attachments as $attachment)
                        <div class="flex items-center gap-2 text-xs mb-2">
                            <span class="text-lg">
                                @if($attachment->isImage()) 🖼️
                                @elseif($attachment->isPdf()) 📄
                                @else 📎
                                @endif
                            </span>
                            <div class="flex-1">
                                <a href="{{ $attachment->file_url }}" target="_blank" 
                                   class="text-blue-600 hover:underline block truncate">
                                    {{ $attachment->file_name }}
                                </a>
                                <span class="text-gray-500">({{ $attachment->file_size_formatted }})</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <div class="mt-6">
        <a href="{{ route('insurance.claims.index', $company->company_slug) }}" 
           class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:opacity-90">
            ← Back to Claims
        </a>
    </div>
</div>

{{-- Approve Modal --}}
@if($claim->status === 'pending')
<div id="approveModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg p-6 w-full max-w-2xl">
            <h3 class="text-lg font-bold mb-4">Approve Claim {{ $claim->claim_number }}</h3>
            
            <form method="POST" action="{{ route('insurance.claims.approve', [$company->company_slug, $claim->id]) }}">
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
            <h3 class="text-lg font-bold mb-4">Reject Claim {{ $claim->claim_number }}</h3>
            
            <form method="POST" action="{{ route('insurance.claims.reject', [$company->company_slug, $claim->id]) }}">
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
// Load service centers
fetch('{{ route("insurance.claims.service-centers", $company->company_slug) }}')
    .then(response => response.json())
    .then(data => {
        const select = document.getElementById('serviceCenterSelect');
        select.innerHTML = '<option value="">Select Service Center</option>';
        data.forEach(center => {
            select.innerHTML += `<option value="${center.id}">${center.name} - ${center.area || 'No Area'}</option>`;
        });
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
</script>
@endif
@endsection