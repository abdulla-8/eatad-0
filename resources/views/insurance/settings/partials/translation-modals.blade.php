<!-- Add Translation Modal -->
<div id="addTranslationModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4 modal-overlay">
    <div class="bg-white rounded-xl max-w-md w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <!-- Modal Header -->
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-gray-900">
                    {{ t($company->translation_group . '.add_translation') }}
                </h3>
                <button type="button" onclick="closeModal('addTranslationModal')" 
                        class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Add Translation Form -->
            <form method="POST" action="{{ route('insurance.settings.translations.store', $company->company_slug) }}">
                @csrf
                
                <!-- Language Selection -->
                <div class="mb-4">
                    <label for="language_id" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ t($company->translation_group . '.language') }} <span class="text-red-500">*</span>
                    </label>
                    <select name="language_id" id="language_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @foreach($languages as $language)
                            <option value="{{ $language->id }}" {{ $language->id == $currentLanguage->id ? 'selected' : '' }}>
                                {{ $language->name }} ({{ $language->code }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Translation Key -->
                <div class="mb-4">
                    <label for="key" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ t($company->translation_group . '.translation_key') }} <span class="text-red-500">*</span>
                    </label>
                    <div class="flex">
                        <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                            {{ $company->translation_group }}.
                        </span>
                        <input type="text" name="key" id="key" required
                               class="flex-1 px-3 py-2 border border-gray-300 rounded-r-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="{{ t($company->translation_group . '.enter_key') }}">
                    </div>
                    <p class="text-xs text-gray-500 mt-1">
                        {{ t($company->translation_group . '.key_example') }}
                    </p>
                </div>

                <!-- Translation Value -->
                <div class="mb-6">
                    <label for="value" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ t($company->translation_group . '.translation_value') }} <span class="text-red-500">*</span>
                    </label>
                    <textarea name="value" id="value" rows="3" required
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="{{ t($company->translation_group . '.enter_value') }}"></textarea>
                </div>

                <!-- Modal Actions -->
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeModal('addTranslationModal')"
                            class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        {{ t($company->translation_group . '.cancel') }}
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 text-white rounded-lg hover:opacity-90 transition-opacity"
                            style="background: {{ $company->primary_color }};">
                        {{ t($company->translation_group . '.add_translation') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Translation Modal -->
<div id="editTranslationModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4 modal-overlay">
    <div class="bg-white rounded-xl max-w-md w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <!-- Modal Header -->
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-gray-900">
                    {{ t($company->translation_group . '.edit_translation') }}
                </h3>
                <button type="button" onclick="closeModal('editTranslationModal')" 
                        class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Edit Translation Form -->
            <form method="POST" action="{{ route('insurance.settings.translations.update', [$company->company_slug, 0]) }}" id="editTranslationForm">
                @csrf
                @method('PUT')
                
                <!-- Translation Key (Read Only) -->
                <div class="mb-4">
                    <label for="editTranslationKey" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ t($company->translation_group . '.translation_key') }}
                    </label>
                    <div class="flex">
                        <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                            {{ $company->translation_group }}.
                        </span>
                        <input type="text" id="editTranslationKey" readonly
                               class="flex-1 px-3 py-2 border border-gray-300 rounded-r-lg bg-gray-50 text-gray-500 cursor-not-allowed">
                    </div>
                </div>

                <!-- Translation Value -->
                <div class="mb-6">
                    <label for="editTranslationValue" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ t($company->translation_group . '.translation_value') }} <span class="text-red-500">*</span>
                    </label>
                    <textarea name="translation_value" id="editTranslationValue" rows="3" required
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="{{ t($company->translation_group . '.enter_value') }}"></textarea>
                </div>

                <!-- Modal Actions -->
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeModal('editTranslationModal')"
                            class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        {{ t($company->translation_group . '.cancel') }}
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 text-white rounded-lg hover:opacity-90 transition-opacity"
                            style="background: {{ $company->primary_color }};">
                        {{ t($company->translation_group . '.update_translation') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>