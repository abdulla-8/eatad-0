@extends('admin.layouts.app')

@section('title', 'Vehicle Inspections')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Vehicle Inspections</h1>
            <p class="text-gray-600 mt-1">View all vehicle inspection reports</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border">
        <div class="p-6">
            <form method="GET" class="flex gap-4">
                <div class="flex-1">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Search by claim number, plate, or chassis..."
                           class="w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent px-4 py-2.5">
                </div>
                <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors">
                    Search
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.inspections.index') }}" 
                       class="px-6 py-2.5 bg-gray-500 text-white rounded-lg font-medium hover:bg-gray-600 transition-colors">
                        Clear
                    </a>
                @endif
            </form>
        </div>
    </div>

    <!-- Inspections List -->
    @if($inspections->count())
        <div class="space-y-4">
            @foreach($inspections as $inspection)
            <div class="bg-white rounded-xl shadow-sm border">
                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h3 class="text-xl font-bold">{{ $inspection->claim->claim_number }}</h3>
                            <p class="text-gray-600">{{ $inspection->vehicle_brand }} {{ $inspection->vehicle_model }} ({{ $inspection->vehicle_year }})</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500">{{ $inspection->created_at->format('M d, Y H:i') }}</p>
                            <p class="text-sm font-medium">{{ $inspection->serviceCenter->legal_name }}</p>
                        </div>
                    </div>

                    <div class="grid md:grid-cols-3 gap-4 mb-4">
                        <div>
                            <span class="text-gray-600">Customer:</span>
                            <p class="font-medium">{{ $inspection->claim->insuranceUser->full_name }}</p>
                        </div>
                        <div>
                            <span class="text-gray-600">Chassis Number:</span>
                            <p class="font-medium">{{ $inspection->chassis_number }}</p>
                        </div>
                        <div>
                            <span class="text-gray-600">Required Parts:</span>
                            <p class="font-medium">{{ count($inspection->required_parts) }} items</p>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <a href="{{ route('admin.inspections.show', $inspection->id) }}" 
                           class="px-4 py-2 bg-blue-50 text-blue-600 rounded-lg font-medium hover:bg-blue-100 transition-colors">
                            View Details
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
        <div class="bg-white rounded-xl shadow-sm border">
            <div class="p-12 text-center">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No inspections found</h3>
                <p class="text-gray-600">No vehicle inspections have been submitted yet.</p>
            </div>
        </div>
    @endif
</div>
@endsection