<div class="space-y-6">
    <!-- Header -->
    <div>
        <h2 class="text-xl font-bold text-gray-900">{{ t($company->translation_group . '.manage_logo') }}</h2>
        <p class="text-gray-600 text-sm">{{ t($company->translation_group . '.logo_description') }}</p>
    </div>

    <!-- Current Logo Display -->
    <div class="bg-gray-50 rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ t($company->translation_group . '.current_logo') }}</h3>
        
        @if($company->company_logo)
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-6">
                    <!-- Logo Preview - Different Sizes -->
                    <div class="space-y-4">
                        <div class="text-center">
                            <div class="w-24 h-24 bg-white rounded-xl p-3 border shadow-lg mb-2">
                                <img src="{{ $company->logo_url }}" alt="{{ $company->legal_name }}" class="w-full h-full object-contain">
                            </div>
                            <div class="text-xs text-gray-600">{{ t($company->translation_group . '.large_size') }}</div>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="text-center">
                            <div class="w-16 h-16 bg-white rounded-lg p-2 border shadow-md mb-2">
                                <img src="{{ $company->logo_url }}" alt="{{ $company->legal_name }}" class="w-full h-full object-contain">
                            </div>
                            <div class="text-xs text-gray-600">{{ t($company->translation_group . '.medium_size') }}</div>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="text-center">
                            <div class="w-10 h-10 bg-white rounded-lg p-1 border shadow-sm mb-2">
                                <img src="{{ $company->logo_url }}" alt="{{ $company->legal_name }}" class="w-full h-full object-contain">
                            </div>
                            <div class="text-xs text-gray-600">{{ t($company->translation_group . '.small_size') }}</div>
                        </div>
                    </div>
                </div>
                
                <!-- Delete Logo Button -->
                <form method="POST" action="{{ route('insurance.settings.logo.delete', $company->company_slug) }}" 
                      class="inline" onsubmit="return confirm('{{ t($company->translation_group . '.confirm_delete_logo') }}')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="px-4 py-2 bg-red-500 text-white rounded-lg font-medium hover:bg-red-600 transition-colors">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        {{ t($company->translation_group . '.delete_logo') }}
                    </button>
                </form>
            </div>
            
            <!-- Logo on Different Backgrounds -->
            <div class="mt-6">
                <h4 class="text-sm font-medium text-gray-700 mb-3">{{ t($company->translation_group . '.logo_on_backgrounds') }}</h4>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <!-- White Background -->
                    <div class="text-center">
                        <div class="bg-white border rounded-lg p-4 mb-2">
                            <img src="{{ $company->logo_url }}" alt="{{ $company->legal_name }}" class="w-12 h-12 mx-auto object-contain">
                        </div>
                        <div class="text-xs text-gray-600">{{ t($company->translation_group . '.white_background') }}</div>
                    </div>
                    
                    <!-- Gray Background -->
                    <div class="text-center">
                        <div class="bg-gray-100 border rounded-lg p-4 mb-2">
                            <img src="{{ $company->logo_url }}" alt="{{ $company->legal_name }}" class="w-12 h-12 mx-auto object-contain">
                        </div>
                        <div class="text-xs text-gray-600">{{ t($company->translation_group . '.gray_background') }}</div>
                    </div>
                    
                    <!-- Primary Color Background -->
                    <div class="text-center">
                        <div class="border rounded-lg p-4 mb-2" style="background: {{ $company->primary_color }};">
                            <img src="{{ $company->logo_url }}" alt="{{ $company->legal_name }}" class="w-12 h-12 mx-auto object-contain">
                        </div>
                        <div class="text-xs text-gray-600">{{ t($company->translation_group . '.primary_background') }}</div>
                    </div>
                    
                    <!-- Dark Background -->
                    <div class="text-center">
                        <div class="bg-gray-900 border rounded-lg p-4 mb-2">
                            <img src="{{ $company->logo_url }}" alt="{{ $company->legal_name }}" class="w-12 h-12 mx-auto object-contain">
                        </div>
                        <div class="text-xs text-gray-600">{{ t($company->translation_group . '.dark_background') }}</div>
                    </div>
                </div>
            </div>
        @else
            <!-- No Logo State -->
            <div class="text-center py-8">
                <div class="w-24 h-24 bg-gray-200 rounded-xl mx-auto mb-4 flex items-center justify-center">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">{{ t($company->translation_group . '.no_logo_uploaded') }}</h3>
                <p class="text-gray-600">{{ t($company->translation_group . '.upload_logo_description') }}</p>
            </div>
        @endif
    </div>

    <!-- Upload Logo Form -->
    <form method="POST" action="{{ route('insurance.settings.logo.update', $company->company_slug) }}" 
          enctype="multipart/form-data" class="bg-white border border-gray-200 rounded-lg p-6">
        @csrf
        @method('PUT')
        
        <h3 class="text-lg font-medium text-gray-900 mb-6">
            {{ $company->company_logo ? t($company->translation_group . '.update_logo') : t($company->translation_group . '.upload_logo') }}
        </h3>
        
        <!-- File Upload Area -->
        <div class="mb-6">
            <label for="company_logo" class="block text-sm font-medium text-gray-700 mb-2">
                {{ t($company->translation_group . '.logo_file') }} <span class="text-red-500">*</span>
            </label>
            
            <div class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 transition-colors">
                <div class="space-y-2 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <div class="text-sm text-gray-600">
                        <label for="company_logo" class="relative cursor-pointer rounded-md font-medium hover:text-blue-500"
                               style="color: {{ $company->primary_color }};">
                            <span>{{ t($company->translation_group . '.upload_file') }}</span>
                            <input id="company_logo" name="company_logo" type="file" accept="image/*" class="sr-only" onchange="previewImage(this)">
                        </label>
                        <p class="pl-1">{{ t($company->translation_group . '.or_drag_drop') }}</p>
                    </div>
                    <p class="text-xs text-gray-500">{{ t($company->translation_group . '.logo_requirements') }}</p>
                </div>
            </div>
            
            @error('company_logo')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <!-- Image Preview -->
        <div id="imagePreview" class="hidden mb-6">
            <h4 class="text-sm font-medium text-gray-700 mb-3">{{ t($company->translation_group . '.preview') }}</h4>
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-gray-100 rounded-lg border overflow-hidden">
                    <img id="previewImg" class="w-full h-full object-contain" alt="Preview">
                </div>
                <div>
                    <div id="fileName" class="text-sm font-medium text-gray-900"></div>
                    <div id="fileSize" class="text-xs text-gray-500"></div>
                </div>
            </div>
        </div>
        
        <!-- Logo Guidelines -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <h4 class="font-medium text-blue-800 mb-1">{{ t($company->translation_group . '.logo_guidelines') }}</h4>
                    <ul class="text-sm text-blue-700 space-y-1">
                        <li>• {{ t($company->translation_group . '.logo_guideline_1') }}</li>
                        <li>• {{ t($company->translation_group . '.logo_guideline_2') }}</li>
                        <li>• {{ t($company->translation_group . '.logo_guideline_3') }}</li>
                        <li>• {{ t($company->translation_group . '.logo_guideline_4') }}</li>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Submit Button -->
        <div class="flex justify-end">
            <button type="submit" 
                    class="px-6 py-3 text-white rounded-lg font-medium hover:opacity-90 transition-opacity"
                    style="background: {{ $company->primary_color }};">
                {{ $company->company_logo ? t($company->translation_group . '.update_logo') : t($company->translation_group . '.upload_logo') }}
            </button>
        </div>
    </form>
</div>

<script>
// Image preview functionality
function previewImage(input) {
    const preview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    const fileName = document.getElementById('fileName');
    const fileSize = document.getElementById('fileSize');
    
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const reader = new FileReader();
        
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            fileName.textContent = file.name;
            fileSize.textContent = formatFileSize(file.size);
            preview.classList.remove('hidden');
        };
        
        reader.readAsDataURL(file);
    } else {
        preview.classList.add('hidden');
    }
}

// Format file size
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}
</script>