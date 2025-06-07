@extends('admin.layouts.app')

@section('title', __('admin.translation_management'))

@section('content')

<!-- Page Header -->
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
    <div>
        <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-2">{{ __('admin.translation_management') }}</h1>
        <nav class="flex text-sm">
            <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-gold-600">{{ __('admin.dashboard') }}</a>
            <span class="mx-2 text-gray-400">></span>
            <span class="text-gold-600 font-medium">{{ __('admin.translations') }}</span>
        </nav>
    </div>
    <button onclick="toggleAddForm()" class="bg-gold-500 hover:bg-gold-600 text-dark-900 px-4 py-2 rounded-lg font-medium transition-colors mt-4 sm:mt-0">
        {{ __('admin.add_translation') }}
    </button>
</div>

<!-- Filters -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Language Filter -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.languages') }}</label>
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
            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.translation_group') }}</label>
            <select name="group" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gold-500 focus:border-gold-500">
                <option value="">{{ __('admin.all') }}</option>
                @foreach($groups as $group)
                    <option value="{{ $group }}" {{ request('group') == $group ? 'selected' : '' }}>{{ $group }}</option>
                @endforeach
            </select>
        </div>
        
        <!-- Search -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.search_translations') }}</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('admin.search') }}..." class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gold-500 focus:border-gold-500">
        </div>
        
        <!-- Actions -->
        <div class="flex items-end space-x-2 {{ $isRtl ? 'space-x-reverse' : '' }}">
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors">
                {{ __('admin.filter') }}
            </button>
            <a href="{{ route('admin.translations.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                {{ __('admin.reset') }}
            </a>
        </div>
    </form>
</div>

<!-- Add Translation Form (Hidden) -->
<div id="addTranslationForm" class="hidden bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
    <h3 class="text-lg font-bold text-gray-900 mb-4">{{ __('admin.add_translation') }}</h3>
    <form method="POST" action="{{ route('admin.translations.store') }}">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.languages') }}</label>
                <select name="language_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gold-500 focus:border-gold-500">
                    @foreach($languages as $language)
                        <option value="{{ $language->id }}" {{ $language->id == $currentLanguage->id ? 'selected' : '' }}>{{ $language->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.translation_group') }}</label>
                <input type="text" name="group" required placeholder="admin" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gold-500 focus:border-gold-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.translation_key') }}</label>
                <input type="text" name="key" required placeholder="welcome_message" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gold-500 focus:border-gold-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.translation_value') }}</label>
                <input type="text" name="value" required placeholder="Welcome Message" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gold-500 focus:border-gold-500">
            </div>
        </div>
        <div class="flex justify-end space-x-2 {{ $isRtl ? 'space-x-reverse' : '' }} mt-4">
            <button type="button" onclick="toggleAddForm()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                {{ __('admin.cancel') }}
            </button>
            <button type="submit" class="bg-gold-500 hover:bg-gold-600 text-dark-900 px-4 py-2 rounded-lg font-medium transition-colors">
                {{ __('admin.add') }}
            </button>
        </div>
    </form>
</div>

<!-- Translations Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="bg-gold-500 text-dark-900 px-6 py-4">
        <h2 class="text-lg font-bold flex items-center justify-between">
            <span class="flex items-center">
                <svg class="w-5 h-5 {{ $isRtl ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path>
                </svg>
                {{ __('admin.translations') }} - {{ $currentLanguage->name }}
            </span>
            <span class="text-sm">{{ $translations->total() }} {{ __('admin.total') }}</span>
        </h2>
    </div>
    
    @if($translations->count() > 0)
        <!-- Desktop Table -->
        <div class="hidden md:block">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-{{ $isRtl ? 'right' : 'left' }} text-xs font-semibold text-gray-600 uppercase">{{ __('admin.translation_group') }}</th>
                        <th class="px-6 py-3 text-{{ $isRtl ? 'right' : 'left' }} text-xs font-semibold text-gray-600 uppercase">{{ __('admin.translation_key') }}</th>
                        <th class="px-6 py-3 text-{{ $isRtl ? 'right' : 'left' }} text-xs font-semibold text-gray-600 uppercase">{{ __('admin.translation_value') }}</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">{{ __('admin.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($translations as $translation)
                        <tr class="hover:bg-gray-50" id="row-{{ $translation->id }}">
                            <td class="px-6 py-4">
                                <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full font-medium">{{ $translation->group }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <code class="bg-gray-100 text-gray-800 text-sm px-2 py-1 rounded">{{ $translation->key }}</code>
                            </td>
                            <td class="px-6 py-4">
                                <div id="value-display-{{ $translation->id }}">
                                    <span class="text-gray-900">{{ Str::limit($translation->value, 100) }}</span>
                                </div>
                                <div id="value-edit-{{ $translation->id }}" class="hidden">
                                    <form method="POST" action="{{ route('admin.translations.update', $translation) }}" class="flex space-x-2 {{ $isRtl ? 'space-x-reverse' : '' }}">
                                        @csrf
                                        @method('PUT')
                                        <input type="text" name="value" value="{{ $translation->value }}" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gold-500 focus:border-gold-500">
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
                                <div class="flex items-center justify-center space-x-2 {{ $isRtl ? 'space-x-reverse' : '' }}">
                                    <button onclick="editTranslation({{ $translation->id }})" class="p-2 bg-blue-100 text-blue-600 hover:bg-blue-200 rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>
                                    <form method="POST" action="{{ route('admin.translations.destroy', $translation) }}" class="inline" onsubmit="return confirm('{{ __('admin.confirm_delete') }}')">
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
                            <code class="bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded {{ $isRtl ? 'mr-2' : 'ml-2' }}">{{ $translation->key }}</code>
                        </div>
                    </div>
                    <div class="mb-3">
                        <p class="text-gray-900 text-sm">{{ $translation->value }}</p>
                    </div>
                    <div class="flex items-center justify-center space-x-3 {{ $isRtl ? 'space-x-reverse' : '' }} pt-3 border-t border-gray-200">
                        <button onclick="editTranslation({{ $translation->id }})" class="flex items-center px-3 py-2 bg-blue-100 text-blue-700 hover:bg-blue-200 rounded-lg text-sm font-medium transition-colors">
                            <svg class="w-4 h-4 {{ $isRtl ? 'ml-1' : 'mr-1' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            {{ __('admin.edit') }}
                        </button>
                        <form method="POST" action="{{ route('admin.translations.destroy', $translation) }}" class="inline" onsubmit="return confirm('{{ __('admin.confirm_delete') }}')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="flex items-center px-3 py-2 bg-red-100 text-red-700 hover:bg-red-200 rounded-lg text-sm font-medium transition-colors">
                                <svg class="w-4 h-4 {{ $isRtl ? 'ml-1' : 'mr-1' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                {{ __('admin.delete') }}
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
            <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ __('admin.no_translations') }}</h3>
            <p class="text-gray-600">{{ __('admin.no_translations_desc') }}</p>
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