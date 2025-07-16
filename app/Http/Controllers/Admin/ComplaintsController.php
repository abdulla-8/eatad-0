<?php
// app/Http/Controllers/Admin/ComplaintsController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Complaint;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class ComplaintsController extends Controller
{
    public function index(Request $request)
    {
        $query = Complaint::orderBy('created_at', 'desc');

        // Apply filters
        if ($request->type && in_array($request->type, ['inquiry', 'complaint', 'other'])) {
            $query->where('type', $request->type);
        }

        if ($request->status) {
            if ($request->status === 'read') {
                $query->where('is_read', true);
            } elseif ($request->status === 'unread') {
                $query->where('is_read', false);
            }
        }

        // تحديث فلترة نوع الشاكي لتشمل مستخدمي التأمين
        if ($request->complainant_type && in_array($request->complainant_type, ['insurance_company', 'service_center', 'insurance_user'])) {
            $query->where('complainant_type', $request->complainant_type);
        }

        // إضافة فلتر بحث محسن لمستخدمي التأمين
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('subject', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhere('complainant_name', 'like', '%' . $request->search . '%');
                  
                // البحث في بيانات مستخدمي التأمين
                if ($request->complainant_type === 'insurance_user' || !$request->complainant_type) {
                    $q->orWhereHas('insuranceUser', function($userQuery) use ($request) {
                        $userQuery->where('full_name', 'like', '%' . $request->search . '%')
                                  ->orWhere('phone', 'like', '%' . $request->search . '%')
                                  ->orWhere('national_id', 'like', '%' . $request->search . '%')
                                  ->orWhere('policy_number', 'like', '%' . $request->search . '%');
                    })
                    ->orWhereHas('insuranceUser.company', function($companyQuery) use ($request) {
                        $companyQuery->where('legal_name', 'like', '%' . $request->search . '%')
                                     ->orWhere('commercial_name', 'like', '%' . $request->search . '%');
                    });
                }
            });
        }

        $complaints = $query->paginate(10);

        // إضافة معلومات تفصيلية عن الشاكي لكل شكوى
        $complaints->getCollection()->transform(function($complaint) {
            $complaint->complainant_details = $this->getComplainantDetails($complaint);
            $complaint->formatted_complainant_info = $this->getFormattedComplainantInfo($complaint);
            return $complaint;
        });

        // Statistics - تحديث الإحصائيات لتشمل مستخدمي التأمين
        $stats = [
            'total' => Complaint::count(),
            'unread' => Complaint::where('is_read', false)->count(),
            'read' => Complaint::where('is_read', true)->count(),
            'inquiry' => Complaint::where('type', 'inquiry')->count(),
            'complaint' => Complaint::where('type', 'complaint')->count(),
            'other' => Complaint::where('type', 'other')->count(),
            'insurance_companies' => Complaint::where('complainant_type', 'insurance_company')->count(),
            'service_centers' => Complaint::where('complainant_type', 'service_center')->count(),
            'insurance_users' => Complaint::where('complainant_type', 'insurance_user')->count(),
        ];

        return view('admin.complaints.index', compact('complaints', 'stats'));
    }

    public function show($id)
    {
        $complaint = Complaint::findOrFail($id);
        
        // إضافة معلومات تفصيلية عن الشاكي
        $complaint->complainant_details = $this->getComplainantDetails($complaint);
        $complaint->formatted_complainant_info = $this->getFormattedComplainantInfo($complaint);
        
        $userType = 'admin';
        $translationGroup = 'admin';
        $primaryColor = '#3B82F6';
        $user = auth('admin')->user();

        return view('admin.complaints.show', compact('complaint', 'userType', 'translationGroup', 'primaryColor', 'user'));
    }

    public function markAsRead($id)
    {
        try {
            $complaint = Complaint::findOrFail($id);
            $complaint->markAsRead();

            Log::info('Complaint marked as read by admin', [
                'complaint_id' => $id,
                'complainant_type' => $complaint->complainant_type,
                'complainant_info' => $this->getComplainantInfoForLog($complaint),
                'admin_id' => auth('admin')->id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم تحديد الشكوى كمقروءة بنجاح'
            ]);
        } catch (\Exception $e) {
            Log::error('Error marking complaint as read', [
                'complaint_id' => $id,
                'error' => $e->getMessage(),
                'admin_id' => auth('admin')->id(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث حالة الشكوى'
            ], 500);
        }
    }

    public function markAsUnread($id)
    {
        try {
            $complaint = Complaint::findOrFail($id);
            $complaint->markAsUnread();

            Log::info('Complaint marked as unread by admin', [
                'complaint_id' => $id,
                'complainant_type' => $complaint->complainant_type,
                'complainant_info' => $this->getComplainantInfoForLog($complaint),
                'admin_id' => auth('admin')->id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم تحديد الشكوى كغير مقروءة بنجاح'
            ]);
        } catch (\Exception $e) {
            Log::error('Error marking complaint as unread', [
                'complaint_id' => $id,
                'error' => $e->getMessage(),
                'admin_id' => auth('admin')->id(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث حالة الشكوى'
            ], 500);
        }
    }

    // حذف شكوى واحدة
    public function destroy($id)
    {
        try {
            $complaint = Complaint::findOrFail($id);
            
            if ($complaint->attachment_path) {
                Storage::disk('public')->delete($complaint->attachment_path);
            }

            $complaint->delete();

            Log::info('Complaint deleted by admin', [
                'complaint_id' => $id,
                'complainant_type' => $complaint->complainant_type,
                'complainant_name' => $complaint->complainant_name,
                'complainant_info' => $this->getComplainantInfoForLog($complaint),
                'admin_id' => auth('admin')->id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم حذف الشكوى بنجاح'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting complaint', [
                'complaint_id' => $id,
                'error' => $e->getMessage(),
                'admin_id' => auth('admin')->id(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حذف الشكوى'
            ], 500);
        }
    }

    // Bulk delete للشكاوى المحددة
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'complaint_ids' => 'required|array|min:1',
            'complaint_ids.*' => 'integer|exists:complaints,id'
        ]);

        try {
            // الحصول على معلومات الشكاوى قبل الحذف للتسجيل
            $complaintsToDelete = Complaint::whereIn('id', $request->complaint_ids)->get();
            $typesCounts = $complaintsToDelete->groupBy('complainant_type')->map->count();
            
            $deletedCount = Complaint::bulkDelete($request->complaint_ids);

            Log::info('Bulk complaints deleted by admin', [
                'deleted_count' => $deletedCount,
                'complaint_ids' => $request->complaint_ids,
                'types_breakdown' => $typesCounts,
                'admin_id' => auth('admin')->id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => "تم حذف {$deletedCount} شكوى بنجاح"
            ]);
        } catch (\Exception $e) {
            Log::error('Error in bulk delete', [
                'error' => $e->getMessage(),
                'complaint_ids' => $request->complaint_ids,
                'admin_id' => auth('admin')->id(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حذف الشكاوى'
            ], 500);
        }
    }

    // حذف جميع الشكاوى مع تأكيد كلمة المرور
    public function deleteAll(Request $request)
    {
        $request->validate([
            'confirmation_password' => 'required|string|min:6'
        ]);

        try {
            $admin = auth('admin')->user();
            
            // التحقق من كلمة المرور
            if (!Hash::check($request->confirmation_password, $admin->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'كلمة المرور غير صحيحة'
                ], 422);
            }

            // الحصول على إحصائيات قبل الحذف
            $preDeleteStats = [
                'total' => Complaint::count(),
                'insurance_companies' => Complaint::where('complainant_type', 'insurance_company')->count(),
                'service_centers' => Complaint::where('complainant_type', 'service_center')->count(),
                'insurance_users' => Complaint::where('complainant_type', 'insurance_user')->count(),
                'unread' => Complaint::where('is_read', false)->count(),
            ];

            // حذف جميع الملفات المرفقة
            $complaintsWithAttachments = Complaint::whereNotNull('attachment_path')->get();
            foreach ($complaintsWithAttachments as $complaint) {
                if ($complaint->attachment_path) {
                    Storage::disk('public')->delete($complaint->attachment_path);
                }
            }

            $totalDeleted = Complaint::count();
            
            // حذف جميع الشكاوى
            Complaint::truncate();

            Log::warning('All complaints deleted by admin', [
                'deleted_count' => $totalDeleted,
                'pre_delete_stats' => $preDeleteStats,
                'admin_id' => $admin->id,
                'admin_email' => $admin->email,
                'timestamp' => now(),
                'ip_address' => $request->ip(),
            ]);

            return response()->json([
                'success' => true,
                'message' => "تم حذف جميع الشكاوى ({$totalDeleted}) بنجاح"
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting all complaints', [
                'error' => $e->getMessage(),
                'admin_id' => auth('admin')->id(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حذف جميع الشكاوى'
            ], 500);
        }
    }

    // تحديث حالة القراءة للشكاوى المحددة
    public function bulkMarkAsRead(Request $request)
    {
        $request->validate([
            'complaint_ids' => 'required|array|min:1',
            'complaint_ids.*' => 'integer|exists:complaints,id'
        ]);

        try {
            $complaintsToUpdate = Complaint::whereIn('id', $request->complaint_ids)->get();
            $typesCounts = $complaintsToUpdate->groupBy('complainant_type')->map->count();
            
            $updatedCount = Complaint::whereIn('id', $request->complaint_ids)
                ->update(['is_read' => true]);

            Log::info('Bulk complaints marked as read by admin', [
                'updated_count' => $updatedCount,
                'complaint_ids' => $request->complaint_ids,
                'types_breakdown' => $typesCounts,
                'admin_id' => auth('admin')->id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => "تم تحديد {$updatedCount} شكوى كمقروءة بنجاح"
            ]);
        } catch (\Exception $e) {
            Log::error('Error marking complaints as read', [
                'error' => $e->getMessage(),
                'complaint_ids' => $request->complaint_ids,
                'admin_id' => auth('admin')->id(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث حالة الشكاوى'
            ], 500);
        }
    }

    public function bulkMarkAsUnread(Request $request)
    {
        $request->validate([
            'complaint_ids' => 'required|array|min:1',
            'complaint_ids.*' => 'integer|exists:complaints,id'
        ]);

        try {
            $complaintsToUpdate = Complaint::whereIn('id', $request->complaint_ids)->get();
            $typesCounts = $complaintsToUpdate->groupBy('complainant_type')->map->count();
            
            $updatedCount = Complaint::whereIn('id', $request->complaint_ids)
                ->update(['is_read' => false]);

            Log::info('Bulk complaints marked as unread by admin', [
                'updated_count' => $updatedCount,
                'complaint_ids' => $request->complaint_ids,
                'types_breakdown' => $typesCounts,
                'admin_id' => auth('admin')->id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => "تم تحديد {$updatedCount} شكوى كغير مقروءة بنجاح"
            ]);
        } catch (\Exception $e) {
            Log::error('Error marking complaints as unread', [
                'error' => $e->getMessage(),
                'complaint_ids' => $request->complaint_ids,
                'admin_id' => auth('admin')->id(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث حالة الشكاوى'
            ], 500);
        }
    }

    public function exportSelected(Request $request)
    {
        $request->validate([
            'complaint_ids' => 'required|array|min:1',
            'complaint_ids.*' => 'integer|exists:complaints,id'
        ]);

        try {
            $complaints = Complaint::whereIn('id', $request->complaint_ids)
                ->orderBy('created_at', 'desc')
                ->get();

            $csvData = [];
            $csvData[] = ['ID', 'Type', 'Complainant Type', 'Subject', 'Complainant Name', 'User Details', 'Insurance Company', 'Status', 'Created At'];

            foreach ($complaints as $complaint) {
                $complainantDetails = $this->getComplainantDetails($complaint);
                $userDetails = '';
                $insuranceCompany = '';
                
                if ($complaint->complainant_type === 'insurance_user' && $complainantDetails) {
                    $userDetails = "Phone: {$complainantDetails->phone}, ID: {$complainantDetails->national_id}, Policy: {$complainantDetails->policy_number}";
                    $insuranceCompany = optional($complainantDetails->company)->legal_name ?? 'غير محدد';
                }
                
                $csvData[] = [
                    $complaint->id,
                    $complaint->type,
                    $this->getComplainantTypeLabel($complaint->complainant_type),
                    $complaint->subject,
                    $complaint->complainant_name,
                    $userDetails,
                    $insuranceCompany,
                    $complaint->is_read ? 'Read' : 'Unread',
                    $complaint->created_at->format('Y-m-d H:i:s')
                ];
            }

            $filename = 'complaints-' . date('Y-m-d-H-i-s') . '.csv';
            $filePath = storage_path('app/public/exports/' . $filename);

            $exportDir = storage_path('app/public/exports');
            if (!file_exists($exportDir)) {
                mkdir($exportDir, 0755, true);
            }

            // إضافة UTF-8 BOM للدعم الصحيح للعربية
            $file = fopen($filePath, 'w');
            fwrite($file, "\xEF\xBB\xBF"); // UTF-8 BOM
            foreach ($csvData as $row) {
                fputcsv($file, $row);
            }
            fclose($file);

            // إحصائيات التصدير
            $typesCounts = $complaints->groupBy('complainant_type')->map->count();

            Log::info('Complaints exported by admin', [
                'exported_count' => count($complaints),
                'types_breakdown' => $typesCounts,
                'filename' => $filename,
                'admin_id' => auth('admin')->id(),
            ]);

            return response()->download($filePath)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            Log::error('Error exporting complaints', [
                'error' => $e->getMessage(),
                'complaint_ids' => $request->complaint_ids,
                'admin_id' => auth('admin')->id(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تصدير الشكاوى'
            ], 500);
        }
    }

    /**
     * الحصول على تفاصيل الشاكي
     */
    private function getComplainantDetails($complaint)
    {
        try {
            switch ($complaint->complainant_type) {
                case 'insurance_company':
                    return \App\Models\InsuranceCompany::find($complaint->complainant_id);
                case 'service_center':
                    return \App\Models\ServiceCenter::find($complaint->complainant_id);
                case 'insurance_user':
                    return \App\Models\InsuranceUser::with('company')->find($complaint->complainant_id);
                default:
                    return null;
            }
        } catch (\Exception $e) {
            Log::error('Error fetching complainant details', [
                'complaint_id' => $complaint->id,
                'complainant_type' => $complaint->complainant_type,
                'complainant_id' => $complaint->complainant_id,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * تنسيق معلومات الشاكي للعرض
     */
    private function getFormattedComplainantInfo($complaint)
    {
        $details = $this->getComplainantDetails($complaint);
        
        if (!$details) {
            return [
                'type' => $this->getComplainantTypeLabel($complaint->complainant_type),
                'name' => $complaint->complainant_name,
                'details' => 'لا توجد تفاصيل إضافية'
            ];
        }

        switch ($complaint->complainant_type) {
            case 'insurance_company':
                return [
                    'type' => 'شركة تأمين',
                    'name' => $details->legal_name ?? $details->commercial_name,
                    'details' => "الرقم التجاري: {$details->commercial_number}, الهاتف: {$details->phone}"
                ];
                
            case 'service_center':
                return [
                    'type' => 'مركز صيانة',
                    'name' => $details->legal_name ?? $details->commercial_name,
                    'details' => "الرقم التجاري: {$details->commercial_number}, الهاتف: {$details->phone}"
                ];
                
            case 'insurance_user':
                return [
                    'type' => 'مستخدم تأمين',
                    'name' => $details->full_name,
                    'details' => "الهاتف: {$details->phone} | الهوية: {$details->national_id} | رقم البوليصة: {$details->policy_number}",
                    'company' => optional($details->company)->legal_name ?? 'غير محدد',
                    'user_id' => $details->id
                ];
                
            default:
                return [
                    'type' => 'غير محدد',
                    'name' => $complaint->complainant_name,
                    'details' => 'لا توجد تفاصيل إضافية'
                ];
        }
    }

    /**
     * معلومات الشاكي للتسجيل
     */
    private function getComplainantInfoForLog($complaint)
    {
        $details = $this->getComplainantDetails($complaint);
        
        if (!$details) {
            return [
                'type' => $complaint->complainant_type,
                'name' => $complaint->complainant_name,
                'id' => $complaint->complainant_id
            ];
        }

        $logInfo = [
            'type' => $complaint->complainant_type,
            'name' => $complaint->complainant_name,
            'id' => $complaint->complainant_id
        ];

        if ($complaint->complainant_type === 'insurance_user') {
            $logInfo['user_details'] = [
                'phone' => $details->phone,
                'national_id' => $details->national_id,
                'policy_number' => $details->policy_number,
                'company' => optional($details->company)->legal_name
            ];
        }

        return $logInfo;
    }

    /**
     * الحصول على تسمية نوع الشاكي
     */
    private function getComplainantTypeLabel($type)
    {
        switch ($type) {
            case 'insurance_company':
                return 'شركة تأمين';
            case 'service_center':
                return 'مركز صيانة';
            case 'insurance_user':
                return 'مستخدم تأمين';
            default:
                return 'غير محدد';
        }
    }
}
