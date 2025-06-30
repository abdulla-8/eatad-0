@extends('service-center.layouts.app')

@section('title', t('service_center.claims'))

@section('content')
<div class="space-y-6">
    <!-- Header with Stats -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ t('service_center.claims') }}</h1>
            <p class="text-gray-600 mt-1">{{ t('service_center.manage_assigned_claims') }}</p>
        </div>
        
        <!-- Quick Stats -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
            <div class="bg-white rounded-lg border px-4 py-3 text-center shadow-sm">
                <div class="text-2xl font-bold text-blue-600">{{ $stats['total'] }}</div>
                <div class="text-xs text-gray-600">{{ t('service_center.total') }}</div>
            </div>
            <div class="bg-white rounded-lg border px-4 py-3 text-center shadow-sm">
                <div class="text-2xl font-bold text-green-600">{{ $stats['approved'] }}</div>
                <div class="text-xs text-gray-600">{{ t('service_center.new_claims') }}</div>
            </div>
            <div class="bg-white rounded-lg border px-4 py-3 text-center shadow-sm">
                <div class="text-2xl font-bold text-yellow-600">{{ $stats['in_progress'] }}</div>
                <div class="text-xs text-gray-600">{{ t('service_center.in_progress') }}</div>
            </div>
            <div class="bg-white rounded-lg border px-4 py-3 text-center shadow-sm">
                <div class="text-2xl font-bold text-gray-600">{{ $stats['completed'] }}</div>
                <div class="text-xs text-gray-600">{{ t('service_center.completed') }}</div>
            </div>
        </div>
    </div>

    <!-- Filters Card -->
    <div class="bg-white rounded-xl shadow-sm border">
        <div class="p-6">
            <form method="GET" class="flex flex-col lg:flex-row gap-4">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ t('service_center.search') }}</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="{{ t('service_center.search_placeholder') }}"
                           class="w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent px-4 py-2.5">
                </div>
                
                <div class="lg:w-48">
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ t('service_center.status') }}</label>
                    <select name="status" class="w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent px-4 py-2.5">
                        <option value="">{{ t('service_center.all_status') }}</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>{{ t('service_center.new_claims') }}</option>
                        <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>{{ t('service_center.in_progress') }}</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>{{ t('service_center.completed') }}</option>
                    </select>
                </div>
                
                <div class="flex gap-2 lg:items-end">
                    <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors">
                        {{ t('service_center.filter') }}
                    </button>
                    
                    @if(request()->hasAny(['status', 'search']))
                        <a href="{{ route('service-center.claims.index') }}" 
                           class="px-6 py-2.5 bg-gray-500 text-white rounded-lg font-medium hover:bg-gray-600 transition-colors">
                            {{ t('service_center.clear') }}
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Claims List -->
    @if($claims->count())
        <div class="space-y-4">
            @foreach($claims as $claim)
            <div class="bg-white rounded-xl shadow-sm border hover:shadow-md transition-shadow">
                <div class="p-6">
                    <!-- Header -->
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center text-white font-bold">
                                {{ substr($claim->claim_number, -2) }}
                            </div>
                            <div>
                                <h3 class="font-bold text-lg">{{ $claim->claim_number }}</h3>
                                <p class="text-gray-600 text-sm">{{ $claim->policy_number }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-3">
                            @if($claim->status === 'approved')
                                <span class="px-3 py-1.5 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    {{ t('service_center.new_claim') }}
                                </span>
                            @elseif($claim->status === 'in_progress')
                                <span class="px-3 py-1.5 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                    {{ t('service_center.in_progress') }}
                                </span>
                            @elseif($claim->status === 'completed')
                                <span class="px-3 py-1.5 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                    {{ t('service_center.completed') }}
                                </span>
                            @endif
                            
                            @if($claim->status === 'approved')
                                <button onclick="markInProgress({{ $claim->id }})" 
                                        class="px-4 py-2 bg-yellow-500 text-white rounded-lg text-sm font-medium hover:bg-yellow-600 transition-colors">
                                    {{ t('service_center.start_work') }}
                                </button>
                            @elseif($claim->status === 'in_progress')
                                <button onclick="markCompleted({{ $claim->id }})" 
                                        class="px-4 py-2 bg-green-500 text-white rounded-lg text-sm font-medium hover:bg-green-600 transition-colors">
                                    {{ t('service_center.mark_completed') }}
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Content Grid -->
                    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 mb-4">
                        <div class="space-y-2">
                            <h4 class="font-medium text-gray-900">{{ t('service_center.customer_info') }}</h4>
                            <div class="space-y-1 text-sm text-gray-600">
                                <div>{{ $claim->insuranceUser->full_name }}</div>
                                <div>{{ $claim->insuranceUser->formatted_phone }}</div>
                            </div>
                        </div>
                        
                        <div class="space-y-2">
                            <h4 class="font-medium text-gray-900">{{ t('service_center.vehicle_info') }}</h4>
                            <div class="space-y-1 text-sm text-gray-600">
                                <div>{{ $claim->vehicle_plate_number ?: $claim->chassis_number }}</div>
                                <div class="{{ $claim->is_vehicle_working ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $claim->is_vehicle_working ? t('service_center.working') : t('service_center.not_working') }}
                                </div>
                            </div>
                        </div>
                        
                        <div class="space-y-2">
                            <h4 class="font-medium text-gray-900">{{ t('service_center.insurance_company') }}</h4>
                            <div class="text-sm text-gray-600">
                                {{ $claim->insuranceCompany->legal_name }}
                            </div>
                        </div>
                        
                        <div class="space-y-2">
                            <h4 class="font-medium text-gray-900">{{ t('service_center.received_date') }}</h4>
                            <div class="text-sm text-gray-600">
                                {{ $claim->created_at->format('M d, Y H:i') }}
                            </div>
                        </div>
                    </div>

                    <!-- Action -->
                    <div class="flex justify-end">
                        <a href="{{ route('service-center.claims.show', $claim->id) }}" 
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
            {{ $claims->withQueryString()->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-xl shadow-sm border">
            <div class="p-12 text-center">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">{{ t('service_center.no_claims_found') }}</h3>
                <p class="text-gray-600">{{ t('service_center.no_claims_description') }}</p>
            </div>
        </div>
    @endif
</div>

<!-- Mark In Progress Modal -->
<div id="inProgressModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl max-w-md w-full">
        <div class="p-6 border-b">
            <h3 class="text-xl font-bold">{{ t('service_center.start_working_on_claim') }}</h3>
        </div>
        
        <form id="inProgressForm" method="POST" class="p-6">
            @csrf
            
            <p class="text-gray-600 mb-6">{{ t('service_center.confirm_start_work_message') }}</p>
            
            <div class="flex gap-4">
                <button type="submit" class="flex-1 py-3 bg-yellow-500 text-white rounded-lg font-medium hover:bg-yellow-600 transition-colors">
                    {{ t('service_center.start_work') }}
                </button>
                <button type="button" onclick="closeModal('inProgressModal')" 
                        class="flex-1 py-3 bg-gray-500 text-white rounded-lg font-medium hover:bg-gray-600 transition-colors">
                    {{ t('service_center.cancel') }}
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Mark Completed Modal -->
<div id="completedModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl max-w-md w-full">
        <div class="p-6 border-b">
            <h3 class="text-xl font-bold">{{ t('service_center.mark_claim_completed') }}</h3>
        </div>
        
        <form id="completedForm" method="POST" class="p-6 space-y-4">
            @csrf
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">{{ t('service_center.completion_notes') }}</label>
                <textarea name="completion_notes" rows="4" 
                          class="w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent px-4 py-2.5"
                          placeholder="{{ t('service_center.completion_notes_placeholder') }}"></textarea>
            </div>
            
            <div class="flex gap-4">
                <button type="submit" class="flex-1 py-3 bg-green-500 text-white rounded-lg font-medium hover:bg-green-600 transition-colors">
                    {{ t('service_center.mark_completed') }}
                </button>
                <button type="button" onclick="closeModal('completedModal')" 
                        class="flex-1 py-3 bg-gray-500 text-white rounded-lg font-medium hover:bg-gray-600 transition-colors">
                    {{ t('service_center.cancel') }}
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function markInProgress(claimId) {
    document.getElementById('inProgressForm').action = `{{ route('service-center.claims.mark-progress', '__ID__') }}`.replace('__ID__', claimId);
    document.getElementById('inProgressModal').classList.remove('hidden');
}

function markCompleted(claimId) {
    document.getElementById('completedForm').action = `{{ route('service-center.claims.mark-completed', '__ID__') }}`.replace('__ID__', claimId);
    document.getElementById('completedModal').classList.remove('hidden');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

// Close modal on outside click
document.getElementById('inProgressModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal('inProgressModal');
});

document.getElementById('completedModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal('completedModal');
});
</script>
@endsection