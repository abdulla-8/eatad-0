<?php
// app/Http/Controllers/Profile/UnifiedProfileController.php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UnifiedProfileController extends Controller
{
    public function show(Request $request)
    {
        // Debug: بداية عرض البروفايل
        Log::info('DEBUG: Profile Show Method Started', [
            'route_name' => $request->route()->getName(),
            'route_params' => $request->route()->parameters(),
            'user_agent' => $request->userAgent()
        ]);

        $userType = $this->getCurrentUserType();
        $user = $this->getCurrentUser();
        
        if (!$user) {
            Log::error('DEBUG: No authenticated user found in profile show');
            abort(403, 'المستخدم غير مسجل الدخول');
        }

        // إضافة متغير company للـ View
        $company = $this->getCompanyForView($user, $userType);

        // Debug: معلومات المستخدم
        Log::info('DEBUG: Profile User Information', [
            'user_id' => $user->id,
            'user_type' => $userType,
            'company_info' => $this->getCompanyInfo($user, $userType),
            'company_data' => $company
        ]);

        // جلب الإحصائيات المحدثة
        $stats = $this->getUserStats($user, $userType);
        
        // جلب النشاطات الأخيرة
        $recentActivities = $this->getRecentActivities($user, $userType);
        
        // معلومات إضافية للعرض
        $profileData = $this->getProfileDisplayData($user, $userType);

        Log::info('DEBUG: Successfully accessed profile', [
            'user_id' => $user->id,
            'user_type' => $userType,
            'stats_count' => count($stats),
            'activities_count' => count($recentActivities)
        ]);

        return view('profile.show', compact(
            'user', 
            'userType', 
            'stats', 
            'recentActivities', 
            'profileData',
            'company'
        ));
    }

    public function edit(Request $request)
    {
        Log::info('DEBUG: Profile Edit Method Started', [
            'route_name' => $request->route()->getName(),
            'user_ip' => $request->ip()
        ]);

        $userType = $this->getCurrentUserType();
        $user = $this->getCurrentUser();
        
        if (!$user) {
            Log::error('DEBUG: No authenticated user found in profile edit');
            abort(403, 'المستخدم غير مسجل الدخول');
        }

        // إضافة متغير company للـ View
        $company = $this->getCompanyForView($user, $userType);

        Log::info('DEBUG: Profile Edit Access', [
            'user_id' => $user->id,
            'user_type' => $userType,
            'company_data' => $company
        ]);

        $profileData = $this->getProfileDisplayData($user, $userType);

        return view('profile.edit', compact(
            'user', 
            'userType', 
            'profileData',
            'company'
        ));
    }

    public function update(Request $request)
    {
        Log::info('DEBUG: Profile Update Method Started', [
            'route_name' => $request->route()->getName(),
            'request_data' => $request->except(['_token'])
        ]);

        $userType = $this->getCurrentUserType();
        $user = $this->getCurrentUser();
        
        if (!$user) {
            Log::error('DEBUG: No authenticated user found in profile update');
            abort(403, 'المستخدم غير مسجل الدخول');
        }

        // تحديد قواعد التحقق حسب نوع المستخدم (بدون الصورة وكلمة المرور)
        $validationRules = $this->getValidationRules($user, $userType);
        
        Log::info('DEBUG: Validation Rules Applied', [
            'user_type' => $userType,
            'rules_count' => count($validationRules)
        ]);

        $request->validate($validationRules);

        try {
            // تحديث البيانات الأساسية فقط
            $this->updateBasicInfo($user, $request, $userType);

            Log::info('DEBUG: Profile updated successfully', [
                'user_id' => $user->id,
                'user_type' => $userType,
                'updated_fields' => array_keys($request->except(['_token', '_method']))
            ]);

            return $this->getRedirectRoute($userType)
                ->with('success', 'تم تحديث البروفايل بنجاح');

        } catch (\Exception $e) {
            Log::error('DEBUG: Profile update failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->withErrors(['error' => 'حدث خطأ أثناء تحديث البروفايل'])
                        ->withInput();
        }
    }

    // دالة جديدة لتغيير كلمة المرور - مع تحديث الفاليديشن إلى 6 أحرف
    public function changePassword(Request $request)
    {
        Log::info('DEBUG: Change Password Method Started', [
            'user_id' => auth()->id(),
            'route_name' => $request->route()->getName()
        ]);

        $userType = $this->getCurrentUserType();
        $user = $this->getCurrentUser();
        
        if (!$user) {
            abort(403, 'المستخدم غير مسجل الدخول');
        }

        // تحديث الفاليديشن إلى 6 أحرف كحد أدنى
        $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::min(6)],
        ], [
            'current_password.required' => 'كلمة المرور الحالية مطلوبة',
            'password.required' => 'كلمة المرور الجديدة مطلوبة',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق',
            'password.min' => 'كلمة المرور يجب أن تكون على الأقل 6 أحرف'
        ]);

        // التحقق من كلمة المرور الحالية
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'كلمة المرور الحالية غير صحيحة']);
        }

        try {
            $user->update([
                'password' => Hash::make($request->password)
            ]);

            Log::info('Password updated successfully', [
                'user_id' => $user->id,
                'user_type' => $userType
            ]);

            return back()->with('success', 'تم تحديث كلمة المرور بنجاح');

        } catch (\Exception $e) {
            Log::error('Password update failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->withErrors(['error' => 'حدث خطأ أثناء تحديث كلمة المرور']);
        }
    }

    private function getCurrentUserType()
    {
        if (auth('insurance_company')->check()) {
            return 'insurance_company';
        } elseif (auth('service_center')->check()) {
            return 'service_center';
        } elseif (auth('insurance_user')->check()) {
            return 'insurance_user';
        }
        
        return null;
    }

    private function getCurrentUser()
    {
        if (auth('insurance_company')->check()) {
            return auth('insurance_company')->user();
        } elseif (auth('service_center')->check()) {
            return auth('service_center')->user();
        } elseif (auth('insurance_user')->check()) {
            return auth('insurance_user')->user()->load('company');
        }
        
        return null;
    }

    private function getCompanyForView($user, $userType)
    {
        switch ($userType) {
            case 'insurance_company':
                return $user;
            
            case 'service_center':
                return (object) [
                    'translation_group' => $user->translation_group ?? 'service_center',
                    'legal_name' => $user->legal_name ?? 'مركز صيانة',
                    'primary_color' => $user->primary_color ?? '#10B981',
                    'secondary_color' => $user->secondary_color ?? '#059669',
                    'company_slug' => $user->center_slug ?? 'service-center'
                ];
            
            case 'insurance_user':
                if (!$user->relationLoaded('company')) {
                    $user->load('company');
                }
                
                return $user->company ?? (object) [
                    'translation_group' => 'default',
                    'legal_name' => 'شركة التأمين',
                    'primary_color' => '#3B82F6',
                    'secondary_color' => '#1E40AF',
                    'company_slug' => 'default'
                ];
            
            default:
                return (object) [
                    'translation_group' => 'default',
                    'legal_name' => 'النظام',
                    'primary_color' => '#3B82F6',
                    'secondary_color' => '#1E40AF',
                    'company_slug' => 'default'
                ];
        }
    }

    private function getValidationRules($user, $userType)
    {
        $rules = [];

        switch ($userType) {
            case 'insurance_company':
                $rules = [
                    'legal_name' => 'required|string|max:255',
                    'phone' => 'required|string|max:20',
                    'commercial_register' => 'required|string|max:100',
                    'tax_number' => 'nullable|string|max:100',
                    'office_address' => 'nullable|string',
                    'employee_count' => 'nullable|integer|min:0',
                    'insured_cars_count' => 'nullable|integer|min:0',
                ];
                break;
            
            case 'service_center':
                $rules = [
                    'legal_name' => 'required|string|max:255',
                    'phone' => 'required|string|max:20',
                    'commercial_register' => 'required|string|max:100',
                    'tax_number' => 'nullable|string|max:100',
                    'center_address' => 'nullable|string',
                    'body_work_technicians' => 'nullable|integer|min:0',
                    'mechanical_technicians' => 'nullable|integer|min:0',
                    'painting_technicians' => 'nullable|integer|min:0',
                    'electrical_technicians' => 'nullable|integer|min:0',
                    'other_technicians' => 'nullable|integer|min:0',
                ];
                break;
            
            case 'insurance_user':
                $rules = [
                    'full_name' => 'required|string|max:255',
                    'phone' => 'required|string|max:20',
                    'national_id' => 'required|string|max:20',
                    'policy_number' => 'required|string|max:100',
                ];
                break;
        }

        return $rules;
    }

    private function updateBasicInfo($user, $request, $userType)
    {
        switch ($userType) {
            case 'insurance_company':
                $user->update([
                    'legal_name' => $request->legal_name,
                    'phone' => $request->phone,
                    'commercial_register' => $request->commercial_register,
                    'tax_number' => $request->tax_number,
                    'office_address' => $request->office_address,
                    'employee_count' => $request->employee_count,
                    'insured_cars_count' => $request->insured_cars_count,
                ]);
                break;
            
            case 'service_center':
                $user->update([
                    'legal_name' => $request->legal_name,
                    'phone' => $request->phone,
                    'commercial_register' => $request->commercial_register,
                    'tax_number' => $request->tax_number,
                    'center_address' => $request->center_address,
                    'body_work_technicians' => $request->body_work_technicians ?? 0,
                    'mechanical_technicians' => $request->mechanical_technicians ?? 0,
                    'painting_technicians' => $request->painting_technicians ?? 0,
                    'electrical_technicians' => $request->electrical_technicians ?? 0,
                    'other_technicians' => $request->other_technicians ?? 0,
                ]);
                break;
            
            case 'insurance_user':
                $user->update([
                    'full_name' => $request->full_name,
                    'phone' => $request->phone,
                    'national_id' => $request->national_id,
                    'policy_number' => $request->policy_number,
                ]);
                break;
        }
    }

    private function getUserStats($user, $userType)
    {
        $stats = [];
        
        switch ($userType) {
            case 'insurance_company':
                $stats = [
                    'total_users' => \App\Models\InsuranceUser::where('insurance_company_id', $user->id)->count(),
                    'employee_count' => $user->employee_count ?? 0,
                    'insured_cars_count' => $user->insured_cars_count ?? 0,
                    'total_complaints' => \App\Models\Complaint::where('complainant_id', $user->id)->where('complainant_type', 'insurance_company')->count(),
                ];
                break;
            
            case 'service_center':
                $totalTechnicians = ($user->body_work_technicians ?? 0) + 
                                  ($user->mechanical_technicians ?? 0) + 
                                  ($user->painting_technicians ?? 0) + 
                                  ($user->electrical_technicians ?? 0) + 
                                  ($user->other_technicians ?? 0);
                
                $stats = [
                    'total_technicians' => $totalTechnicians,
                    'tow_trucks_count' => $user->tow_trucks_count ?? 0,
                    'daily_tow_capacity' => $user->daily_tow_capacity ?? 0,
                    'total_complaints' => \App\Models\Complaint::where('complainant_id', $user->id)->where('complainant_type', 'service_center')->count(),
                ];
                break;
            
            case 'insurance_user':
                // إحصائيات مستخدم التأمين مع المطالبات
                $claimsCount = 0;
                try {
                    // البحث في جدول Claims إذا كان موجود
                    if (class_exists('\App\Models\Claim')) {
                        $claimsCount = \App\Models\Claim::where('insurance_user_id', $user->id)->count();
                    }
                } catch (\Exception $e) {
                    Log::warning('Claims table not found or accessible', ['error' => $e->getMessage()]);
                }

                $stats = [
                    'total_complaints' => \App\Models\Complaint::where('complainant_id', $user->id)->where('complainant_type', 'insurance_user')->count(),
                    'total_claims' => $claimsCount,
                    'policy_status' => $user->is_active ? 'نشط' : 'غير نشط',
                    'company_name' => $user->company->legal_name ?? 'غير محدد',
                ];
                break;
        }
        
        return $stats;
    }

    private function getRecentActivities($user, $userType)
    {
        $complaints = \App\Models\Complaint::where('complainant_id', $user->id)
            ->where('complainant_type', $userType)
            ->latest()
            ->take(5)
            ->get();
        
        return $complaints->map(function($complaint) {
            return [
                'type' => 'complaint',
                'title' => $complaint->subject,
                'date' => $complaint->created_at,
                'status' => $complaint->is_read ? 'مقروءة' : 'غير مقروءة'
            ];
        });
    }

    private function getCompanyInfo($user, $userType)
    {
        switch ($userType) {
            case 'insurance_company':
                return [
                    'name' => $user->legal_name,
                    'logo' => $user->company_logo ?? null,
                    'type' => 'شركة تأمين'
                ];
            
            case 'service_center':
                return [
                    'name' => $user->legal_name,
                    'logo' => $user->center_logo ?? null,
                    'type' => 'مركز صيانة'
                ];
            
            case 'insurance_user':
                return [
                    'name' => $user->company->legal_name ?? 'غير محدد',
                    'logo' => $user->company->company_logo ?? null,
                    'type' => 'مستخدم تأمين'
                ];
            
            default:
                return null;
        }
    }

    private function getProfileDisplayData($user, $userType)
    {
        $data = [
            'company_info' => $this->getCompanyInfo($user, $userType),
            'display_fields' => [],
            'colors' => $this->getThemeColors($user, $userType)
        ];

        switch ($userType) {
            case 'insurance_company':
                $data['display_fields'] = [
                    'legal_name' => ['label' => 'الاسم القانوني', 'icon' => 'fas fa-building'],
                    'phone' => ['label' => 'رقم الهاتف', 'icon' => 'fas fa-phone'],
                    'commercial_register' => ['label' => 'السجل التجاري', 'icon' => 'fas fa-file-alt'],
                    'tax_number' => ['label' => 'الرقم الضريبي', 'icon' => 'fas fa-calculator'],
                    'office_address' => ['label' => 'عنوان المكتب', 'icon' => 'fas fa-map-marker-alt'],
                    'employee_count' => ['label' => 'عدد الموظفين', 'icon' => 'fas fa-users'],
                    'insured_cars_count' => ['label' => 'عدد السيارات المؤمنة', 'icon' => 'fas fa-car']
                ];
                break;
            
            case 'service_center':
                $data['display_fields'] = [
                    'legal_name' => ['label' => 'اسم المركز', 'icon' => 'fas fa-tools'],
                    'phone' => ['label' => 'رقم الهاتف', 'icon' => 'fas fa-phone'],
                    'commercial_register' => ['label' => 'السجل التجاري', 'icon' => 'fas fa-file-alt'],
                    'tax_number' => ['label' => 'الرقم الضريبي', 'icon' => 'fas fa-calculator'],
                    'center_address' => ['label' => 'عنوان المركز', 'icon' => 'fas fa-map-marker-alt'],
                    'body_work_technicians' => ['label' => 'فنيي أعمال الهيكل', 'icon' => 'fas fa-hammer'],
                    'mechanical_technicians' => ['label' => 'فنيي الميكانيكا', 'icon' => 'fas fa-cog'],
                    'painting_technicians' => ['label' => 'فنيي الدهان', 'icon' => 'fas fa-paint-brush'],
                    'electrical_technicians' => ['label' => 'فنيي الكهرباء', 'icon' => 'fas fa-bolt'],
                    'other_technicians' => ['label' => 'فنيين آخرين', 'icon' => 'fas fa-user-cog']
                ];
                break;
            
            case 'insurance_user':
                $data['display_fields'] = [
                    'full_name' => ['label' => 'الاسم الكامل', 'icon' => 'fas fa-user'],
                    'phone' => ['label' => 'رقم الهاتف', 'icon' => 'fas fa-phone'],
                    'national_id' => ['label' => 'رقم الهوية', 'icon' => 'fas fa-id-card'],
                    'policy_number' => ['label' => 'رقم البوليصة', 'icon' => 'fas fa-shield-alt']
                ];
                break;
        }

        return $data;
    }

    private function getThemeColors($user, $userType)
    {
        switch ($userType) {
            case 'insurance_company':
                return [
                    'primary' => $user->primary_color ?? '#3B82F6',
                    'secondary' => $user->secondary_color ?? '#1E40AF'
                ];
            
            case 'service_center':
                return [
                    'primary' => $user->primary_color ?? '#10B981',
                    'secondary' => $user->secondary_color ?? '#059669'
                ];
            
            case 'insurance_user':
                return [
                    'primary' => $user->company->primary_color ?? '#3B82F6',
                    'secondary' => $user->company->secondary_color ?? '#1E40AF'
                ];
            
            default:
                return [
                    'primary' => '#3B82F6',
                    'secondary' => '#1E40AF'
                ];
        }
    }

    private function getRedirectRoute($userType)
    {
        switch ($userType) {
            case 'insurance_company':
                $user = auth('insurance_company')->user();
                return redirect()->route('insurance.profile.show', ['companyRoute' => $user->company_slug]);
            
            case 'service_center':
                return redirect()->route('service-center.profile.show');
            
            case 'insurance_user':
                $user = auth('insurance_user')->user();
                $company = $user->company ?? \App\Models\InsuranceCompany::find($user->insurance_company_id);
                return redirect()->route('insurance.user.profile.show', ['companySlug' => $company->company_slug]);
            
            default:
                return redirect()->back();
        }
    }
}