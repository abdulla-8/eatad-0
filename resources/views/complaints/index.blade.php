{{-- resources/views/complaints/index.blade.php --}}
@extends($userType === 'insurance_company' ? 'insurance.layouts.app' : 'service-center.layouts.app')

@section('title', t($translationGroup . '.complaints_inquiries'))

@section('content')
<div class="space-y-6">
    <!-- Header with Stats -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ t($translationGroup . '.complaints_inquiries') }}</h1>
            <p class="text-gray-600 mt-1">{{ t($translationGroup . '.manage_complaints_inquiries') }}</p>
        </div>
        
        <button onclick="openComplaintModal()" 
                class="px-6 py-3 text-white rounded-lg font-medium hover:opacity-90 transition-opacity"
                style="background: {{ $primaryColor }};">
            {{ t($translationGroup . '.add_new_complaint') }}
        </button>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-2 lg:grid-cols-6 gap-3">
        <div class="bg-white rounded-lg border px-4 py-3 text-center shadow-sm">
            <div class="text-2xl font-bold" style="color: {{ $primaryColor }};">{{ $stats['total'] }}</div>
            <div class="text-xs text-gray-600">{{ t($translationGroup . '.total') }}</div>
        </div>
        <div class="bg-white rounded-lg border px-4 py-3 text-center shadow-sm">
            <div class="text-2xl font-bold text-red-600">{{ $stats['unread'] }}</div>
            <div class="text-xs text-gray-600">{{ t($translationGroup . '.unread') }}</div>
        </div>
        <div class="bg-white rounded-lg border px-4 py-3 text-center shadow-sm">
            <div class="text-2xl font-bold text-green-600">{{ $stats['read'] }}</div>
            <div class="text-xs text-gray-600">{{ t($translationGroup . '.read') }}</div>
        </div>
        <div class="bg-white rounded-lg border px-4 py-3 text-center shadow-sm">
            <div class="text-2xl font-bold text-blue-600">{{ $stats['inquiry'] }}</div>
            <div class="text-xs text-gray-600">{{ t($translationGroup . '.inquiries') }}</div>
        </div>
        <div class="bg-white rounded-lg border px-4 py-3 text-center shadow-sm">
            <div class="text-2xl font-bold text-yellow-600">{{ $stats['complaint'] }}</div>
            <div class="text-xs text-gray-600">{{ t($translationGroup . '.complaints') }}</div>
        </div>
        <div class="bg-white rounded-lg border px-4 py-3 text-center shadow-sm">
            <div class="text-2xl font-bold text-gray-600">{{ $stats['other'] }}</div>
            <div class="text-xs text-gray-600">{{ t($translationGroup . '.other') }}</div>
        </div>
    </div>

    <!-- Filters Card -->
    <div class="bg-white rounded-xl shadow-sm border">
        <div class="p-6">
            <form method="GET" class="flex flex-col lg:flex-row gap-4">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ t($translationGroup . '.search') }}</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="{{ t($translationGroup . '.search_placeholder') }}"
                           class="w-full border-gray-300 rounded-lg focus:ring-2 focus:border-transparent px-4 py-2.5"
                           style="focus:ring-color: {{ $primaryColor }};">
                </div>
                
                <div class="lg:w-48">
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ t($translationGroup . '.request_type') }}</label>
                    <select name="type" class="w-full border-gray-300 rounded-lg focus:ring-2 focus:border-transparent px-4 py-2.5"
                            style="focus:ring-color: {{ $primaryColor }};">
                        <option value="">{{ t($translationGroup . '.all_types') }}</option>
                        <option value="inquiry" {{ request('type') === 'inquiry' ? 'selected' : '' }}>{{ t($translationGroup . '.inquiry') }}</option>
                        <option value="complaint" {{ request('type') === 'complaint' ? 'selected' : '' }}>{{ t($translationGroup . '.complaint') }}</option>
                        <option value="other" {{ request('type') === 'other' ? 'selected' : '' }}>{{ t($translationGroup . '.other') }}</option>
                    </select>
                </div>
                
                <div class="lg:w-48">
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ t($translationGroup . '.status') }}</label>
                    <select name="status" class="w-full border-gray-300 rounded-lg focus:ring-2 focus:border-transparent px-4 py-2.5"
                            style="focus:ring-color: {{ $primaryColor }};">
                        <option value="">{{ t($translationGroup . '.all_status') }}</option>
                        <option value="unread" {{ request('status') === 'unread' ? 'selected' : '' }}>{{ t($translationGroup . '.unread') }}</option>
                        <option value="read" {{ request('status') === 'read' ? 'selected' : '' }}>{{ t($translationGroup . '.read') }}</option>
                    </select>
                </div>
                
                <div class="flex gap-2 lg:items-end">
                    <button type="submit" class="px-6 py-2.5 text-white rounded-lg font-medium hover:opacity-90 transition-opacity"
                            style="background: {{ $primaryColor }};">
                        {{ t($translationGroup . '.filter') }}
                    </button>
                    
                    @if(request()->hasAny(['status', 'type', 'search']))
                        @if($userType === 'insurance_company')
                            <a href="{{ route('insurance.complaints.index', ['companyRoute' => $user->company_slug]) }}" 
                               class="px-6 py-2.5 bg-gray-500 text-white rounded-lg font-medium hover:opacity-90 transition-opacity">
                                {{ t($translationGroup . '.clear') }}
                            </a>
                        @else
                            <a href="{{ route('service-center.complaints.index') }}" 
                               class="px-6 py-2.5 bg-gray-500 text-white rounded-lg font-medium hover:opacity-90 transition-opacity">
                                {{ t($translationGroup . '.clear') }}
                            </a>
                        @endif
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Complaints List -->
    @if($complaints->count())
        <div class="space-y-4">
            @foreach($complaints as $complaint)
                {{-- تحقق مشدد من أن الشكوى تخص المستخدم الحالي --}}
                @if($complaint->complainant_type === $userType && $complaint->complainant_id === $user->id)
                    <div class="bg-white rounded-xl shadow-sm border hover:shadow-md transition-shadow {{ !$complaint->is_read ? 'ring-2 ring-red-100' : '' }}">
                        <div class="p-6">
                            <!-- Header -->
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-lg flex items-center justify-center text-white font-bold {{ !$complaint->is_read ? 'bg-red-500' : 'bg-blue-500' }}"
                                         style="{{ !$complaint->is_read ? '' : 'background: ' . $primaryColor . ';' }}">
                                        {{ str_pad($complaint->id, 2, '0', STR_PAD_LEFT) }}
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-lg {{ !$complaint->is_read ? 'text-red-900' : 'text-gray-900' }}">{{ $complaint->subject }}</h3>
                                        <p class="text-gray-500 text-xs">{{ $complaint->created_at->format('d/m/Y H:i') }}</p>
                                        {{-- Debug info - احذف هذا السطر بعد التأكد من الحل --}}
                                        <p class="text-xs text-gray-400">Type: {{ $complaint->complainant_type }} | ID: {{ $complaint->complainant_id }}</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-center gap-3">
                                    <span class="px-3 py-1.5 rounded-full text-sm font-medium {{ $complaint->type_badge['class'] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ t($translationGroup . '.' . $complaint->type) }}
                                    </span>
                                    <span class="px-3 py-1.5 rounded-full text-sm font-medium {{ $complaint->is_read ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $complaint->is_read ? t($translationGroup . '.read') : t($translationGroup . '.unread') }}
                                    </span>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="mb-4">
                                <p class="text-gray-600 text-sm">
                                    {{ Str::limit($complaint->description, 150) }}
                                </p>
                            </div>

                            <!-- Action -->
                            <div class="flex justify-end">
                                @if($userType === 'insurance_company')
                                    <a href="{{ route('insurance.complaints.show', ['companyRoute' => $user->company_slug, 'id' => $complaint->id]) }}" 
                                       class="inline-flex items-center gap-2 px-4 py-2 rounded-lg font-medium transition-colors"
                                       style="background: {{ $primaryColor }}20; color: {{ $primaryColor }};">
                                        {{ t($translationGroup . '.read_more') }}
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                @else
                                    <a href="{{ route('service-center.complaints.show', $complaint->id) }}" 
                                       class="inline-flex items-center gap-2 px-4 py-2 rounded-lg font-medium transition-colors"
                                       style="background: {{ $primaryColor }}20; color: {{ $primaryColor }};">
                                        {{ t($translationGroup . '.read_more') }}
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @else
                    {{-- Log للشكاوى التي لا تخص المستخدم الحالي --}}
                    @php
                        \Log::warning('Invalid complaint shown to user', [
                            'complaint_id' => $complaint->id,
                            'complaint_type' => $complaint->complainant_type,
                            'complaint_owner' => $complaint->complainant_id,
                            'current_user_type' => $userType,
                            'current_user_id' => $user->id
                        ]);
                    @endphp
                @endif
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="flex justify-center">
            {{ $complaints->withQueryString()->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-xl shadow-sm border">
            <div class="p-12 text-center">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full flex items-center justify-center"
                     style="background: {{ $primaryColor }}20;">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: {{ $primaryColor }};">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">{{ t($translationGroup . '.no_complaints_found') }}</h3>
                <p class="text-gray-600">{{ t($translationGroup . '.no_complaints_description') }}</p>
            </div>
        </div>
    @endif
</div>

<!-- Add Complaint Modal -->
<div id="complaintModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b">
            <h3 class="text-xl font-bold">{{ t($translationGroup . '.add_new_complaint') }}</h3>
        </div>
        
        @if($userType === 'insurance_company')
            <form id="complaintForm" method="POST" action="{{ route('insurance.complaints.store', ['companyRoute' => $user->company_slug]) }}" enctype="multipart/form-data" class="p-6 space-y-6">
        @else
            <form id="complaintForm" method="POST" action="{{ route('service-center.complaints.store') }}" enctype="multipart/form-data" class="p-6 space-y-6">
        @endif
            @csrf
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">{{ t($translationGroup . '.type') }} *</label>
                <select name="type" required 
                        class="w-full border-gray-300 rounded-lg focus:ring-2 focus:border-transparent px-4 py-2.5"
                        style="focus:ring-color: {{ $primaryColor }};">
                    <option value="">{{ t($translationGroup . '.select_type') }}</option>
                    <option value="inquiry">{{ t($translationGroup . '.inquiry') }}</option>
                    <option value="complaint">{{ t($translationGroup . '.complaint') }}</option>
                    <option value="other">{{ t($translationGroup . '.other') }}</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">{{ t($translationGroup . '.subject') }} *</label>
                <input type="text" name="subject" required 
                       class="w-full border-gray-300 rounded-lg focus:ring-2 focus:border-transparent px-4 py-2.5"
                       style="focus:ring-color: {{ $primaryColor }};">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">{{ t($translationGroup . '.description') }} *</label>
                <textarea name="description" rows="4" required 
                          class="w-full border-gray-300 rounded-lg focus:ring-2 focus:border-transparent px-4 py-2.5"
                          style="focus:ring-color: {{ $primaryColor }};"
                          placeholder="{{ t($translationGroup . '.description_placeholder') }}"></textarea>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">{{ t($translationGroup . '.attachment') }}</label>
                <input type="file" name="attachment" accept=".jpeg,.png,.jpg,.pdf,.doc,.docx"
                       class="w-full border-gray-300 rounded-lg focus:ring-2 focus:border-transparent px-4 py-2.5"
                       style="focus:ring-color: {{ $primaryColor }};">
                <p class="text-xs text-gray-500 mt-1">{{ t($translationGroup . '.attachment_note') }}</p>
            </div>
            
            <div class="flex gap-4 pt-4">
                <button type="submit" class="flex-1 py-3 text-white rounded-lg font-medium hover:opacity-90 transition-opacity"
                        style="background: {{ $primaryColor }};">
                    {{ t($translationGroup . '.submit_complaint') }}
                </button>
                <button type="button" onclick="closeModal('complaintModal')" 
                        class="flex-1 py-3 bg-gray-500 text-white rounded-lg font-medium hover:opacity-90 transition-opacity">
                    {{ t($translationGroup . '.cancel') }}
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openComplaintModal() {
    document.getElementById('complaintModal').classList.remove('hidden');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

// Close modal on outside click
document.getElementById('complaintModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal('complaintModal');
});
</script>

@endsection
