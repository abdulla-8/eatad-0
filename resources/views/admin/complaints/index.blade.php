{{-- resources/views/admin/complaints/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', t('admin.complaints_inquiries'))

@section('content')
<div class="space-y-6">
    <!-- Header with Stats -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ t('admin.complaints_inquiries') }}</h1>
            <p class="text-gray-600 mt-1">{{ t('admin.manage_all_complaints_inquiries') }}</p>
        </div>
        
        <!-- Quick Stats -->
        <div class="grid grid-cols-2 lg:grid-cols-9 gap-3">
            <div class="bg-white rounded-lg border px-4 py-3 text-center shadow-sm">
                <div class="text-2xl font-bold text-blue-600">{{ $stats['total'] }}</div>
                <div class="text-xs text-gray-600">{{ t('admin.total') }}</div>
            </div>
            <div class="bg-white rounded-lg border px-4 py-3 text-center shadow-sm">
                <div class="text-2xl font-bold text-red-600">{{ $stats['unread'] }}</div>
                <div class="text-xs text-gray-600">{{ t('admin.unread') }}</div>
            </div>
            <div class="bg-white rounded-lg border px-4 py-3 text-center shadow-sm">
                <div class="text-2xl font-bold text-green-600">{{ $stats['read'] }}</div>
                <div class="text-xs text-gray-600">{{ t('admin.read') }}</div>
            </div>
            <div class="bg-white rounded-lg border px-4 py-3 text-center shadow-sm">
                <div class="text-2xl font-bold text-blue-600">{{ $stats['inquiry'] }}</div>
                <div class="text-xs text-gray-600">{{ t('admin.inquiries') }}</div>
            </div>
            <div class="bg-white rounded-lg border px-4 py-3 text-center shadow-sm">
                <div class="text-2xl font-bold text-yellow-600">{{ $stats['complaint'] }}</div>
                <div class="text-xs text-gray-600">{{ t('admin.complaints') }}</div>
            </div>
            <div class="bg-white rounded-lg border px-4 py-3 text-center shadow-sm">
                <div class="text-2xl font-bold text-gray-600">{{ $stats['other'] }}</div>
                <div class="text-xs text-gray-600">{{ t('admin.other') }}</div>
            </div>
            <div class="bg-white rounded-lg border px-4 py-3 text-center shadow-sm">
                <div class="text-2xl font-bold text-purple-600">{{ $stats['insurance_companies'] }}</div>
                <div class="text-xs text-gray-600">{{ t('admin.insurance_companies') }}</div>
            </div>
            <div class="bg-white rounded-lg border px-4 py-3 text-center shadow-sm">
                <div class="text-2xl font-bold text-green-600">{{ $stats['service_centers'] }}</div>
                <div class="text-xs text-gray-600">{{ t('admin.service_centers') }}</div>
            </div>
            <div class="bg-white rounded-lg border px-4 py-3 text-center shadow-sm">
                <div class="text-2xl font-bold text-indigo-600">{{ $stats['insurance_users'] }}</div>
                <div class="text-xs text-gray-600">{{ t('admin.insurance_users') }}</div>
            </div>
        </div>
    </div>

    <!-- Filters Card -->
    <div class="bg-white rounded-xl shadow-sm border">
        <div class="p-6">
            <form method="GET" class="flex flex-col lg:flex-row gap-4">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ t('admin.search') }}</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="{{ t('admin.search_placeholder') }}"
                           class="w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent px-4 py-2.5">
                </div>
                
                <div class="lg:w-48">
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ t('admin.complainant_type') }}</label>
                    <select name="complainant_type" class="w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent px-4 py-2.5">
                        <option value="">{{ t('admin.all_types') }}</option>
                        <option value="insurance_company" {{ request('complainant_type') === 'insurance_company' ? 'selected' : '' }}>{{ t('admin.insurance_companies') }}</option>
                        <option value="service_center" {{ request('complainant_type') === 'service_center' ? 'selected' : '' }}>{{ t('admin.service_centers') }}</option>
                        <option value="insurance_user" {{ request('complainant_type') === 'insurance_user' ? 'selected' : '' }}>{{ t('admin.insurance_users') }}</option>
                    </select>
                </div>
                
                <div class="lg:w-48">
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ t('admin.request_type') }}</label>
                    <select name="type" class="w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent px-4 py-2.5">
                        <option value="">{{ t('admin.all_types') }}</option>
                        <option value="inquiry" {{ request('type') === 'inquiry' ? 'selected' : '' }}>{{ t('admin.inquiry') }}</option>
                        <option value="complaint" {{ request('type') === 'complaint' ? 'selected' : '' }}>{{ t('admin.complaint') }}</option>
                        <option value="other" {{ request('type') === 'other' ? 'selected' : '' }}>{{ t('admin.other') }}</option>
                    </select>
                </div>
                
                <div class="lg:w-48">
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ t('admin.status') }}</label>
                    <select name="status" class="w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent px-4 py-2.5">
                        <option value="">{{ t('admin.all_status') }}</option>
                        <option value="unread" {{ request('status') === 'unread' ? 'selected' : '' }}>{{ t('admin.unread') }}</option>
                        <option value="read" {{ request('status') === 'read' ? 'selected' : '' }}>{{ t('admin.read') }}</option>
                    </select>
                </div>
                
                <div class="flex gap-2 lg:items-end">
                    <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors">
                        {{ t('admin.filter') }}
                    </button>
                    
                    @if(request()->hasAny(['status', 'type', 'complainant_type', 'search']))
                        <a href="{{ route('admin.complaints.index') }}" 
                           class="px-6 py-2.5 bg-gray-500 text-white rounded-lg font-medium hover:bg-gray-600 transition-colors">
                            {{ t('admin.clear') }}
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Bulk Actions Card -->
    @if($complaints->count())
        <div class="bg-white rounded-xl shadow-sm border">
            <div class="p-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" id="selectAll" class="w-5 h-5 text-blue-600 rounded border border-gray-300 focus:ring-blue-500">
                            <span class="text-sm font-medium text-gray-700">{{ t('admin.select_all') }}</span>
                        </label>
                        <span id="selectedCount" class="text-sm text-gray-600 px-3 py-1 bg-gray-100 rounded-full border">
                            {{ t('admin.selected_items') }}: <span id="countNumber">0</span>
                        </span>
                    </div>
                    
                    <div class="flex gap-2 flex-wrap">
                        <!-- Mark as Read -->
                        <button onclick="bulkMarkAsRead()" 
                                class="bulk-action-btn px-4 py-2 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2 border border-green-600"
                                disabled>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ t('admin.mark_as_read') }}
                        </button>
                        
                        <!-- Mark as Unread -->
                        <button onclick="bulkMarkAsUnread()" 
                                class="bulk-action-btn px-4 py-2 bg-orange-600 text-white rounded-lg font-medium hover:bg-orange-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2 border border-orange-600"
                                disabled>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            {{ t('admin.mark_as_unread') }}
                        </button>
                        
                        <!-- Export Selected -->
                        <button onclick="exportSelected()" 
                                class="bulk-action-btn px-4 py-2 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2 border border-indigo-600"
                                disabled>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            {{ t('admin.export_selected') }}
                        </button>
                        
                        <!-- Delete Selected -->
                        <button onclick="bulkDelete()" 
                                class="bulk-action-btn px-4 py-2 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2 border border-red-600"
                                disabled>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            {{ t('admin.delete_selected') }}
                        </button>
                        
                        <!-- Delete All -->
                        <button onclick="showDeleteAllModal()" 
                                class="px-4 py-2 bg-red-800 text-white rounded-lg font-medium hover:bg-red-900 transition-colors flex items-center gap-2 border border-red-800">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            {{ t('admin.delete_all') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Complaints List -->
    @if($complaints->count())
        <div class="space-y-4">
            @foreach($complaints as $complaint)
            <div class="bg-white rounded-xl shadow-sm border hover:shadow-md transition-shadow {{ !$complaint->is_read ? 'ring-2 ring-red-100' : '' }}">
                <div class="p-6">
                    <!-- Header -->
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <input type="checkbox" class="complaint-checkbox w-5 h-5 text-blue-600 rounded border border-gray-300 focus:ring-blue-500" 
                                   value="{{ $complaint->id }}" onchange="updateBulkActions()">
                            <div class="w-12 h-12 rounded-lg flex items-center justify-center text-white font-bold {{ !$complaint->is_read ? 'bg-red-500' : 'bg-blue-500' }}">
                                #{{ $complaint->id }}
                            </div>
                            <div>
                                <h3 class="font-bold text-lg {{ !$complaint->is_read ? 'text-red-900' : 'text-gray-900' }}">{{ $complaint->subject }}</h3>
                                <p class="text-gray-600 text-sm">{{ $complaint->complainant_name }}</p>
                                
                                <!-- عرض معلومات إضافية حسب نوع المستخدم -->
                                @if($complaint->complainant_type === 'insurance_user' && $complaint->complainant_details)
                                    <div class="mt-1 text-xs text-gray-500">
                                        <div class="flex items-center gap-2">
                                            <span class="font-medium">{{ t('admin.insurance_company') }}:</span>
                                            <span class="text-blue-600 font-semibold">{{ optional($complaint->complainant_details->company)->legal_name ?? 'غير محدد' }}</span>
                                        </div>
                                        <div class="flex items-center gap-4 mt-1">
                                            <span>{{ t('admin.policy_number') }}: {{ $complaint->complainant_details->policy_number }}</span>
                                            <span>{{ t('admin.phone') }}: {{ $complaint->complainant_details->phone }}</span>
                                        </div>
                                    </div>
                                @endif
                                
                                <p class="text-gray-500 text-xs mt-1">{{ $complaint->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-3">
                            <span class="px-3 py-1.5 rounded-full text-sm font-medium border {{ $complaint->complainant_type_badge['class'] }}">
                                {{ $complaint->complainant_type_badge['text'] }}
                            </span>
                            <span class="px-3 py-1.5 rounded-full text-sm font-medium border {{ $complaint->type_badge['class'] }}">
                                {{ t('admin.' . $complaint->type) }}
                            </span>
                            <span class="px-3 py-1.5 rounded-full text-sm font-medium border {{ $complaint->is_read ? 'bg-green-100 text-green-800 border-green-300' : 'bg-red-100 text-red-800 border-red-300' }}">
                                {{ $complaint->is_read ? t('admin.read') : t('admin.unread') }}
                            </span>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="mb-4">
                        <p class="text-gray-600 text-sm">
                            {{ Str::limit($complaint->description, 150) }}
                        </p>
                        
                        <!-- Show attachment info if exists -->
                        @if($complaint->attachment_path)
                            <div class="mt-3 flex items-center gap-2 text-sm text-gray-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                </svg>
                                <span>{{ t('admin.has_attachment') }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-between items-center">
                        <div class="flex gap-2">
                            @if(!$complaint->is_read)
                                <button onclick="markAsRead({{ $complaint->id }})"
                                        class="px-3 py-1.5 bg-green-500 text-white rounded-lg text-sm font-medium hover:bg-green-600 transition-colors border border-green-500">
                                    {{ t('admin.mark_as_read') }}
                                </button>
                            @else
                                <button onclick="markAsUnread({{ $complaint->id }})"
                                        class="px-3 py-1.5 bg-orange-500 text-white rounded-lg text-sm font-medium hover:bg-orange-600 transition-colors border border-orange-500">
                                    {{ t('admin.mark_as_unread') }}
                                </button>
                            @endif
                            
                            <button onclick="deleteComplaint({{ $complaint->id }})"
                                    class="px-3 py-1.5 bg-red-500 text-white rounded-lg text-sm font-medium hover:bg-red-600 transition-colors border border-red-500">
                                {{ t('admin.delete') }}
                            </button>
                        </div>
                        
                        <a href="{{ route('admin.complaints.show', $complaint->id) }}" 
                           class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-600 rounded-lg font-medium hover:bg-blue-100 transition-colors border border-blue-200">
                            {{ t('admin.read_more') }}
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
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">{{ t('admin.no_complaints_found') }}</h3>
                <p class="text-gray-600">{{ t('admin.no_complaints_description') }}</p>
            </div>
        </div>
    @endif
</div>

<!-- Delete All Modal -->
<div id="deleteAllModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl max-w-md w-full shadow-2xl">
        <div class="p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900">{{ t('admin.delete_all_complaints') }}</h3>
            </div>
            
            <p class="text-gray-600 mb-4">{{ t('admin.delete_all_confirmation') }}</p>
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">{{ t('admin.confirm_password') }}</label>
                <input type="password" id="confirmPassword" 
                       class="w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent px-4 py-2.5"
                       placeholder="{{ t('admin.enter_password') }}">
            </div>
            
            <div class="flex gap-3">
                <button onclick="confirmDeleteAll()" 
                        class="flex-1 py-2.5 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 transition-colors border border-red-600">
                    {{ t('admin.confirm_delete') }}
                </button>
                <button onclick="hideDeleteAllModal()" 
                        class="flex-1 py-2.5 bg-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-400 transition-colors border border-gray-300">
                    {{ t('admin.cancel') }}
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Global variables
let selectedIds = [];

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    updateBulkActions();
    
    // Select all checkbox
    document.getElementById('selectAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.complaint-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateBulkActions();
    });
});

// Update bulk actions state
function updateBulkActions() {
    const checkboxes = document.querySelectorAll('.complaint-checkbox:checked');
    const selectAllCheckbox = document.getElementById('selectAll');
    const bulkActionButtons = document.querySelectorAll('.bulk-action-btn');
    const countNumber = document.getElementById('countNumber');
    
    selectedIds = Array.from(checkboxes).map(cb => cb.value);
    countNumber.textContent = selectedIds.length;
    
    // Enable/disable bulk action buttons
    bulkActionButtons.forEach(button => {
        button.disabled = selectedIds.length === 0;
    });
    
    // Update select all state
    const allCheckboxes = document.querySelectorAll('.complaint-checkbox');
    selectAllCheckbox.checked = selectedIds.length === allCheckboxes.length;
    selectAllCheckbox.indeterminate = selectedIds.length > 0 && selectedIds.length < allCheckboxes.length;
}

// Individual actions
function markAsRead(id) {
    fetch(`{{ route('admin.complaints.mark-as-read', '__ID__') }}`.replace('__ID__', id), {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            setTimeout(() => window.location.reload(), 1000);
        } else {
            showNotification('{{ t('admin.error_updating_status') }}', 'error');
        }
    })
    .catch(error => {
        showNotification('{{ t('admin.error_connecting_server') }}', 'error');
    });
}

function markAsUnread(id) {
    fetch(`{{ route('admin.complaints.mark-as-unread', '__ID__') }}`.replace('__ID__', id), {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            setTimeout(() => window.location.reload(), 1000);
        } else {
            showNotification('{{ t('admin.error_updating_status') }}', 'error');
        }
    })
    .catch(error => {
        showNotification('{{ t('admin.error_connecting_server') }}', 'error');
    });
}

function deleteComplaint(id) {
    if (confirm('{{ t('admin.confirm_delete_single') }}')) {
        fetch(`{{ route('admin.complaints.destroy', '__ID__') }}`.replace('__ID__', id), {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
                setTimeout(() => window.location.reload(), 1000);
            } else {
                showNotification('{{ t('admin.error_deleting_complaint') }}', 'error');
            }
        })
        .catch(error => {
            showNotification('{{ t('admin.error_connecting_server') }}', 'error');
        });
    }
}

// Bulk actions
function bulkMarkAsRead() {
    if (selectedIds.length === 0) return;
    
    fetch('{{ route('admin.complaints.bulk-mark-read') }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ complaint_ids: selectedIds })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            setTimeout(() => window.location.reload(), 1000);
        } else {
            showNotification('{{ t('admin.error_updating_status') }}', 'error');
        }
    })
    .catch(error => {
        showNotification('{{ t('admin.error_connecting_server') }}', 'error');
    });
}

function bulkMarkAsUnread() {
    if (selectedIds.length === 0) return;
    
    fetch('{{ route('admin.complaints.bulk-mark-unread') }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ complaint_ids: selectedIds })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            setTimeout(() => window.location.reload(), 1000);
        } else {
            showNotification('{{ t('admin.error_updating_status') }}', 'error');
        }
    })
    .catch(error => {
        showNotification('{{ t('admin.error_connecting_server') }}', 'error');
    });
}

function bulkDelete() {
    if (selectedIds.length === 0) return;
    
    if (confirm(`{{ t('admin.confirm_delete_selected') }} (${selectedIds.length} {{ t('admin.items') }})`)) {
        fetch('{{ route('admin.complaints.bulk-delete') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ complaint_ids: selectedIds })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
                setTimeout(() => window.location.reload(), 1000);
            } else {
                showNotification('{{ t('admin.error_deleting_complaints') }}', 'error');
            }
        })
        .catch(error => {
            showNotification('{{ t('admin.error_connecting_server') }}', 'error');
        });
    }
}

function exportSelected() {
    if (selectedIds.length === 0) return;
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route('admin.complaints.export-selected') }}';
    
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    form.appendChild(csrfToken);
    
    selectedIds.forEach(id => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'complaint_ids[]';
        input.value = id;
        form.appendChild(input);
    });
    
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
}

// Delete All Modal
function showDeleteAllModal() {
    document.getElementById('deleteAllModal').classList.remove('hidden');
}

function hideDeleteAllModal() {
    document.getElementById('deleteAllModal').classList.add('hidden');
    document.getElementById('confirmPassword').value = '';
}

function confirmDeleteAll() {
    const password = document.getElementById('confirmPassword').value;
    
    if (!password) {
        showNotification('{{ t('admin.password_required') }}', 'error');
        return;
    }
    
    fetch('{{ route('admin.complaints.delete-all') }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ confirmation_password: password })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            hideDeleteAllModal();
            setTimeout(() => window.location.reload(), 1000);
        } else {
            showNotification(data.message || '{{ t('admin.error_deleting_all') }}', 'error');
        }
    })
    .catch(error => {
        showNotification('{{ t('admin.error_connecting_server') }}', 'error');
    });
}

// Notification system
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-sm border ${
        type === 'success' ? 'bg-green-500 text-white border-green-600' :
        type === 'error' ? 'bg-red-500 text-white border-red-600' :
        'bg-blue-500 text-white border-blue-600'
    }`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Close modal when clicking outside
document.getElementById('deleteAllModal').addEventListener('click', function(e) {
    if (e.target === this) {
        hideDeleteAllModal();
    }
});
</script>
@endsection
