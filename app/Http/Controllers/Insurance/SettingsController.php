<?php

namespace App\Http\Controllers\Insurance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\Translation;
use App\Models\Language;

class SettingsController extends Controller
{
    /**
     * Display the settings page with tabs for translations, colors, and logo
     */
    public function index(Request $request)
    {
        $company = Auth::guard('insurance_company')->user();
        $activeTab = $request->get('tab', 'translations');
        
        // Get available languages
        $languages = Language::where('is_active', true)->get();
        $currentLanguageId = $request->get('language_id') ?: 1; // Default to Arabic
        $currentLanguage = Language::find($currentLanguageId);
        
        $data = [
            'company' => $company,
            'activeTab' => $activeTab,
            'languages' => $languages,
            'currentLanguage' => $currentLanguage
        ];
        
        // Load translations data if needed
        if ($activeTab === 'translations') {
            $data['translations'] = $this->getTranslations($company, $request, $currentLanguageId);
        }
        
        return view('insurance.settings.index', $data);
    }
    
    /**
     * Get translations for the company with pagination and search
     */
private function getTranslations($company, $request, $languageId)
{
    $query = Translation::where('language_id', $languageId)
        ->where('translation_key', 'LIKE', $company->translation_group . '.%');

    if ($request->get('search')) {
        $search = $request->get('search');
        $query->where(function ($q) use ($search) {
            $q->where('translation_key', 'LIKE', "%{$search}%")
                ->orWhere('translation_value', 'LIKE', "%{$search}%");
        });
    }

    $translations = $query->orderBy('translation_key')->paginate(20);
    
    // تأكد إن دي Paginator قبل ما تعمل appending
    if (method_exists($translations, 'appending')) {
        $translations->appending($request->query());
    }

    // اعمل التحويل بدون تغيير نوع الـ object
    foreach ($translations as $translation) {
        $translation->display_key = str_replace($company->translation_group . '.', '', $translation->translation_key);
    }

    return $translations;
}
    
    /**
     * Store a new translation
     */
    public function storeTranslation(Request $request)
    {
        $company = Auth::guard('insurance_company')->user();
        
        $request->validate([
            'language_id' => 'required|exists:languages,id',
            'key' => 'required|string|max:200',
            'value' => 'required|string'
        ]);

        try {
            // Add company prefix to the key
            $translationKey = $company->translation_group . '.' . $request->key;
            
            // Check if translation already exists
            $existingTranslation = Translation::where('language_id', $request->language_id)
                ->where('translation_key', $translationKey)
                ->first();
                
            if ($existingTranslation) {
                return redirect()->back()
                    ->with('error', t('admin.translation_key_exists', 'This translation key already exists'))
                    ->withInput();
            }
            
            Translation::create([
                'language_id' => $request->language_id,
                'translation_key' => $translationKey,
                'translation_value' => $request->value,
            ]);

            return redirect()->back()
                ->with('success', t('admin.translation_created', 'Translation added successfully'))
                ->with('tab', 'translations');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', t('admin.error_occurred', 'An error occurred'))
                ->withInput();
        }
    }
    
    /**
     * Update an existing translation
     */
 /**
 * Update an existing translation
 */
public function updateTranslation(Request $request, $companyRoute, $id)
{
    $company = Auth::guard('insurance_company')->user();
    
    // التأكد من أن الشركة تطابق الـ route
    if ($company->company_slug !== $companyRoute) {
        abort(404);
    }
    
    $translation = Translation::findOrFail($id);
    
    // Check if this translation belongs to the company
    if (!str_starts_with($translation->translation_key, $company->translation_group . '.')) {
        return redirect()->back()
            ->with('error', 'غير مسموح بهذا الإجراء');
    }
    
    $request->validate([
        'translation_value' => 'required|string'
    ]);

    try {
        $translation->update(['translation_value' => $request->translation_value]);
        
        return redirect()->route('insurance.settings.index', [
            'companyRoute' => $companyRoute,
            'tab' => 'translations'
        ])->with('success', 'تم تحديث الترجمة بنجاح');
        
    } catch (\Exception $e) {
        return redirect()->back()
            ->with('error', 'حدث خطأ أثناء التحديث');
    }
}


    
    /**
     * Delete a translation
     */
  /**
 * Delete a translation
 */
public function deleteTranslation($companyRoute, $id)
{
    $company = Auth::guard('insurance_company')->user();

    if ($company->company_slug !== $companyRoute) {
        abort(404);
    }

    $translation = Translation::findOrFail($id);

    if (!str_starts_with($translation->translation_key, $company->translation_group . '.')) {
        return redirect()->back()->with('error', t('admin.unauthorized_action', 'Unauthorized action'));
    }

    try {
        $translation->delete();

        return redirect()->back()->with('success', t('admin.translation_deleted', 'Translation deleted successfully'))->with('tab', 'translations');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', t('admin.error_occurred', 'An error occurred'));
    }
}

    
    /**
     * Update company colors
     */
    public function updateColors(Request $request)
    {
        $company = Auth::guard('insurance_company')->user();
        
        $request->validate([
            'primary_color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'secondary_color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/'
        ], [
            'primary_color.regex' => t('admin.invalid_color_format', 'Invalid color format'),
            'secondary_color.regex' => t('admin.invalid_color_format', 'Invalid color format')
        ]);

        try {
            $company->update([
                'primary_color' => $request->primary_color,
                'secondary_color' => $request->secondary_color
            ]);
            
            return redirect()->back()
                ->with('success', t('admin.colors_updated', 'Colors updated successfully'))
                ->with('tab', 'colors');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', t('admin.error_occurred', 'An error occurred'));
        }
    }
    
    /**
     * Update company logo
     */
    public function updateLogo(Request $request)
    {
        $company = Auth::guard('insurance_company')->user();
        
        $request->validate([
            'company_logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        try {
            // Delete old logo if exists
            if ($company->company_logo && Storage::disk('public')->exists($company->company_logo)) {
                Storage::disk('public')->delete($company->company_logo);
            }
            
            // Store new logo
            $logo = $request->file('company_logo');
            $filename = $company->company_slug . '_logo_' . time() . '.' . $logo->getClientOriginalExtension();
            $path = $logo->storeAs('company_logos', $filename, 'public');
            
            $company->update(['company_logo' => $path]);
            
            return redirect()->back()
                ->with('success', t('admin.logo_updated', 'Logo updated successfully'))
                ->with('tab', 'logo');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', t('admin.error_occurred', 'An error occurred'));
        }
    }
    
    /**
     * Delete company logo
     */
    public function deleteLogo()
    {
        $company = Auth::guard('insurance_company')->user();
        
        try {
            // Delete logo file if exists
            if ($company->company_logo && Storage::disk('public')->exists($company->company_logo)) {
                Storage::disk('public')->delete($company->company_logo);
            }
            
            $company->update(['company_logo' => null]);
            
            return redirect()->back()
                ->with('success', t('admin.logo_deleted', 'Logo deleted successfully'))
                ->with('tab', 'logo');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', t('admin.error_occurred', 'An error occurred'));
        }
    }
}