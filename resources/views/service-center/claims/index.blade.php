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
        <div class="md:grid grid-cols-2 lg:grid-cols-7 gap-3 p-3">
            <div class="bg-white rounded-lg border px-4 py-3 text-center shadow-sm mb-6">
                <div class="text-2xl font-bold text-blue-600">{{ $stats['total'] }}</div>
                <div class="text-xs text-gray-600">{{ t('service_center.total') }}</div>
            </div>
     
            <div class="bg-white rounded-lg border px-4 py-3 text-center shadow-sm mb-6">
                <div class="text-2xl font-bold text-blue-600">{{ $stats['approved'] }}</div>
                <div class="text-xs text-gray-600">{{ t('service_center.accepted_by_center') }}</div>
            </div>
            <div class="bg-white rounded-lg border px-4 py-3 text-center shadow-sm mb-6">
                <div class="text-2xl font-bold text-red-600">{{ $stats['rejected'] }}</div>
                <div class="text-xs text-gray-600">{{ t('service_center.rejected_by_center') }}</div>
            </div>
            <div class="bg-white rounded-lg border px-4 py-3 text-center shadow-sm mb-6">
                <div class="text-2xl font-bold text-purple-600">{{ $stats['awaiting_parts'] ?? 0 }}</div>
                <div class="text-xs text-gray-600">{{ t('service_center.awaiting_parts') }}</div>
            </div>
            <div class="bg-white rounded-lg border px-4 py-3 text-center shadow-sm mb-6">
                <div class="text-2xl font-bold text-yellow-600">{{ $stats['in_progress'] }}</div>
                <div class="text-xs text-gray-600">{{ t('service_center.in_progress') }}</div>
            </div>
            <div class="bg-white rounded-lg border px-4 py-3 text-center shadow-sm mb-6">
                <div class="text-2xl font-bold text-gray-600">{{ $stats['completed'] }}</div>
                <div class="text-xs text-gray-600">{{ t('service_center.completed') }}</div>
            </div>
        </div>
    </div>

    <!-- Filters Card -->
 <div class="bg-white rounded-xl shadow-sm border p-6">
    <form method="GET" class="flex flex-col lg:flex-row gap-4">
        <div class="flex-1">
            <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                {{ t('service_center.search') }}
            </label>
            <input type="text" id="search" name="search" value="{{ request('search') }}" 
                   placeholder="{{ t('service_center.search_placeholder') }}"
                   class="w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-4 py-2.5">
        </div>

        <div class="lg:w-48">
            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                {{ t('service_center.status') }}
            </label>
            <select id="status" name="status" 
                    class="w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-4 py-1.5">
                <option value="">{{ t('service_center.all_status') }}</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>{{ t('service_center.pending') }}</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>{{ t('service_center.approved') }}</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>{{ t('service_center.rejected') }}</option>
                <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>{{ t('service_center.in_progress') }}</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>{{ t('service_center.completed') }}</option>
            </select>
        </div>

        <div class="flex gap-2 lg:items-end">
            <button type="submit" 
                    class="px-6 py-2.5 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors whitespace-nowrap">
                {{ t('service_center.filter') }}
            </button>
            @if(request()->hasAny(['status', 'search']))
                <a href="{{ route('service-center.claims.index') }}" 
                   class="px-6 py-2.5 bg-gray-500 text-white rounded-lg font-medium hover:bg-gray-600 transition-colors whitespace-nowrap">
                    {{ t('service_center.clear') }}
                </a>
            @endif
        </div>
    </form>
</div>


    <!-- Claims List -->
    @if($claims->count())
        <div class="space-y-4">
            @foreach($claims as $claim)
        <div class="bg-white rounded-xl shadow-sm border hover:shadow-md transition-shadow">
    <div class="p-6">
        <!-- Header -->
        <div class="flex items-start justify-between mb-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center text-white font-bold text-xl">
                    {{ substr($claim->claim_number, -2) }}
                </div>
                <div>
                    <h3 class="font-bold text-lg flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        {{ $claim->claim_number }}
                    </h3>
                    <p class="text-gray-600 text-sm flex items-center gap-1">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 7h18M3 12h18M3 17h18" />
                        </svg>
                        {{ $claim->policy_number }}
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-3 flex-wrap">
                @php
                    $statusColors = [
                        'approved' => 'bg-green-100 text-green-800',
                        'rejected' => 'bg-red-100 text-red-800',
                        'in_progress' => 'bg-yellow-100 text-yellow-800',
                        'completed' => 'bg-gray-100 text-gray-800',
                    ];
                    $statusClass = $statusColors[$claim->status] ?? 'bg-gray-100 text-gray-800';
                @endphp
                <span class="px-3 py-1.5 rounded-full text-sm font-medium {{ $statusClass }}">
                    {{ t('service_center.' . $claim->status) }}
                </span>

                <!-- Action Buttons -->
                @if($claim->status === 'pending')
                    <button onclick="openSCApproveModal({{ $claim->id }})"
                        class="px-4 py-2 bg-green-500 text-white rounded-lg text-sm font-medium hover:bg-green-600 transition-colors flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 13l4 4L19 7"></path>
                        </svg>
                        {{ t('service_center.approve_claim') }}
                    </button>
                    <button onclick="openSCRejectModal({{ $claim->id }})"
                        class="px-4 py-2 bg-red-500 text-white rounded-lg text-sm font-medium hover:bg-red-600 transition-colors flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        {{ t('service_center.reject_claim') }}
                    </button>
                @elseif($claim->shouldShowConfirmPartsButton())
                    <button onclick="openConfirmPartsModal({{ $claim->id }})"
                        class="px-4 py-2 bg-purple-500 text-white rounded-lg text-sm font-medium hover:bg-purple-600 transition-colors flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 13l4 4L19 7"></path>
                        </svg>
                        {{ t('service_center.confirm_parts_received') }}
                    </button>
                @elseif($claim->canStartWork())
                    <button onclick="markInProgress({{ $claim->id }})" 
                            class="px-4 py-2 bg-yellow-500 text-white rounded-lg text-sm font-medium hover:bg-yellow-600 transition-colors flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 8v4l3 3"></path>
                        </svg>
                        {{ t('service_center.start_work') }}
                    </button>
                @elseif($claim->status === 'in_progress')
                    <button onclick="markCompleted({{ $claim->id }})" 
                            class="px-4 py-2 bg-green-500 text-white rounded-lg text-sm font-medium hover:bg-green-600 transition-colors flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 13l4 4L19 7"></path>
                        </svg>
                        {{ t('service_center.mark_completed') }}
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
                    <div>{{ $claim->insuranceUser->full_name }}</div>
                    <div>{{ $claim->insuranceUser->formatted_phone }}</div>
                </div>
            </div>

            <div class="space-y-2">
                <h4 class="font-semibold text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 13l1.5-4.5a1 1 0 011-1h13a1 1 0 011 1l1.5 4.5M5 16h14v2a2 2 0 01-2 2H7a2 2 0 01-2-2v-2z"></path>
                    </svg>
                    {{ t('service_center.vehicle_info') }}
                </h4>
                <div class="space-y-1 text-sm text-gray-600">
                    <div>{{ $claim->vehicle_plate_number ?: $claim->chassis_number }}</div>
                    <div class="{{ $claim->is_vehicle_working ? 'text-green-600' : 'text-red-600' }}">
                        {{ $claim->is_vehicle_working ? t('service_center.working') : t('service_center.not_working') }}
                    </div>
                </div>
            </div>

            <div class="space-y-2">
                <h4 class="font-semibold text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4z"></path>
                        <path d="M6 20v-2a4 4 0 014-4h4a4 4 0 014 4v2"></path>
                    </svg>
                    {{ t('service_center.insurance_company') }}
                </h4>
                <div class="text-sm text-gray-600">
                    {{ $claim->insuranceCompany->legal_name }}
                </div>
            </div>

            <div class="space-y-2">
                <h4 class="font-semibold text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M8 7V3a4 4 0 118 0v4m-4 12v-6m6 6H6a2 2 0 01-2-2v-6a2 2 0 012-2h12a2 2 0 012 2v6a2 2 0 01-2 2z"></path>
                    </svg>
                    {{ t('service_center.dates') }}
                </h4>
                <div class="space-y-1 text-sm text-gray-600">
                    <div>{{ $claim->created_at->format('M d, Y H:i') }}</div>
                    @if($claim->parts_received_at)
                        <div class="text-green-600 font-medium">
                            Parts: {{ $claim->parts_received_at->format('M d, Y') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Parts Status Alert -->
        @if($claim->inspection && $claim->inspection->insurance_response === 'approved' && !$claim->parts_received_at)
            <div class="mb-4">
                <div class="bg-purple-50 border border-purple-200 rounded-xl p-3 text-purple-800 flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span class="font-bold">{{ t('service_center.parts_approved') }}:</span>
                    <span>{{ t('service_center.waiting_for_parts_delivery') }}</span>
                </div>
            </div>
        @elseif($claim->parts_received_at)
            <div class="mb-4">
                <div class="bg-green-50 border border-green-200 rounded-xl p-3 text-green-800 flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span class="font-bold">{{ t('service_center.parts_received') }}:</span>
                    <span>{{ $claim->parts_received_at->format('M d, Y H:i') }}</span>
                </div>
            </div>
        @endif

        <!-- Service Center Note -->
        @if($claim->service_center_note)
            <div class="mb-4">
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-3 text-blue-800 flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    <span class="font-bold">{{ t('service_center.center_note') }}:</span>
                    <span>{{ $claim->service_center_note }}</span>
                </div>
            </div>
        @endif

        <!-- View Details Button -->
        <div class="flex justify-end">
            <a href="{{ route('service-center.claims.show', $claim->id) }}" 
               class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-600 rounded-lg font-medium hover:bg-blue-100 transition-colors">
                {{ t('service_center.view_details') }}
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 5l7 7-7 7"></path>
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

<!-- Approve Modal -->
<div id="scApproveModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl max-w-md w-full">
        <div class="p-6 border-b">
            <h3 class="text-xl font-bold">{{ t('service_center.approve_claim') }}</h3>
        </div>
        <form id="scApproveForm" method="POST" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">{{ t('service_center.approval_note') }}</label>
                <textarea name="approval_note" rows="4"
                    class="w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent px-4 py-2.5"
                    placeholder="{{ t('service_center.approval_note_placeholder') }}"></textarea>
            </div>
            <div class="flex gap-4">
                <button type="submit" class="flex-1 py-3 bg-green-500 text-white rounded-lg font-medium hover:bg-green-600 transition-colors">
                    {{ t('service_center.confirm_approval') }}
                </button>
                <button type="button" onclick="closeModal('scApproveModal')"
                    class="flex-1 py-3 bg-gray-500 text-white rounded-lg font-medium hover:bg-gray-600 transition-colors">
                    {{ t('service_center.cancel') }}
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Reject Modal -->
<div id="scRejectModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl max-w-md w-full">
        <div class="p-6 border-b">
            <h3 class="text-xl font-bold">{{ t('service_center.reject_claim') }}</h3>
        </div>
        <form id="scRejectForm" method="POST" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">{{ t('service_center.rejection_reason') }} *</label>
                <textarea name="rejection_reason" rows="4" required
                    class="w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent px-4 py-2.5"
                    placeholder="{{ t('service_center.rejection_reason_placeholder') }}"></textarea>
            </div>
            <div class="flex gap-4">
                <button type="submit" class="flex-1 py-3 bg-red-500 text-white rounded-lg font-medium hover:bg-red-600 transition-colors">
                    {{ t('service_center.confirm_rejection') }}
                </button>
                <button type="button" onclick="closeModal('scRejectModal')"
                    class="flex-1 py-3 bg-gray-500 text-white rounded-lg font-medium hover:bg-gray-600 transition-opacity">
                    {{ t('service_center.cancel') }}
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Confirm Parts Received Modal -->
<div id="confirmPartsModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl max-w-md w-full">
        <div class="p-6 border-b">
            <h3 class="text-xl font-bold">{{ t('service_center.confirm_parts_received') }}</h3>
        </div>
        <form id="confirmPartsForm" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">{{ t('service_center.parts_received_notes') }}</label>
                <textarea name="parts_received_notes" rows="4"
                    class="w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent px-4 py-2.5"
                    placeholder="{{ t('service_center.parts_received_notes_placeholder') }}"></textarea>
            </div>
            <div class="flex gap-4">
                <button type="submit" class="flex-1 py-3 bg-purple-500 text-white rounded-lg font-medium hover:bg-purple-600 transition-colors">
                    {{ t('service_center.confirm_received') }}
                </button>
                <button type="button" onclick="closeModal('confirmPartsModal')"
                    class="flex-1 py-3 bg-gray-500 text-white rounded-lg font-medium hover:bg-gray-600 transition-opacity">
                    {{ t('service_center.cancel') }}
                </button>
            </div>
        </form>
    </div>
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
// رسالة تنبيه ديناميكية
function showAlert(message, color) {
    let alertDiv = document.createElement('div');
    alertDiv.className = `fixed top-6 left-1/2 transform -translate-x-1/2 z-50 px-6 py-3 rounded shadow-lg text-white font-bold bg-${color}-500`;
    alertDiv.textContent = message;
    document.body.appendChild(alertDiv);
    setTimeout(() => { alertDiv.remove(); }, 3000);
}

function openSCApproveModal(claimId) {
    document.getElementById('scApproveForm').action = `/service-center/claims/${claimId}/approve`;
    document.getElementById('scApproveModal').classList.remove('hidden');
}

function openSCRejectModal(claimId) {
    document.getElementById('scRejectForm').action = `/service-center/claims/${claimId}/reject`;
    document.getElementById('scRejectModal').classList.remove('hidden');
}

function openConfirmPartsModal(claimId) {
    document.getElementById('confirmPartsForm').setAttribute('data-claim-id', claimId);
    document.getElementById('confirmPartsModal').classList.remove('hidden');
}

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

// Event listeners for outside clicks
document.getElementById('scApproveModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal('scApproveModal');
});

document.getElementById('scRejectModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal('scRejectModal');
});

document.getElementById('confirmPartsModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal('confirmPartsModal');
});

document.getElementById('inProgressModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal('inProgressModal');
});

document.getElementById('completedModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal('completedModal');
});

// AJAX للموافقة
document.getElementById('scApproveForm').addEventListener('submit', function(e) {
    e.preventDefault();
    let form = this;
    let formData = new FormData(form);
    fetch(form.action, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': form.querySelector('[name="_token"]').value },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showAlert(data.message || 'تمت الموافقة بنجاح', 'green');
            closeModal('scApproveModal');
            setTimeout(() => window.location.reload(), 1000);
        } else {
            showAlert(data.error || 'حدث خطأ أثناء الموافقة', 'red');
        }
    })
    .catch(() => {
        showAlert('حدث خطأ أثناء الاتصال بالخادم', 'red');
    });
});

// AJAX للرفض
document.getElementById('scRejectForm').addEventListener('submit', function(e) {
    e.preventDefault();
    let form = this;
    let formData = new FormData(form);
    fetch(form.action, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': form.querySelector('[name="_token"]').value },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showAlert(data.message || 'تم الرفض بنجاح', 'green');
            closeModal('scRejectModal');
            setTimeout(() => window.location.reload(), 1000);
        } else {
            showAlert(data.error || 'حدث خطأ أثناء الرفض', 'red');
        }
    })
    .catch(() => {
        showAlert('حدث خطأ أثناء الاتصال بالخادم', 'red');
    });
});

// AJAX لتأكيد استلام القطع
document.getElementById('confirmPartsForm').addEventListener('submit', function(e) {
    e.preventDefault();
    let form = this;
    let claimId = form.getAttribute('data-claim-id');
    let formData = new FormData(form);
    
    fetch(`/service-center/claims/${claimId}/confirm-parts-received`, {
        method: 'POST',
        headers: { 
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            parts_received_notes: formData.get('parts_received_notes')
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showAlert(data.message || 'تم تأكيد استلام القطع بنجاح', 'green');
            closeModal('confirmPartsModal');
            setTimeout(() => window.location.reload(), 1000);
        } else {
            showAlert(data.error || 'حدث خطأ أثناء تأكيد الاستلام', 'red');
        }
    })
    .catch(() => {
        showAlert('حدث خطأ أثناء الاتصال بالخادم', 'red');
    });
});

</script>

@endsection
