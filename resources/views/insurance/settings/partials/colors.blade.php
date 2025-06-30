{{-- resources/views/insurance/settings/partials/colors.blade.php --}}
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h2 class="text-xl font-bold text-gray-900">{{ t($company->translation_group . '.manage_colors') }}</h2>
        <p class="text-gray-600 text-sm">{{ t($company->translation_group . '.colors_description') }}</p>
    </div>

    <!-- Current Colors Preview -->
    <div class="bg-gray-50 rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ t($company->translation_group . '.current_colors') }}</h3>
        
        <div class="grid md:grid-cols-2 gap-6">
            <!-- Current Primary Color -->
            <div class="text-center">
                <div class="w-24 h-24 rounded-lg mx-auto mb-3 shadow-lg border" style="background: {{ $company->primary_color }};"></div>
                <div class="text-sm text-gray-600">{{ t($company->translation_group . '.primary_color') }}</div>
                <div class="font-mono text-sm font-medium">{{ $company->primary_color }}</div>
            </div>
            
            <!-- Current Secondary Color -->
            <div class="text-center">
                <div class="w-24 h-24 rounded-lg mx-auto mb-3 shadow-lg border" style="background: {{ $company->secondary_color }};"></div>
                <div class="text-sm text-gray-600">{{ t($company->translation_group . '.secondary_color') }}</div>
                <div class="font-mono text-sm font-medium">{{ $company->secondary_color }}</div>
            </div>
        </div>
        
        <!-- Gradient Preview -->
        <div class="mt-6">
            <div class="text-sm text-gray-600 mb-2">{{ t($company->translation_group . '.gradient_preview') }}</div>
            <div class="h-16 rounded-lg shadow-lg" style="background: linear-gradient(to right, {{ $company->primary_color }}, {{ $company->secondary_color }});"></div>
        </div>
    </div>

    <!-- Color Update Form -->
    <form method="POST" action="{{ route('insurance.settings.colors.update', $company->company_slug) }}" class="bg-white border border-gray-200 rounded-lg p-6">
        @csrf
        @method('PUT')
        
        <h3 class="text-lg font-medium text-gray-900 mb-6">{{ t($company->translation_group . '.update_colors') }}</h3>
        
        <div class="grid md:grid-cols-2 gap-6">
            <!-- Primary Color -->
            <div>
                <label for="primary_color" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ t($company->translation_group . '.primary_color') }} <span class="text-red-500">*</span>
                </label>
                <div class="flex items-center gap-3">
                    <input type="color" 
                           id="primary_color" 
                           name="primary_color" 
                           value="{{ old('primary_color', $company->primary_color) }}"
                           onchange="updateColorPreview()"
                           class="w-16 h-12 rounded-lg border border-gray-300 cursor-pointer">
                    <input type="text" 
                           value="{{ old('primary_color', $company->primary_color) }}"
                           oninput="document.getElementById('primary_color').value = this.value; updateColorPreview();"
                           class="flex-1 border-gray-300 rounded-lg focus:ring-2 focus:border-transparent px-4 py-2.5 font-mono text-sm"
                           style="focus:ring-color: {{ $company->primary_color }};"
                           placeholder="#10B981"
                           pattern="^#[0-9A-Fa-f]{6}$">
                </div>
                @error('primary_color')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Secondary Color -->
            <div>
                <label for="secondary_color" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ t($company->translation_group . '.secondary_color') }} <span class="text-red-500">*</span>
                </label>
                <div class="flex items-center gap-3">
                    <input type="color" 
                           id="secondary_color" 
                           name="secondary_color" 
                           value="{{ old('secondary_color', $company->secondary_color) }}"
                           onchange="updateColorPreview()"
                           class="w-16 h-12 rounded-lg border border-gray-300 cursor-pointer">
                    <input type="text" 
                           value="{{ old('secondary_color', $company->secondary_color) }}"
                           oninput="document.getElementById('secondary_color').value = this.value; updateColorPreview();"
                           class="flex-1 border-gray-300 rounded-lg focus:ring-2 focus:border-transparent px-4 py-2.5 font-mono text-sm"
                           style="focus:ring-color: {{ $company->primary_color }};"
                           placeholder="#059669"
                           pattern="^#[0-9A-Fa-f]{6}$">
                </div>
                @error('secondary_color')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <!-- Live Preview -->
        <div class="mt-6">
            <h4 class="text-sm font-medium text-gray-700 mb-3">{{ t($company->translation_group . '.live_preview') }}</h4>
            <div class="space-y-4">
                <!-- Button Preview -->
                <div class="flex items-center gap-4">
                    <div id="colorPreview" class="px-6 py-3 text-white rounded-lg font-medium shadow-lg transition-all duration-300"
                         style="background: linear-gradient(to right, {{ $company->primary_color }}, {{ $company->secondary_color }});">
                        {{ t($company->translation_group . '.sample_button') }}
                    </div>
                    <div class="text-sm text-gray-600">{{ t($company->translation_group . '.button_preview') }}</div>
                </div>
                
                <!-- Card Preview -->
                <div class="border rounded-lg p-4 max-w-sm">
                    <div class="flex items-center gap-3 mb-3">
                        <div id="iconPreview" class="w-10 h-10 rounded-lg flex items-center justify-center"
                             style="background: {{ $company->primary_color }}20;">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: {{ $company->primary_color }};">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <div>
                            <h5 class="font-medium text-gray-900">{{ t($company->translation_group . '.sample_card') }}</h5>
                            <p class="text-sm text-gray-600">{{ t($company->translation_group . '.card_description') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Color Suggestions -->
        <div class="mt-6">
            <h4 class="text-sm font-medium text-gray-700 mb-3">{{ t($company->translation_group . '.color_suggestions') }}</h4>
            <div class="grid grid-cols-3 sm:grid-cols-6 gap-2">
                @php
                $colorPairs = [
                    ['#3B82F6', '#1D4ED8'], // Blue
                    ['#10B981', '#059669'], // Green
                    ['#8B5CF6', '#7C3AED'], // Purple
                    ['#F59E0B', '#D97706'], // Orange
                    ['#EF4444', '#DC2626'], // Red
                    ['#6B7280', '#4B5563'], // Gray
                ];
                @endphp
                @foreach($colorPairs as $pair)
                <button type="button" 
                        onclick="setPredefinedColors('{{ $pair[0] }}', '{{ $pair[1] }}')"
                        class="h-12 rounded-lg border-2 border-gray-200 hover:border-gray-400 transition-colors"
                        style="background: linear-gradient(to right, {{ $pair[0] }}, {{ $pair[1] }});"
                        title="{{ $pair[0] }} → {{ $pair[1] }}">
                </button>
                @endforeach
            </div>
        </div>
        
        <!-- Submit Button -->
        <div class="flex justify-end mt-8">
            <button type="submit" 
                    class="px-6 py-3 text-white rounded-lg font-medium hover:opacity-90 transition-opacity"
                    style="background: {{ $company->primary_color }};">
                {{ t($company->translation_group . '.save_colors') }}
            </button>
        </div>
    </form>
</div>

<script>
// Set predefined color combinations
function setPredefinedColors(primary, secondary) {
    document.getElementById('primary_color').value = primary;
    document.getElementById('secondary_color').value = secondary;
    
    // Update text inputs
    document.querySelector('input[oninput*="primary_color"]').value = primary;
    document.querySelector('input[oninput*="secondary_color"]').value = secondary;
    
    updateColorPreview();
}

// Update live preview
function updateColorPreview() {
    const primaryColor = document.getElementById('primary_color').value;
    const secondaryColor = document.getElementById('secondary_color').value;
    const preview = document.getElementById('colorPreview');
    const iconPreview = document.getElementById('iconPreview');
    
    preview.style.background = `linear-gradient(to right, ${primaryColor}, ${secondaryColor})`;
    iconPreview.style.background = `${primaryColor}20`;
    iconPreview.querySelector('svg').style.color = primaryColor;
    
    // Update text inputs
    document.querySelector('input[oninput*="primary_color"]').value = primaryColor;
    document.querySelector('input[oninput*="secondary_color"]').value = secondaryColor;
}
</script>