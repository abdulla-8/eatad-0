{{-- resources/views/complaints/edit.blade.php --}}
@extends($userType === 'insurance_company' ? 'insurance.layouts.app' : ($userType === 'service_center' ? 'service-center.layouts.app' : 'insurance-user.layouts.app'))

@section('title', t($translationGroup . '.edit_complaint'))

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
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
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
            
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ t($translationGroup . '.edit_complaint') }}</h1>
          
                
                <!-- Show company name for insurance users -->
                @if($userType === 'insurance_user' && $user->company)
                    <p class="text-sm text-blue-600 mt-2">{{ $user->company->legal_name ?? $user->company->commercial_name }}</p>
                @endif
            </div>
        </div>
        
        <!-- Complaint Info -->
        <div class="flex items-center gap-3">
            <div class="px-4 py-2 bg-gray-100 rounded-lg">
                <div class="text-sm font-medium text-gray-700">{{ t($translationGroup . '.complaint_id') }}</div>
                <div class="text-lg font-bold text-gray-900">#{{ str_pad($complaint->id, 2, '0', STR_PAD_LEFT) }}</div>
            </div>
            
            <span class="px-3 py-1.5 rounded-full text-sm font-medium border {{ $complaint->type_badge['class'] ?? 'bg-gray-100 text-gray-800' }}">
                {{ t($translationGroup . '.' . $complaint->type) }}
            </span>
            
            <span class="px-3 py-1.5 rounded-full text-sm font-medium border {{ $complaint->is_read ? 'bg-green-100 text-green-800 border-green-300' : 'bg-red-100 text-red-800 border-red-300' }}">
                {{ $complaint->is_read ? t($translationGroup . '.read') : t($translationGroup . '.unread') }}
            </span>
        </div>
    </div>

    <!-- Edit Form Card -->
    <div class="bg-white rounded-xl shadow-sm border">
        <div class="p-6">
            <!-- Form Header -->
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-lg bg-yellow-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900">{{ t($translationGroup . '.edit_complaint_form') }}</h3>
                  
                </div>
            </div>

            @if($userType === 'insurance_company')
                <form method="POST" action="{{ route('insurance.complaints.update', ['companyRoute' => $companySlug, 'id' => $complaint->id]) }}" enctype="multipart/form-data">
            @elseif($userType === 'insurance_user')
                <form method="POST" action="{{ route('insurance.user.complaints.update', ['companySlug' => $companySlug, 'id' => $complaint->id]) }}" enctype="multipart/form-data">
            @else
                <form method="POST" action="{{ route('service-center.complaints.update', $complaint->id) }}" enctype="multipart/form-data">
            @endif
                @csrf
                @method('PUT')
                
                <!-- Show user info for insurance users -->
                @if($userType === 'insurance_user')
                    <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                        <h4 class="font-medium text-blue-900 mb-2">{{ t($translationGroup . '.complainant_info') }}</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-blue-700 font-medium">{{ t($translationGroup . '.name') }}:</span>
                                <span class="text-blue-800">{{ $user->full_name }}</span>
                            </div>
                            <div>
                                <span class="text-blue-700 font-medium">{{ t($translationGroup . '.policy_number') }}:</span>
                                <span class="text-blue-800">{{ $user->policy_number }}</span>
                            </div>
                            <div>
                                <span class="text-blue-700 font-medium">{{ t($translationGroup . '.phone') }}:</span>
                                <span class="text-blue-800">{{ $user->phone }}</span>
                            </div>
                            <div>
                                <span class="text-blue-700 font-medium">{{ t($translationGroup . '.company') }}:</span>
                                <span class="text-blue-800">{{ optional($user->company)->legal_name ?? 'غير محدد' }}</span>
                            </div>
                        </div>
                    </div>
                @endif
                
                <div class="space-y-6">
                    <!-- Type Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">{{ t($translationGroup . '.type') }} *</label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <label class="relative cursor-pointer">
                                <input type="radio" name="type" value="inquiry" class="sr-only" required {{ $complaint->type === 'inquiry' ? 'checked' : '' }}>
                                <div class="p-4 border-2 rounded-lg hover:border-blue-300 transition-colors type-option {{ $complaint->type === 'inquiry' ? 'border-blue-500 bg-blue-50' : 'border-gray-300' }}">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-900">{{ t($translationGroup . '.inquiry') }}</div>
                                           
                                        </div>
                                    </div>
                                </div>
                            </label>
                            
                            <label class="relative cursor-pointer">
                                <input type="radio" name="type" value="complaint" class="sr-only" required {{ $complaint->type === 'complaint' ? 'checked' : '' }}>
                                <div class="p-4 border-2 rounded-lg hover:border-blue-300 transition-colors type-option {{ $complaint->type === 'complaint' ? 'border-blue-500 bg-blue-50' : 'border-gray-300' }}">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-900">{{ t($translationGroup . '.complaint') }}</div>
                                         
                                        </div>
                                    </div>
                                </div>
                            </label>
                            
                            <label class="relative cursor-pointer">
                                <input type="radio" name="type" value="other" class="sr-only" required {{ $complaint->type === 'other' ? 'checked' : '' }}>
                                <div class="p-4 border-2 rounded-lg hover:border-blue-300 transition-colors type-option {{ $complaint->type === 'other' ? 'border-blue-500 bg-blue-50' : 'border-gray-300' }}">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-900">{{ t($translationGroup . '.other') }}</div>
                                    
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                    
                    <!-- Subject -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ t($translationGroup . '.subject') }} *</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 {{ app()->getLocale() === 'ar' ? 'right-0 pr-3' : 'left-0 pl-3' }} flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                </svg>
                            </div>
                            <input type="text" name="subject" value="{{ old('subject', $complaint->subject) }}" required 
                                   class="w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent {{ app()->getLocale() === 'ar' ? 'pr-10' : 'pl-10' }} py-2.5"
                                   placeholder="{{ t($translationGroup . '.subject_placeholder') }}">
                        </div>
                    </div>
                    
                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ t($translationGroup . '.description') }} *</label>
                        <textarea name="description" rows="4" required 
                                  class="w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent px-4 py-2.5 resize-none"
                                  placeholder="{{ t($translationGroup . '.description_placeholder') }}">{{ old('description', $complaint->description) }}</textarea>
                    </div>
                    
                    <!-- Current Attachment -->
                    @if($complaint->attachment_path)
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <h4 class="font-medium text-gray-900 mb-2">{{ t($translationGroup . '.current_attachment') }}</h4>
                            <div class="flex items-center gap-3">
                                <div class="flex-shrink-0">
                                    <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">{{ basename($complaint->attachment_path) }}</p>
                                    <p class="text-sm text-gray-500">{{ t($translationGroup . '.uploaded_at') }}: {{ $complaint->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                            <div class="flex flex-col sm:flex-row gap-3 p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg border border-gray-200 shadow-sm">
    <!-- زر عرض المرفق -->
    <a href="{{ Storage::url($complaint->attachment_path) }}" target="_blank"
       class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg font-medium hover:from-blue-600 hover:to-blue-700 transform hover:scale-105 transition-all duration-200 shadow-md hover:shadow-lg group">
        <svg class="w-4 h-4 group-hover:animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
        </svg>
        <span>{{ t($translationGroup . '.view') }}</span>
        <svg class="w-3 h-3 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 4h6m0 0v6m0-6L10 14"></path>
        </svg>
    </a>

    <!-- خانة حذف المرفق -->
    <label class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-red-50 to-red-100 text-red-700 rounded-lg font-medium hover:from-red-100 hover:to-red-200 cursor-pointer transition-all duration-200 border border-red-200 hover:border-red-300 group">
        <div class="relative">
            <input type="checkbox" name="remove_attachment" value="1" 
                   class="w-4 h-4 text-red-600 bg-white border-2 border-red-300 rounded focus:ring-red-500 focus:ring-2 transition-all duration-200">
            <svg class="w-3 h-3 absolute top-0.5 left-0.5 text-white opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
            </svg>
        </div>
        <svg class="w-4 h-4 group-hover:animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
        </svg>
        <span>{{ t($translationGroup . '.remove') }}</span>
    </label>
</div>

                            </div>
                        </div>
                    @endif
                    
                    <!-- Enhanced File Upload -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            {{ $complaint->attachment_path ? t($translationGroup . '.replace_attachment') : t($translationGroup . '.attachment') }}
                        </label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-400 transition-colors">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                        <span>{{ t($translationGroup . '.upload_file') }}</span>
                                        <input id="file-upload" name="attachment" type="file" class="sr-only" accept=".jpeg,.png,.jpg,.pdf,.doc,.docx" onchange="handleFilePreview(this)">
                                    </label>
                               
                                </div>
                       
                            </div>
                        </div>
                        
                        <!-- File Preview -->
                        <div id="filePreview" class="hidden mt-3">
                            <div class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                                <div class="flex-shrink-0">
                                    <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div class="ml-3 flex-1">
                                    <p class="text-sm font-medium text-gray-900" id="fileName"></p>
                                    <p class="text-sm text-gray-500" id="fileSize"></p>
                                </div>
                                <button type="button" onclick="removeFile()" class="ml-3 text-red-400 hover:text-red-600">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Form Actions -->
                <div class="flex gap-4 pt-6 border-t border-gray-200 mt-6">
                    <button  style="background-color: {{ $profileData['colors']['primary'] ?? '#000' }};" type="submit" class="flex-1 inline-flex justify-center items-center gap-2 py-3  text-white rounded-lg font-medium hover:bg-black transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        {{ t($translationGroup . '.update_complaint') }}
                    </button>
                    
                    @if($userType === 'insurance_company')
                        <a href="{{ route('insurance.complaints.index', ['companyRoute' => $companySlug]) }}" 
                           class="flex-1 inline-flex justify-center items-center py-3 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition-colors">
                            {{ t($translationGroup . '.cancel') }}
                        </a>
                    @elseif($userType === 'insurance_user')
                        <a href="{{ route('insurance.user.complaints.index', ['companySlug' => $companySlug]) }}" 
                           class="flex-1 inline-flex justify-center items-center py-3 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition-colors">
                            {{ t($translationGroup . '.cancel') }}
                        </a>
                    @else
                        <a href="{{ route('service-center.complaints.index') }}" 
                           class="flex-1 inline-flex justify-center items-center py-3 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition-colors">
                            {{ t($translationGroup . '.cancel') }}
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Handle radio button styling
document.querySelectorAll('input[name="type"]').forEach(radio => {
    radio.addEventListener('change', function() {
        document.querySelectorAll('.type-option').forEach(option => {
            option.classList.remove('border-blue-500', 'bg-blue-50');
            option.classList.add('border-gray-300');
        });
        
        if (this.checked) {
            const option = this.nextElementSibling;
            option.classList.remove('border-gray-300');
            option.classList.add('border-blue-500', 'bg-blue-50');
        }
    });
});

// Handle file preview
function handleFilePreview(input) {
    const file = input.files[0];
    const preview = document.getElementById('filePreview');
    const fileName = document.getElementById('fileName');
    const fileSize = document.getElementById('fileSize');
    
    if (file) {
        fileName.textContent = file.name;
        fileSize.textContent = formatFileSize(file.size);
        preview.classList.remove('hidden');
    } else {
        preview.classList.add('hidden');
    }
}

function removeFile() {
    document.getElementById('file-upload').value = '';
    document.getElementById('filePreview').classList.add('hidden');
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

// Handle drag and drop
const dropArea = document.querySelector('.border-dashed');
['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
    dropArea.addEventListener(eventName, preventDefaults, false);
});

function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
}

['dragenter', 'dragover'].forEach(eventName => {
    dropArea.addEventListener(eventName, highlight, false);
});

['dragleave', 'drop'].forEach(eventName => {
    dropArea.addEventListener(eventName, unhighlight, false);
});

function highlight(e) {
    dropArea.classList.add('border-blue-400', 'bg-blue-50');
}

function unhighlight(e) {
    dropArea.classList.remove('border-blue-400', 'bg-blue-50');
}

dropArea.addEventListener('drop', handleDrop, false);

function handleDrop(e) {
    const dt = e.dataTransfer;
    const files = dt.files;
    
    if (files.length > 0) {
        document.getElementById('file-upload').files = files;
        handleFilePreview(document.getElementById('file-upload'));
    }
}
</script>

@endsection
