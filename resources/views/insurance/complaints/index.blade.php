{{-- resources/views/insurance/complaints/index.blade.php --}}
@extends('insurance.layouts.app')

@section('title', t($company->translation_group . '.complaints_inquiries'))

@section('content')
<div class="space-y-6">
    <!-- Header with Stats -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ t($company->translation_group . '.complaints_inquiries') }}</h1>
            <p class="text-gray-600 mt-1">{{ t($company->translation_group . '.manage_complaints_inquiries') }}</p>
        </div>
        
        <button onclick="openComplaintModal()" 
                class="px-6 py-3 text-white rounded-lg font-medium hover:opacity-90 transition-opacity"
                style="background: {{ $company->primary_color }};">
            {{ t($company->translation_group . '.add_new_complaint') }}
        </button>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-2 lg:grid-cols-6 gap-3">
        <div class="bg-white rounded-lg border px-4 py-3 text-center shadow-sm">
            <div class="text-2xl font-bold text-blue-600">{{ $stats['total'] }}</div>
            <div class="text-xs text-gray-600">{{ t($company->translation_group . '.total') }}</div>
        </div>
        <div class="bg-white rounded-lg border px-4 py-3 text-center shadow-sm">
            <div class="text-2xl font-bold text-red-600">{{ $stats['unread'] }}</div>
            <div class="text-xs text-gray-600">{{ t($company->translation_group . '.unread') }}</div>
        </div>
        <div class="bg-white rounded-lg border px-4 py-3 text-center shadow-sm">
            <div class="text-2xl font-bold text-green-600">{{ $stats['read'] }}</div>
            <div class="text-xs text-gray-600">{{ t($company->translation_group . '.read') }}</div>
        </div>
        <div class="bg-white rounded-lg border px-4 py-3 text-center shadow-sm">
            <div class="text-2xl font-bold text-blue-600">{{ $stats['inquiry'] }}</div>
            <div class="text-xs text-gray-600">{{ t($company->translation_group . '.inquiries') }}</div>
        </div>
        <div class="bg-white rounded-lg border px-4 py-3 text-center shadow-sm">
            <div class="text-2xl font-bold text-yellow-600">{{ $stats['complaint'] }}</div>
            <div class="text-xs text-gray-600">{{ t($company->translation_group . '.complaints') }}</div>
        </div>
        <div class="bg-white rounded-lg border px-4 py-3 text-center shadow-sm">
            <div class="text-2xl font-bold text-gray-600">{{ $stats['other'] }}</div>
            <div class="text-xs text-gray-600">{{ t($company->translation_group . '.other') }}</div>
        </div>
    </div>

    <!-- Filters Card -->
    <div class="bg-white rounded-xl shadow-sm border">
        <div class="p-6">
            <form method="GET" class="flex flex-col lg:flex-row gap-4">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ t($company->translation_group . '.search') }}</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="{{ t($company->translation_group . '.search_placeholder') }}"
                           class="w-full border-gray-300 rounded-lg focus:ring-2 focus:border-transparent px-4 py-2.5"
                           style="focus:ring-color: {{ $company->primary_color }};">
                </div>
                
                <div class="lg:w-48">
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ t($company->translation_group . '.request_type') }}</label>
                    <select name="type" class="w-full border-gray-300 rounded-lg focus:ring-2 focus:border-transparent px-4 py-2.5"
                            style="focus:ring-color: {{ $company->primary_color }};">
                        <option value="">{{ t($company->translation_group . '.all_types') }}</option>
                        <option value="inquiry" {{ request('type') === 'inquiry' ? 'selected' : '' }}>{{ t($company->translation_group . '.inquiry') }}</option>
                        <option value="complaint" {{ request('type') === 'complaint' ? 'selected' : '' }}>{{ t($company->translation_group . '.complaint') }}</option>
                        <option value="other" {{ request('type') === 'other' ? 'selected' : '' }}>{{ t($company->translation_group . '.other') }}</option>
                    </select>
                </div>
                
                <div class="lg:w-48">
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ t($company->translation_group . '.status') }}</label>
                    <select name="status" class="w-full border-gray-300 rounded-lg focus:ring-2 focus:border-transparent px-4 py-2.5"
                            style="focus:ring-color: {{ $company->primary_color }};">
                        <option value="">{{ t($company->translation_group . '.all_status') }}</option>
                        <option value="unread" {{ request('status') === 'unread' ? 'selected' : '' }}>{{ t($company->translation_group . '.unread') }}</option>
                        <option value="read" {{ request('status') === 'read' ? 'selected' : '' }}>{{ t($company->translation_group . '.read') }}</option>
                    </select>
                </div>
                
                <div class="flex gap-2 lg:items-end">
                    <button type="submit" class="px-6 py-2.5 text-white rounded-lg font-medium hover:opacity-90 transition-opacity"
                            style="background: {{ $company->primary_color }};">
                        {{ t($company->translation_group . '.filter') }}
                    </button>
                    
                    @if(request()->hasAny(['status', 'type', 'search']))
                        <a href="{{ route('insurance.complaints.index', $company->company_slug) }}" 
                           class="px-6 py-2.5 bg-gray-500 text-white rounded-lg font-medium hover:opacity-90 transition-opacity">
                            {{ t($company->translation_group . '.clear') }}
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Complaints List -->
    @if($complaints->count())
        <div class="space-y-4">
            @foreach($complaints as $complaint)
            <div class="bg-white rounded-xl shadow-sm border hover:shadow-md transition-shadow">
                <div class="p-6">
                    <!-- Header -->
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-lg flex items-center justify-center text-white font-bold"
                                 style="background: {{ $company->primary_color }};">
                                {{ substr($complaint->id, -2) }}
                            </div>
                            <div>
                                <h3 class="font-bold text-lg">{{ $complaint->subject }}</h3>
                                <p class="text-gray-600 text-sm">{{ $complaint->created_at->format('M d, Y H:i') }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-3">
                            <span class="px-3 py-1.5 rounded-full text-sm font-medium {{ $complaint->type_badge['class'] }}">
                                {{ t($company->translation_group . '.' . $complaint->type) }}
                            </span>
                            <span class="px-3 py-1.5 rounded-full text-sm font-medium {{ $complaint->status_badge['class'] }}">
                                {{ $complaint->is_read ? t($company->translation_group . '.read') : t($company->translation_group . '.unread') }}
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
                        <a href="{{ route('insurance.complaints.show', [$company->company_slug, $complaint->id]) }}" 
                           class="inline-flex items-center gap-2 px-4 py-2 rounded-lg font-medium transition-colors"
                           style="background: {{ $company->primary_color }}20; color: {{ $company->primary_color }};">
                            {{ t($company->translation_group . '.read_more') }}
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
            {{ $complaints->withQueryString()->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-xl shadow-sm border">
            <div class="p-12 text-center">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full flex items-center justify-center"
                     style="background: {{ $company->primary_color }}20;">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: {{ $company->primary_color }};">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">{{ t($company->translation_group . '.no_complaints_found') }}</h3>
                <p class="text-gray-600">{{ t($company->translation_group . '.no_complaints_description') }}</p>
            </div>
        </div>
    @endif
</div>

<!-- Add Complaint Modal -->
<div id="complaintModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b">
            <h3 class="text-xl font-bold">{{ t($company->translation_group . '.add_new_complaint') }}</h3>
        </div>
        
        <form id="complaintForm" method="POST" action="{{ route('insurance.complaints.store', $company->company_slug) }}" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">{{ t($company->translation_group . '.type') }} *</label>
                <select name="type" required 
                        class="w-full border-gray-300 rounded-lg focus:ring-2 focus:border-transparent px-4 py-2.5"
                        style="focus:ring-color: {{ $company->primary_color }};">
                    <option value="">{{ t($company->translation_group . '.select_type') }}</option>
                    <option value="inquiry">{{ t($company->translation_group . '.inquiry') }}</option>
                    <option value="complaint">{{ t($company->translation_group . '.complaint') }}</option>
                    <option value="other">{{ t($company->translation_group . '.other') }}</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">{{ t($company->translation_group . '.subject') }} *</label>
                <input type="text" name="subject" required 
                       class="w-full border-gray-300 rounded-lg focus:ring-2 focus:border-transparent px-4 py-2.5"
                       style="focus:ring-color: {{ $company->primary_color }};">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">{{ t($company->translation_group . '.description') }} *</label>
                <textarea name="description" rows="4" required 
                          class="w-full border-gray-300 rounded-lg focus:ring-2 focus:border-transparent px-4 py-2.5"
                          style="focus:ring-color: {{ $company->primary_color }};"
                          placeholder="{{ t($company->translation_group . '.description_placeholder') }}"></textarea>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">{{ t($company->translation_group . '.attachment') }}</label>
                <input type="file" name="attachment" accept=".jpeg,.png,.jpg,.pdf,.doc,.docx"
                       class="w-full border-gray-300 rounded-lg focus:ring-2 focus:border-transparent px-4 py-2.5"
                       style="focus:ring-color: {{ $company->primary_color }};">
                <p class="text-xs text-gray-500 mt-1">{{ t($company->translation_group . '.attachment_note') }}</p>
            </div>
            
            <div class="flex gap-4 pt-4">
                <button type="submit" class="flex-1 py-3 text-white rounded-lg font-medium hover:opacity-90 transition-opacity"
                        style="background: {{ $company->primary_color }};">
                    {{ t($company->translation_group . '.submit_complaint') }}
                </button>
                <button type="button" onclick="closeModal('complaintModal')" 
                        class="flex-1 py-3 bg-gray-500 text-white rounded-lg font-medium hover:opacity-90 transition-opacity">
                    {{ t($company->translation_group . '.cancel') }}
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
