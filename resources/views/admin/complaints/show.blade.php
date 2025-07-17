{{-- resources/views/admin/complaints/show.blade.php --}}
@extends('admin.layouts.app')

@section('title', t('admin.complaint_details'))

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.complaints.index') }}" 
           class="p-2 rounded-lg hover:bg-gray-100 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <div class="flex-1">
            <h1 class="text-3xl font-bold text-gray-900">{{ t('admin.complaint_details') }}</h1>
            <p class="text-gray-600 mt-1">{{ t('admin.view_complaint_inquiry_details') }}</p>
        </div>
        
        <!-- Status Action Button -->
        <div class="flex gap-3">
            @if(!$complaint->is_read)
                <button onclick="markAsRead({{ $complaint->id }})"
                        class="px-6 py-3 bg-green-500 text-white rounded-lg font-medium hover:bg-green-600 transition-colors">
                    {{ t('admin.mark_as_read') }}
                </button>
            @else
                <button onclick="markAsUnread({{ $complaint->id }})"
                        class="px-6 py-3 bg-orange-500 text-white rounded-lg font-medium hover:bg-orange-600 transition-colors">
                    {{ t('admin.mark_as_unread') }}
                </button>
            @endif
        </div>
    </div>

    <!-- Complaint Details -->
    <div class="bg-white rounded-xl shadow-sm border {{ !$complaint->is_read ? 'ring-2 ring-red-100' : '' }}">
        <div class="p-6">
            <!-- Status and Type -->
            <div class="flex items-center gap-3 mb-6">
                <span class="px-4 py-2 rounded-full text-sm font-medium {{ $complaint->type_badge['class'] ?? 'bg-gray-100 text-gray-800' }}">
                    {{ t('admin.' . $complaint->type) }}
                </span>
                <span class="px-4 py-2 rounded-full text-sm font-medium {{ $complaint->complainant_type_badge['class'] ?? 'bg-blue-100 text-blue-800' }}">
                    {{ $complaint->complainant_type_badge['text'] ?? t('admin.' . $complaint->complainant_type) }}
                </span>
                <span class="px-4 py-2 rounded-full text-sm font-medium {{ $complaint->is_read ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $complaint->is_read ? t('admin.read') : t('admin.unread') }}
                </span>
                @if(!$complaint->is_read)
                    <span class="px-3 py-1 bg-red-500 text-white text-xs rounded-full animate-pulse">
                        {{ t('admin.new') }}
                    </span>
                @endif
            </div>

            <!-- Complaint ID -->
            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-2">{{ t('admin.complaint_id') }}</h3>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-gray-700 font-mono">#{{ $complaint->id }}</p>
                </div>
            </div>

            <!-- Complainant Information -->
            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-2">{{ t('admin.complainant_information') }}</h3>
                <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                    <div class="flex items-center gap-2">
                        <span class="font-medium text-gray-700">{{ t('admin.name') }}:</span>
                        <span class="text-gray-900">{{ $complaint->complainant_name }}</span>
                    </div>
                    
                    <div class="flex items-center gap-2">
                        <span class="font-medium text-gray-700">{{ t('admin.type') }}:</span>
                        <span class="px-2 py-1 rounded text-xs {{ $complaint->complainant_type_badge['class'] ?? 'bg-blue-100 text-blue-800' }}">
                            {{ $complaint->complainant_type_badge['text'] ?? t('admin.' . $complaint->complainant_type) }}
                        </span>
                    </div>

                    <!-- معلومات إضافية لمستخدمي شركة التأمين -->
                    @if($complaint->complainant_type === 'insurance_user' && $complaint->complainant_details)
                        <div class="border-t pt-3 mt-3">
                            <h4 class="font-medium text-gray-900 mb-2">{{ t('admin.additional_details') }}</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                                @if($complaint->complainant_details->company)
                                    <div class="flex items-center gap-2">
                                        <span class="font-medium text-gray-700">{{ t('admin.insurance_company') }}:</span>
                                        <span class="text-blue-600 font-semibold">{{ $complaint->complainant_details->company->legal_name }}</span>
                                    </div>
                                @endif
                                
                                @if($complaint->complainant_details->policy_number)
                                    <div class="flex items-center gap-2">
                                        <span class="font-medium text-gray-700">{{ t('admin.policy_number') }}:</span>
                                        <span class="text-gray-900">{{ $complaint->complainant_details->policy_number }}</span>
                                    </div>
                                @endif
                                
                                @if($complaint->complainant_details->phone)
                                    <div class="flex items-center gap-2">
                                        <span class="font-medium text-gray-700">{{ t('admin.phone') }}:</span>
                                        <span class="text-gray-900">{{ $complaint->complainant_details->phone }}</span>
                                    </div>
                                @endif
                                
                                @if($complaint->complainant_details->email)
                                    <div class="flex items-center gap-2">
                                        <span class="font-medium text-gray-700">{{ t('admin.email') }}:</span>
                                        <span class="text-gray-900">{{ $complaint->complainant_details->email }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- معلومات إضافية لمراكز الصيانة مع الشركة التابعة -->
                    @if($complaint->complainant_type === 'service_center' && $complaint->complainant_details)
                        <div class="border-t pt-3 mt-3">
                            <h4 class="font-medium text-gray-900 mb-2">{{ t('admin.service_center_details') }}</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                                @if($complaint->complainant_details->phone)
                                    <div class="flex items-center gap-2">
                                        <span class="font-medium text-gray-700">{{ t('admin.phone') }}:</span>
                                        <span class="text-gray-900">{{ $complaint->complainant_details->phone }}</span>
                                    </div>
                                @endif
                                
                                @if($complaint->complainant_details->commercial_register)
                                    <div class="flex items-center gap-2">
                                        <span class="font-medium text-gray-700">{{ t('admin.commercial_register') }}:</span>
                                        <span class="text-gray-900">{{ $complaint->complainant_details->commercial_register }}</span>
                                    </div>
                                @endif
                                
                                @if($complaint->complainant_details->industrialArea)
                                    <div class="flex items-center gap-2">
                                        <span class="font-medium text-gray-700">{{ t('admin.industrial_area') }}:</span>
                                        <span class="text-gray-900">{{ $complaint->complainant_details->industrialArea->name }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- عرض الشركة التابع لها في قسم منفصل ومميز -->
                        @if($complaint->complainant_details->created_by_company && $complaint->complainant_details->insuranceCompany)
                            <div class="mt-4 p-4 bg-gradient-to-r from-emerald-50 to-teal-50 border-l-4 border-emerald-400 rounded-lg">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-emerald-800">{{ t('admin.managed_by_insurance_company') }}</p>
                                        <p class="text-emerald-700 text-sm">{{ t('admin.this_service_center_is_managed_by') }}</p>
                                    </div>
                                </div>
                                <div class="mt-3 flex items-center gap-2">
                                    <span class="px-4 py-2 bg-emerald-100 text-emerald-800 rounded-full font-semibold text-sm">
                                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $complaint->complainant_details->insuranceCompany->legal_name }}
                                    </span>
                                    <span class="text-xs text-emerald-600 bg-emerald-50 px-2 py-1 rounded border border-emerald-200">
                                        {{ t('admin.company_managed_center') }}
                                    </span>
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
            </div>

            <!-- Subject -->
            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-2">{{ t('admin.subject') }}</h3>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-gray-700 {{ !$complaint->is_read ? 'font-semibold' : '' }}">{{ $complaint->subject }}</p>
                </div>
            </div>

            <!-- Description -->
            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-2">{{ t('admin.details') }}</h3>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-gray-700 whitespace-pre-wrap leading-relaxed">{{ $complaint->description }}</p>
                </div>
            </div>

            <!-- Attachment -->
            @if($complaint->attachment_path)
            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-2">{{ t('admin.attachment') }}</h3>
                <div class="bg-gray-50 rounded-lg p-4">
                    <a href="{{ Storage::url($complaint->attachment_path) }}" 
                       target="_blank"
                       class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-600 rounded-lg font-medium hover:bg-blue-100 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                        </svg>
                        {{ t('admin.view_attachment') }}
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                        </svg>
                    </a>
                    <p class="text-xs text-gray-500 mt-2">
                        {{ t('admin.click_to_view_attachment') }}
                    </p>
                </div>
            </div>
            @endif

            <!-- Timeline -->
            <div class="border-t pt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ t('admin.timeline') }}</h3>
                <div class="space-y-4">
                    <!-- Submission -->
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-2">
                                <span class="font-medium text-gray-900">{{ t('admin.complaint_submitted') }}</span>
                                <span class="text-sm text-gray-500">{{ $complaint->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <p class="text-sm text-gray-600 mt-1">
                                {{ t('admin.submitted_by') }} {{ $complaint->complainant_name }}
                                
                                {{-- عرض معلومات الشركة التابعة لمستخدم التأمين --}}
                                @if($complaint->complainant_type === 'insurance_user' && $complaint->complainant_details && $complaint->complainant_details->company)
                                    <span class="text-blue-600 font-medium">
                                        ({{ $complaint->complainant_details->company->legal_name }})
                                    </span>
                                @endif
                                
                                {{-- عرض معلومات الشركة التابعة لمركز الصيانة --}}
                                @if($complaint->complainant_type === 'service_center' && $complaint->complainant_details && $complaint->complainant_details->created_by_company && $complaint->complainant_details->insuranceCompany)
                                    <div class="mt-2 inline-flex items-center gap-1 px-3 py-1 bg-emerald-100 text-emerald-800 rounded-full text-xs font-medium">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                        تابع لشركة {{ $complaint->complainant_details->insuranceCompany->legal_name }}
                                    </div>
                                @endif
                            </p>
                        </div>
                    </div>

                    <!-- Current Status -->
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 {{ $complaint->is_read ? 'bg-green-100' : 'bg-red-100' }} rounded-full flex items-center justify-center flex-shrink-0">
                            @if($complaint->is_read)
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            @else
                                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            @endif
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-2">
                                <span class="font-medium text-gray-900">{{ t('admin.current_status') }}</span>
                                <span class="px-2 py-1 rounded text-xs {{ $complaint->is_read ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $complaint->is_read ? t('admin.read') : t('admin.unread') }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mt-1">
                                @if($complaint->is_read)
                                    {{ t('admin.complaint_has_been_reviewed') }}
                                @else
                                    {{ t('admin.complaint_awaiting_review') }}
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Metadata -->
            <div class="border-t pt-6 mt-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                    <div>
                        <span class="font-medium">{{ t('admin.submission_date') }}:</span>
                        {{ $complaint->created_at->format('d/m/Y H:i') }}
                    </div>
                    <div>
                        <span class="font-medium">{{ t('admin.last_update') }}:</span>
                        {{ $complaint->updated_at->format('d/m/Y H:i') }}
                    </div>
                    <div>
                        <span class="font-medium">{{ t('admin.complainant_type') }}:</span>
                        {{ $complaint->complainant_type_badge['text'] ?? t('admin.' . $complaint->complainant_type) }}
                    </div>
                    
                    {{-- معلومات الشركة للمستخدمين --}}
                    @if($complaint->complainant_type === 'insurance_user' && $complaint->complainant_details && $complaint->complainant_details->company)
                        <div>
                            <span class="font-medium">{{ t('admin.insurance_company') }}:</span>
                            <span class="text-blue-600">{{ $complaint->complainant_details->company->legal_name }}</span>
                        </div>
                    @endif
                    
                    {{-- معلومات الشركة التابعة لمراكز الصيانة --}}
                    @if($complaint->complainant_type === 'service_center' && $complaint->complainant_details && $complaint->complainant_details->created_by_company && $complaint->complainant_details->insuranceCompany)
                        <div class="md:col-span-2">
                            <div class="flex items-center gap-2">
                                <span class="font-medium">{{ t('admin.parent_insurance_company') }}:</span>
                                <span class="px-3 py-1 bg-gradient-to-r from-emerald-100 to-teal-100 text-emerald-800 rounded-full font-semibold text-sm border border-emerald-200">
                                    <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $complaint->complainant_details->insuranceCompany->legal_name }}
                                </span>
                                <span class="text-xs text-emerald-600 bg-emerald-50 px-2 py-1 rounded border border-emerald-200">
                                    {{ t('admin.service_center_managed_by_company') }}
                                </span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
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

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-sm ${
        type === 'success' ? 'bg-green-500 text-white' :
        type === 'error' ? 'bg-red-500 text-white' :
        'bg-blue-500 text-white'
    }`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}
</script>
@endsection
