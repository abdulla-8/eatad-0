@extends('insurance.layouts.app')

@section('title', 'Parts Pricing Review')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('insurance.parts-quotes.index', $company->company_slug) }}" 
               class="w-10 h-10 rounded-lg border flex items-center justify-center hover:bg-gray-50 transition-colors">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Parts Pricing Review</h1>
                <p class="text-gray-600">{{ $inspection->claim->claim_number }}</p>
            </div>
        </div>
        
        <!-- Status & Actions -->
        <div class="flex items-center gap-4">
            <span class="px-4 py-2 rounded-full text-sm font-medium {{ $inspection->insurance_response_badge['class'] }}">
                {{ $inspection->insurance_response_badge['text'] }}
            </span>
            
            @if($inspection->pricing_status === 'sent_to_insurance')
                <div class="flex gap-3">
                    <button onclick="approveModal()" 
                            class="px-6 py-2.5 bg-green-500 text-white rounded-lg font-medium hover:bg-green-600 transition-colors">
                        Approve Pricing
                    </button>
                    <button onclick="rejectModal()" 
                            class="px-6 py-2.5 bg-red-500 text-white rounded-lg font-medium hover:bg-red-600 transition-colors">
                        Reject Pricing
                    </button>
                </div>
            @endif
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Vehicle & Claim Information -->
            <div class="bg-white rounded-xl shadow-sm border">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-bold">Vehicle & Claim Information</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid md:grid-cols-2 gap-4">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Claim Number:</span>
                            <span class="font-medium">{{ $inspection->claim->claim_number }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Policy Number:</span>
                            <span class="font-medium">{{ $inspection->claim->policy_number }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Vehicle Brand:</span>
                            <span class="font-medium">{{ $inspection->vehicle_brand }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Vehicle Model:</span>
                            <span class="font-medium">{{ $inspection->vehicle_model }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Vehicle Year:</span>
                            <span class="font-medium">{{ $inspection->vehicle_year }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Chassis Number:</span>
                            <span class="font-medium">{{ $inspection->chassis_number }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Parts List with Pricing -->
            <div class="bg-white rounded-xl shadow-sm border">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-bold">Parts with Pricing</h3>
                </div>
                <div class="p-6">
                    <div class="border rounded-lg overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Part Name</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Price</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($inspection->parts_with_pricing as $index => $part)
                                    <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-50' }}">
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ $index + 1 }}</td>
                                        <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $part['name'] }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ number_format($part['price'], 2) }} SAR</td>
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ $part['quantity'] }}</td>
                                        <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ number_format($part['total'], 2) }} SAR</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Pricing Summary -->
            <div class="bg-white rounded-xl shadow-sm border">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-bold">Pricing Summary</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex justify-between text-base">
                            <span class="text-gray-600">Parts Total:</span>
                            <span class="font-medium">{{ number_format($inspection->parts_total, 2) }} SAR</span>
                        </div>
                        <div class="flex justify-between text-base">
                            <span class="text-gray-600">Service Center Fees:</span>
                            <span class="font-medium">{{ number_format($inspection->service_center_fees, 2) }} SAR</span>
                        </div>
                        <div class="flex justify-between text-base">
                            <span class="text-gray-600">Subtotal:</span>
                            <span class="font-medium">{{ number_format($inspection->parts_total + $inspection->service_center_fees, 2) }} SAR</span>
                        </div>
                        <div class="flex justify-between text-base">
                            <span class="text-gray-600">Tax ({{ $inspection->tax_percentage }}%):</span>
                            <span class="font-medium">{{ number_format($inspection->tax_amount, 2) }} SAR</span>
                        </div>
                        <div class="border-t pt-4 flex justify-between text-xl font-bold">
                            <span>Total Amount:</span>
                            <span class="text-2xl" style="color: {{ $company->primary_color }};">{{ number_format($inspection->total_amount, 2) }} SAR</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Admin Notes -->
            @if($inspection->admin_notes)
            <div class="bg-white rounded-xl shadow-sm border">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-bold">Admin Notes</h3>
                </div>
                <div class="p-6">
                    <p class="text-gray-700 whitespace-pre-line">{{ $inspection->admin_notes }}</p>
                </div>
            </div>
            @endif

            <!-- Insurance Response -->
            @if($inspection->hasInsuranceResponse())
            <div class="bg-white rounded-xl shadow-sm border">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-bold">Your Response</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            <span class="px-3 py-1.5 rounded-full text-sm font-medium {{ $inspection->insurance_response_badge['class'] }}">
                                {{ $inspection->insurance_response_badge['text'] }}
                            </span>
                            <span class="text-sm text-gray-600">
                                {{ $inspection->insurance_responded_at->format('M d, Y H:i') }}
                            </span>
                        </div>
                        
                        @if($inspection->rejection_reason)
                            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                                <h4 class="font-semibold text-red-800 mb-2">Rejection Reason:</h4>
                                <p class="text-red-700">{{ $inspection->rejection_reason }}</p>
                            </div>
                        @endif
                        
                        @if($inspection->insurance_notes)
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <h4 class="font-semibold text-blue-800 mb-2">Your Notes:</h4>
                                <p class="text-blue-700">{{ $inspection->insurance_notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Customer Information -->
            <div class="bg-white rounded-xl shadow-sm border">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-bold">Customer Information</h3>
                </div>
                <div class="p-6 space-y-3">
                    <div>
                        <span class="text-gray-600">Name:</span>
                        <p class="font-medium">{{ $inspection->claim->insuranceUser->full_name }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600">Phone:</span>
                        <p class="font-medium">{{ $inspection->claim->insuranceUser->formatted_phone }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600">National ID:</span>
                        <p class="font-medium">{{ $inspection->claim->insuranceUser->formatted_national_id }}</p>
                    </div>
                </div>
            </div>

            <!-- Service Center Information -->
            <div class="bg-white rounded-xl shadow-sm border">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-bold">Service Center</h3>
                </div>
                <div class="p-6 space-y-3">
                    <div>
                        <span class="text-gray-600">Name:</span>
                        <p class="font-medium">{{ $inspection->serviceCenter->legal_name }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600">Phone:</span>
                        <p class="font-medium">{{ $inspection->serviceCenter->formatted_phone }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600">Inspection Date:</span>
                        <p class="font-medium">{{ $inspection->created_at->format('M d, Y H:i') }}</p>
                    </div>
                    @if($inspection->priced_at)
                        <div>
                            <span class="text-gray-600">Priced Date:</span>
                            <p class="font-medium">{{ $inspection->priced_at->format('M d, Y H:i') }}</p>
                        </div>
                    @endif
                    @if($inspection->sent_to_insurance_at)
                        <div>
                            <span class="text-gray-600">Sent Date:</span>
                            <p class="font-medium">{{ $inspection->sent_to_insurance_at->format('M d, Y H:i') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm border">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-bold">Actions</h3>
                </div>
                <div class="p-6 space-y-3">
                    @if($inspection->pricing_status === 'sent_to_insurance')
                        <button onclick="approveModal()" 
                                class="w-full px-4 py-3 bg-green-500 text-white rounded-lg font-medium hover:bg-green-600 transition-colors">
                            Approve Pricing
                        </button>
                        <button onclick="rejectModal()" 
                                class="w-full px-4 py-3 bg-red-500 text-white rounded-lg font-medium hover:bg-red-600 transition-colors">
                            Reject Pricing
                        </button>
                    @endif
                    
                    <a href="{{ route('insurance.claims.show', [$company->company_slug, $inspection->claim->id]) }}" 
                       class="w-full px-4 py-3 rounded-lg font-medium text-white text-center block"
                       style="background: {{ $company->primary_color }};">
                        View Full Claim
                    </a>
                    
                    <a href="{{ route('insurance.parts-quotes.index', $company->company_slug) }}" 
                       class="w-full px-4 py-3 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition-colors text-center block">
                        Back to Parts Quotes
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Approve Modal -->
@if($inspection->pricing_status === 'sent_to_insurance')
<div id="approveModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl max-w-md w-full">
        <div class="p-6 border-b">
            <h3 class="text-xl font-bold">Approve Parts Pricing</h3>
        </div>
        
        <form method="POST" action="{{ route('insurance.parts-quotes.approve', [$company->company_slug, $inspection->id]) }}" class="p-6 space-y-6">
            @csrf
            
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex items-center gap-2 mb-2">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span class="font-medium text-green-800">Approve Total Amount:</span>
                </div>
                <p class="text-2xl font-bold text-green-700">{{ number_format($inspection->total_amount, 2) }} SAR</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Additional Notes (Optional)</label>
                <textarea name="insurance_notes" rows="4" 
                          class="w-full border-gray-300 rounded-lg focus:ring-2 focus:border-transparent px-4 py-2.5"
                          style="focus:ring-color: {{ $company->primary_color }};"
                          placeholder="Any additional notes about your approval..."></textarea>
            </div>
            
            <div class="flex gap-4 pt-4">
                <button type="submit" class="flex-1 py-3 bg-green-500 text-white rounded-lg font-medium hover:bg-green-600 transition-colors">
                    Approve Pricing
                </button>
                <button type="button" onclick="closeModal('approveModal')" 
                        class="flex-1 py-3 bg-gray-500 text-white rounded-lg font-medium hover:bg-gray-600 transition-colors">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl max-w-md w-full">
        <div class="p-6 border-b">
            <h3 class="text-xl font-bold">Reject Parts Pricing</h3>
        </div>
        
        <form method="POST" action="{{ route('insurance.parts-quotes.reject', [$company->company_slug, $inspection->id]) }}" class="p-6 space-y-6">
            @csrf
            
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex items-center gap-2 mb-2">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    <span class="font-medium text-red-800">Rejecting Total Amount:</span>
                </div>
                <p class="text-2xl font-bold text-red-700">{{ number_format($inspection->total_amount, 2) }} SAR</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Rejection Reason *</label>
                <textarea name="rejection_reason" rows="4" required 
                          class="w-full border-gray-300 rounded-lg focus:ring-2 focus:border-transparent px-4 py-2.5"
                          style="focus:ring-color: {{ $company->primary_color }};"
                          placeholder="Please explain why you are rejecting this pricing..."></textarea>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Additional Notes (Optional)</label>
                <textarea name="insurance_notes" rows="3" 
                          class="w-full border-gray-300 rounded-lg focus:ring-2 focus:border-transparent px-4 py-2.5"
                          style="focus:ring-color: {{ $company->primary_color }};"
                          placeholder="Any additional feedback or suggestions..."></textarea>
            </div>
            
            <div class="flex gap-4 pt-4">
                <button type="submit" class="flex-1 py-3 bg-red-500 text-white rounded-lg font-medium hover:bg-red-600 transition-colors">
                    Reject Pricing
                </button>
                <button type="button" onclick="closeModal('rejectModal')" 
                        class="flex-1 py-3 bg-gray-500 text-white rounded-lg font-medium hover:bg-gray-600 transition-colors">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function approveModal() {
    document.getElementById('approveModal').classList.remove('hidden');
}

function rejectModal() {
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
@endif
@endsection