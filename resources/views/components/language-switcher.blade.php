@props(['class' => 'bg-white border border-gray-200 text-gray-700 hover:bg-gray-50'])

@php $uniqueId = uniqid(); @endphp

<div class="relative" style="z-index: 9999;">
    <button type="button" 
            onclick="toggleLanguageDropdown{{ $uniqueId }}()"
            class="flex items-center px-3 py-2 {{ $class }} rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-gold-500 focus:ring-opacity-50"
            id="languageButton{{ $uniqueId }}">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path>
        </svg>
        <span class="text-xs font-medium">{{ $currentLanguage->name ?? t('admin.language', 'Language') }}</span>
        <svg class="w-4 h-4 ml-2 transition-transform duration-200" 
             id="languageArrow{{ $uniqueId }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>
    
    <div id="languageDropdown{{ $uniqueId }}" 
         class="absolute right-0 mt-2 w-44 bg-white rounded-lg shadow-lg border border-gray-200 py-1 opacity-0 invisible transform scale-95 transition-all duration-200 origin-top-right"
         style="z-index: 10000;">
        @foreach($activeLanguages as $language)
            <a href="{{ route('language.change', $language->code) }}" 
               class="flex items-center px-4 py-2 text-xs hover:bg-gray-50 transition-colors duration-150 {{ app()->getLocale() == $language->code ? 'bg-gold-50 text-gold-800' : 'text-gray-700' }}">
                <div class="w-5 h-5 rounded bg-gold-500 flex items-center justify-center mr-2 text-white text-xs font-bold">
                    {{ strtoupper($language->code) }}
                </div>
                <div class="flex-1">
                    <div class="font-medium">{{ $language->name }}</div>
                    <div class="text-xs text-gray-500 flex items-center">
                        <span class="w-2 h-2 rounded-full {{ $language->direction == 'rtl' ? 'bg-orange-400' : 'bg-blue-400' }} mr-1"></span>
                        {{ strtoupper($language->direction) }}
                        @if($language->is_default)
                            <span class="text-gold-600 ml-2">• {{ t('admin.default', 'Default') }}</span>
                        @endif
                    </div>
                </div>
                @if(app()->getLocale() == $language->code)
                    <svg class="w-4 h-4 text-gold-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                @endif
            </a>
        @endforeach
    </div>
</div>

<script>
function toggleLanguageDropdown{{ $uniqueId }}() {
    const dropdown = document.getElementById('languageDropdown{{ $uniqueId }}');
    const arrow = document.getElementById('languageArrow{{ $uniqueId }}');
    
    if (dropdown && dropdown.classList.contains('opacity-0')) {
        dropdown.classList.remove('opacity-0', 'invisible', 'scale-95');
        dropdown.classList.add('opacity-100', 'visible', 'scale-100');
        if (arrow) arrow.style.transform = 'rotate(180deg)';
    } else if (dropdown) {
        dropdown.classList.add('opacity-0', 'invisible', 'scale-95');
        dropdown.classList.remove('opacity-100', 'visible', 'scale-100');
        if (arrow) arrow.style.transform = 'rotate(0deg)';
    }
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    const button = document.getElementById('languageButton{{ $uniqueId }}');
    const dropdown = document.getElementById('languageDropdown{{ $uniqueId }}');
    const arrow = document.getElementById('languageArrow{{ $uniqueId }}');
    
    if (button && dropdown && !button.contains(event.target) && !dropdown.contains(event.target)) {
        dropdown.classList.add('opacity-0', 'invisible', 'scale-95');
        dropdown.classList.remove('opacity-100', 'visible', 'scale-100');
        if (arrow) arrow.style.transform = 'rotate(0deg)';
    }
});
</script>