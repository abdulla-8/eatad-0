<?php
// app/Http/Controllers/Admin/LanguageController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Language;

class LanguageController extends Controller
{
    public function index()
    {
        $languages = Language::all();
        return view('admin.languages.index', compact('languages'));
    }

    public function changeLanguage($code)
    {
        try {
            $language = Language::where('code', $code)->where('is_active', true)->first();
            
            if ($language) {
                session([
                    'language_code' => $code,
                    'current_language_id' => $language->id
                ]);
            }
        } catch (\Exception $e) {
            // تجاهل الخطأ
        }
        
        return redirect()->back();
    }

    public function toggle($id)
    {
        try {
            $language = Language::findOrFail($id);
            $language->update(['is_active' => !$language->is_active]);
            
            return redirect()->back()->with('success', 'تم تحديث اللغة بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء التحديث');
        }
    }

    public function setDefault($id)
    {
        try {
            // إزالة الافتراضي من جميع اللغات
            Language::where('is_default', true)->update(['is_default' => false]);
            
            // تعيين اللغة الجديدة كافتراضية
            $language = Language::findOrFail($id);
            $language->update(['is_default' => true]);
            
            return redirect()->back()->with('success', 'تم تعيين اللغة الافتراضية بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء التحديث');
        }
    }
}