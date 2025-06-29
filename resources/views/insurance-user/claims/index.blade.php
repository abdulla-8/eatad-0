@extends('insurance-user.layouts.app')

@section('title', 'My Claims')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">My Claims</h1>
    <a href="{{ route('insurance.user.claims.create', $company->company_slug) }}" 
       class="bg-primary text-white px-4 py-2 rounded-lg hover:opacity-90">
        + New Claim
    </a>
</div>

@if($claims->count())
    <div class="grid gap-4">
        @foreach($claims as $claim)
        <div class="bg-white rounded-lg border p-4">
            <div class="flex justify-between items-start mb-2">
                <div>
                    <h3 class="font-bold">{{ $claim->claim_number }}</h3>
                    <p class="text-sm text-gray-600">{{ $claim->policy_number }}</p>
                </div>
                <span class="px-2 py-1 rounded text-xs {{ $claim->status_badge['class'] }}">
                    {{ $claim->status_badge['text'] }}
                </span>
            </div>
            
            <div class="text-sm text-gray-600 mb-3">
                <p>Vehicle: {{ $claim->vehicle_plate_number ?: $claim->chassis_number }}</p>
                <p>Created: {{ $claim->created_at->format('M d, Y H:i') }}</p>
                @if($claim->service_center_id)
                    <p>Service Center: {{ $claim->serviceCenter->legal_name }}</p>
                @endif
            </div>

            <div class="flex gap-2">
                <a href="{{ route('insurance.user.claims.show', [$company->company_slug, $claim->id]) }}" 
                   class="text-primary hover:underline text-sm">View Details</a>
                
                @if($claim->status === 'rejected')
                    <a href="{{ route('insurance.user.claims.edit', [$company->company_slug, $claim->id]) }}" 
                       class="text-blue-600 hover:underline text-sm">Edit & Resubmit</a>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-6">
        {{ $claims->links() }}
    </div>
@else
    <div class="text-center py-12">
        <p class="text-gray-500 mb-4">No claims submitted yet</p>
        <a href="{{ route('insurance.user.claims.create', $company->company_slug) }}" 
           class="bg-primary text-white px-6 py-2 rounded-lg hover:opacity-90">
            Submit Your First Claim
        </a>
    </div>
@endif
@endsection