@extends('insurance.layouts.app')

@section('title', t($company->translation_group . '.settings'))

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ t($company->translation_group . '.settings') }}</h1>
            <p class="text-gray-600 mt-1">{{ t($company->translation_group . '.manage_company_settings') }}</p>
        </div>
        
        <!-- Company Info -->
        <div class="flex items-center gap-3">
            @if($company->company_logo)
                <img src="{{ $company->logo_url }}" alt="{{ $company->legal_name }}" class="w-10 h-10 object-contain rounded-lg border">
            @else
                <div class="w-10 h-10 rounded-lg flex items-center justify-center border" style="background: {{ $company->primary_color }}20;">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: {{ $company->primary_color }};">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
            @endif
            <div>
                <h3 class="font-bold text-gray-900">{{ $company->legal_name }}</h3>
                <p class="text-sm text-gray-600">{{ $company->company_slug }}</p>
            </div>
        </div>
    </div>

    <!-- Settings Tabs -->
    <div class="bg-white rounded-xl shadow-sm border">
        <!-- Tab Navigation -->
        <div class="border-b border-gray-200">
            <nav class="flex space-x-8 px-6" aria-label="Tabs">
                <a href="{{ route('insurance.settings.index', [$company->company_slug, 'tab' => 'translations']) }}" 
                   class="py-4 px-1 border-b-2 font-medium text-sm transition-colors {{ $activeTab === 'translations' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path>
                        </svg>
                        {{ t($company->translation_group . '.translations') }}
                    </div>
                </a>
                
                <a href="{{ route('insurance.settings.index', [$company->company_slug, 'tab' => 'colors']) }}" 
                   class="py-4 px-1 border-b-2 font-medium text-sm transition-colors {{ $activeTab === 'colors' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM21 5a2 2 0 00-2-2h-4a2 2 0 00-2 2v12a4 4 0 004 4h4a2 2 0 002-2V5z"></path>
                        </svg>
                        {{ t($company->translation_group . '.colors') }}
                    </div>
                </a>
                
                <a href="{{ route('insurance.settings.index', [$company->company_slug, 'tab' => 'logo']) }}" 
                   class="py-4 px-1 border-b-2 font-medium text-sm transition-colors {{ $activeTab === 'logo' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        {{ t($company->translation_group . '.logo') }}
                    </div>
                </a>
            </nav>
        </div>

        <!-- Tab Content -->
        <div class="p-6">
            @if($activeTab === 'translations')
                @include('insurance.settings.partials.translations')
            @elseif($activeTab === 'colors')
                @include('insurance.settings.partials.colors')
            @elseif($activeTab === 'logo')
                @include('insurance.settings.partials.logo')
            @endif
        </div>
    </div>
</div>

@if($activeTab === 'translations')
    @include('insurance.settings.partials.translation-modals')
@endif
@endsection

@push('scripts')
@if($activeTab === 'translations')
<script>
// Translation management functions
function openAddTranslationModal() {
    document.getElementById('addTranslationModal').classList.remove('hidden');
}

function openEditTranslationModal(id, key, value) {
    const modal = document.getElementById('editTranslationModal');
    const form = document.getElementById('editTranslationForm');
    const keyInput = document.getElementById('editTranslationKey');
    const valueInput = document.getElementById('editTranslationValue');

     
    console.log('Generated URL:', form.action);
    console.log('Translation ID:', id);
    console.log('Company Slug:', '{{ $company->company_slug }}');
    

    const baseUrl = "{{ route('insurance.settings.translations.update', [$company->company_slug, '__ID__']) }}";
    form.action = baseUrl.replace('__ID__', id);
    
    keyInput.value = key;
    valueInput.value = value;
    
   
    // إظهار الـ modal
    modal.classList.remove('hidden');
}


function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

// Close modals when clicking outside
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('modal-overlay')) {
        e.target.classList.add('hidden');
    }
});
</script>
@endif

@if($activeTab === 'colors')
<script>
// Color picker preview
function updateColorPreview() {
    const primaryColor = document.getElementById('primary_color').value;
    const secondaryColor = document.getElementById('secondary_color').value;
    const preview = document.getElementById('colorPreview');
    
    preview.style.background = `linear-gradient(to right, ${primaryColor}, ${secondaryColor})`;
}

// Initialize color preview
document.addEventListener('DOMContentLoaded', function() {
    updateColorPreview();
});
</script>
@endif
@endpush