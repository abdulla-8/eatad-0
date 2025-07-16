{{-- resources/views/complaints/show.blade.php --}}
@extends($userType === 'insurance_company' ? 'insurance.layouts.app' : ($userType === 'service_center' ? 'service-center.layouts.app' : 'insurance-user.layouts.app'))

@section('title', t($translationGroup . '.complaint_details'))

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-4">
        @if($userType === 'insurance_company')
            <a href="{{ route('insurance.complaints.index', ['companyRoute' => $user->company_slug ?? 'default']) }}" 
               class="p-2 rounded-lg hover:bg-gray-100 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
        @elseif($userType === 'insurance_user')
            <a href="{{ route('insurance.user.complaints.index', ['companySlug' => optional($user->company)->company_slug ?? 'default']) }}" 
               class="p-2 rounded-lg hover:bg-gray-100 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
        @else
            <a href="{{ route('service-center.complaints.index') }}" 
               class="p-2 rounded-lg hover:bg-gray-100 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
    </div>

    <!-- Complaint Details -->
    <div class="bg-white rounded-xl shadow-sm border {{ !$complaint->is_read ? 'ring-2 ring-red-100' : '' }}">
        <div class="p-6">
            <!-- Status and Type -->
            <div class="flex items-center gap-3 mb-6">
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

            <!-- Complaint ID -->
            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-2">{{ t($translationGroup . '.complaint_id') }}</h3>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-gray-700 font-mono">#{{ $complaint->id }}</p>
                </div>
            </div>

            <!-- معلومات الشاكي (لمستخدمي شركة التأمين) -->
            @if($userType === 'insurance_user')
                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">{{ t($translationGroup . '.complainant_information') }}</h3>
                    <div class="bg-blue-50 rounded-lg p-4 space-y-3">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div class="flex items-center gap-2">
                                <span class="font-medium text-blue-700">{{ t($translationGroup . '.name') }}:</span>
                                <span class="text-blue-900">{{ $user->full_name }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="font-medium text-blue-700">{{ t($translationGroup . '.policy_number') }}:</span>
                                <span class="text-blue-900">{{ $user->policy_number }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="font-medium text-blue-700">{{ t($translationGroup . '.phone') }}:</span>
                                <span class="text-blue-900">{{ $user->phone }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="font-medium text-blue-700">{{ t($translationGroup . '.company') }}:</span>
                                <span class="text-blue-900">{{ optional($user->company)->legal_name ?? 'غير محدد' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Subject -->
            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-2">{{ t($translationGroup . '.subject') }}</h3>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-gray-700 {{ !$complaint->is_read ? 'font-semibold' : '' }}">{{ $complaint->subject }}</p>
                </div>
            </div>

            <!-- Description -->
            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-2">{{ t($translationGroup . '.details') }}</h3>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-gray-700 whitespace-pre-wrap leading-relaxed">{{ $complaint->description }}</p>
                </div>
            </div>

            <!-- Attachment -->
            @if($complaint->attachment_path)
            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-2">{{ t($translationGroup . '.attachment') }}</h3>
                <div class="bg-gray-50 rounded-lg p-4">
                    <a href="{{ Storage::url($complaint->attachment_path) }}" 
                       target="_blank"
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-lg font-medium transition-colors"
                       style="background: {{ $primaryColor }}20; color: {{ $primaryColor }};">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                        </svg>
                        {{ t($translationGroup . '.view_attachment') }}
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                        </svg>
                    </a>
                    <p class="text-xs text-gray-500 mt-2">
                        {{ t($translationGroup . '.click_to_view_attachment') }}
                    </p>
                </div>
            </div>
            @endif

            <!-- Timeline -->
            <div class="border-t pt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ t($translationGroup . '.timeline') }}</h3>
                <div class="space-y-4">
                    <!-- Submission -->
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0"
                             style="background: {{ $primaryColor }}20;">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: {{ $primaryColor }};">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-2">
                                <span class="font-medium text-gray-900">{{ t($translationGroup . '.complaint_submitted') }}</span>
                                <span class="text-sm text-gray-500">{{ $complaint->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <p class="text-sm text-gray-600 mt-1">
                                {{ t($translationGroup . '.submitted_by_you') }}
                                @if($userType === 'insurance_user' && $user->company)
                                    <span class="text-blue-600 font-medium">
                                        ({{ optional($user->company)->legal_name }})
                                    </span>
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
                                <span class="font-medium text-gray-900">{{ t($translationGroup . '.current_status') }}</span>
                                <span class="px-2 py-1 rounded text-xs {{ $complaint->is_read ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $complaint->is_read ? t($translationGroup . '.read') : t($translationGroup . '.unread') }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mt-1">
                                @if($complaint->is_read)
                                    {{ t($translationGroup . '.complaint_has_been_reviewed') }}
                                @else
                                    {{ t($translationGroup . '.complaint_awaiting_review') }}
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
                        <span class="font-medium">{{ t($translationGroup . '.submission_date') }}:</span>
                        {{ $complaint->created_at->format('d/m/Y H:i') }}
                    </div>
                    <div>
                        <span class="font-medium">{{ t($translationGroup . '.last_update') }}:</span>
                        {{ $complaint->updated_at->format('d/m/Y H:i') }}
                    </div>
                    @if($userType === 'insurance_user')
                        <div>
                            <span class="font-medium">{{ t($translationGroup . '.complainant_type') }}:</span>
                            <span class="text-blue-600">{{ t($translationGroup . '.insurance_user') }}</span>
                        </div>
                        @if($user->company)
                            <div>
                                <span class="font-medium">{{ t($translationGroup . '.insurance_company') }}:</span>
                                <span class="text-blue-600">{{ $user->company->legal_name }}</span>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
