<?php
// app/Http/Controllers/Admin/ComplaintsController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Complaint;

class ComplaintsController extends Controller
{
    public function index(Request $request)
    {
        $query = Complaint::orderBy('created_at', 'desc');

        // Apply filters
        if ($request->type) {
            $query->byType($request->type);
        }

        if ($request->status) {
            $query->byStatus($request->status);
        }

        if ($request->complainant_type) {
            $query->where('complainant_type', $request->complainant_type);
        }

        if ($request->search) {
            $query->search($request->search);
        }

        $complaints = $query->paginate(15);

        // Statistics - إضافة الإحصائيات المفقودة
        $stats = [
            'total' => Complaint::count(),
            'unread' => Complaint::where('is_read', false)->count(),
            'read' => Complaint::where('is_read', true)->count(),
            'inquiry' => Complaint::where('type', 'inquiry')->count(),
            'complaint' => Complaint::where('type', 'complaint')->count(),
            'other' => Complaint::where('type', 'other')->count(),
            'insurance_companies' => Complaint::where('complainant_type', 'insurance_company')->count(),
            'service_centers' => Complaint::where('complainant_type', 'service_center')->count(),
        ];

        return view('admin.complaints.index', compact('complaints', 'stats'));
    }

    public function show($id)
    {
        $complaint = Complaint::findOrFail($id);
        
        // إضافة المتغيرات المطلوبة للـ view
        $userType = 'admin';
        $translationGroup = 'admin';
        $primaryColor = '#3B82F6'; // اللون الأزرق الافتراضي للإدارة
        $user = auth('admin')->user();

        return view('admin.complaints.show', compact('complaint', 'userType', 'translationGroup', 'primaryColor', 'user'));
    }

    public function markAsRead($id)
    {
        $complaint = Complaint::findOrFail($id);
        $complaint->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'message' => t('admin.complaint_marked_as_read')
        ]);
    }

    public function markAsUnread($id)
    {
        $complaint = Complaint::findOrFail($id);
        $complaint->update(['is_read' => false]);

        return response()->json([
            'success' => true,
            'message' => t('admin.complaint_marked_as_unread')
        ]);
    }
}
