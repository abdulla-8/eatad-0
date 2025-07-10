@extends('admin.layouts.app')

@section('title', 'Inspection Details & Pricing')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.inspections.index') }}" 
           class="w-10 h-10 rounded-lg border flex items-center justify-center hover:bg-gray-50 transition-colors">
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <div class="flex-1">
            <h1 class="text-3xl font-bold text-gray-900">Inspection Details</h1>
            <p class="text-gray-600">{{ $inspection->claim->claim_number }}</p>
        </div>
        
        <!-- Status Badges -->
        <div class="flex items-center gap-3">
            <span class="px-3 py-1.5 rounded-full text-sm font-medium {{ $inspection->pricing_status_badge['class'] }}">
                {{ $inspection->pricing_status_badge['text'] }}
            </span>
            @if($inspection->isSentToInsurance())
                <span class="px-3 py-1.5 rounded-full text-sm font-medium {{ $inspection->insurance_response_badge['class'] }}">
                    {{ $inspection->insurance_response_badge['text'] }}
                </span>
            @endif
        </div>

        
    </div>
    <!-- Parts Status Alert -->
    @if($inspection->claim && $inspection->claim->parts_received_at && $inspection->insurance_response === 'approved')
        <div class="bg-green-50 border border-green-200 rounded-xl p-6">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-green-800 mb-2">Parts Received by Service Center</h3>
                    <p class="text-green-700">Service center confirmed receipt on: {{ $inspection->claim->parts_received_at->format('M d, Y H:i') }}</p>
                    @if($inspection->claim->parts_received_notes)
                        <p class="text-green-600 text-sm mt-1">Notes: {{ $inspection->claim->parts_received_notes }}</p>
                    @endif
                </div>
            </div>
        </div>
    @elseif($inspection->insurance_response === 'approved' && !$inspection->claim->parts_received_at)
        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-full bg-yellow-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-yellow-800 mb-2">Awaiting Parts Delivery</h3>
                    <p class="text-yellow-700">Parts pricing approved. Service center has not yet confirmed receipt of parts.</p>
                </div>
            </div>
        </div>
    @endif
    <div class="grid lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Vehicle Information -->
            <div class="bg-white rounded-xl shadow-sm border">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-bold">Vehicle Information</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid md:grid-cols-2 gap-4">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Brand:</span>
                            <span class="font-medium">{{ $inspection->vehicle_brand }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Model:</span>
                            <span class="font-medium">{{ $inspection->vehicle_model }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Year:</span>
                            <span class="font-medium">{{ $inspection->vehicle_year }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Chassis Number:</span>
                            <span class="font-medium">{{ $inspection->chassis_number }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Required Parts & Pricing -->
            <div class="bg-white rounded-xl shadow-sm border">
                <div class="p-6 border-b flex items-center justify-between">
                    <h3 class="text-lg font-bold">Required Parts & Pricing</h3>
                    <div class="flex gap-3">
                        @if(!$inspection->hasPricing())
                            <button onclick="openPricingModal()" 
                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors">
                                Add Pricing
                            </button>
                        @elseif($inspection->pricing_status === 'priced')
                            <button onclick="openPricingModal()" 
                                    class="px-4 py-2 bg-yellow-600 text-white rounded-lg font-medium hover:bg-yellow-700 transition-colors">
                                Edit Pricing
                            </button>
                            <form method="POST" action="{{ route('admin.inspections.send-to-insurance', $inspection->id) }}" class="inline">
                                @csrf
                                <button type="submit" 
                                        class="px-4 py-2 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition-colors"
                                        onclick="return confirm('Send this pricing to insurance company?')">
                                    Send to Insurance
                                </button>
                            </form>
                        @elseif($inspection->pricing_status === 'rejected')
                            <button onclick="openPricingModal()" 
                                    class="px-4 py-2 bg-yellow-600 text-white rounded-lg font-medium hover:bg-yellow-700 transition-colors">
                                Update Pricing
                            </button>
                            <form method="POST" action="{{ route('admin.inspections.reset-pricing', $inspection->id) }}" class="inline">
                                @csrf
                                <button type="submit" 
                                        class="px-4 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors">
                                    Reset for Resubmission
                                </button>
                            </form>
                        @endif
                        
                        @if($inspection->hasPricing() && !$inspection->isSentToInsurance())
                            <form method="POST" action="{{ route('admin.inspections.delete-pricing', $inspection->id) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="px-4 py-2 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 transition-colors"
                                        onclick="return confirm('Delete all pricing data?')">
                                    Delete Pricing
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
                
                <div class="p-6">
                    @if($inspection->hasPricing())
                        <!-- Parts List with Pricing -->
                        <div class="space-y-4 mb-6">
                            <h4 class="font-semibold text-gray-900">Parts with Pricing:</h4>
                            <div class="border rounded-lg overflow-hidden">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Part Name</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($inspection->parts_with_pricing as $part)
                                            <tr>
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

                        <!-- Pricing Summary -->
                        <div class="bg-gray-50 rounded-lg p-4 space-y-2">
                            <div class="flex justify-between text-sm">
                                <span>Parts Total:</span>
                                <span class="font-medium">{{ number_format($inspection->parts_total, 2) }} SAR</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span>Service Center Fees:</span>
                                <span class="font-medium">{{ number_format($inspection->service_center_fees, 2) }} SAR</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span>Tax ({{ $inspection->tax_percentage }}%):</span>
                                <span class="font-medium">{{ number_format($inspection->tax_amount, 2) }} SAR</span>
                            </div>
                            <div class="border-t pt-2 flex justify-between font-bold">
                                <span>Total Amount:</span>
                                <span class="text-lg text-blue-600">{{ number_format($inspection->total_amount, 2) }} SAR</span>
                            </div>
                        </div>
                    @else
                        <!-- Original Required Parts -->
                        <div class="space-y-3">
                            <h4 class="font-semibold text-gray-900">Required Parts:</h4>
                            @foreach($inspection->required_parts as $index => $part)
                                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                                    <span class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-bold">{{ $index + 1 }}</span>
                                    <span class="font-medium">{{ $part }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Insurance Response -->
            @if($inspection->hasInsuranceResponse())
                <div class="bg-white rounded-xl shadow-sm border">
                    <div class="p-6 border-b">
                        <h3 class="text-lg font-bold">Insurance Company Response</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex items-center gap-3">
                                <span class="px-3 py-1.5 rounded-full text-sm font-medium {{ $inspection->insurance_response_badge['class'] }}">
                                    {{ $inspection->insurance_response_badge['text'] }}
                                </span>
                                <span class="text-sm text-gray-600">
                                    {{ $inspection->insurance_responded_at ? $inspection->insurance_responded_at->format('M d, Y H:i') : '' }}
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
                                    <h4 class="font-semibold text-blue-800 mb-2">Insurance Notes:</h4>
                                    <p class="text-blue-700">{{ $inspection->insurance_notes }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- Registration Image -->
            @if($inspection->registration_image_path)
            <div class="bg-white rounded-xl shadow-sm border">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-bold">Registration Document</h3>
                </div>
                <div class="p-6">
                    <div class="border rounded-lg p-4">
                        <img src="{{ asset('storage/' . $inspection->registration_image_path) }}" 
                             alt="Registration Document" 
                             class="max-w-full h-auto rounded-lg">
                    </div>
                </div>
            </div>
            @endif

            <!-- Inspection Notes -->
            @if($inspection->inspection_notes)
            <div class="bg-white rounded-xl shadow-sm border">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-bold">Inspection Notes</h3>
                </div>
                <div class="p-6">
                    <p class="text-gray-700 whitespace-pre-line">{{ $inspection->inspection_notes }}</p>
                </div>
            </div>
            @endif

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
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Claim Information -->
            <div class="bg-white rounded-xl shadow-sm border">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-bold">Claim Information</h3>
                </div>
                <div class="p-6 space-y-3">
                    <div>
                        <span class="text-gray-600">Claim Number:</span>
                        <p class="font-medium">{{ $inspection->claim->claim_number }}</p>
                    </div>
                    <div>
<span class="text-gray-600">Policy Number:</span>
                        <p class="font-medium">{{ $inspection->claim->policy_number }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600">Vehicle Plate:</span>
                        <p class="font-medium">{{ $inspection->claim->vehicle_plate_number ?: 'N/A' }}</p>
                    </div>
                </div>
            </div>

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
                    @if($inspection->claim && $inspection->claim->parts_received_at)
    <div>
        <span class="text-gray-600">Parts Received:</span>
        <p class="font-medium text-green-600">{{ $inspection->claim->parts_received_at->format('M d, Y H:i') }}</p>
    </div>
@endif
                </div>
            </div>

            <!-- Insurance Company -->
            <div class="bg-white rounded-xl shadow-sm border">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-bold">Insurance Company</h3>
                </div>
                <div class="p-6 space-y-3">
                    <div>
                        <span class="text-gray-600">Company:</span>
                        <p class="font-medium">{{ $inspection->claim->insuranceCompany->legal_name }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600">Phone:</span>
                        <p class="font-medium">{{ $inspection->claim->insuranceCompany->formatted_phone }}</p>
                    </div>
                    @if($inspection->sent_to_insurance_at)
                        <div>
                            <span class="text-gray-600">Sent Date:</span>
                            <p class="font-medium">{{ $inspection->sent_to_insurance_at->format('M d, Y H:i') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Pricing Modal -->
<div id="pricingModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold">Parts Pricing</h3>
                <button onclick="closePricingModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
        
        <form method="POST" action="{{ route('admin.inspections.update-pricing', $inspection->id) }}" class="p-6 space-y-6">
            @csrf
            
            <!-- Parts List -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">Parts with Pricing</label>
                <div id="partsContainer">
                    @if($inspection->hasPricing())
                        @foreach($inspection->parts_with_pricing as $index => $part)
                            <div class="part-row grid grid-cols-12 gap-3 mb-3 p-3 border rounded-lg">
                                <div class="col-span-5">
                                    <input type="text" name="parts[{{ $index }}][name]" value="{{ $part['name'] }}" 
                                           class="w-full border-gray-300 rounded-lg px-3 py-2 text-sm" 
                                           placeholder="Part name" required>
                                </div>
                                <div class="col-span-2">
                                    <input type="number" name="parts[{{ $index }}][price]" value="{{ $part['price'] }}" 
                                           step="0.01" min="0" 
                                           class="w-full border-gray-300 rounded-lg px-3 py-2 text-sm part-price" 
                                           placeholder="Price" required>
                                </div>
                                <div class="col-span-2">
                                    <input type="number" name="parts[{{ $index }}][quantity]" value="{{ $part['quantity'] }}" 
                                           min="1" 
                                           class="w-full border-gray-300 rounded-lg px-3 py-2 text-sm part-quantity" 
                                           placeholder="Qty" required>
                                </div>
                                <div class="col-span-2">
                                    <input type="text" class="w-full border-gray-300 rounded-lg px-3 py-2 text-sm part-total bg-gray-50" 
                                           value="{{ number_format($part['total'], 2) }}" readonly>
                                </div>
                                <div class="col-span-1">
                                    <button type="button" onclick="removePart(this)" 
                                            class="w-full h-10 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                                        <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    @else
                        @foreach($inspection->required_parts as $index => $part)
                            <div class="part-row grid grid-cols-12 gap-3 mb-3 p-3 border rounded-lg">
                                <div class="col-span-5">
                                    <input type="text" name="parts[{{ $index }}][name]" value="{{ $part }}" 
                                           class="w-full border-gray-300 rounded-lg px-3 py-2 text-sm" 
                                           placeholder="Part name" required>
                                </div>
                                <div class="col-span-2">
                                    <input type="number" name="parts[{{ $index }}][price]" value="0" 
                                           step="0.01" min="0" 
                                           class="w-full border-gray-300 rounded-lg px-3 py-2 text-sm part-price" 
                                           placeholder="Price" required>
                                </div>
                                <div class="col-span-2">
                                    <input type="number" name="parts[{{ $index }}][quantity]" value="1" 
                                           min="1" 
                                           class="w-full border-gray-300 rounded-lg px-3 py-2 text-sm part-quantity" 
                                           placeholder="Qty" required>
                                </div>
                                <div class="col-span-2">
                                    <input type="text" class="w-full border-gray-300 rounded-lg px-3 py-2 text-sm part-total bg-gray-50" 
                                           value="0.00" readonly>
                                </div>
                                <div class="col-span-1">
                                    <button type="button" onclick="removePart(this)" 
                                            class="w-full h-10 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                                        <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
                
                <button type="button" onclick="addPart()" 
                        class="mt-3 px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors">
                    Add Another Part
                </button>
            </div>

            <!-- Service Center Fees -->
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Service Center Fees (SAR)</label>
                    <input type="number" name="service_center_fees" 
                           value="{{ $inspection->service_center_fees ?? 0 }}" 
                           step="0.01" min="0" id="serviceFees"
                           class="w-full border-gray-300 rounded-lg px-4 py-2" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tax Percentage (%)</label>
                    <input type="number" name="tax_percentage" 
                           value="{{ $inspection->tax_percentage ?? 15 }}" 
                           step="0.01" min="0" max="100" id="taxPercentage"
                           class="w-full border-gray-300 rounded-lg px-4 py-2" required>
                </div>
            </div>

            <!-- Summary -->
            <div class="bg-gray-50 rounded-lg p-4 space-y-2">
                <div class="flex justify-between text-sm">
                    <span>Parts Total:</span>
                    <span class="font-medium" id="partsTotal">0.00 SAR</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span>Service Center Fees:</span>
                    <span class="font-medium" id="serviceFeesDisplay">0.00 SAR</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span>Tax Amount:</span>
                    <span class="font-medium" id="taxAmount">0.00 SAR</span>
                </div>
                <div class="border-t pt-2 flex justify-between font-bold">
                    <span>Total Amount:</span>
                    <span class="text-lg text-blue-600" id="totalAmount">0.00 SAR</span>
                </div>
            </div>

            <!-- Admin Notes -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Admin Notes (Optional)</label>
                <textarea name="admin_notes" rows="3" 
                          class="w-full border-gray-300 rounded-lg px-4 py-2"
                          placeholder="Any additional notes about the pricing...">{{ $inspection->admin_notes }}</textarea>
            </div>
            
            <div class="flex gap-4 pt-4">
                <button type="submit" class="flex-1 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors">
                    Save Pricing
                </button>
                <button type="button" onclick="closePricingModal()" 
                        class="flex-1 py-3 bg-gray-500 text-white rounded-lg font-medium hover:bg-gray-600 transition-colors">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<script>
let partIndex = {{ $inspection->hasPricing() ? count($inspection->parts_with_pricing) : count($inspection->required_parts) }};

function openPricingModal() {
    document.getElementById('pricingModal').classList.remove('hidden');
    calculateTotals();
}

function closePricingModal() {
    document.getElementById('pricingModal').classList.add('hidden');
}

function addPart() {
    const container = document.getElementById('partsContainer');
    const partRow = document.createElement('div');
    partRow.className = 'part-row grid grid-cols-12 gap-3 mb-3 p-3 border rounded-lg';
    partRow.innerHTML = `
        <div class="col-span-5">
            <input type="text" name="parts[${partIndex}][name]" 
                   class="w-full border-gray-300 rounded-lg px-3 py-2 text-sm" 
                   placeholder="Part name" required>
        </div>
        <div class="col-span-2">
            <input type="number" name="parts[${partIndex}][price]" value="0" 
                   step="0.01" min="0" 
                   class="w-full border-gray-300 rounded-lg px-3 py-2 text-sm part-price" 
                   placeholder="Price" required>
        </div>
        <div class="col-span-2">
            <input type="number" name="parts[${partIndex}][quantity]" value="1" 
                   min="1" 
                   class="w-full border-gray-300 rounded-lg px-3 py-2 text-sm part-quantity" 
                   placeholder="Qty" required>
        </div>
        <div class="col-span-2">
            <input type="text" class="w-full border-gray-300 rounded-lg px-3 py-2 text-sm part-total bg-gray-50" 
                   value="0.00" readonly>
        </div>
        <div class="col-span-1">
            <button type="button" onclick="removePart(this)" 
                    class="w-full h-10 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
            </button>
        </div>
    `;
    container.appendChild(partRow);
    partIndex++;
    
    // Add event listeners
    const priceInput = partRow.querySelector('.part-price');
    const quantityInput = partRow.querySelector('.part-quantity');
    priceInput.addEventListener('input', calculateTotals);
    quantityInput.addEventListener('input', calculateTotals);
}

function removePart(button) {
    button.closest('.part-row').remove();
    calculateTotals();
}

function calculateTotals() {
    let partsTotal = 0;
    
    // Calculate each part total
    document.querySelectorAll('.part-row').forEach(row => {
        const price = parseFloat(row.querySelector('.part-price').value) || 0;
        const quantity = parseInt(row.querySelector('.part-quantity').value) || 1;
        const total = price * quantity;
        
        row.querySelector('.part-total').value = total.toFixed(2);
        partsTotal += total;
    });
    
    const serviceFees = parseFloat(document.getElementById('serviceFees').value) || 0;
    const taxPercentage = parseFloat(document.getElementById('taxPercentage').value) || 0;
    
    const subtotal = partsTotal + serviceFees;
    const taxAmount = (subtotal * taxPercentage) / 100;
    const totalAmount = subtotal + taxAmount;
    
    // Update displays
    document.getElementById('partsTotal').textContent = partsTotal.toFixed(2) + ' SAR';
    document.getElementById('serviceFeesDisplay').textContent = serviceFees.toFixed(2) + ' SAR';
    document.getElementById('taxAmount').textContent = taxAmount.toFixed(2) + ' SAR';
    document.getElementById('totalAmount').textContent = totalAmount.toFixed(2) + ' SAR';
}

// Add event listeners
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('serviceFees').addEventListener('input', calculateTotals);
    document.getElementById('taxPercentage').addEventListener('input', calculateTotals);
    
    document.querySelectorAll('.part-price, .part-quantity').forEach(input => {
        input.addEventListener('input', calculateTotals);
    });
});

// Close modal on outside click
document.getElementById('pricingModal').addEventListener('click', function(e) {
    if (e.target === this) closePricingModal();
});
</script>

@endsection