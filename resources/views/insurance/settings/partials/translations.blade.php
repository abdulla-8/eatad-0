<div class="space-y-6">
    <!-- Header and Controls -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-900">{{ t($company->translation_group . '.manage_translations') }}</h2>
            <p class="text-gray-600 text-sm">{{ t($company->translation_group . '.translations_description') }}</p>
        </div>
        
        <button onclick="openAddTranslationModal()" 
                class="inline-flex items-center gap-2 px-4 py-2.5 text-white rounded-lg font-medium hover:opacity-90 transition-opacity"
                style="background: {{ $company->primary_color }};">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            {{ t($company->translation_group . '.add_translation') }}
        </button>
    </div>

    <!-- Language Selector and Search -->
    <div class="bg-gray-50 rounded-lg p-4">
        <form method="GET" class="flex flex-col lg:flex-row gap-4">
            <input type="hidden" name="tab" value="translations">
            
            <div class="lg:w-48">
                <label class="block text-sm font-medium text-gray-700 mb-2">{{ t($company->translation_group . '.language') }}</label>
              <select name="language_id" 
        class="w-full border-gray-300 rounded-lg focus:ring-2 focus:border-transparent px-4 py-2.5"
        style="focus:ring-color: {{ $company->primary_color }};">
    @foreach($languages as $language)
        <option value="{{ $language->id }}" {{ $currentLanguage->id == $language->id ? 'selected' : '' }}>
            {{ $language->name }}
        </option>
    @endforeach
</select>

            </div>
            
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">{{ t($company->translation_group . '.search') }}</label>
                <div class="flex gap-2">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="{{ t($company->translation_group . '.search_translations') }}"
                           class="flex-1 border-gray-300 rounded-lg focus:ring-2 focus:border-transparent px-4 py-2.5"
                           style="focus:ring-color: {{ $company->primary_color }};">
                    
               <button type="submit" 
        class="px-6 py-2.5 text-white rounded-lg font-medium hover:opacity-90 transition-opacity"
        style="background: {{ $company->primary_color }};">
    {{ t($company->translation_group . '.filter') }}
</button>

                    
               @if(request('search') || request('language_id'))
    <a href="{{ route('insurance.settings.index', [$company->company_slug, 'tab' => 'translations']) }}" 
       class="px-6 py-2.5 bg-gray-500 text-white rounded-lg font-medium hover:opacity-90 transition-opacity">
        {{ t($company->translation_group . '.clear') }}
    </a>
@endif

                </div>
            </div>
        </form>
    </div>

    <!-- Translations Table -->
    @if(isset($translations) && $translations->count())
        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ t($company->translation_group . '.key') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ t($company->translation_group . '.value') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ t($company->translation_group . '.language') }}
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ t($company->translation_group . '.actions') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($translations as $translation)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $translation->display_key }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 max-w-md truncate" title="{{ $translation->translation_value }}">
                                    {{ Str::limit($translation->translation_value, 60) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $currentLanguage->name }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    <button onclick="openEditTranslationModal({{ $translation->id }}, '{{ $translation->display_key }}', '{{ addslashes($translation->translation_value) }}')"
                                            class="text-blue-600 hover:text-blue-900 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>
                                    
                                    <form method="POST" action="{{ route('insurance.settings.translations.delete', [$company->company_slug, $translation->id]) }}" 
                                          class="inline" onsubmit="return confirm('{{ t($company->translation_group . '.confirm_delete') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Pagination -->
        <div class="flex justify-center">
            {{ $translations->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="text-center py-12">
            <div class="w-16 h-16 mx-auto mb-4 rounded-full flex items-center justify-center"
                 style="background: {{ $company->primary_color }}20;">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: {{ $company->primary_color }};">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">{{ t($company->translation_group . '.no_translations_found') }}</h3>
            <p class="text-gray-600 mb-4">{{ t($company->translation_group . '.start_adding_translations') }}</p>
            <button onclick="openAddTranslationModal()" 
                    class="inline-flex items-center gap-2 px-4 py-2.5 text-white rounded-lg font-medium hover:opacity-90 transition-opacity"
                    style="background: {{ $company->primary_color }};">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                {{ t($company->translation_group . '.add_first_translation') }}
            </button>
        </div>
    @endif
</div>