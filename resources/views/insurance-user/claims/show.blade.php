@extends('insurance-user.layouts.app')

@section('title', 'Claim Details')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Claim {{ $claim->claim_number }}</h1>
        <span class="px-3 py-1 rounded {{ $claim->status_badge['class'] }}">
            {{ $claim->status_badge['text'] }}
        </span>
    </div>

    {{-- Status Messages --}}
    @if($claim->status === 'rejected')
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
            <h3 class="font-bold text-red-800">Claim Rejected</h3>
            <p class="text-red-700">{{ $claim->rejection_reason }}</p>
            <a href="{{ route('insurance.user.claims.edit', [$company->company_slug, $claim->id]) }}" 
               class="mt-2 inline-block bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                Edit & Resubmit
            </a>
        </div>
    @endif

    @if($claim->tow_service_offered && is_null($claim->tow_service_accepted))
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <h3 class="font-bold text-blue-800">Tow Service Offered</h3>
            <p class="text-blue-700 mb-3">Since your vehicle is not working, we can provide tow service to the service center.</p>
            <form method="POST" action="{{ route('insurance.user.claims.tow-service', [$company->company_slug, $claim->id]) }}" 
                  class="flex gap-2">
                @csrf
                <button type="submit" name="tow_service_accepted" value="1" 
                        class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    Accept Tow Service
                </button>
                <button type="submit" name="tow_service_accepted" value="0" 
                        class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                    Decline - I'll Go Myself
                </button>
            </form>
        </div>
    @endif

    <div class="grid lg:grid-cols-2 gap-6">
        {{-- Basic Info --}}
        <div class="bg-white rounded-lg border p-6">
            <h2 class="text-lg font-bold mb-4">Claim Information</h2>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between"><span class="font-medium">Policy Number:</span><span>{{ $claim->policy_number }}</span></div>
                <div class="flex justify-between"><span class="font-medium">Vehicle:</span><span>{{ $claim->vehicle_plate_number ?: $claim->chassis_number }}</span></div>
                <div class="flex justify-between"><span class="font-medium">Vehicle Working:</span><span>{{ $claim->is_vehicle_working ? 'Yes' : 'No' }}</span></div>
                <div class="flex justify-between"><span class="font-medium">Receipt Ready:</span><span>{{ $claim->repair_receipt_ready ? 'Yes' : 'No' }}</span></div>
                <div class="flex justify-between"><span class="font-medium">Submitted:</span><span>{{ $claim->created_at->format('M d, Y H:i') }}</span></div>
                @if($claim->tow_service_offered)
                    <div class="flex justify-between">
                        <span class="font-medium">Tow Service:</span>
                        <span>
                            @if(is_null($claim->tow_service_accepted))
                                Pending Response
                            @elseif($claim->tow_service_accepted)
                                Accepted
                            @else
                                Declined
                            @endif
                        </span>
                    </div>
                @endif
            </div>
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
                       class="inline-block mt-2 bg-blue-500 text-white px-3 py-1 rounded text-xs hover:bg-blue-600">
                        📍 View on Map
                    </a>
                @endif
            </div>
        </div>
        @endif

        {{-- Location --}}
        <div class="bg-white rounded-lg border p-6">
            <h2 class="text-lg font-bold mb-4">Vehicle Location</h2>
            <p class="text-sm text-gray-700 mb-2">{{ $claim->vehicle_location }}</p>
            @if($claim->vehicle_location_lat)
                <a href="{{ $claim->vehicle_location_url }}" target="_blank" 
                   class="inline-block bg-green-500 text-white px-3 py-1 rounded text-xs hover:bg-green-600">
                    📍 View on Map
                </a>
            @endif
        </div>

        {{-- Notes --}}
        @if($claim->notes)
        <div class="bg-white rounded-lg border p-6">
            <h2 class="text-lg font-bold mb-4">Notes</h2>
            <p class="text-sm text-gray-700">{{ $claim->notes }}</p>
        </div>
        @endif
    </div>

    {{-- Attachments --}}
    @if($claim->attachments->count())
    <div class="bg-white rounded-lg border p-6 mt-6">
        <h2 class="text-lg font-bold mb-4">Attachments</h2>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($claim->attachments->groupBy('type') as $type => $attachments)
                <div class="border rounded-lg p-3">
                    <h3 class="font-medium text-sm mb-2">{{ $attachments->first()->type_display_name }}</h3>
                    @foreach($attachments as $attachment)
                        <div class="flex items-center gap-2 text-xs mb-1">
                            <span>📎</span>
                            <a href="{{ $attachment->file_url }}" target="_blank" 
                               class="text-blue-600 hover:underline truncate">
                                {{ $attachment->file_name }}
                            </a>
                            <span class="text-gray-500">({{ $attachment->file_size_formatted }})</span>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <div class="mt-6 flex gap-4">
        <a href="{{ route('insurance.user.claims.index', $company->company_slug) }}" 
           class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:opacity-90">
            ← Back to Claims
        </a>
        
        @if($claim->status === 'rejected')
            <a href="{{ route('insurance.user.claims.edit', [$company->company_slug, $claim->id]) }}" 
               class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                Edit & Resubmit
            </a>
        @endif
    </div>
</div>
@endsection