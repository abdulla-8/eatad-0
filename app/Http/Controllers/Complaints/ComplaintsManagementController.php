<?php
// app/Http/Controllers/Complaints/ComplaintsManagementController.php

namespace App\Http\Controllers\Complaints;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Complaint;
use App\Models\InsuranceCompany;
use App\Models\ServiceCenter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ComplaintsManagementController extends Controller
{
    public function index(Request $request)
    {
        $userType = $this->getCurrentUserType();
        $user = $this->getCurrentUser();
        
        if (!$user) {
            abort(403);
        }

        // تحديد نوع المستخدم بناءً على الراوت الحالي
        $routeName = $request->route()->getName();
        
        if (str_contains($routeName, 'insurance')) {
            $filterUserType = 'insurance_company';
        } elseif (str_contains($routeName, 'service-center')) {
            $filterUserType = 'service_center';
        } else {
            $filterUserType = $userType;
        }
        
        // التأكد من أن المستخدم يصل للراوت الصحيح
        if ($userType !== $filterUserType) {
            abort(403, 'غير مسموح لك بالوصول لهذه الصفحة');
        }

        // تحسين الـ query مع فلترة مشددة
        $query = Complaint::where('complainant_type', $filterUserType)
            ->where('complainant_id', $user->id);
        
        $query->orderBy('created_at', 'desc');

        // Apply filters
        if ($request->type) {
            $query->where('type', $request->type);
        }

        if ($request->status) {
            if ($request->status === 'read') {
                $query->where('is_read', true);
            } elseif ($request->status === 'unread') {
                $query->where('is_read', false);
            }
        }

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('subject', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $complaints = $query->paginate(5);

        // Statistics محسنة مع فلترة مشددة
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

        // Log للتتبع
        Log::info('Complaints Index Access', [
            'user_type' => $userType,
            'filter_user_type' => $filterUserType,
            'user_id' => $user->id,
            'route_name' => $routeName,
            'complaints_count' => $complaints->count()
        ]);

        // استخدام الـ view الموحد
        return view('complaints.index', compact('complaints', 'stats', 'user', 'userType', 'translationGroup', 'primaryColor'));
    }

    public function store(Request $request)
    {
        $userType = $this->getCurrentUserType();
        $user = $this->getCurrentUser();
        
        if (!$user) {
            abort(403);
        }

        // تحديد نوع المستخدم بناءً على الراوت الحالي
        $routeName = $request->route()->getName();
        
        if (str_contains($routeName, 'insurance')) {
            $filterUserType = 'insurance_company';
        } elseif (str_contains($routeName, 'service-center')) {
            $filterUserType = 'service_center';
        } else {
            $filterUserType = $userType;
        }
        
        // التأكد من أن المستخدم يصل للراوت الصحيح
        if ($userType !== $filterUserType) {
            abort(403, 'غير مسموح لك بالوصول لهذه الصفحة');
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

        // إنشاء الشكوى مع تأكيد نوع المستخدم
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

        // Log إنشاء الشكوى
        Log::info('Complaint Created', [
            'complaint_id' => $complaint->id,
            'complainant_type' => $filterUserType,
            'complainant_id' => $user->id,
            'subject' => $request->subject,
            'route_name' => $routeName
        ]);

        // إعادة التوجيه حسب نوع المستخدم
        $redirectRoute = $this->getRedirectRoute($filterUserType, $user);
        
        return redirect()->route($redirectRoute['name'], $redirectRoute['params'] ?? [])
            ->with('success', 'تم إرسال الشكوى بنجاح');
    }

    public function show(Request $request, $companyRouteOrId, $id = null)
    {
        $userType = $this->getCurrentUserType();
        $user = $this->getCurrentUser();
        
        if (!$user) {
            abort(403);
        }

        // تحديد نوع المستخدم بناءً على الراوت الحالي
        $routeName = $request->route()->getName();
        
        if (str_contains($routeName, 'insurance')) {
            $filterUserType = 'insurance_company';
        } elseif (str_contains($routeName, 'service-center')) {
            $filterUserType = 'service_center';
        } else {
            $filterUserType = $userType;
        }
        
        // التأكد من أن المستخدم يصل للراوت الصحيح
        if ($userType !== $filterUserType) {
            abort(403, 'غير مسموح لك بالوصول لهذه الصفحة');
        }

        // تحديد الـ ID الصحيح حسب نوع المستخدم
        if ($filterUserType === 'insurance_company') {
            // للتأمين: المعامل الأول هو companyRoute والثاني هو ID
            $complaintId = $id;
        } else {
            // لمركز الصيانة: المعامل الأول هو ID مباشرة
            $complaintId = $companyRouteOrId;
        }

        // البحث عن الشكوى مع تأكيد مضاعف من نوع المستخدم
        $complaint = Complaint::where('complainant_type', $filterUserType)
            ->where('complainant_id', $user->id)
            ->where('id', $complaintId)
            ->firstOrFail();

        // تأكيد إضافي من الملكية
        if ($complaint->complainant_type !== $filterUserType || $complaint->complainant_id !== $user->id) {
            Log::warning('Unauthorized complaint access attempt', [
                'complaint_id' => $complaintId,
                'complaint_type' => $complaint->complainant_type,
                'complaint_owner' => $complaint->complainant_id,
                'current_user_type' => $filterUserType,
                'current_user_id' => $user->id,
                'route_name' => $routeName
            ]);
            abort(403, 'غير مسموح لك بالوصول لهذه الشكوى');
        }

        $translationGroup = $this->getTranslationGroup($user, $userType);
        $primaryColor = $this->getPrimaryColor($user, $userType);

        // استخدام الـ view الموحد
        return view('complaints.show', compact('complaint', 'user', 'userType', 'translationGroup', 'primaryColor'));
    }

    private function getCurrentUserType()
    {
        if (auth('insurance_company')->check()) {
            return 'insurance_company';
        } elseif (auth('service_center')->check()) {
            return 'service_center';
        }
        
        return null;
    }

    private function getCurrentUser()
    {
        if (auth('insurance_company')->check()) {
            return auth('insurance_company')->user();
        } elseif (auth('service_center')->check()) {
            return auth('service_center')->user();
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
            default:
                return '#3B82F6';
        }
    }

    private function getRedirectRoute($userType, $user)
    {
        switch ($userType) {
            case 'insurance_company':
                return [
                    'name' => 'insurance.complaints.index',
                    'params' => ['companyRoute' => $user->company_slug ?? 'default']
                ];
            case 'service_center':
                return [
                    'name' => 'service-center.complaints.index'
                ];
            default:
                return ['name' => 'complaints.index'];
        }
    }
}
