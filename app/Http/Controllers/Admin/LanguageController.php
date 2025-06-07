<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
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
        $language = Language::where('code', $code)->active()->first();
        
        if ($language) {
            Session::put('locale', $code);
        }
        
        return redirect()->back();
    }

    public function toggle($id)
    {
        $language = Language::findOrFail($id);
        $language->update(['is_active' => !$language->is_active]);
        
        return redirect()->back()->with('success', __('admin.language_updated'));
    }

    public function setDefault($id)
    {
        // Remove default from all languages
        Language::where('is_default', true)->update(['is_default' => false]);
        
        // Set new default
        $language = Language::findOrFail($id);
        $language->update(['is_default' => true]);
        
        return redirect()->back()->with('success', __('admin.default_language_updated'));
    }
}