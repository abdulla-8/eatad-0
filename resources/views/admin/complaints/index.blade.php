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
        <div class="grid grid-cols-2 lg:grid-cols-8 gap-3">
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
                           class="w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent px-4 py-2.5">
                </div>
                
                <div class="lg:w-48">
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ t('admin.complainant_type') }}</label>
                    <select name="complainant_type" class="w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent px-4 py-2.5">
                        <option value="">{{ t('admin.all_types') }}</option>
                        <option value="insurance_company" {{ request('complainant_type') === 'insurance_company' ? 'selected' : '' }}>{{ t('admin.insurance_companies') }}</option>
                        <option value="service_center" {{ request('complainant_type') === 'service_center' ? 'selected' : '' }}>{{ t('admin.service_centers') }}</option>
                    </select>
                </div>
                
                <div class="lg:w-48">
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ t('admin.request_type') }}</label>
                    <select name="type" class="w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent px-4 py-2.5">
                        <option value="">{{ t('admin.all_types') }}</option>
                        <option value="inquiry" {{ request('type') === 'inquiry' ? 'selected' : '' }}>{{ t('admin.inquiry') }}</option>
                        <option value="complaint" {{ request('type') === 'complaint' ? 'selected' : '' }}>{{ t('admin.complaint') }}</option>
                        <option value="other" {{ request('type') === 'other' ? 'selected' : '' }}>{{ t('admin.other') }}</option>
                    </select>
                </div>
                
                <div class="lg:w-48">
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ t('admin.status') }}</label>
                    <select name="status" class="w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent px-4 py-2.5">
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

    <!-- Complaints List -->
    @if($complaints->count())
        <div class="space-y-4">
            @foreach($complaints as $complaint)
            <div class="bg-white rounded-xl shadow-sm border hover:shadow-md transition-shadow {{ !$complaint->is_read ? 'ring-2 ring-red-100' : '' }}">
                <div class="p-6">
                    <!-- Header -->
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-lg flex items-center justify-center text-white font-bold {{ !$complaint->is_read ? 'bg-red-500' : 'bg-blue-500' }}">
                                {{ str_pad($complaint->id, 2, '0', STR_PAD_LEFT) }}
                            </div>
                            <div>
                                <h3 class="font-bold text-lg {{ !$complaint->is_read ? 'text-red-900' : 'text-gray-900' }}">{{ $complaint->subject }}</h3>
                                <p class="text-gray-600 text-sm">{{ $complaint->complainant_name }}</p>
                                <p class="text-gray-500 text-xs">{{ $complaint->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-3">
                            <span class="px-3 py-1.5 rounded-full text-sm font-medium {{ $complaint->complainant_type_badge['class'] }}">
                                {{ $complaint->complainant_type_badge['text'] }}
                            </span>
                            <span class="px-3 py-1.5 rounded-full text-sm font-medium {{ $complaint->type_badge['class'] }}">
                                {{ t('admin.' . $complaint->type) }}
                            </span>
                            <span class="px-3 py-1.5 rounded-full text-sm font-medium {{ $complaint->is_read ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $complaint->is_read ? t('admin.read') : t('admin.unread') }}
                            </span>
                            
                            <div class="flex gap-2">
                                @if(!$complaint->is_read)
                                    <button onclick="markAsRead({{ $complaint->id }})"
                                            class="px-3 py-1.5 bg-green-500 text-white rounded-lg text-sm font-medium hover:bg-green-600 transition-colors">
                                        {{ t('admin.mark_as_read') }}
                                    </button>
                                @else
                                    <button onclick="markAsUnread({{ $complaint->id }})"
                                            class="px-3 py-1.5 bg-orange-500 text-white rounded-lg text-sm font-medium hover:bg-orange-600 transition-colors">
                                        {{ t('admin.mark_as_unread') }}
                                    </button>
                                @endif
                            </div>
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
                        <a href="{{ route('admin.complaints.show', $complaint->id) }}" 
                           class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-600 rounded-lg font-medium hover:bg-blue-100 transition-colors">
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

<script>
function markAsRead(id) {
    fetch(`{{ route('admin.complaints.mark-read', '__ID__') }}`.replace('__ID__', id), {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        } else {
            alert('{{ t('admin.error_updating_status') }}');
        }
    })
    .catch(error => {
        alert('{{ t('admin.error_connecting_server') }}');
    });
}

function markAsUnread(id) {
    fetch(`{{ route('admin.complaints.mark-unread', '__ID__') }}`.replace('__ID__', id), {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        } else {
            alert('{{ t('admin.error_updating_status') }}');
        }
    })
    .catch(error => {
        alert('{{ t('admin.error_connecting_server') }}');
    });
}
</script>
@endsection
