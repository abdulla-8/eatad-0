<?php

namespace App\Http\Controllers\ServiceCenter;

use App\Http\Controllers\Controller;
use App\Models\ServiceCenter;
use App\Models\InsuranceCompany;
use App\Models\Claim; // استخدام موديل Claim الصحيح
use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $serviceCenter = Auth::guard('service_center')->user();

        // Load insurance company if exists
        $insuranceCompany = null;
        if ($serviceCenter->insurance_company_id) {
            $insuranceCompany = InsuranceCompany::find($serviceCenter->insurance_company_id);
        }

        // الحصول على الطلبات (Claims) المخصصة لهذا المركز
        // تحتاج لمعرفة اسم العمود الصحيح في جدول claims للربط مع مركز الصيانة
        $serviceCenterClaims = Claim::where('service_center_id', $serviceCenter->id)->get();
        // أو إذا كان اسم العمود مختلف:
        // $serviceCenterClaims = Claim::where('assigned_service_center_id', $serviceCenter->id)->get();
        
        // Get complaints for this center using the correct structure
        $centerComplaints = Complaint::where('complainant_type', 'service_center')
            ->where('complainant_id', $serviceCenter->id)
            ->get();
        
        // Claims/Requests by status
        $claimsByStatus = $serviceCenterClaims->groupBy('status')->map(function ($claims) {
            return $claims->count();
        });

        // Complaints by status
        $complaintsByStatus = $centerComplaints->groupBy('status')->map(function ($complaints) {
            return $complaints->count();
        });

        // Claims by month (last 6 months)
        $claimsByMonth = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthName = $month->format('M Y');
            
            $claimsCount = $serviceCenterClaims->filter(function ($claim) use ($month) {
                return Carbon::parse($claim->created_at)->format('Y-m') === $month->format('Y-m');
            })->count();
            
            $claimsByMonth[] = [
                'month' => $monthName,
                'count' => $claimsCount
            ];
        }

        // Technicians data
        $techniciansData = [
            'body_work' => $serviceCenter->body_work_technicians ?? 0,
            'mechanical' => $serviceCenter->mechanical_technicians ?? 0,
            'painting' => $serviceCenter->painting_technicians ?? 0,
            'electrical' => $serviceCenter->electrical_technicians ?? 0,
            'other' => $serviceCenter->other_technicians ?? 0,
        ];

        $stats = [
            'center_info' => [
                'legal_name' => $serviceCenter->legal_name,
                'phone' => $serviceCenter->phone,
                'commercial_register' => $serviceCenter->commercial_register,
                'center_address' => $serviceCenter->center_address,
                'member_since' => $serviceCenter->created_at->format('Y/m/d'),
                'last_login' => $serviceCenter->updated_at->format('Y/m/d H:i'),
                'industrial_area' => $serviceCenter->industrial_area_id ? 'المنطقة الصناعية رقم ' . $serviceCenter->industrial_area_id : null,
                'specialization' => $serviceCenter->service_specialization_id ? 'تخصص رقم ' . $serviceCenter->service_specialization_id : null,
            ],
            'insurance_company' => $insuranceCompany ? [
                'name' => $insuranceCompany->legal_name,
                'phone' => $insuranceCompany->phone,
                'address' => $insuranceCompany->office_address
            ] : null,
            'claims_stats' => [
                'total_claims' => $serviceCenterClaims->count(),
                'pending_claims' => $claimsByStatus->get('pending', 0),
                'in_progress_claims' => $claimsByStatus->get('in_progress', 0),
                'completed_claims' => $claimsByStatus->get('completed', 0),
                'approved_claims' => $claimsByStatus->get('approved', 0),
                'rejected_claims' => $claimsByStatus->get('rejected', 0),
                'by_status' => $claimsByStatus,
                'by_month' => $claimsByMonth
            ],
            'complaints_stats' => [
                'total_complaints' => $centerComplaints->count(),
                'unread_complaints' => $complaintsByStatus->get('unread', 0),
                'read_complaints' => $complaintsByStatus->get('read', 0),
                'by_status' => $complaintsByStatus
            ],
            'technicians_stats' => [
                'total_technicians' => array_sum($techniciansData),
                'technicians_data' => $techniciansData,
                'has_tow_service' => $serviceCenter->has_tow_service ?? false,
                'tow_trucks_count' => $serviceCenter->tow_trucks_count ?? 0,
                'daily_tow_capacity' => $serviceCenter->daily_tow_capacity ?? 0
            ],
            'profile_completion' => $this->calculateProfileCompletion($serviceCenter)
        ];

        return view('service-center.dashboard.index', compact('serviceCenter', 'stats', 'insuranceCompany'));
    }

    private function calculateProfileCompletion($serviceCenter)
    {
        $fields = [
            'legal_name',
            'phone', 
            'commercial_register',
            'center_address',
            'industrial_area_id',
            'service_specialization_id',
            'center_area_sqm'
        ];

        $completed = 0;
        foreach ($fields as $field) {
            if (!empty($serviceCenter->$field)) {
                $completed++;
            }
        }

        // إضافة نقطة إضافية إذا كان لديه فنيين
        if (($serviceCenter->body_work_technicians + $serviceCenter->mechanical_technicians + 
             $serviceCenter->painting_technicians + $serviceCenter->electrical_technicians + 
             $serviceCenter->other_technicians) > 0) {
            $completed++;
        }

        return round(($completed / (count($fields) + 1)) * 100);
    }
}
