<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Translation;
use App\Models\Language;
use App\Helpers\LanguageHelper;

class TranslationController extends Controller
{
    public function index(Request $request)
    {
        $languages = Language::active()->get();
        $currentLanguage = $request->get('language_id') 
            ? Language::find($request->get('language_id')) 
            : Language::default()->first();

        $translations = Translation::byLanguage($currentLanguage->id)
            ->when($request->get('group'), function($query, $group) {
                return $query->byGroup($group);
            })
            ->when($request->get('search'), function($query, $search) {
                return $query->search($search);
            })
            ->orderBy('group')
            ->orderBy('key')
            ->paginate(20);

        $groups = Translation::distinct('group')->pluck('group');

        return view('admin.translations.index', compact(
            'translations', 
            'languages', 
            'currentLanguage', 
            'groups'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'language_id' => 'required|exists:languages,id',
            'group' => 'required|string|max:100',
            'key' => 'required|string|max:255',
            'value' => 'required|string'
        ]);

        $exists = Translation::where('language_id', $request->language_id)
            ->where('group', $request->group)
            ->where('key', $request->key)
            ->exists();

        if ($exists) {
            return back()->withErrors(['key' => 'Translation key already exists for this language and group.']);
        }

        Translation::create($request->all());

        $language = Language::find($request->language_id);
        LanguageHelper::clearTranslationCache($language->code, $request->group);

        return back()->with('success', __('admin.translation_added_successfully'));
    }

    public function update(Request $request, Translation $translation)
    {
        $request->validate([
            'value' => 'required|string'
        ]);

        $translation->update(['value' => $request->value]);

        LanguageHelper::clearTranslationCache($translation->language->code, $translation->group);

        return back()->with('success', __('admin.translation_updated_successfully'));
    }

    public function destroy(Translation $translation)
    {
        $language_code = $translation->language->code;
        $group = $translation->group;
        
        $translation->delete();
        
        LanguageHelper::clearTranslationCache($language_code, $group);
        
        return back()->with('success', __('admin.translation_deleted_successfully'));
    }
}