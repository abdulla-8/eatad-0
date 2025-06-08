@extends('admin.layouts.app')

@section('title', isset($specialization) ? t('admin.edit_specialization') : t('admin.add_specialization'))

@section('content')

<!-- Page Header -->
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
    <div>
        <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-2">
            {{ isset($specialization) ? t('admin.edit_specialization') : t('admin.add_specialization') }}
        </h1>
        <nav class="flex text-sm">
            <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-gold-600">{{ t('admin.dashboard') }}</a>
            <span class="mx-2 text-gray-400">></span>
            <a href="{{ route('admin.specializations.index') }}" class="text-gray-500 hover:text-gold-600">{{ t('admin.specializations') }}</a>
            <span class="mx-2 text-gray-400">></span>
            <span class="text-gold-600 font-medium">
                {{ isset($specialization) ? t('admin.edit') : t('admin.add_new') }}
            </span>
        </nav>
    </div>
    <a href="{{ route('admin.specializations.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium transition-colors mt-4 sm:mt-0">
        <svg class="w-4 h-4 inline {{ $isRtl ? 'ml-1' : 'mr-1' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        {{ t('admin.back_to_list') }}
    </a>
</div>

<!-- Form Container -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="bg-gold-500 text-dark-900 px-6 py-4">
        <h2 class="text-lg font-bold flex items-center">
            <svg class="w-5 h-5 {{ $isRtl ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ isset($specialization) ? 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z' : 'M12 4v16m8-8H4' }}"></path>
            </svg>
            {{ t('admin.specialization_details') }}
        </h2>
    </div>
    
    <form method="POST" 
          action="{{ isset($specialization) ? route('admin.specializations.update', $specialization) : route('admin.specializations.store') }}" 
          enctype="multipart/form-data" 
          class="p-6"
          id="specializationForm">
        @csrf
        @if(isset($specialization))
            @method('PUT')
        @endif
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Brand Name (English) -->
            <div>
                <label for="brand_name" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ t('admin.brand_name') }} <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="brand_name" 
                       name="brand_name" 
                       value="{{ old('brand_name', $specialization->brand_name ?? '') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gold-500 focus:border-gold-500 @error('brand_name') border-red-500 @enderror"
                       placeholder="{{ t('admin.brand_name_placeholder') }}"
                       required>
                @error('brand_name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Brand Name (Arabic) -->
            <div>
                <label for="brand_name_ar" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ t('admin.brand_name_ar') }} <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="brand_name_ar" 
                       name="brand_name_ar" 
                       value="{{ old('brand_name_ar', $specialization->brand_name_ar ?? '') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gold-500 focus:border-gold-500 @error('brand_name_ar') border-red-500 @enderror"
                       placeholder="{{ t('admin.brand_name_ar_placeholder') }}"
                       required>
                @error('brand_name_ar')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Sort Order -->
            <div>
                <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ t('admin.sort_order') }}
                </label>
                <input type="number" 
                       id="sort_order" 
                       name="sort_order" 
                       value="{{ old('sort_order', $specialization->sort_order ?? 0) }}"
                       min="0"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gold-500 focus:border-gold-500 @error('sort_order') border-red-500 @enderror"
                       placeholder="{{ t('admin.sort_order_placeholder') }}">
                <p class="text-xs text-gray-500 mt-1">{{ t('admin.sort_order_help') }}</p>
                @error('sort_order')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Status -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    {{ t('admin.status') }}
                </label>
                <div class="flex items-center space-x-3 {{ $isRtl ? 'space-x-reverse' : '' }}">
                    <label class="inline-flex items-center">
                        <input type="radio" 
                               name="is_active" 
                               value="1" 
                               {{ old('is_active', $specialization->is_active ?? 1) == 1 ? 'checked' : '' }}
                               class="form-radio text-gold-500 focus:ring-gold-500">
                        <span class="{{ $isRtl ? 'mr-2' : 'ml-2' }} text-sm text-gray-700">{{ t('admin.active') }}</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" 
                               name="is_active" 
                               value="0" 
                               {{ old('is_active', $specialization->is_active ?? 1) == 0 ? 'checked' : '' }}
                               class="form-radio text-gray-500 focus:ring-gray-500">
                        <span class="{{ $isRtl ? 'mr-2' : 'ml-2' }} text-sm text-gray-700">{{ t('admin.inactive') }}</span>
                    </label>
                </div>
            </div>
        </div>
        
        <!-- Image Upload Section -->
        <div class="mt-8">
            <label class="block text-sm font-medium text-gray-700 mb-4">
                {{ t('admin.brand_image') }}
            </label>
            
            @if(isset($specialization) && $specialization->image)
                <!-- Current Image Display -->
                <div class="mb-6">
                    <h4 class="text-sm font-medium text-gray-700 mb-2">{{ t('admin.current_image') }}:</h4>
                    <div class="relative inline-block group">
                        <img src="{{ $specialization->image_url }}" 
                             alt="{{ $specialization->display_name }}" 
                             class="w-32 h-32 object-cover rounded-lg border-2 border-gray-300 shadow-sm">
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-200 rounded-lg flex items-center justify-center">
                            <span class="text-white text-xs opacity-0 group-hover:opacity-100 font-medium">{{ t('admin.current_image') }}</span>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">{{ t('admin.upload_new_replace') }}</p>
                </div>
            @endif
            
            <!-- Upload Area -->
            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-gold-400 transition-colors duration-200" id="dropZone">
                <input type="file" 
                       id="image" 
                       name="image" 
                       accept="image/*" 
                       class="hidden" 
                       onchange="handleFileSelect(this)">
                
                <div id="uploadArea">
                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 48 48">
                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">{{ t('admin.upload_image') }}</h3>
                    <p class="text-gray-500 mb-4">{{ t('admin.drag_drop_or') }}</p>
                    <button type="button" 
                            onclick="document.getElementById('image').click()" 
                            class="bg-gold-500 hover:bg-gold-600 text-dark-900 px-4 py-2 rounded-lg font-medium transition-colors">
                        {{ t('admin.choose_file') }}
                    </button>
                    <p class="text-xs text-gray-400 mt-3">
                        {{ t('admin.supported_formats') }}: PNG, JPG, GIF<br>
                        {{ t('admin.max_size') }}: 2MB
                    </p>
                </div>
                
                <!-- Image Preview -->
                <div id="imagePreview" class="hidden">
                    <div class="relative inline-block">
                        <img id="previewImage" 
                             src="" 
                             alt="{{ t('admin.image_preview') }}" 
                             class="w-48 h-48 object-cover rounded-lg border-2 border-gold-300 shadow-md">
                        <button type="button" 
                                onclick="removeImage()" 
                                class="absolute -top-2 -{{ $isRtl ? 'left' : 'right' }}-2 bg-red-500 hover:bg-red-600 text-white rounded-full w-8 h-8 flex items-center justify-center text-sm font-bold transition-colors">
                            ×
                        </button>
                    </div>
                    <p class="text-sm text-gray-600 mt-2">{{ t('admin.new_image_preview') }}</p>
                    <button type="button" 
                            onclick="changeImage()" 
                            class="mt-2 text-gold-600 hover:text-gold-700 text-sm font-medium">
                        {{ t('admin.change_image') }}
                    </button>
                </div>
            </div>
            
            @error('image')
                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
            @enderror
        </div>
        
        <!-- Form Actions -->
        <div class="flex items-center justify-between mt-8 pt-6 border-t border-gray-200">
            <div class="flex items-center space-x-4 {{ $isRtl ? 'space-x-reverse' : '' }}">
                <button type="button" 
                        onclick="resetForm()" 
                        class="text-gray-500 hover:text-gray-700 text-sm font-medium">
                    {{ t('admin.reset_form') }}
                </button>
            </div>
            
            <div class="flex items-center space-x-3 {{ $isRtl ? 'space-x-reverse' : '' }}">
                <a href="{{ route('admin.specializations.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                    {{ t('admin.cancel') }}
                </a>
                <button type="submit" 
                        class="bg-gold-500 hover:bg-gold-600 text-dark-900 px-6 py-2 rounded-lg font-medium transition-colors flex items-center"
                        id="submitBtn">
                    <svg class="w-4 h-4 {{ $isRtl ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span id="submitText">{{ isset($specialization) ? t('admin.update') : t('admin.save') }}</span>
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Information Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
    <div class="bg-blue-50 rounded-xl p-6 border border-blue-200">
        <div class="flex items-center mb-3">
            <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center {{ $isRtl ? 'ml-3' : 'mr-3' }}">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h3 class="font-bold text-gray-900">{{ t('admin.form_guidelines') }}</h3>
        </div>
        <ul class="text-gray-700 text-sm space-y-2">
            <li class="flex items-start">
                <span class="w-1.5 h-1.5 rounded-full bg-blue-500 mt-2 {{ $isRtl ? 'ml-2' : 'mr-2' }} flex-shrink-0"></span>
                {{ t('admin.guideline_brand_names') }}
            </li>
            <li class="flex items-start">
                <span class="w-1.5 h-1.5 rounded-full bg-blue-500 mt-2 {{ $isRtl ? 'ml-2' : 'mr-2' }} flex-shrink-0"></span>
                {{ t('admin.guideline_sort_order') }}
            </li>
            <li class="flex items-start">
                <span class="w-1.5 h-1.5 rounded-full bg-blue-500 mt-2 {{ $isRtl ? 'ml-2' : 'mr-2' }} flex-shrink-0"></span>
                {{ t('admin.guideline_status') }}
            </li>
        </ul>
    </div>
    
    <div class="bg-green-50 rounded-xl p-6 border border-green-200">
        <div class="flex items-center mb-3">
            <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center {{ $isRtl ? 'ml-3' : 'mr-3' }}">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            <h3 class="font-bold text-gray-900">{{ t('admin.image_requirements') }}</h3>
        </div>
        <ul class="text-gray-700 text-sm space-y-2">
            <li class="flex items-start">
                <span class="w-1.5 h-1.5 rounded-full bg-green-500 mt-2 {{ $isRtl ? 'ml-2' : 'mr-2' }} flex-shrink-0"></span>
                {{ t('admin.req_format') }}
            </li>
            <li class="flex items-start">
                <span class="w-1.5 h-1.5 rounded-full bg-green-500 mt-2 {{ $isRtl ? 'ml-2' : 'mr-2' }} flex-shrink-0"></span>
                {{ t('admin.req_size') }}
            </li>
            <li class="flex items-start">
                <span class="w-1.5 h-1.5 rounded-full bg-green-500 mt-2 {{ $isRtl ? 'ml-2' : 'mr-2' }} flex-shrink-0"></span>
                {{ t('admin.req_dimensions') }}
            </li>
        </ul>
    </div>
    
    <div class="bg-gold-50 rounded-xl p-6 border border-gold-200">
        <div class="flex items-center mb-3">
            <div class="w-10 h-10 bg-gold-500 rounded-lg flex items-center justify-center {{ $isRtl ? 'ml-3' : 'mr-3' }}">
                <svg class="w-5 h-5 text-dark-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                </svg>
            </div>
            <h3 class="font-bold text-gray-900">{{ t('admin.tips') }}</h3>
        </div>
        <ul class="text-gray-700 text-sm space-y-2">
            <li class="flex items-start">
                <span class="w-1.5 h-1.5 rounded-full bg-gold-500 mt-2 {{ $isRtl ? 'ml-2' : 'mr-2' }} flex-shrink-0"></span>
                {{ t('admin.tip_logo_quality') }}
            </li>
            <li class="flex items-start">
                <span class="w-1.5 h-1.5 rounded-full bg-gold-500 mt-2 {{ $isRtl ? 'ml-2' : 'mr-2' }} flex-shrink-0"></span>
                {{ t('admin.tip_consistent_naming') }}
            </li>
            <li class="flex items-start">
                <span class="w-1.5 h-1.5 rounded-full bg-gold-500 mt-2 {{ $isRtl ? 'ml-2' : 'mr-2' }} flex-shrink-0"></span>
                {{ t('admin.tip_logical_order') }}
            </li>
        </ul>
    </div>
</div>

<script>
let selectedFile = null;

// Handle file selection
function handleFileSelect(input) {
    const file = input.files[0];
    if (!file) return;
    
    // Validate file size
    if (file.size > 2 * 1024 * 1024) {
        alert('{{ t("admin.file_too_large") }}');
        input.value = '';
        return;
    }
    
    // Validate file type
    const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
    if (!allowedTypes.includes(file.type)) {
        alert('{{ t("admin.invalid_file_type") }}');
        input.value = '';
        return;
    }
    
    selectedFile = file;
    previewImage(file);
}

// Preview selected image
function previewImage(file) {
    const reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById('previewImage').src = e.target.result;
        document.getElementById('uploadArea').classList.add('hidden');
        document.getElementById('imagePreview').classList.remove('hidden');
    };
    reader.readAsDataURL(file);
}

// Remove selected image
function removeImage() {
    document.getElementById('image').value = '';
    document.getElementById('uploadArea').classList.remove('hidden');
    document.getElementById('imagePreview').classList.add('hidden');
    document.getElementById('previewImage').src = '';
    selectedFile = null;
}

// Change selected image
function changeImage() {
    document.getElementById('image').click();
}

// Reset form
function resetForm() {
    if (confirm('{{ t("admin.confirm_reset_form") }}')) {
        document.getElementById('specializationForm').reset();
        removeImage();
    }
}

// Drag and drop functionality
document.addEventListener('DOMContentLoaded', function() {
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('image');
    
    // Prevent default drag behaviors
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
        document.body.addEventListener(eventName, preventDefaults, false);
    });
    
    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }
    
    // Highlight drop zone when item is dragged over
    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, highlight, false);
    });
    
    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, unhighlight, false);
    });
    
    function highlight(e) {
        dropZone.classList.add('border-gold-500', 'bg-gold-50');
    }
    
    function unhighlight(e) {
        dropZone.classList.remove('border-gold-500', 'bg-gold-50');
    }
    
    // Handle dropped files
    dropZone.addEventListener('drop', handleDrop, false);
    
    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        
        if (files.length > 0) {
            fileInput.files = files;
            handleFileSelect(fileInput);
        }
    }
    
    // Form submission handling
    const form = document.getElementById('specializationForm');
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    
    form.addEventListener('submit', function(e) {
        // Disable submit button to prevent double submission
        submitBtn.disabled = true;
        submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
        submitText.textContent = '{{ t("admin.saving") }}...';
        
        // Add loading spinner
        const spinner = document.createElement('div');
        spinner.className = 'animate-spin rounded-full h-4 w-4 border-b-2 border-dark-900 {{ $isRtl ? "ml-2" : "mr-2" }}';
        submitBtn.insertBefore(spinner, submitText);
        
        // Re-enable button after 10 seconds as fallback
        setTimeout(function() {
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            submitText.textContent = '{{ isset($specialization) ? t("admin.update") : t("admin.save") }}';
            if (spinner.parentNode) {
                spinner.parentNode.removeChild(spinner);
            }
        }, 10000);
    });
    
    // Auto-save draft functionality (optional)
    let autoSaveTimer;
    const formInputs = form.querySelectorAll('input[type="text"], input[type="number"], input[type="radio"]:checked');
    
    formInputs.forEach(input => {
        input.addEventListener('input', function() {
            clearTimeout(autoSaveTimer);
            autoSaveTimer = setTimeout(saveDraft, 2000);
        });
    });
    
    function saveDraft() {
        const formData = new FormData(form);
        const draftData = {};
        
        for (let [key, value] of formData.entries()) {
            if (key !== 'image' && key !== '_token' && key !== '_method') {
                draftData[key] = value;
            }
        }
        
        localStorage.setItem('specialization_draft', JSON.stringify(draftData));
        
        // Show save indicator
        const saveIndicator = document.createElement('div');
        saveIndicator.className = 'fixed top-4 {{ $isRtl ? "left-4" : "right-4" }} bg-green-500 text-white px-3 py-1 rounded text-sm z-50';
        saveIndicator.textContent = '{{ t("admin.draft_saved") }}';
        document.body.appendChild(saveIndicator);
        
        setTimeout(() => {
            if (saveIndicator.parentNode) {
                saveIndicator.parentNode.removeChild(saveIndicator);
            }
        }, 2000);
    }
    
    // Load draft on page load (for new entries only)
    @if(!isset($specialization))
    const savedDraft = localStorage.getItem('specialization_draft');
    if (savedDraft) {
        const draftData = JSON.parse(savedDraft);
        Object.keys(draftData).forEach(key => {
            const input = form.querySelector(`[name="${key}"]`);
            if (input) {
                if (input.type === 'radio') {
                    const radioInput = form.querySelector(`[name="${key}"][value="${draftData[key]}"]`);
                    if (radioInput) radioInput.checked = true;
                } else {
                    input.value = draftData[key];
                }
            }
        });
        
        // Show draft loaded message
        const draftMessage = document.createElement('div');
        draftMessage.className = 'fixed top-4 {{ $isRtl ? "left-4" : "right-4" }} bg-blue-500 text-white px-3 py-1 rounded text-sm z-50 flex items-center';
        draftMessage.innerHTML = `
            <svg class="w-4 h-4 {{ $isRtl ? "ml-2" : "mr-2" }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            {{ t("admin.draft_loaded") }}
            <button onclick="this.parentNode.remove()" class="{{ $isRtl ? "mr-2" : "ml-2" }} text-white hover:text-gray-200">×</button>
        `;
        document.body.appendChild(draftMessage);
    }
    @endif
    
    // Clear draft on successful submission
    form.addEventListener('submit', function() {
        localStorage.removeItem('specialization_draft');
    });
    
    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Ctrl/Cmd + S to save
        if ((e.ctrlKey || e.metaKey) && e.key === 's') {
            e.preventDefault();
            form.submit();
        }
        
        // Escape to cancel
        if (e.key === 'Escape') {
            if (confirm('{{ t("admin.confirm_cancel") }}')) {
                window.location.href = '{{ route("admin.specializations.index") }}';
            }
        }
    });
    
    // Live validation
    const brandNameInput = document.getElementById('brand_name');
    const brandNameArInput = document.getElementById('brand_name_ar');
    
    function validateInput(input, minLength = 2) {
        const value = input.value.trim();
        const isValid = value.length >= minLength;
        
        if (value.length > 0) {
            if (isValid) {
                input.classList.remove('border-red-500');
                input.classList.add('border-green-500');
            } else {
                input.classList.remove('border-green-500');
                input.classList.add('border-red-500');
            }
        } else {
            input.classList.remove('border-red-500', 'border-green-500');
        }
        
        return isValid;
    }
    
    brandNameInput.addEventListener('input', function() {
        validateInput(this);
    });
    
    brandNameArInput.addEventListener('input', function() {
        validateInput(this);
    });
    
    // Character counter for inputs
    function addCharacterCounter(input, maxLength) {
        const counter = document.createElement('div');
        counter.className = 'text-xs text-gray-500 mt-1 text-{{ $isRtl ? "left" : "right" }}';
        input.parentNode.appendChild(counter);
        
        function updateCounter() {
            const remaining = maxLength - input.value.length;
            counter.textContent = `${input.value.length}/${maxLength}`;
            
            if (remaining < 20) {
                counter.classList.add('text-orange-500');
            } else if (remaining < 10) {
                counter.classList.remove('text-orange-500');
                counter.classList.add('text-red-500');
            } else {
                counter.classList.remove('text-orange-500', 'text-red-500');
                counter.classList.add('text-gray-500');
            }
        }
        
        input.addEventListener('input', updateCounter);
        updateCounter();
    }
    
    addCharacterCounter(brandNameInput, 255);
    addCharacterCounter(brandNameArInput, 255);
});
</script>

@endsection