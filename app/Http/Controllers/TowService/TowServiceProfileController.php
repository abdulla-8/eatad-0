<?php

namespace App\Http\Controllers\TowService;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class TowServiceProfileController extends Controller
{
    public function show(Request $request)
    {
        Log::info('TowService Profile Show Started', [
            'route_name' => $request->route()->getName(),
            'user_type' => $this->getCurrentUserType()
        ]);

        $userType = $this->getCurrentUserType();
        $user = $this->getCurrentUser();
        
        if (!$user) {
            Log::error('No authenticated tow service user found');
            abort(403, 'المستخدم غير مسجل الدخول');
        }

        // معلومات الشركة للعرض
        $company = $this->getCompanyForView($user, $userType);
        
        // جلب الإحصائيات
        $stats = $this->getUserStats($user, $userType);
        
        // جلب النشاطات الأخيرة
        $recentActivities = $this->getRecentActivities($user, $userType);
        
        // معلومات العرض
        $profileData = $this->getProfileDisplayData($user, $userType);

        Log::info('TowService Profile accessed successfully', [
            'user_id' => $user->id,
            'user_type' => $userType
        ]);

        return view('tow-service.profile.show', compact(
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
        Log::info('TowService Profile Edit Started', [
            'user_type' => $this->getCurrentUserType()
        ]);

        $userType = $this->getCurrentUserType();
        $user = $this->getCurrentUser();
        
        if (!$user) {
            abort(403, 'المستخدم غير مسجل الدخول');
        }

        $company = $this->getCompanyForView($user, $userType);
        $profileData = $this->getProfileDisplayData($user, $userType);

        return view('tow-service.profile.edit', compact(
            'user', 
            'userType', 
            'profileData',
            'company'
        ));
    }

    public function update(Request $request)
    {
        Log::info('TowService Profile Update Started', [
            'user_type' => $this->getCurrentUserType()
        ]);

        $userType = $this->getCurrentUserType();
        $user = $this->getCurrentUser();
        
        if (!$user) {
            abort(403, 'المستخدم غير مسجل الدخول');
        }

        // قواعد التحقق
        $validationRules = $this->getValidationRules($userType);
        $request->validate($validationRules);

        try {
            $this->updateUserInfo($user, $request, $userType);

            Log::info('TowService Profile updated successfully', [
                'user_id' => $user->id,
                'user_type' => $userType
            ]);

            return redirect()->route('tow-service.profile.show')
                ->with('success', 'تم تحديث البروفايل بنجاح');

        } catch (\Exception $e) {
            Log::error('TowService Profile update failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);

            return back()->withErrors(['error' => 'حدث خطأ أثناء تحديث البروفايل'])
                        ->withInput();
        }
    }

    public function changePassword(Request $request)
    {
        Log::info('TowService Password Change Started');

        $user = $this->getCurrentUser();
        $userType = $this->getCurrentUserType();
        
        if (!$user) {
            abort(403, 'المستخدم غير مسجل الدخول');
        }

        $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::min(6)],
        ], [
            'current_password.required' => 'كلمة المرور الحالية مطلوبة',
            'password.required' => 'كلمة المرور الجديدة مطلوبة',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق',
            'password.min' => 'كلمة المرور يجب أن تكون على الأقل 6 أحرف'
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'كلمة المرور الحالية غير صحيحة']);
        }

        try {
            $user->update([
                'password' => Hash::make($request->password)
            ]);

            Log::info('TowService Password updated successfully', [
                'user_id' => $user->id,
                'user_type' => $userType
            ]);

            return back()->with('success', 'تم تحديث كلمة المرور بنجاح');

        } catch (\Exception $e) {
            Log::error('TowService Password update failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);

            return back()->withErrors(['error' => 'حدث خطأ أثناء تحديث كلمة المرور']);
        }
    }

    // دوال إدارة العروض للشركات
    public function companyOffers()
    {
        if (!auth('tow_service_company')->check()) {
            abort(403, 'غير مصرح للوصول');
        }

        $user = auth('tow_service_company')->user();
        
        // جلب عروض الشركة
        $offers = $this->getOffersForUser($user->id, 'tow_service_company');
        
        return view('tow-service.offers.index', compact('offers', 'user'));
    }

    public function acceptCompanyOffer($offerId)
    {
        if (!auth('tow_service_company')->check()) {
            abort(403, 'غير مصرح للوصول');
        }

        return $this->handleOfferAction($offerId, 'accept', 'tow_service_company');
    }

    public function rejectCompanyOffer($offerId)
    {
        if (!auth('tow_service_company')->check()) {
            abort(403, 'غير مصرح للوصول');
        }

        return $this->handleOfferAction($offerId, 'reject', 'tow_service_company');
    }

    // دوال إدارة العروض للأفراد
    public function individualOffers()
    {
        if (!auth('tow_service_individual')->check()) {
            abort(403, 'غير مصرح للوصول');
        }

        $user = auth('tow_service_individual')->user();
        
        // جلب عروض الفرد
        $offers = $this->getOffersForUser($user->id, 'tow_service_individual');
        
        return view('tow-service.offers.index', compact('offers', 'user'));
    }

    public function acceptIndividualOffer($offerId)
    {
        if (!auth('tow_service_individual')->check()) {
            abort(403, 'غير مصرح للوصول');
        }

        return $this->handleOfferAction($offerId, 'accept', 'tow_service_individual');
    }

    public function rejectIndividualOffer($offerId)
    {
        if (!auth('tow_service_individual')->check()) {
            abort(403, 'غير مصرح للوصول');
        }

        return $this->handleOfferAction($offerId, 'reject', 'tow_service_individual');
    }

    private function getCurrentUserType()
    {
        if (auth('tow_service_company')->check()) {
            return 'tow_service_company';
        } elseif (auth('tow_service_individual')->check()) {
            return 'tow_service_individual';
        }
        
        return null;
    }

    private function getCurrentUser()
    {
        if (auth('tow_service_company')->check()) {
            return auth('tow_service_company')->user();
        } elseif (auth('tow_service_individual')->check()) {
            return auth('tow_service_individual')->user();
        }
        
        return null;
    }

    private function getCompanyForView($user, $userType)
    {
        if ($userType === 'tow_service_company') {
            return (object) [
                'translation_group' => 'tow_company',
                'legal_name' => $user->legal_name ?? 'شركة خدمة السحب',
                'primary_color' => '#F59E0B',
                'secondary_color' => '#D97706',
                'company_slug' => 'tow-company'
            ];
        } else {
            return (object) [
                'translation_group' => 'tow_individual',
                'legal_name' => $user->full_name ?? 'فرد خدمة السحب',
                'primary_color' => '#8B5CF6',
                'secondary_color' => '#7C3AED',
                'company_slug' => 'tow-individual'
            ];
        }
    }

    private function getValidationRules($userType)
    {
        if ($userType === 'tow_service_company') {
            return [
                'legal_name' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'commercial_register' => 'required|string|max:100',
                'tax_number' => 'nullable|string|max:100',
                'office_address' => 'nullable|string',
                'daily_capacity' => 'nullable|integer|min:0',
                'delegate_number' => 'nullable|string|max:100',
            ];
        } else {
            return [
                'full_name' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'national_id' => 'required|string|max:20',
                'tow_truck_plate_number' => 'required|string|max:50',
                'address' => 'nullable|string',
            ];
        }
    }

    private function updateUserInfo($user, $request, $userType)
    {
        if ($userType === 'tow_service_company') {
            $user->update([
                'legal_name' => $request->legal_name,
                'phone' => $request->phone,
                'commercial_register' => $request->commercial_register,
                'tax_number' => $request->tax_number,
                'office_address' => $request->office_address,
                'daily_capacity' => $request->daily_capacity,
                'delegate_number' => $request->delegate_number,
            ]);
        } else {
            $user->update([
                'full_name' => $request->full_name,
                'phone' => $request->phone,
                'national_id' => $request->national_id,
                'tow_truck_plate_number' => $request->tow_truck_plate_number,
                'address' => $request->address,
            ]);
        }
    }

    private function getUserStats($user, $userType)
    {
        if ($userType === 'tow_service_company') {
            return [
                'daily_capacity' => $user->daily_capacity ?? 0,
                'total_requests' => $this->getTowRequestsCount($user->id, 'tow_service_company'),
                'completed_requests' => $this->getCompletedTowRequestsCount($user->id, 'tow_service_company'),
                'account_status' => $user->is_active && $user->is_approved ? 'نشط ومعتمد' : 'في الانتظار',
                'delegate_number' => $user->delegate_number ?? 'غير محدد',
            ];
        } else {
            return [
                'truck_plate' => $user->tow_truck_plate_number ?? 'غير محدد',
                'total_requests' => $this->getTowRequestsCount($user->id, 'tow_service_individual'),
                'completed_requests' => $this->getCompletedTowRequestsCount($user->id, 'tow_service_individual'),
                'account_status' => $user->is_active && $user->is_approved ? 'نشط ومعتمد' : 'في الانتظار',
                'national_id' => $user->national_id ?? 'غير محدد',
            ];
        }
    }

    private function getTowRequestsCount($userId, $userType)
    {
        try {
            if (class_exists('\App\Models\TowRequest')) {
                return \App\Models\TowRequest::where('tow_provider_id', $userId)
                    ->where('tow_provider_type', $userType)
                    ->count();
            }
        } catch (\Exception $e) {
            Log::warning('TowRequest table not accessible', ['error' => $e->getMessage()]);
        }
        return 0;
    }

    private function getCompletedTowRequestsCount($userId, $userType)
    {
        try {
            if (class_exists('\App\Models\TowRequest')) {
                return \App\Models\TowRequest::where('tow_provider_id', $userId)
                    ->where('tow_provider_type', $userType)
                    ->where('status', 'completed')
                    ->count();
            }
        } catch (\Exception $e) {
            Log::warning('TowRequest table not accessible', ['error' => $e->getMessage()]);
        }
        return 0;
    }

    private function getRecentActivities($user, $userType)
    {
        $activities = collect();

        // الشكاوى
        try {
            if (class_exists('\App\Models\Complaint')) {
                $complaints = \App\Models\Complaint::where('complainant_id', $user->id)
                    ->where('complainant_type', $userType)
                    ->latest()
                    ->take(3)
                    ->get();

                $complaintActivities = $complaints->map(function($complaint) {
                    return [
                        'type' => 'complaint',
                        'title' => $complaint->subject,
                        'date' => $complaint->created_at,
                        'status' => $complaint->is_read ? 'مقروءة' : 'غير مقروءة'
                    ];
                });

                $activities = $activities->merge($complaintActivities);
            }
        } catch (\Exception $e) {
            Log::warning('Complaints not accessible', ['error' => $e->getMessage()]);
        }

        // طلبات السحب
        try {
            if (class_exists('\App\Models\TowRequest')) {
                $towRequests = \App\Models\TowRequest::where('tow_provider_id', $user->id)
                    ->where('tow_provider_type', $userType)
                    ->latest()
                    ->take(2)
                    ->get();

                $towActivities = $towRequests->map(function($request) {
                    return [
                        'type' => 'tow_request',
                        'title' => 'طلب سحب جديد',
                        'date' => $request->created_at,
                        'status' => $request->status ?? 'في الانتظار'
                    ];
                });

                $activities = $activities->merge($towActivities);
            }
        } catch (\Exception $e) {
            Log::warning('TowRequest activities not accessible', ['error' => $e->getMessage()]);
        }

        return $activities->sortByDesc('date')->take(5);
    }

    private function getProfileDisplayData($user, $userType)
    {
        $companyInfo = [
            'name' => $userType === 'tow_service_company' ? ($user->legal_name ?? 'شركة خدمة سحب') : ($user->full_name ?? 'فرد خدمة سحب'),
            'logo' => $userType === 'tow_service_company' ? ($user->company_logo ?? null) : ($user->profile_image ?? null),
            'type' => $userType === 'tow_service_company' ? 'شركة خدمة سحب' : 'فرد خدمة سحب'
        ];

        $colors = [
            'primary' => $userType === 'tow_service_company' ? '#F59E0B' : '#8B5CF6',
            'secondary' => $userType === 'tow_service_company' ? '#D97706' : '#7C3AED'
        ];

        if ($userType === 'tow_service_company') {
            $displayFields = [
                'legal_name' => ['label' => 'اسم الشركة', 'icon' => 'fas fa-truck'],
                'phone' => ['label' => 'رقم الهاتف', 'icon' => 'fas fa-phone'],
                'commercial_register' => ['label' => 'السجل التجاري', 'icon' => 'fas fa-file-alt'],
                'tax_number' => ['label' => 'الرقم الضريبي', 'icon' => 'fas fa-calculator'],
                'office_address' => ['label' => 'عنوان المكتب', 'icon' => 'fas fa-map-marker-alt'],
                'daily_capacity' => ['label' => 'السعة اليومية', 'icon' => 'fas fa-tachometer-alt'],
                'delegate_number' => ['label' => 'رقم المندوب', 'icon' => 'fas fa-id-badge']
            ];
        } else {
            $displayFields = [
                'full_name' => ['label' => 'الاسم الكامل', 'icon' => 'fas fa-user'],
                'phone' => ['label' => 'رقم الهاتف', 'icon' => 'fas fa-phone'],
                'national_id' => ['label' => 'رقم الهوية', 'icon' => 'fas fa-id-card'],
                'tow_truck_plate_number' => ['label' => 'رقم لوحة السطحة', 'icon' => 'fas fa-truck'],
                'address' => ['label' => 'العنوان', 'icon' => 'fas fa-map-marker-alt']
            ];
        }

        return [
            'company_info' => $companyInfo,
            'display_fields' => $displayFields,
            'colors' => $colors
        ];
    }

    // دالة موحدة لجلب العروض
    private function getOffersForUser($userId, $userType)
    {
        try {
            if (class_exists('\App\Models\TowOffer')) {
                return \App\Models\TowOffer::where('tow_provider_id', $userId)
                    ->where('tow_provider_type', $userType)
                    ->latest()
                    ->paginate(10);
            }
        } catch (\Exception $e) {
            Log::warning('TowOffer table not accessible', ['error' => $e->getMessage()]);
        }
        
        return collect(); // إرجاع collection فارغة إذا لم يكن الجدول موجود
    }

    // دالة موحدة لمعالجة أكشن العروض
    private function handleOfferAction($offerId, $action, $userType)
    {
        try {
            if (class_exists('\App\Models\TowOffer')) {
                $user = $this->getCurrentUser();
                
                $offer = \App\Models\TowOffer::where('id', $offerId)
                    ->where('tow_provider_id', $user->id)
                    ->where('tow_provider_type', $userType)
                    ->firstOrFail();

                $status = $action === 'accept' ? 'accepted' : 'rejected';
                $offer->update(['status' => $status]);

                $message = $action === 'accept' ? 'تم قبول العرض بنجاح' : 'تم رفض العرض';
                
                Log::info("Tow offer {$action}ed", [
                    'offer_id' => $offerId,
                    'user_id' => $user->id,
                    'user_type' => $userType
                ]);

                return back()->with('success', $message);
            }
        } catch (\Exception $e) {
            Log::error("Failed to {$action} tow offer", [
                'offer_id' => $offerId,
                'error' => $e->getMessage()
            ]);

            return back()->withErrors(['error' => 'حدث خطأ أثناء معالجة العرض']);
        }

        return back()->withErrors(['error' => 'نظام العروض غير متوفر حالياً']);
    }
}
