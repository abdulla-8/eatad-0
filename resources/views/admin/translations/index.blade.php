@extends('admin.layouts.app')

@section('title', t('admin.translation_management', 'Translation Management'))

@section('content')

<!-- Page Header -->
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
    <div>
        <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-2">{{ t('admin.translation_management', 'Translation Management') }}</h1>
        <nav class="flex text-sm">
            <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-gold-600">{{ t('admin.dashboard', 'Dashboard') }}</a>
            <span class="mx-2 text-gray-400">></span>
            <span class="text-gold-600 font-medium">{{ t('admin.translations', 'Translations') }}</span>
        </nav>
    </div>
    <button onclick="toggleAddForm()" class="bg-gold-500 hover:bg-gold-600 text-dark-900 px-4 py-2 rounded-lg font-medium transition-colors mt-4 sm:mt-0">
        {{ t('admin.add_translation', 'Add Translation') }}
    </button>
</div>

<!-- Filters -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Language Filter -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">{{ t('admin.languages', 'Languages') }}</label>
            <select name="language_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gold-500 focus:border-gold-500">
                @foreach($languages as $language)
                    <option value="{{ $language->id }}" {{ request('language_id') == $language->id || (!request('language_id') && $language->id == $currentLanguage->id) ? 'selected' : '' }}>
                        {{ $language->name }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <!-- Group Filter -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">{{ t('admin.group', 'Group') }}</label>
            <select name="group" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gold-500 focus:border-gold-500">
                <option value="">{{ t('admin.all_groups', 'All Groups') }}</option>
                @foreach($groups as $group)
                    <option value="{{ $group }}" {{ request('group') == $group ? 'selected' : '' }}>{{ ucfirst($group) }}</option>
                @endforeach
            </select>
        </div>
        
        <!-- Search -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">{{ t('admin.search', 'Search') }}</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ t('admin.search_translations', 'Search translations...') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gold-500 focus:border-gold-500">
        </div>
        
        <!-- Actions -->
        <div class="flex items-end space-x-2">
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors me-2">
                {{ t('admin.filter', 'Filter') }}
            </button>
            <a href="{{ route('admin.translations.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                {{ t('admin.reset', 'Reset') }}
            </a>
        </div>
    </form>
</div>

<!-- Add Translation Form (Hidden) -->
<div id="addTranslationForm" class="hidden bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
    <h3 class="text-lg font-bold text-gray-900 mb-4">{{ t('admin.add_translation', 'Add Translation') }}</h3>
    <form method="POST" action="{{ route('admin.translations.store') }}">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">{{ t('admin.languages', 'Languages') }}</label>
                <select name="language_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gold-500 focus:border-gold-500">
                    @foreach($languages as $language)
                        <option value="{{ $language->id }}" {{ $language->id == $currentLanguage->id ? 'selected' : '' }}>{{ $language->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">{{ t('admin.group', 'Group') }}</label>
                <input type="text" name="group" required placeholder="admin" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gold-500 focus:border-gold-500">
                <p class="text-xs text-gray-500 mt-1">{{ t('admin.group_example', 'Example: admin, general, messages') }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">{{ t('admin.key', 'Key') }}</label>
                <input type="text" name="key" required placeholder="welcome_message" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gold-500 focus:border-gold-500">
                <p class="text-xs text-gray-500 mt-1">{{ t('admin.key_description', 'Without dots, will be combined with group') }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">{{ t('admin.value', 'Value') }}</label>
                <input type="text" name="value" required placeholder="{{ t('admin.value_placeholder', 'Translation value') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gold-500 focus:border-gold-500">
            </div>
        </div>
        <div class="flex justify-end space-x-2 mt-4">
            <button type="button" onclick="toggleAddForm()" class="bg-gray-500 hover:bg-gray-600 me-2 text-white px-4 py-2 rounded-lg transition-colors">
                {{ t('admin.cancel', 'Cancel') }}
            </button>
            <button type="submit" class="bg-gold-500 hover:bg-gold-600 text-dark-900 px-4 py-2 rounded-lg font-medium transition-colors">
                {{ t('admin.add', 'Add') }}
            </button>
        </div>
    </form>
</div>

<!-- Translations Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="bg-gold-500 text-dark-900 px-6 py-4">
        <h2 class="text-lg font-bold flex items-center justify-between">
            <span class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path>
                </svg>
                {{ t('admin.translations', 'Translations') }} - {{ $currentLanguage->name }}
            </span>
            <span class="text-sm">{{ $translations->total() }} {{ t('admin.total', 'Total') }}</span>
        </h2>
    </div>
    
    @if($translations->count() > 0)
        <!-- Desktop Table -->
        <div class="hidden md:block">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class=" py-3  text-xs font-semibold text-gray-600 uppercase">{{ t('admin.group', 'Group') }}</th>
                        <th class=" py-3  text-xs font-semibold text-gray-600 uppercase">{{ t('admin.key', 'Key') }}</th>
                        <th class=" py-3  text-xs font-semibold text-gray-600 uppercase">{{ t('admin.value', 'Value') }}</th>
                        <th class=" py-3  text-xs font-semibold text-gray-600 uppercase">{{ t('admin.actions', 'Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($translations as $translation)
                        <tr class="hover:bg-gray-50" id="row-{{ $translation->id }}">
                            <td class=" py-4">
                                <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full font-medium">{{ $translation->group }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <code class="bg-gray-100 text-gray-800 text-sm px-2 py-1 rounded">{{ $translation->key }}</code>
                            </td>
                            <td class="px-6 py-4">
                                <div id="value-display-{{ $translation->id }}">
                                    <span class="text-gray-900">{{ Str::limit($translation->translation_value, 100) }}</span>
                                </div>
                                <div id="value-edit-{{ $translation->id }}" class="hidden">
                                    <form method="POST" action="{{ route('admin.translations.update', $translation) }}" class="flex space-x-2">
                                        @csrf
                                        @method('PUT')
                                        <input type="text" name="translation_value" value="{{ $translation->translation_value }}" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gold-500 focus:border-gold-500">
                                        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-3 py-2 rounded-lg transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </button>
                                        <button type="button" onclick="cancelEdit({{ $translation->id }})" class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-2 rounded-lg transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <button onclick="editTranslation({{ $translation->id }})" class="p-2 me-2 bg-blue-100 text-blue-600 hover:bg-blue-200 rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>
                                    <form method="POST" action="{{ route('admin.translations.destroy', $translation) }}" class="inline" onsubmit="return confirm('{{ t('admin.confirm_delete', 'Are you sure you want to delete this translation?') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 bg-red-100 text-red-600 hover:bg-red-200 rounded-lg transition-colors">
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

        <!-- Mobile Cards -->
        <div class="md:hidden space-y-4 p-4">
            @foreach($translations as $translation)
                <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full font-medium">{{ $translation->group }}</span>
                            <code class="bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded ml-2">{{ $translation->key }}</code>
                        </div>
                    </div>
                    <div class="mb-3">
                        <p class="text-gray-900 text-sm">{{ $translation->translation_value }}</p>
                    </div>
                    <div class="flex items-center justify-center space-x-3 pt-3 border-t border-gray-200">
                        <button onclick="editTranslation({{ $translation->id }})" class="flex  items-center px-3 py-2 bg-blue-100 text-blue-700 hover:bg-blue-200 rounded-lg text-sm font-medium transition-colors">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            {{ t('admin.edit', 'Edit') }}
                        </button>
                        <form method="POST" action="{{ route('admin.translations.destroy', $translation) }}" class="inline" onsubmit="return confirm('{{ t('admin.confirm_delete', 'Are you sure you want to delete this translation?') }}')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="flex items-center px-3 py-2 bg-red-100 text-red-700 hover:bg-red-200 rounded-lg text-sm font-medium transition-colors">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                {{ t('admin.delete', 'Delete') }}
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $translations->appends(request()->query())->links() }}
        </div>
    @else
        <div class="p-12 text-center">
            <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path>
            </svg>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ t('admin.no_translations', 'No Translations Found') }}</h3>
            <p class="text-gray-600">{{ t('admin.no_translations_desc', 'Start by adding translations to the system') }}</p>
        </div>
    @endif
</div>

<script>
function toggleAddForm() {
    const form = document.getElementById('addTranslationForm');
    form.classList.toggle('hidden');
}

function editTranslation(id) {
    document.getElementById('value-display-' + id).classList.add('hidden');
    document.getElementById('value-edit-' + id).classList.remove('hidden');
}

function cancelEdit(id) {
    document.getElementById('value-display-' + id).classList.remove('hidden');
    document.getElementById('value-edit-' + id).classList.add('hidden');
}
</script>

@endsection