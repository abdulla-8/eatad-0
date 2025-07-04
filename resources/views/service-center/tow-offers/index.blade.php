@extends('service-center.layouts.app')

@section('title', t('service_center.tow_offers'))

@section('content')
<div class="space-y-6">
    <!-- Header with Stats -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ t('service_center.tow_offers') }}</h1>
            <p class="text-gray-600 mt-1">{{ t('service_center.manage_tow_offers') }}</p>
        </div>
        
        <!-- Quick Stats -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
            <div class="bg-white rounded-lg border px-4 py-3 text-center shadow-sm">
                <div class="text-2xl font-bold text-blue-600">{{ $stats['total'] }}</div>
                <div class="text-xs text-gray-600">{{ t('service_center.total_offers') }}</div>
            </div>
            <div class="bg-white rounded-lg border px-4 py-3 text-center shadow-sm">
                <div class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</div>
                <div class="text-xs text-gray-600">{{ t('service_center.pending') }}</div>
            </div>
            <div class="bg-white rounded-lg border px-4 py-3 text-center shadow-sm">
                <div class="text-2xl font-bold text-green-600">{{ $stats['accepted'] }}</div>
                <div class="text-xs text-gray-600">{{ t('service_center.accepted') }}</div>
            </div>
            <div class="bg-white rounded-lg border px-4 py-3 text-center shadow-sm">
                <div class="text-2xl font-bold text-red-600">{{ $stats['rejected'] }}</div>
                <div class="text-xs text-gray-600">{{ t('service_center.rejected') }}</div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border">
        <div class="p-6">
            <form method="GET" class="flex flex-col lg:flex-row gap-4">
                <div class="lg:w-48">
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ t('service_center.status') }}</label>
                    <select name="status" class="w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent px-4 py-2.5">
                        <option value="">{{ t('service_center.all_status') }}</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>{{ t('service_center.pending') }}</option>
                        <option value="accepted" {{ request('status') === 'accepted' ? 'selected' : '' }}>{{ t('service_center.accepted') }}</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>{{ t('service_center.rejected') }}</option>
                        <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>{{ t('service_center.expired') }}</option>
                    </select>
                </div>
                
                <div class="flex gap-2 lg:items-end">
                    <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors">
                        {{ t('service_center.filter') }}
                    </button>
                    
                    @if(request('status'))
                        <a href="{{ route('service-center.tow-offers.index') }}" 
                           class="px-6 py-2.5 bg-gray-500 text-white rounded-lg font-medium hover:bg-gray-600 transition-colors">
                            {{ t('service_center.clear') }}
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Offers List -->
    @if($offers->count())
        <div class="space-y-4">
            @foreach($offers as $offer)
            <div class="bg-white rounded-xl shadow-sm border hover:shadow-md transition-shadow">
                <div class="p-6">
                    <!-- Header -->
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center text-white font-bold">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-lg">{{ t('service_center.tow_request') }} #{{ $offer->towRequest->request_code }}</h3>
                                <p class="text-gray-600 text-sm">{{ t('service_center.claim') }}: {{ $offer->towRequest->claim->claim_number }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-3">
                            <span class="px-3 py-1.5 rounded-full text-sm font-medium {{ $offer->status_badge['class'] }}">
                                {{ t('service_center.' . $offer->status) }}
                            </span>
                            
                            @if($offer->status === 'pending')
                                <div class="flex gap-2">
                                    <button onclick="acceptOffer({{ $offer->id }})" 
                                            class="px-4 py-2 bg-green-500 text-white rounded-lg text-sm font-medium hover:bg-green-600 transition-colors">
                                        {{ t('service_center.accept') }}
                                    </button>
                                    <button onclick="rejectOffer({{ $offer->id }})" 
                                            class="px-4 py-2 bg-red-500 text-white rounded-lg text-sm font-medium hover:bg-red-600 transition-colors">
                                        {{ t('service_center.reject') }}
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Content Grid -->
                    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 mb-4">
                        <div class="space-y-2">
                            <h4 class="font-medium text-gray-900">{{ t('service_center.customer_info') }}</h4>
                            <div class="space-y-1 text-sm text-gray-600">
                                <div>{{ $offer->towRequest->claim->insuranceUser->full_name }}</div>
                                <div>{{ $offer->towRequest->claim->insuranceUser->formatted_phone }}</div>
                            </div>
                        </div>
                        
                        <div class="space-y-2">
                            <h4 class="font-medium text-gray-900">{{ t('service_center.pickup_location') }}</h4>
                            <div class="text-sm text-gray-600">
                                {{ Str::limit($offer->towRequest->pickup_location_address, 40) }}
                            </div>
                        </div>
                        
                        <div class="space-y-2">
                            <h4 class="font-medium text-gray-900">{{ t('service_center.delivery_location') }}</h4>
                            <div class="text-sm text-gray-600">
                                {{ Str::limit($offer->towRequest->dropoff_location_address, 40) }}
                            </div>
                        </div>
                        
                        <div class="space-y-2">
                            <h4 class="font-medium text-gray-900">{{ t('service_center.offer_time') }}</h4>
                            <div class="text-sm text-gray-600">
                                {{ $offer->offer_time->format('M d, Y H:i') }}
                                @if($offer->status === 'pending' && $offer->expires_at)
                                    <div class="text-red-600 font-medium">
                                        {{ t('service_center.expires') }}: {{ $offer->expires_at->format('H:i') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-between items-center pt-4 border-t border-gray-100">
                        <div class="text-sm text-gray-500">
                            {{ t('service_center.stage') }}: {{ t('service_center.' . $offer->stage) }}
                        </div>
                        <a href="{{ route('service-center.tow-offers.show', $offer->id) }}" 
                           class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-600 rounded-lg font-medium hover:bg-blue-100 transition-colors">
                            {{ t('service_center.view_details') }}
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
            {{ $offers->withQueryString()->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-xl shadow-sm border">
            <div class="p-12 text-center">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">{{ t('service_center.no_tow_offers') }}</h3>
                <p class="text-gray-600">{{ t('service_center.no_tow_offers_description') }}</p>
            </div>
        </div>
    @endif
</div>

<!-- Accept Offer Modal -->
<div id="acceptModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl max-w-md w-full">
        <div class="p-6 border-b">
            <h3 class="text-xl font-bold">{{ t('service_center.accept_tow_offer') }}</h3>
        </div>
        
        <form id="acceptForm" class="p-6 space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">{{ t('service_center.estimated_pickup_time') }}</label>
                <input type="datetime-local" name="estimated_pickup_time" 
                       class="w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent px-4 py-2.5"
                       min="{{ now()->addMinutes(30)->format('Y-m-d\TH:i') }}">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">{{ t('service_center.notes') }}</label>
                <textarea name="notes" rows="3" 
                          class="w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent px-4 py-2.5"
                          placeholder="{{ t('service_center.additional_notes') }}"></textarea>
            </div>
            
            <div class="flex gap-4 pt-4">
                <button type="submit" class="flex-1 py-3 bg-green-500 text-white rounded-lg font-medium hover:bg-green-600 transition-colors">
                    {{ t('service_center.accept_offer') }}
                </button>
                <button type="button" onclick="closeModal('acceptModal')" 
                        class="flex-1 py-3 bg-gray-500 text-white rounded-lg font-medium hover:bg-gray-600 transition-colors">
                    {{ t('service_center.cancel') }}
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Reject Offer Modal -->
<div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl max-w-md w-full">
        <div class="p-6 border-b">
            <h3 class="text-xl font-bold">{{ t('service_center.reject_tow_offer') }}</h3>
        </div>
        
        <form id="rejectForm" class="p-6 space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">{{ t('service_center.rejection_reason') }}</label>
                <textarea name="rejection_reason" rows="4" 
                          class="w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent px-4 py-2.5"
                          placeholder="{{ t('service_center.why_rejecting') }}"></textarea>
            </div>
            
            <div class="flex gap-4 pt-4">
                <button type="submit" class="flex-1 py-3 bg-red-500 text-white rounded-lg font-medium hover:bg-red-600 transition-colors">
                    {{ t('service_center.reject_offer') }}
                </button>
                <button type="button" onclick="closeModal('rejectModal')" 
                        class="flex-1 py-3 bg-gray-500 text-white rounded-lg font-medium hover:bg-gray-600 transition-colors">
                    {{ t('service_center.cancel') }}
                </button>
            </div>
        </form>
    </div>
</div>

<script>
let currentOfferId = null;

function acceptOffer(offerId) {
    currentOfferId = offerId;
    document.getElementById('acceptModal').classList.remove('hidden');
    
    // Set default pickup time to 1 hour from now
    const defaultTime = new Date();
    defaultTime.setHours(defaultTime.getHours() + 1);
    document.querySelector('[name="estimated_pickup_time"]').value = defaultTime.toISOString().slice(0, 16);
}

function rejectOffer(offerId) {
    currentOfferId = offerId;
    document.getElementById('rejectModal').classList.remove('hidden');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
    currentOfferId = null;
}

// Handle accept form submission
document.getElementById('acceptForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    if (!currentOfferId) return;
    
    const formData = new FormData(this);
    const data = {
        estimated_pickup_time: formData.get('estimated_pickup_time'),
        notes: formData.get('notes')
    };
    
    try {
        const response = await fetch(`{{ route('service-center.tow-offers.accept', '__ID__') }}`.replace('__ID__', currentOfferId), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            location.reload();
        } else {
            alert(result.error || 'Failed to accept offer');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred while accepting the offer');
    }
});

// Handle reject form submission
document.getElementById('rejectForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    if (!currentOfferId) return;
    
    const formData = new FormData(this);
    const data = {
        rejection_reason: formData.get('rejection_reason')
    };
    
    try {
        const response = await fetch(`{{ route('service-center.tow-offers.reject', '__ID__') }}`.replace('__ID__', currentOfferId), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            location.reload();
        } else {
            alert(result.error || 'Failed to reject offer');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred while rejecting the offer');
    }
});

// Close modal on outside click
document.getElementById('acceptModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal('acceptModal');
});

document.getElementById('rejectModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal('rejectModal');
});
</script>
@endsection