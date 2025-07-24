@extends('service-center.layouts.app')

@section('title', t('service_center.tow_offer_details'))

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
       
        <div class="flex items-center gap-4">
         
            <a href="{{ route('service-center.tow-offers.index') }}" 
               class="w-10 h-10 rounded-lg border flex items-center justify-center hover:bg-gray-50 transition-colors">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ t('service_center.tow_request') }} #{{ $offer->towRequest->request_code }}</h1>
                <p class="text-gray-600">{{ t('service_center.tow_offer_details') }}</p>
            </div>
        </div>
        
        <div class="flex items-center gap-4">
            
            <span class="px-4 py-2 rounded-full text-sm font-medium {{ $offer->status_badge['class'] }}">
                {{ t('service_center.' . $offer->status) }}
            </span>
            
            @if($offer->status === 'pending')
                <div class="flex gap-3">
                    <button onclick="acceptOffer()" 
                            class="px-6 py-2.5 bg-green-500 text-white rounded-lg font-medium hover:bg-green-600 transition-colors">
                        {{ t('service_center.accept_offer') }}
                    </button>
                    <button onclick="rejectOffer()" 
                            class="px-6 py-2.5 bg-red-500 text-white rounded-lg font-medium hover:bg-red-600 transition-colors">
                        {{ t('service_center.reject_offer') }}
                    </button>
                </div>
            @endif
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
        <!-- Tow Request Information -->
<!-- Tow Request Information -->
<div class="bg-white rounded-xl shadow-sm border">
    <div class="p-6 border-b bg-gradient-to-r from-blue-50 to-white">
        <h3 class="text-lg font-bold flex items-center gap-2" style="color: #2563eb;">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
            </svg>
            {{ t('service_center.tow_request_information') }}
        </h3>
    </div>
    <div class="p-6 space-y-6">
        <div class="grid md:grid-cols-2 gap-6">
            @php
                $statusColor = $offer->towRequest->status === 'pending' ? '#f59e0b' : ($offer->towRequest->status === 'assigned' ? '#10b981' : '#ef4444');
            @endphp

            <div class="bg-gray-50 rounded-lg p-4 border-l-4 border-blue-600">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span class="text-gray-600 text-sm">{{ t('service_center.request_code') }}</span>
                    </div>
                    <span class="font-medium">{{ $offer->towRequest->request_code }}</span>
                </div>
            </div>

            <div class="bg-gray-50 rounded-lg p-4 border-l-4 border-blue-600">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 8v4l3 3"></path>
                        </svg>
                        <span class="text-gray-600 text-sm">{{ t('service_center.current_stage') }}</span>
                    </div>
                    <!-- <span class="font-medium">{{ t('service_center.' . $offer->towRequest->current_stage) }}</span> -->
                </div>
            </div>

            <div class="bg-gray-50 rounded-lg p-4 border-l-4" style="border-color: {{ $statusColor }};">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path>
                        </svg>
                        <span class="text-gray-600 text-sm">{{ t('service_center.request_status') }}</span>
                    </div>
                    <span class="font-semibold" style="color: {{ $statusColor }};">
                        {{ t('service_center.' . $offer->towRequest->status) }}
                    </span>
                </div>
            </div>

            <div class="bg-gray-50 rounded-lg p-4 border-l-4 border-blue-600">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 8v4l3 3"></path>
                        </svg>
                        <span class="text-gray-600 text-sm">{{ t('service_center.stage_expires_at') }}</span>
                    </div>
                    <span class="font-medium">
                        @if($offer->towRequest->stage_expires_at)
                            {{ $offer->towRequest->stage_expires_at->format('M d, Y H:i') }}
                            @if($offer->towRequest->stage_expires_at->isPast())
                                <span class="text-red-600">({{ t('service_center.expired') }})</span>
                            @endif
                        @else
                            {{ t('service_center.no_expiry') }}
                        @endif
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>



            <!-- Customer Information -->
           <div class="bg-white rounded-xl shadow-sm border">
  <div class="p-6 border-b bg-gradient-to-r from-blue-50 to-white">
    <div class="flex items-center gap-3">
      <div class="w-12 h-12 rounded-full flex items-center justify-center text-white font-bold" style="background: #2563eb;">
        {{ substr($offer->towRequest->claim->insuranceUser->full_name, 0, 2) }}
      </div>
      <div>
        <h3 class="text-lg font-bold">{{ t('service_center.customer_information') }}</h3>
        <p class="text-gray-600 text-sm">{{ t('service_center.contact_details') }}</p>
      </div>
    </div>
  </div>
  <div class="p-6 space-y-6">
    <div class="grid md:grid-cols-2 gap-6">

      <div class="bg-gray-50 rounded-lg p-4 border-l-4 border-blue-600">
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-2">
            <!-- أيقونة المستخدم -->
            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A7 7 0 0112 15a7 7 0 016.879 2.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <span class="text-gray-600 text-sm">{{ t('service_center.name') }}</span>
          </div>
          <span class="font-medium">{{ $offer->towRequest->claim->insuranceUser->full_name }}</span>
        </div>
      </div>

      <div class="bg-gray-50 rounded-lg p-4 border-l-4 border-blue-600">
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-2">
            <!-- أيقونة الهاتف -->
            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h2l3.6 7.59a1 1 0 01-.36 1.41l-2.6 1.54a11 11 0 005.6 5.6l1.54-2.6a1 1 0 011.41-.36L19 19v2a2 2 0 01-2 2H5a2 2 0 01-2-2V5z" />
            </svg>
            <span class="text-gray-600 text-sm">{{ t('service_center.phone') }}</span>
          </div>
          <span class="font-medium">{{ $offer->towRequest->claim->insuranceUser->formatted_phone }}</span>
        </div>
      </div>

      <div class="bg-gray-50 rounded-lg p-4 border-l-4 border-blue-600">
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-2">
            <!-- أيقونة رقم الكليم -->
            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <span class="text-gray-600 text-sm">{{ t('service_center.claim_number') }}</span>
          </div>
          <span class="font-medium">{{ $offer->towRequest->claim->claim_number }}</span>
        </div>
      </div>

      <div class="bg-gray-50 rounded-lg p-4 border-l-4 border-blue-600">
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-2">
            <!-- أيقونة السيارة -->
            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 13l1.5-4.5a1 1 0 011-1h13a1 1 0 011 1l1.5 4.5M5 16h14v2a2 2 0 01-2 2H7a2 2 0 01-2-2v-2z" />
            </svg>
            <span class="text-gray-600 text-sm">{{ t('service_center.chassis_number') }}</span>
          </div>
          <span class="font-medium">{{  $offer->towRequest->claim->chassis_number }}</span>
        </div>
      </div>

    </div>
  </div>
</div>


            <!-- Locations -->
           <!-- Locations -->
<div class="bg-white rounded-xl shadow-sm border">
    <div class="p-6 border-b bg-gradient-to-r from-blue-50 to-white">
        <h3 class="text-lg font-bold flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background: #2563eb;">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </div>
            {{ t('service_center.locations') }}
        </h3>
    </div>
    <div class="p-6 space-y-6">
        <!-- Pickup Location -->
        <div>
            <h4 class="font-medium text-gray-900 mb-2 flex items-center gap-2">
                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                {{ t('service_center.pickup_location') }}
            </h4>
            <p class="text-gray-700 mb-2">{{ $offer->towRequest->pickup_location_address }}</p>
            @if($offer->towRequest->pickup_location_lat)
                <a href="https://maps.google.com/?q={{ $offer->towRequest->pickup_location_lat }},{{ $offer->towRequest->pickup_location_lng }}" 
                   target="_blank" 
                   class="inline-flex items-center gap-2 px-3 py-1.5 bg-red-100 text-red-700 rounded-lg text-sm font-medium hover:bg-red-200 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    {{ t('service_center.view_on_map') }}
                </a>
            @endif
        </div>

        <!-- Delivery Location -->
        <div>
            <h4 class="font-medium text-gray-900 mb-2 flex items-center gap-2">
                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                {{ t('service_center.delivery_location') }}
            </h4>
            <p class="text-gray-700 mb-2">{{ $offer->towRequest->dropoff_location_address }}</p>
            @if($offer->towRequest->dropoff_location_lat)
                <a href="https://maps.google.com/?q={{ $offer->towRequest->dropoff_location_lat }},{{ $offer->towRequest->dropoff_location_lng }}" 
                   target="_blank" 
                   class="inline-flex items-center gap-2 px-3 py-1.5 bg-green-100 text-green-700 rounded-lg text-sm font-medium hover:bg-green-200 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    {{ t('service_center.view_on_map') }}
                </a>
            @endif
        </div>
    </div>
</div>

        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Offer Information -->
            <div class="bg-white rounded-xl shadow-sm border">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-bold">{{ t('service_center.offer_information') }}</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex justify-between">
                        <span class="text-gray-600">{{ t('service_center.offer_time') }}</span>
                        <span class="font-medium">{{ $offer->offer_time->format('M d, Y H:i') }}</span>
                    </div>
                    
                    @if($offer->expires_at)
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ t('service_center.expires_at') }}</span>
                            <span class="font-medium {{ $offer->expires_at->isPast() ? 'text-red-600' : '' }}">
                                {{ $offer->expires_at->format('M d, Y H:i') }}
                                @if($offer->expires_at->isPast())
                                    <br><span class="text-red-600 text-xs">({{ t('service_center.expired') }})</span>
                                @endif
                            </span>
                        </div>
                    @endif

                    @if($offer->response_time)
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ t('service_center.response_time') }}</span>
                            <span class="font-medium">{{ $offer->response_time->format('M d, Y H:i') }}</span>
                        </div>
                    @endif

                    @if($offer->estimated_pickup_time)
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ t('service_center.estimated_pickup') }}</span>
                            <span class="font-medium">{{ $offer->estimated_pickup_time->format('M d, Y H:i') }}</span>
                        </div>
                    @endif

                    @if($offer->notes)
                        <div>
                            <span class="text-gray-600 block mb-1">{{ t('service_center.notes') }}</span>
                            <p class="text-sm text-gray-800 bg-gray-50 p-3 rounded">{{ $offer->notes }}</p>
                        </div>
                    @endif

                    @if($offer->rejection_reason)
                        <div>
                            <span class="text-gray-600 block mb-1">{{ t('service_center.rejection_reason') }}</span>
                            <p class="text-sm text-red-700 bg-red-50 p-3 rounded">{{ $offer->rejection_reason }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm border">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-bold">{{ t('service_center.quick_actions') }}</h3>
                </div>
                <div class="p-6 space-y-3">
                    @if($offer->status === 'pending')
                        <button onclick="acceptOffer()" 
                                class="w-full px-4 py-3 bg-green-500 text-white rounded-lg font-medium hover:bg-green-600 transition-colors">
                            {{ t('service_center.accept_offer') }}
                        </button>
                        <button onclick="rejectOffer()" 
                                class="w-full px-4 py-3 bg-red-500 text-white rounded-lg font-medium hover:bg-red-600 transition-colors">
                            {{ t('service_center.reject_offer') }}
                        </button>
                    @endif
                    
                    <a href="{{ route('service-center.tow-offers.index') }}" 
                       class="w-full px-4 py-3 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition-colors text-center block">
                        {{ t('service_center.back_to_offers') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Accept Offer Modal -->
@if($offer->status === 'pending')
<div id="acceptModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl max-w-md w-full">
        <div class="p-6 border-b">
            <h3 class="text-xl font-bold">{{ t('service_center.accept_tow_offer') }}</h3>
        </div>
        
        <form id="acceptForm" class="p-6 space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">{{ t('service_center.estimated_pickup_time') }}</label>
                <input type="datetime-local" name="estimated_pickup_time" 
                       class="w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent px-4 py-2.5"
                       min="{{ now()->addMinutes(30)->format('Y-m-d\TH:i') }}"
                       max="{{ now()->addHours(12)->format('Y-m-d\TH:i') }}">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">{{ t('service_center.notes') }}</label>
                <textarea name="notes" rows="3" 
                          class="w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent px-4 py-2.5"
                          placeholder=""></textarea>
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
                          class="w-full border  border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent px-4 py-2.5"
                          placeholder=""></textarea>
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
function acceptOffer() {
    document.getElementById('acceptModal').classList.remove('hidden');
    
    // Set default pickup time to 1 hour from now
    const defaultTime = new Date();
    defaultTime.setHours(defaultTime.getHours() + 1);
    document.querySelector('[name="estimated_pickup_time"]').value = defaultTime.toISOString().slice(0, 16);
}

function rejectOffer() {
    document.getElementById('rejectModal').classList.remove('hidden');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

// Handle accept form submission
document.getElementById('acceptForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = {
        estimated_pickup_time: formData.get('estimated_pickup_time'),
        notes: formData.get('notes')
    };
    
    try {
        const response = await fetch('{{ route("service-center.tow-offers.accept", $offer->id) }}', {
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
    
    const formData = new FormData(this);
    const data = {
        rejection_reason: formData.get('rejection_reason')
    };
    
    try {
        const response = await fetch('{{ route("service-center.tow-offers.reject", $offer->id) }}', {
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
@endif
@endsection