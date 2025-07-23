@extends('service-center.layouts.app')

@section('title', t('service_center.tow_offers'))

@section('content')
<div class="space-y-6 ">
    <!-- Header with Stats -->
    <div class="md:flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ t('service_center.tow_offers') }}</h1>
            <p class="text-gray-600 mt-1">{{ t('service_center.manage_tow_offers') }}</p>
        </div>
        
        <!-- Quick Stats -->
        <div class="md:grid grid-cols-2 lg:grid-cols-4 gap-3  md:p-0">
            <div class="bg-white rounded-lg border px-4 py-3 text-center shadow-sm mb-4">
                <div class="text-2xl font-bold text-blue-600">{{ $stats['total'] }}</div>
                <div class="text-xs text-gray-600">{{ t('service_center.total_offers') }}</div>
            </div>
            <div class="bg-white rounded-lg border px-4 py-3 text-center shadow-sm mb-4">
                <div class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</div>
                <div class="text-xs text-gray-600">{{ t('service_center.pending') }}</div>
            </div>
            <div class="bg-white rounded-lg border px-4 py-3 text-center shadow-sm mb-4">
                <div class="text-2xl font-bold text-green-600">{{ $stats['accepted'] }}</div>
                <div class="text-xs text-gray-600">{{ t('service_center.accepted') }}</div>
            </div>
            <div class="bg-white rounded-lg border px-4 py-3 text-center shadow-sm mb-4">
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
        <div class="flex items-start justify-between mb-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center text-white font-bold text-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-lg flex items-center gap-2">
                        {{ t('service_center.tow_request') }} #{{ $offer->towRequest->request_code }}
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 7h18M3 12h18M3 17h18" />
                        </svg>
                    </h3>
                    <p class="text-gray-600 text-sm flex items-center gap-1">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        {{ t('service_center.claim') }}: {{ $offer->towRequest->claim->claim_number }}
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-3 flex-wrap">
                <span class="px-3 py-1.5 rounded-full text-sm font-medium {{ $offer->status_badge['class'] }}">
                    {{ t('service_center.' . $offer->status) }}
                </span>

                @if($offer->status === 'pending')
                    <button onclick="acceptOffer({{ $offer->id }})" 
                            class="px-4 py-2 bg-green-500 text-white rounded-lg text-sm font-medium hover:bg-green-600 transition-colors flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 13l4 4L19 7"></path>
                        </svg>
                        {{ t('service_center.accept') }}
                    </button>
                    <button onclick="rejectOffer({{ $offer->id }})" 
                            class="px-4 py-2 bg-red-500 text-white rounded-lg text-sm font-medium hover:bg-red-600 transition-colors flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        {{ t('service_center.reject') }}
                    </button>
                @elseif($offer->status === 'accepted' && $offer->towRequest->driver_tracking_token)
                    <button onclick="showDriverLink('{{ $offer->towRequest->driver_tracking_token }}')" 
                            class="px-4 py-2 bg-blue-500 text-white rounded-lg text-sm font-medium hover:bg-blue-600 transition-colors flex items-center gap-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 5a2 2 0 012-2h3l3 7-3 7H5a2 2 0 01-2-2v-4z"></path>
                            <path d="M13 7h8"></path>
                        </svg>
                        {{ t('service_center.driver_link') }}
                    </button>
                @endif
            </div>
        </div>

        <!-- Content Grid -->
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <div class="space-y-2">
                <h4 class="font-semibold text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 13l4 4L19 7"></path>
                    </svg>
                    {{ t('service_center.customer_info') }}
                </h4>
                <div class="space-y-1 text-sm text-gray-600">
                    <div>{{ $offer->towRequest->claim->insuranceUser->full_name }}</div>
                    <div>{{ $offer->towRequest->claim->insuranceUser->formatted_phone }}</div>
                </div>
            </div>

            <div class="space-y-2">
                <h4 class="font-semibold text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    {{ t('service_center.pickup_location') }}
                </h4>
                <div class="text-sm text-gray-600">
                    {{ Str::limit($offer->towRequest->pickup_location_address, 40) }}
                </div>
            </div>

            <div class="space-y-2">
                <h4 class="font-semibold text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    {{ t('service_center.delivery_location') }}
                </h4>
                <div class="text-sm text-gray-600">
                    {{ Str::limit($offer->towRequest->dropoff_location_address, 40) }}
                </div>
            </div>

            <div class="space-y-2">
                <h4 class="font-semibold text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M8 7v6h8v-6"></path>
                        <path d="M12 12v9"></path>
                    </svg>
                    {{ t('service_center.offer_time') }}
                </h4>
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
            <div class="text-sm text-gray-500 flex items-center gap-2">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
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
           min="{{ now()->addMinutes(30)->format('Y-m-d\TH:i') }}"
           max="{{ now()->addHours(12)->format('Y-m-d\TH:i') }}"
           required>
    <div class="mt-1 text-xs text-gray-500">
        {{ t('service_center.pickup_time_range') }}: {{ t('service_center.between_30_minutes_and_12_hours') }}
    </div>
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

<!-- Success Modal with Driver Link -->
<div id="successModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl max-w-lg w-full">
        <div class="p-6 border-b">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-green-800">{{ t('service_center.offer_accepted_successfully') }}</h3>
            </div>
        </div>
        
        <div class="p-6 space-y-6">
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <p class="text-green-800 text-sm">{{ t('service_center.tow_request_assigned_to_you') }}</p>
            </div>
            
            <div>
                <h4 class="font-bold text-gray-900 mb-3">📱 {{ t('service_center.driver_tracking_link') }}</h4>
                <p class="text-gray-600 text-sm mb-3">{{ t('service_center.share_link_with_driver') }}</p>
                
                <div class="bg-gray-50 border rounded-lg p-4">
                    <div class="flex items-center gap-3">
                        <input type="text" id="driverLinkInput" readonly 
                               class="flex-1 bg-white border border-gray-300 rounded px-3 py-2 text-sm font-mono">
                        <button onclick="copyDriverLink()" 
                                class="px-4 py-2 bg-blue-600 text-white rounded font-medium hover:bg-blue-700 transition-colors">
                            📋 {{ t('service_center.copy') }}
                        </button>
                    </div>
                </div>
                
                <div class="mt-3 p-3 bg-blue-50 border border-blue-200 rounded">
                    <p class="text-blue-800 text-xs">
                        <strong>{{ t('service_center.instructions') }}:</strong> {{ t('service_center.driver_link_instructions') }}
                    </p>
                </div>
            </div>
            
            <div class="flex gap-3">
                <button onclick="openDriverLink()" 
                        class="flex-1 px-4 py-3 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition-colors">
                    🔗 {{ t('service_center.open_driver_page') }}
                </button>
                <button onclick="closeModal('successModal')" 
                        class="flex-1 px-4 py-3 bg-gray-500 text-white rounded-lg font-medium hover:bg-gray-600 transition-colors">
                    {{ t('service_center.close') }}
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Driver Link Modal (for already accepted offers) -->
<div id="driverLinkModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl max-w-md w-full">
        <div class="p-6 border-b">
            <h3 class="text-xl font-bold">📱 {{ t('service_center.driver_tracking_link') }}</h3>
        </div>
        
        <div class="p-6 space-y-4">
            <p class="text-gray-600 text-sm">{{ t('service_center.share_link_with_driver') }}</p>
            
            <div class="bg-gray-50 border rounded-lg p-4">
                <div class="flex items-center gap-3">
                    <input type="text" id="existingDriverLinkInput" readonly 
                           class="flex-1 bg-white border border-gray-300 rounded px-3 py-2 text-sm font-mono">
                    <button onclick="copyExistingDriverLink()" 
                            class="px-4 py-2 bg-blue-600 text-white rounded font-medium hover:bg-blue-700 transition-colors">
                        📋 {{ t('service_center.copy') }}
                    </button>
                </div>
            </div>
            
            <div class="flex gap-3">
                <button onclick="openExistingDriverLink()" 
                        class="flex-1 px-4 py-3 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition-colors">
                    🔗 {{ t('service_center.open_driver_page') }}
                </button>
                <button onclick="closeModal('driverLinkModal')" 
                        class="flex-1 px-4 py-3 bg-gray-500 text-white rounded-lg font-medium hover:bg-gray-600 transition-colors">
                    {{ t('service_center.close') }}
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let currentOfferId = null;
let currentDriverLink = '';

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

function showDriverLink(token) {
    const driverLink = `${window.location.origin}/driver/track/${token}`;
    document.getElementById('existingDriverLinkInput').value = driverLink;
    currentDriverLink = driverLink;
    document.getElementById('driverLinkModal').classList.remove('hidden');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
    currentOfferId = null;
}

function copyDriverLink() {
    const input = document.getElementById('driverLinkInput');
    input.select();
    document.execCommand('copy');
    showMessage('{{ t("service_center.link_copied") }}', 'success');
}

function copyExistingDriverLink() {
    const input = document.getElementById('existingDriverLinkInput');
    input.select();
    document.execCommand('copy');
    showMessage('{{ t("service_center.link_copied") }}', 'success');
}

function openDriverLink() {
    const link = document.getElementById('driverLinkInput').value;
    window.open(link, '_blank');
}

function openExistingDriverLink() {
    window.open(currentDriverLink, '_blank');
}

function showMessage(message, type) {
    const div = document.createElement('div');
    div.className = `fixed top-4 right-4 px-4 py-3 rounded-lg shadow-lg text-white font-medium z-50 ${
        type === 'success' ? 'bg-green-600' : 'bg-red-600'
    }`;
    div.textContent = message;
    document.body.appendChild(div);
    
    setTimeout(() => {
        div.remove();
    }, 3000);
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
            closeModal('acceptModal');
            
            // Show success modal with driver link
            const driverLink = result.driver_tracking_url || `${window.location.origin}/driver/track/${result.tow_request.driver_tracking_token}`;
            document.getElementById('driverLinkInput').value = driverLink;
            currentDriverLink = driverLink;
            document.getElementById('successModal').classList.remove('hidden');
            
        } else {
            showMessage(result.error || 'Failed to accept offer', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showMessage('An error occurred while accepting the offer', 'error');
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
            closeModal('rejectModal');
            showMessage(result.message || 'Offer rejected successfully', 'success');
            setTimeout(() => location.reload(), 1500);
        } else {
            showMessage(result.error || 'Failed to reject offer', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showMessage('An error occurred while rejecting the offer', 'error');
    }
});

// Close modal on outside click
document.getElementById('acceptModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal('acceptModal');
});

document.getElementById('rejectModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal('rejectModal');
});

document.getElementById('successModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal('successModal');
});

document.getElementById('driverLinkModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal('driverLinkModal');
});
</script>
@endsection