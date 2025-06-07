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
        
        // الحصول على المجموعات المتاحة
        $groups = Translation::distinct()
            ->whereNotNull('translation_key')
            ->get()
            ->map(function($translation) {
                // استخراج المجموعة من المفتاح (الجزء قبل النقطة الأولى)
                $parts = explode('.', $translation->translation_key);
                return count($parts) > 1 ? $parts[0] : 'general';
            })
            ->unique()
            ->sort()
            ->values();
        
        $query = Translation::where('language_id', $currentLanguageId);
        
        // فلترة حسب المجموعة
        if ($request->get('group')) {
            $group = $request->get('group');
            if ($group === 'general') {
                // المفاتيح التي لا تحتوي على نقطة
                $query->where('translation_key', 'NOT LIKE', '%.%');
            } else {
                // المفاتيح التي تبدأ بالمجموعة المحددة
                $query->where('translation_key', 'LIKE', $group . '.%');
            }
        }
        
        // البحث
        if ($request->get('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('translation_key', 'LIKE', "%{$search}%")
                  ->orWhere('translation_value', 'LIKE', "%{$search}%");
            });
        }
        
        $translations = $query->orderBy('translation_key')->paginate(20);
        
        // إضافة خاصية group لكل ترجمة للعرض
        $translations->getCollection()->transform(function($translation) {
            $parts = explode('.', $translation->translation_key);
            $translation->group = count($parts) > 1 ? $parts[0] : 'general';
            $translation->key = count($parts) > 1 ? implode('.', array_slice($parts, 1)) : $translation->translation_key;
            return $translation;
        });
        
        return view('admin.translations.index', compact(
            'translations', 'languages', 'currentLanguage', 'groups'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'language_id' => 'required|exists:languages,id',
            'group' => 'required|string|max:50',
            'key' => 'required|string|max:200',
            'value' => 'required|string'
        ]);

        try {
            // دمج المجموعة والمفتاح
            $translationKey = $request->group . '.' . $request->key;
            
            Translation::create([
                'language_id' => $request->language_id,
                'translation_key' => $translationKey,
                'translation_value' => $request->value,
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