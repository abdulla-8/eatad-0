{{-- resources/views/insurance/complaints/show.blade.php --}}
@extends('insurance.layouts.app')

@section('title', t($company->translation_group . '.complaint_details'))

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ route('insurance.complaints.index', $company->company_slug) }}" 
           class="p-2 rounded-lg hover:bg-gray-100 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ t($company->translation_group . '.complaint_details') }}</h1>
            <p class="text-gray-600 mt-1">{{ t($company->translation_group . '.view_complaint_inquiry_details') }}</p>
        </div>
    </div>

    <!-- Complaint Details -->
    <div class="bg-white rounded-xl shadow-sm border">
        <div class="p-6">
            <!-- Status and Type -->
            <div class="flex items-center gap-3 mb-6">
                <span class="px-4 py-2 rounded-full text-sm font-medium {{ $complaint->type_badge['class'] }}">
                    {{ t($company->translation_group . '.' . $complaint->type) }}
                </span>
                <span class="px-4 py-2 rounded-full text-sm font-medium {{ $complaint->is_read ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $complaint->is_read ? t($company->translation_group . '.read') : t($company->translation_group . '.unread') }}
                </span>
            </div>

            <!-- Subject -->
            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-2">{{ t($company->translation_group . '.subject') }}</h3>
                <p class="text-gray-700">{{ $complaint->subject }}</p>
            </div>

            <!-- Description -->
            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-2">{{ t($company->translation_group . '.details') }}</h3>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-gray-700 whitespace-pre-wrap">{{ $complaint->description }}</p>
                </div>
            </div>

            <!-- Attachment -->
            @if($complaint->attachment_path)
            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-2">{{ t($company->translation_group . '.attachment') }}</h3>
                <a href="{{ Storage::url($complaint->attachment_path) }}" 
                   target="_blank"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-lg font-medium transition-colors"
                   style="background: {{ $company->primary_color }}20; color: {{ $company->primary_color }};">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                    </svg>
                    {{ t($company->translation_group . '.view_attachment') }}
                </a>
            </div>
            @endif

            <!-- Metadata -->
            <div class="border-t pt-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                    <div>
                        <span class="font-medium">{{ t($company->translation_group . '.submission_date') }}:</span>
                        {{ $complaint->created_at->format('d/m/Y H:i') }}
                    </div>
                    <div>
                        <span class="font-medium">{{ t($company->translation_group . '.last_update') }}:</span>
                        {{ $complaint->updated_at->format('d/m/Y H:i') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
