<?php
// app/Http/Controllers/Admin/TranslationController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Translation;
use App\Models\Language;

class TranslationController extends Controller
{
    public function index(Request $request)
    {
        $languages = Language::where('is_active', true)->get();
        
        $currentLanguageId = $request->get('language_id') ?: session('current_language_id', 1);
        $currentLanguage = Language::find($currentLanguageId);
        
        $query = Translation::where('language_id', $currentLanguageId);
        
        if ($request->get('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('translation_key', 'LIKE', "%{$search}%")
                  ->orWhere('translation_value', 'LIKE', "%{$search}%");
            });
        }
        
        $translations = $query->orderBy('translation_key')->paginate(20);
        
        return view('admin.translations.index', compact(
            'translations', 'languages', 'currentLanguage'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'language_id' => 'required|exists:languages,id',
            'translation_key' => 'required|string|max:255',
            'translation_value' => 'required|string'
        ]);

        try {
            Translation::create([
                'language_id' => $request->language_id,
                'translation_key' => $request->translation_key,
                'translation_value' => $request->translation_value,
            ]);

            return redirect()->back()->with('success', 'تم إضافة الترجمة بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'هذا المفتاح موجود مسبقاً لهذه اللغة');
        }
    }

    public function update(Request $request, Translation $translation)
    {
        $request->validate([
            'translation_value' => 'required|string'
        ]);

        try {
            $translation->update(['translation_value' => $request->translation_value]);
            return redirect()->back()->with('success', 'تم تحديث الترجمة بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء التحديث');
        }
    }

    public function destroy(Translation $translation)
    {
        try {
            $translation->delete();
            return redirect()->back()->with('success', 'تم حذف الترجمة بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء الحذف');
        }
    }
}