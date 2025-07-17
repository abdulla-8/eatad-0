{{-- resources/views/complaints/show.blade.php --}}
@extends($userType === 'insurance_company' ? 'insurance.layouts.app' : ($userType === 'service_center' ? 'service-center.layouts.app' : 'insurance-user.layouts.app'))

@section('title', t($translationGroup . '.complaint_details'))

@section('content')
@php
    // تحديد الـ company slug بشكل موحد
    $companySlug = '';
    if ($userType === 'insurance_company') {
        $companySlug = $user->company_slug ?? ($user->company->company_slug ?? 'default');
    } elseif ($userType === 'insurance_user') {
        $companySlug = optional($user->company)->company_slug ?? 'default';
    }
@endphp

<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-4">
        @if($userType === 'insurance_company')
            <a href="{{ route('insurance.complaints.index', ['companyRoute' => $companySlug]) }}" 
               class="p-2 rounded-lg hover:bg-gray-100 transition-colors">
                <svg class="w-6 h-6 {{ app()->getLocale() === 'ar' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
        @elseif($userType === 'insurance_user')
            <a href="{{ route('insurance.user.complaints.index', ['companySlug' => $companySlug]) }}" 
               class="p-2 rounded-lg hover:bg-gray-100 transition-colors">
                <svg class="w-6 h-6 {{ app()->getLocale() === 'ar' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
        @else
            <a href="{{ route('service-center.complaints.index') }}" 
               class="p-2 rounded-lg hover:bg-gray-100 transition-colors">
                <svg class="w-6 h-6 {{ app()->getLocale() === 'ar' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
        @endif
        <div class="flex-1">
            <h1 class="text-3xl font-bold text-gray-900">{{ t($translationGroup . '.complaint_details') }}</h1>
            <p class="text-gray-600 mt-1">{{ t($translationGroup . '.view_complaint_inquiry_details') }}</p>
            
            <!-- عرض اسم الشركة لمستخدمي شركة التأمين -->
            @if($userType === 'insurance_user' && $user->company)
                <div class="mt-2 flex items-center gap-2">
                    <span class="text-sm font-medium text-gray-700">{{ t($translationGroup . '.company') }}:</span>
                    <span class="text-sm text-blue-600 font-semibold">{{ $user->company->legal_name ?? $user->company->commercial_name }}</span>
                </div>
            @endif
        </div>
        
        <!-- Action Buttons -->
        <div class="flex gap-2">
            <!-- Edit Button -->
            @if($userType === 'insurance_company')
                <a href="{{ route('insurance.complaints.edit', ['companyRoute' => $companySlug, 'id' => $complaint->id]) }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 bg-yellow-50 text-yellow-700 rounded-lg font-medium hover:bg-yellow-100 transition-colors border border-yellow-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    {{ t($translationGroup . '.edit') }}
                </a>
            @elseif($userType === 'insurance_user')
                <a href="{{ route('insurance.user.complaints.edit', ['companySlug' => $companySlug, 'id' => $complaint->id]) }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 bg-yellow-50 text-yellow-700 rounded-lg font-medium hover:bg-yellow-100 transition-colors border border-yellow-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    {{ t($translationGroup . '.edit') }}
                </a>
            @else
                <a href="{{ route('service-center.complaints.edit', $complaint->id) }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 bg-yellow-50 text-yellow-700 rounded-lg font-medium hover:bg-yellow-100 transition-colors border border-yellow-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    {{ t($translationGroup . '.edit') }}
                </a>
            @endif
            
            <!-- Mark as Read Button (if unread) -->
            @if(!$complaint->is_read)
                <form method="POST" action="{{ route('complaints.mark-read', $complaint->id) }}" class="inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-green-50 text-green-700 rounded-lg font-medium hover:bg-green-100 transition-colors border border-green-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        {{ t($translationGroup . '.mark_as_read') }}
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Complaint Details -->
    <div class="bg-white rounded-xl shadow-sm border {{ !$complaint->is_read ? 'ring-2 ring-red-100' : '' }}">
        <div class="p-6">
            <!-- Status and Type -->
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <span class="px-4 py-2 rounded-full text-sm font-medium {{ $complaint->type_badge['class'] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ t($translationGroup . '.' . $complaint->type) }}
                    </span>
                    <span class="px-4 py-2 rounded-full text-sm font-medium {{ $complaint->is_read ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $complaint->is_read ? t($translationGroup . '.read') : t($translationGroup . '.unread') }}
                    </span>
                    @if(!$complaint->is_read)
                        <span class="px-3 py-1 bg-red-500 text-white text-xs rounded-full animate-pulse">
                            {{ t($translationGroup . '.new') }}
                        </span>
                    @endif
                </div>
                
                <!-- Complaint ID Badge -->
                <div class="flex items-center gap-2">
                    <span class="text-sm font-medium text-gray-600">{{ t($translationGroup . '.complaint_id') }}:</span>
                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-mono font-bold">
                        #{{ str_pad($complaint->id, 4, '0', STR_PAD_LEFT) }}
                    </span>
                </div>
            </div>

            <!-- معلومات الشاكي (لمستخدمي شركة التأمين) -->
            @if($userType === 'insurance_user')
                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        {{ t($translationGroup . '.complainant_information') }}
                    </h3>
                    <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex items-center gap-2">
                                <span class="font-medium text-blue-700 text-sm">{{ t($translationGroup . '.name') }}:</span>
                                <span class="text-blue-900 font-semibold">{{ $user->full_name }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="font-medium text-blue-700 text-sm">{{ t($translationGroup . '.policy_number') }}:</span>
                                <span class="text-blue-900 font-mono">{{ $user->policy_number }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="font-medium text-blue-700 text-sm">{{ t($translationGroup . '.phone') }}:</span>
                                <span class="text-blue-900" dir="ltr">{{ $user->phone }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="font-medium text-blue-700 text-sm">{{ t($translationGroup . '.email') }}:</span>
                                <span class="text-blue-900" dir="ltr">{{ $user->email }}</span>
                            </div>
                            @if($user->company)
                                <div class="flex items-center gap-2 md:col-span-2">
                                    <span class="font-medium text-blue-700 text-sm">{{ t($translationGroup . '.company') }}:</span>
                                    <span class="text-blue-900 font-semibold">{{ $user->company->legal_name ?? $user->company->commercial_name }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- Subject -->
            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                    </svg>
                    {{ t($translationGroup . '.subject') }}
                </h3>
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <p class="text-gray-900 font-medium {{ !$complaint->is_read ? 'text-lg' : 'text-base' }}">{{ $complaint->subject }}</p>
                </div>
            </div>

            <!-- Description -->
            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    {{ t($translationGroup . '.details') }}
                </h3>
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <p class="text-gray-700 whitespace-pre-wrap leading-relaxed">{{ $complaint->description }}</p>
                </div>
            </div>

            <!-- Attachment -->
            @if($complaint->attachment_path)
            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                    </svg>
                    {{ t($translationGroup . '.attachment') }}
                </h3>
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-gray-900">{{ basename($complaint->attachment_path) }}</p>
                            <p class="text-sm text-gray-600">{{ t($translationGroup . '.click_to_view_attachment') }}</p>
                        </div>
                        <a href="{{ Storage::url($complaint->attachment_path) }}" 
                           target="_blank"
                           class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-700 rounded-lg font-medium hover:bg-blue-100 transition-colors border border-blue-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                            </svg>
                            {{ t($translationGroup . '.view_attachment') }}
                        </a>
                    </div>
                </div>
            </div>
            @endif

            <!-- Timeline -->
            <div class="border-t pt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ t($translationGroup . '.timeline') }}
                </h3>
                <div class="space-y-6">
                    <!-- Submission -->
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="font-medium text-gray-900">{{ t($translationGroup . '.complaint_submitted') }}</span>
                                <span class="text-sm text-gray-500">{{ $complaint->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-sm text-gray-600">
                                {{ t($translationGroup . '.submitted_by_you') }}
                                @if($userType === 'insurance_user' && $user->company)
                                    <span class="text-blue-600 font-medium">
                                        ({{ $user->company->legal_name ?? $user->company->commercial_name }})
                                    </span>
                                @endif
                            </p>
                            <p class="text-xs text-gray-500 mt-1">{{ $complaint->created_at->format('l, d F Y \a\t H:i') }}</p>
                        </div>
                    </div>

                    <!-- Current Status -->
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 {{ $complaint->is_read ? 'bg-green-100' : 'bg-red-100' }} rounded-full flex items-center justify-center flex-shrink-0">
                            @if($complaint->is_read)
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            @else
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            @endif
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="font-medium text-gray-900">{{ t($translationGroup . '.current_status') }}</span>
                                <span class="px-2 py-1 rounded-full text-xs font-medium {{ $complaint->is_read ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $complaint->is_read ? t($translationGroup . '.read') : t($translationGroup . '.unread') }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600">
                                @if($complaint->is_read)
                                    {{ t($translationGroup . '.complaint_has_been_reviewed') }}
                                @else
                                    {{ t($translationGroup . '.complaint_awaiting_review') }}
                                @endif
                            </p>
                            @if($complaint->is_read)
                                <p class="text-xs text-gray-500 mt-1">{{ $complaint->updated_at->format('l, d F Y \a\t H:i') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Metadata -->
            <div class="border-t pt-6 mt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ t($translationGroup . '.additional_information') }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center gap-2 mb-2">
                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4M5 7h14l-1 10H6L5 7z"></path>
                            </svg>
                            <span class="font-medium text-gray-700">{{ t($translationGroup . '.submission_date') }}</span>
                        </div>
                        <p class="text-sm text-gray-600">{{ $complaint->created_at->format('d/m/Y H:i') }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $complaint->created_at->diffForHumans() }}</p>
                    </div>
                    
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center gap-2 mb-2">
                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            <span class="font-medium text-gray-700">{{ t($translationGroup . '.last_update') }}</span>
                        </div>
                        <p class="text-sm text-gray-600">{{ $complaint->updated_at->format('d/m/Y H:i') }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $complaint->updated_at->diffForHumans() }}</p>
                    </div>
                    
                    @if($userType === 'insurance_user')
                        <div class="bg-blue-50 rounded-lg p-4">
                            <div class="flex items-center gap-2 mb-2">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <span class="font-medium text-blue-700">{{ t($translationGroup . '.complainant_type') }}</span>
                            </div>
                            <p class="text-sm text-blue-600 font-medium">{{ t($translationGroup . '.insurance_user') }}</p>
                        </div>
                        
                        @if($user->company)
                            <div class="bg-blue-50 rounded-lg p-4">
                                <div class="flex items-center gap-2 mb-2">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h1a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                    <span class="font-medium text-blue-700">{{ t($translationGroup . '.insurance_company') }}</span>
                                </div>
                                <p class="text-sm text-blue-600 font-medium">{{ $user->company->legal_name ?? $user->company->commercial_name }}</p>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
