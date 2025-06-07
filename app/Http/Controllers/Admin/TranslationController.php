<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Translation;
use App\Models\Language;

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

        // Check if translation already exists
        $exists = Translation::where('language_id', $request->language_id)
            ->where('group', $request->group)
            ->where('key', $request->key)
            ->exists();

        if ($exists) {
            return back()->withErrors(['key' => 'Translation key already exists for this language and group.']);
        }

        Translation::create($request->all());

        return back()->with('success', __('admin.translation_added_successfully'));
    }

    public function update(Request $request, Translation $translation)
    {
        $request->validate([
            'value' => 'required|string'
        ]);

        $translation->update(['value' => $request->value]);

        return back()->with('success', __('admin.translation_updated_successfully'));
    }

    public function destroy(Translation $translation)
    {
        $translation->delete();
        
        return back()->with('success', __('admin.translation_deleted_successfully'));
    }
}