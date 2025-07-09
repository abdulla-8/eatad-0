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

class ComplaintsManagementController extends Controller
{
    public function index(Request $request)
    {
        $userType = $this->getCurrentUserType();
        $user = $this->getCurrentUser();
        
        if (!$user) {
            abort(403);
        }

        $query = Complaint::where('complainant_type', $userType)
            ->where('complainant_id', $user->id)
            ->orderBy('created_at', 'desc');

        // Apply filters
        if ($request->type) {
            $query->byType($request->type);
        }

        if ($request->status) {
            $query->byStatus($request->status);
        }

        if ($request->search) {
            $query->search($request->search);
        }

        $complaints = $query->paginate(10);

        // Statistics
        $stats = [
            'total' => Complaint::where('complainant_type', $userType)->where('complainant_id', $user->id)->count(),
            'unread' => Complaint::where('complainant_type', $userType)->where('complainant_id', $user->id)->where('is_read', false)->count(),
            'read' => Complaint::where('complainant_type', $userType)->where('complainant_id', $user->id)->where('is_read', true)->count(),
            'inquiry' => Complaint::where('complainant_type', $userType)->where('complainant_id', $user->id)->where('type', 'inquiry')->count(),
            'complaint' => Complaint::where('complainant_type', $userType)->where('complainant_id', $user->id)->where('type', 'complaint')->count(),
            'other' => Complaint::where('complainant_type', $userType)->where('complainant_id', $user->id)->where('type', 'other')->count(),
        ];

        $translationGroup = $this->getTranslationGroup($user, $userType);
        $primaryColor = $this->getPrimaryColor($user, $userType);

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

        Complaint::create([
            'complainant_type' => $userType,
            'complainant_id' => $user->id,
            'complainant_name' => $this->getComplainantName($user, $userType),
            'type' => $request->type,
            'subject' => $request->subject,
            'description' => $request->description,
            'attachment_path' => $attachmentPath,
            'is_read' => false
        ]);

        // إعادة التوجيه حسب نوع المستخدم
        $redirectRoute = $this->getRedirectRoute($userType, $user);
        
        return redirect()->route($redirectRoute['name'], $redirectRoute['params'] ?? [])
            ->with('success', 'تم إرسال الشكوى بنجاح');
    }

    // تحديث دالة الـ show لتدعم companyRoute
    public function show(Request $request, $companyRouteOrId, $id = null)
    {
        $userType = $this->getCurrentUserType();
        $user = $this->getCurrentUser();
        
        if (!$user) {
            abort(403);
        }

        // تحديد الـ ID الصحيح حسب نوع المستخدم
        if ($userType === 'insurance_company') {
            // للتأمين: المعامل الأول هو companyRoute والثاني هو ID
            $complaintId = $id;
        } else {
            // لمركز الصيانة: المعامل الأول هو ID مباشرة
            $complaintId = $companyRouteOrId;
        }

        $complaint = Complaint::where('complainant_type', $userType)
            ->where('complainant_id', $user->id)
            ->where('id', $complaintId)
            ->firstOrFail();

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
