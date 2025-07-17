<?php
// app/Http/Controllers/Complaints/UnifiedComplaintsController.php

namespace App\Http\Controllers\Complaints;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Complaint;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ComplaintsManagementController extends Controller
{
    public function index(Request $request)
    {
        $userType = $this->getCurrentUserType();
        $user = $this->getCurrentUser();
        
        if (!$user) {
            abort(403, 'المستخدم غير مسجل الدخول');
        }

        // الحصول على companySlug من الراوت
        $companySlug = $request->route('companySlug');
        $routeName = $request->route()->getName();
        
        // Debug مفصل للتحقق من البيانات
        Log::info('DEBUG: Full User Data', [
            'user_id' => $user->id,
            'user_type' => $userType,
            'route_name' => $routeName,
            'company_slug_from_route' => $companySlug,
            'user_data' => $user->toArray(),
            'insurance_company_id' => $user->insurance_company_id ?? 'NOT SET'
        ]);

        // تحديد نوع المستخدم بناءً على الراوت الحالي
        $filterUserType = $this->detectRouteUserType($routeName, $userType);
        
        // التأكد من أن المستخدم يصل للراوت الصحيح
        if ($userType !== $filterUserType) {
            Log::warning('User type mismatch', [
                'expected' => $filterUserType,
                'actual' => $userType,
                'route' => $routeName
            ]);
            abort(403, 'غير مسموح لك بالوصول لهذه الصفحة');
        }

        // الحل الجديد - التحقق من الشركة لمستخدمي التأمين
        if ($userType === 'insurance_user' && $companySlug) {
            // جلب الشركة مباشرة من قاعدة البيانات
            $company = \App\Models\InsuranceCompany::find($user->insurance_company_id);
            
            Log::info('DEBUG: Company Direct Query', [
                'user_id' => $user->id,
                'insurance_company_id' => $user->insurance_company_id,
                'company_found' => $company ? 'YES' : 'NO',
                'company_data' => $company ? $company->toArray() : 'NULL'
            ]);
            
            if (!$company) {
                Log::error('No company found for user', [
                    'user_id' => $user->id,
                    'insurance_company_id' => $user->insurance_company_id,
                    'route_company_slug' => $companySlug
                ]);
                abort(403, 'المستخدم غير مرتبط بأي شركة تأمين');
            }
            
            Log::info('DEBUG: Company Slug Comparison', [
                'company_slug_from_db' => $company->company_slug,
                'company_slug_from_route' => $companySlug,
                'are_equal' => $company->company_slug === $companySlug
            ]);
            
            if ($company->company_slug !== $companySlug) {
                abort(403, "غير مسموح لك بالوصول لهذه الشركة. شركتك: {$company->company_slug}, المطلوب: {$companySlug}");
            }
            
            // حفظ الشركة في المستخدم للاستخدام اللاحق
            $user->setRelation('company', $company);
        }

        // تحسين الـ query مع فلترة مشددة لعرض الشكاوى الخاصة بالمستخدم فقط
        $query = Complaint::where('complainant_type', $filterUserType)
            ->where('complainant_id', $user->id)
            ->latest();

        // Apply filters
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('is_read', $request->status === 'read');
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('subject', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $complaints = $query->paginate(5);

        // Statistics محسنة مع فلترة مشددة للمستخدم الحالي فقط
        $baseQuery = Complaint::where('complainant_type', $filterUserType)
            ->where('complainant_id', $user->id);

        $stats = [
            'total' => $baseQuery->count(),
            'unread' => $baseQuery->where('is_read', false)->count(),
            'read' => $baseQuery->where('is_read', true)->count(),
            'inquiry' => $baseQuery->where('type', 'inquiry')->count(),
            'complaint' => $baseQuery->where('type', 'complaint')->count(),
            'other' => $baseQuery->where('type', 'other')->count(),
        ];

        $translationGroup = $this->getTranslationGroup($user, $userType);
        $primaryColor = $this->getPrimaryColor($user, $userType);
        
        // إضافة متغير الشركة بحسب نوع المستخدم
        $company = $this->getCompanyForView($user, $userType);

        // Log للتتبع
        Log::info('Successfully accessed complaints', [
            'user_type' => $userType,
            'filter_user_type' => $filterUserType,
            'user_id' => $user->id,
            'company_slug' => $companySlug,
            'route_name' => $routeName,
            'complaints_count' => $complaints->count()
        ]);

        return view('complaints.index', compact('complaints', 'company', 'stats', 'user', 'userType', 'translationGroup', 'primaryColor'));
    }

    public function store(Request $request)
    {
        $userType = $this->getCurrentUserType();
        $user = $this->getCurrentUser();
        
        if (!$user) {
            abort(403);
        }

        $routeName = $request->route()->getName();
        $filterUserType = $this->detectRouteUserType($routeName, $userType);
        
        if ($userType !== $filterUserType) {
            abort(403, 'غير مسموح لك بالوصول لهذه الصفحة');
        }

        // التحقق من الشركة لمستخدمي التأمين
        $companySlug = $request->route('companySlug');
        if ($userType === 'insurance_user' && $companySlug) {
            $company = \App\Models\InsuranceCompany::find($user->insurance_company_id);
            
            if (!$company) {
                abort(403, 'المستخدم غير مرتبط بأي شركة تأمين');
            }
            
            if ($company->company_slug !== $companySlug) {
                abort(403, 'غير مسموح لك بالوصول لهذه الشركة');
            }
            
            $user->setRelation('company', $company);
        }

        $request->validate([
            'type' => 'required|in:inquiry,complaint,other',
            'subject' => 'required|string|max:500',
            'description' => 'required|string|max:2000',
            'attachment' => 'nullable|file|mimes:jpeg,png,jpg,pdf,doc,docx|max:5120'
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = time() . '_' . $file->getClientOriginalName();
            $attachmentPath = $file->storeAs('complaints', $filename, 'public');
        }

        $complaint = Complaint::create([
            'complainant_type' => $filterUserType,
            'complainant_id' => $user->id,
            'complainant_name' => $this->getComplainantName($user, $filterUserType),
            'type' => $request->type,
            'subject' => $request->subject,
            'description' => $request->description,
            'attachment_path' => $attachmentPath,
            'is_read' => false
        ]);

        Log::info('Complaint Created', [
            'complaint_id' => $complaint->id,
            'complainant_type' => $filterUserType,
            'complainant_id' => $user->id,
            'company_slug' => $companySlug,
            'subject' => $request->subject,
            'route_name' => $routeName
        ]);

        $redirectRoute = $this->getRedirectRoute($filterUserType, $user, $companySlug);
        
        return redirect()->route($redirectRoute['name'], $redirectRoute['params'] ?? [])
            ->with('success', 'تم إرسال الشكوى بنجاح');
    }

    // تم إصلاح هذه الدالة لمعالجة المعاملات بشكل ديناميكي
    public function show(Request $request, ...$params)
    {
        // استخراج الـ ID من آخر parameter (مناسب لجميع الـ routes)
        $id = end($params);
        
        Log::info('Show method called with dynamic params', [
            'all_params' => $params,
            'extracted_id' => $id,
            'route' => $request->route()->getName(),
            'route_params' => $request->route()->parameters()
        ]);
        
        return $this->viewOrEdit($request, $id, 'show');
    }

    // تم إصلاح هذه الدالة لمعالجة المعاملات بشكل ديناميكي
    public function edit(Request $request, ...$params)
    {
        // استخراج الـ ID من آخر parameter (مناسب لجميع الـ routes)
        $id = end($params);
        
        Log::info('Edit method called with dynamic params', [
            'all_params' => $params,
            'extracted_id' => $id,
            'route' => $request->route()->getName()
        ]);
        
        return $this->viewOrEdit($request, $id, 'edit');
    }

    // تم إصلاح هذه الدالة لمعالجة المعاملات بشكل ديناميكي
    public function update(Request $request, ...$params)
    {
        // استخراج الـ ID من آخر parameter (مناسب لجميع الـ routes)
        $id = end($params);
        
        Log::info('Update method called with dynamic params', [
            'all_params' => $params,
            'extracted_id' => $id,
            'route' => $request->route()->getName()
        ]);

        $userType = $this->getCurrentUserType();
        $user = $this->getCurrentUser();
        
        if (!$user) {
            abort(403);
        }

        $routeName = $request->route()->getName();
        $filterUserType = $this->detectRouteUserType($routeName, $userType);
        
        if ($userType !== $filterUserType) {
            abort(403, 'غير مسموح لك بالوصول لهذه الصفحة');
        }

        // التحقق من الشركة لمستخدمي التأمين
        $companySlug = $request->route('companySlug');
        if ($userType === 'insurance_user' && $companySlug) {
            $company = \App\Models\InsuranceCompany::find($user->insurance_company_id);
            
            if (!$company) {
                abort(403, 'المستخدم غير مرتبط بأي شركة تأمين');
            }
            
            if ($company->company_slug !== $companySlug) {
                abort(403, 'غير مسموح لك بالوصول لهذه الشركة');
            }
            
            $user->setRelation('company', $company);
        }

        $complaint = Complaint::where([
            'id' => $id,
            'complainant_id' => $user->id,
            'complainant_type' => $filterUserType,
        ])->firstOrFail();

        $request->validate([
            'type' => 'required|in:inquiry,complaint,other',
            'subject' => 'required|string|max:500',
            'description' => 'required|string|max:2000',
            'attachment' => 'nullable|file|mimes:jpeg,png,jpg,pdf,doc,docx|max:5120'
        ]);

        $attachmentPath = $complaint->attachment_path;
        
        if ($request->hasFile('attachment')) {
            if ($attachmentPath) {
                Storage::disk('public')->delete($attachmentPath);
            }
            
            $file = $request->file('attachment');
            $filename = time() . '_' . $file->getClientOriginalName();
            $attachmentPath = $file->storeAs('complaints', $filename, 'public');
        }

        $complaint->update([
            'type' => $request->type,
            'subject' => $request->subject,
            'description' => $request->description,
            'attachment_path' => $attachmentPath,
            'is_read' => false
        ]);

        Log::info('Complaint Updated', [
            'complaint_id' => $complaint->id,
            'complainant_type' => $filterUserType,
            'complainant_id' => $user->id,
            'company_slug' => $companySlug,
            'subject' => $request->subject,
            'route_name' => $routeName
        ]);

        $redirectRoute = $this->getRedirectRoute($filterUserType, $user, $companySlug);
        
        return redirect()->route($redirectRoute['name'], $redirectRoute['params'] ?? [])
            ->with('success', 'تم تحديث الشكوى بنجاح');
    }

    private function viewOrEdit(Request $request, $id, $view)
    {
        // Debug: البداية
        Log::info('DEBUG: ViewOrEdit Method Started', [
            'complaint_id' => $id,
            'view' => $view,
            'route_name' => $request->route()->getName(),
            'route_params' => $request->route()->parameters()
        ]);

        $userType = $this->getCurrentUserType();
        $user = $this->getCurrentUser();
        
        if (!$user) {
            Log::error('DEBUG: No authenticated user found');
            abort(403, 'المستخدم غير مسجل الدخول');
        }

        // Debug: معلومات المستخدم
        Log::info('DEBUG: User Information', [
            'user_id' => $user->id,
            'user_type' => $userType,
            'user_company_id' => $user->insurance_company_id ?? 'NOT SET',
            'user_company_slug' => $user->company_slug ?? 'NOT SET'
        ]);

        $routeName = $request->route()->getName();
        $filterUserType = $this->detectRouteUserType($routeName, $userType);
        
        // Debug: نوع المستخدم المفلتر
        Log::info('DEBUG: User Type Detection', [
            'route_name' => $routeName,
            'authenticated_user_type' => $userType,
            'filter_user_type' => $filterUserType
        ]);
        
        if ($userType !== $filterUserType) {
            Log::error('DEBUG: User type mismatch in viewOrEdit', [
                'expected' => $filterUserType,
                'actual' => $userType,
                'route' => $routeName
            ]);
            abort(403, 'غير مسموح لك بالوصول لهذه الصفحة');
        }

        // التعامل مع معاملات مختلفة حسب نوع المستخدم
        if ($userType === 'insurance_company') {
            $companyRoute = $request->route('companyRoute');
            
            Log::info('DEBUG: Insurance Company Route Access', [
                'company_route_param' => $companyRoute,
                'user_company_slug' => $user->company_slug,
                'complaint_id' => $id,
                'route_params' => $request->route()->parameters()
            ]);
            
            if ($companyRoute && $user->company_slug !== $companyRoute) {
                Log::error('DEBUG: Company route mismatch', [
                    'expected' => $companyRoute,
                    'actual' => $user->company_slug
                ]);
                abort(403, 'غير مسموح لك بالوصول لهذه الشركة');
            }
        } elseif ($userType === 'insurance_user') {
            $companySlug = $request->route('companySlug');
            
            Log::info('DEBUG: Insurance User Route Access', [
                'company_slug_param' => $companySlug,
                'user_insurance_company_id' => $user->insurance_company_id,
                'complaint_id' => $id,
                'route_params' => $request->route()->parameters()
            ]);
            
            $company = \App\Models\InsuranceCompany::find($user->insurance_company_id);
            
            Log::info('DEBUG: Insurance User Company Query', [
                'company_found' => $company ? 'YES' : 'NO',
                'company_data' => $company ? $company->toArray() : 'NULL'
            ]);
            
            if (!$company) {
                Log::error('DEBUG: No company found for insurance user', [
                    'user_id' => $user->id,
                    'insurance_company_id' => $user->insurance_company_id
                ]);
                abort(403, 'المستخدم غير مرتبط بأي شركة تأمين');
            }
            
            if ($company->company_slug !== $companySlug) {
                Log::error('DEBUG: Company slug mismatch for insurance user', [
                    'expected' => $companySlug,
                    'actual' => $company->company_slug
                ]);
                abort(403, 'غير مسموح لك بالوصول لهذه الشركة');
            }
            
            $user->setRelation('company', $company);
        }

        // Debug: التحقق من وجود الشكوى قبل البحث
        Log::info('DEBUG: Before Complaint Query', [
            'complaint_id' => $id,
            'complainant_id' => $user->id,
            'complainant_type' => $filterUserType
        ]);

        // البحث المفصل عن الشكوى
        $complaintQuery = Complaint::where('id', $id);
        
        // Debug: التحقق من وجود الشكوى بالـ ID فقط
        $complaintExists = $complaintQuery->exists();
        Log::info('DEBUG: Complaint Exists Check', [
            'complaint_id' => $id,
            'exists' => $complaintExists
        ]);

        if (!$complaintExists) {
            Log::error('DEBUG: Complaint not found by ID', [
                'complaint_id' => $id
            ]);
            abort(404, 'الشكوى غير موجودة');
        }

        // البحث مع فلترة المستخدم
        $complaint = Complaint::where([
            'id' => $id,
            'complainant_id' => $user->id,
            'complainant_type' => $filterUserType,
        ])->first();

        // Debug: نتيجة البحث مع الفلترة
        Log::info('DEBUG: Complaint Query Result', [
            'complaint_found' => $complaint ? 'YES' : 'NO',
            'complaint_data' => $complaint ? $complaint->toArray() : 'NULL'
        ]);

        if (!$complaint) {
            // Debug: البحث عن الشكوى بدون فلترة للتحقق من البيانات
            $anyComplaint = Complaint::where('id', $id)->first();
            
            Log::error('DEBUG: Complaint ownership check failed', [
                'complaint_id' => $id,
                'expected_complainant_id' => $user->id,
                'expected_complainant_type' => $filterUserType,
                'actual_complainant_id' => $anyComplaint ? $anyComplaint->complainant_id : 'NULL',
                'actual_complainant_type' => $anyComplaint ? $anyComplaint->complainant_type : 'NULL',
                'complaint_data' => $anyComplaint ? $anyComplaint->toArray() : 'NULL'
            ]);
            
            abort(404, 'الشكوى غير موجودة أو غير مسموح لك بالوصول إليها');
        }

        // تحديد القراءة عند العرض
        if ($view === 'show' && !$complaint->is_read) {
            $complaint->update(['is_read' => true]);
            Log::info('DEBUG: Complaint marked as read', ['complaint_id' => $id]);
        }

        $translationGroup = $this->getTranslationGroup($user, $userType);
        $primaryColor = $this->getPrimaryColor($user, $userType);
        $company = $this->getCompanyForView($user, $userType);

        Log::info('DEBUG: Successfully accessed complaint', [
            'complaint_id' => $id,
            'user_type' => $userType,
            'view' => $view,
            'route_name' => $routeName
        ]);

        return view("complaints.$view", compact('complaint', 'user', 'userType', 'translationGroup', 'primaryColor', 'company'));
    }

    private function detectRouteUserType(string $routeName, string $authType): string
    {
        Log::info('Detecting route user type', [
            'route_name' => $routeName,
            'auth_type' => $authType
        ]);
        
        if (str_contains($routeName, 'insurance.user.complaints')) {
            return 'insurance_user';
        }
        
        if (str_contains($routeName, 'insurance.complaints')) {
            return 'insurance_company';
        }
        
        if (str_contains($routeName, 'service-center.complaints')) {
            return 'service_center';
        }
        
        return $authType;
    }

    private function getCompanyForView($user, $userType)
    {
        switch ($userType) {
            case 'insurance_company':
                return $user;
            case 'service_center':
                return (object) [
                    'translation_group' => 'service_center',
                    'legal_name' => $user->legal_name ?? 'مركز صيانة',
                    'primary_color' => '#10B981',
                    'company_slug' => 'service-center'
                ];
            case 'insurance_user':
                // تأكد من تحميل العلاقة
                if (!$user->relationLoaded('company')) {
                    $user->load('company');
                }
                
                return $user->company ?? (object) [
                    'translation_group' => 'default',
                    'legal_name' => 'لوحة التحكم',
                    'primary_color' => '#3B82F6',
                    'company_slug' => 'default'
                ];
            default:
                return (object) [
                    'translation_group' => 'default',
                    'legal_name' => 'لوحة التحكم',
                    'primary_color' => '#3B82F6',
                    'company_slug' => 'default'
                ];
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
            return auth('insurance_user')->user()->load('company'); // تحميل العلاقة
        }
        
        return null;
    }

    private function getComplainantName($user, $userType)
    {
        switch ($userType) {
            case 'insurance_company':
                return $user->legal_name ?? $user->name ?? 'شركة تأمين';
            case 'service_center':
                return $user->legal_name ?? $user->name ?? 'مركز صيانة';
            case 'insurance_user':
                return $user->full_name ?? $user->name ?? 'مستخدم التأمين';
            default:
                return 'غير محدد';
        }
    }

    private function getTranslationGroup($user, $userType)
    {
        switch ($userType) {
            case 'insurance_company':
                return $user->translation_group ?? 'default';
            case 'service_center':
                return 'service_center';
            case 'insurance_user':
                return $user->company?->translation_group ?? 'default';
            default:
                return 'default';
        }
    }

    private function getPrimaryColor($user, $userType)
    {
        switch ($userType) {
            case 'insurance_company':
                return $user->primary_color ?? '#3B82F6';
            case 'service_center':
                return '#10B981';
            case 'insurance_user':
                return $user->company?->primary_color ?? '#3B82F6';
            default:
                return '#3B82F6';
        }
    }

    private function getRedirectRoute($userType, $user, $companySlug = null)
    {
        switch ($userType) {
            case 'insurance_company':
                return [
                    'name' => 'insurance.complaints.index',
                    'params' => ['companyRoute' => $user->company_slug]
                ];
            case 'service_center':
                return [
                    'name' => 'service-center.complaints.index'
                ];
            case 'insurance_user':
                $company = $user->company ?? \App\Models\InsuranceCompany::find($user->insurance_company_id);
                return [
                    'name' => 'insurance.user.complaints.index',
                    'params' => ['companySlug' => $companySlug ?? $company?->company_slug ?? 'default']
                ];
            default:
                return ['name' => 'complaints.index'];
        }
    }
}
