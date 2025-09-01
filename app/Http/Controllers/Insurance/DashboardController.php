<?php

namespace App\Http\Controllers\Insurance;

use App\Http\Controllers\Controller;
use App\Models\InsuranceCompany;
use App\Models\InsuranceUser;
use App\Models\ServiceCenter;
use App\Models\Claim;
use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $company = Auth::user()->loadCount(['users', 'serviceCenters', 'claims', 'complaints']);

        $claims = $company->claims()->with('status')->get();
        $complaints = $company->complaints()->with('status')->get();

        $claimsByStatus = $claims->groupBy('status.name')->map->count();
        $complaintsByStatus = $complaints->groupBy('status.name')->map->count();

        $claimsByMonth = $this->getClaimsByMonth($company->id);
        $usersByMonth = $this->getUsersByMonth($company->id);

        $centersBySpecialization = $company->serviceCenters->groupBy('service_specialization_id')->map->count();

        $stats = [
            'company_info' => $this->getCompanyInfo($company),
            'users_stats' => $this->getUserStats($company, $usersByMonth),
            'service_centers_stats' => $this->getServiceCenterStats($company, $centersBySpecialization),
            'claims_stats' => $this->getClaimStats($company, $claimsByStatus, $claimsByMonth),
            'complaints_stats' => $this->getComplaintStats($company, $complaintsByStatus),
            'financial_stats' => $this->getFinancialStats($claims),
            'performance_metrics' => $this->getPerformanceMetrics($claimsByStatus, $claims, $complaints),
            'profile_completion' => $this->calculateProfileCompletion($company),
        ];

        return view('insurance.dashboard.index', compact('company', 'stats'));
    }

    private function getClaimsByMonth($companyId)
    {
        return Claim::where('insurance_company_id', $companyId)
            ->select(DB::raw('DATE_FORMAT(created_at, "%b %Y") as month'), DB::raw('count(*) as count'))
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->groupBy('month')
            ->orderBy(DB::raw('MIN(created_at)'))
            ->get()
            ->toArray();
    }

    private function getUsersByMonth($companyId)
    {
        return InsuranceUser::where('insurance_company_id', $companyId)
            ->select(DB::raw('DATE_FORMAT(created_at, "%b %Y") as month'), DB::raw('count(*) as count'))
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->groupBy('month')
            ->orderBy(DB::raw('MIN(created_at)'))
            ->get()
            ->toArray();
    }

    private function getCompanyInfo($company)
    {
        return [
            'legal_name' => $company->legal_name ?? 'اسم الشركة',
            'phone' => $company->phone ?? 'غير متوفر',
            'commercial_register' => $company->commercial_register ?? 'غير متوفر',
            'office_address' => $company->office_address ?? 'غير متوفر',
            'employee_count' => $company->employee_count ?? 0,
            'insured_cars_count' => $company->insured_cars_count ?? 0,
            'member_since' => $company->created_at ? $company->created_at->format('Y/m/d') : date('Y/m/d'),
            'last_login' => $company->updated_at ? $company->updated_at->format('Y/m/d H:i') : date('Y/m/d H:i'),
        ];
    }

    private function getUserStats($company, $usersByMonth)
    {
        return [
            'total_users' => $company->users_count,
            'active_users' => $company->users()->where('is_active', 1)->count(),
            'inactive_users' => $company->users()->where('is_active', 0)->count(),
            'verified_users' => $company->users()->whereNotNull('email_verified_at')->count(),
            'by_month' => $usersByMonth,
        ];
    }

    private function getServiceCenterStats($company, $centersBySpecialization)
    {
        return [
            'total_centers' => $company->service_centers_count,
            'active_centers' => $company->serviceCenters()->where('is_active', 1)->count(),
            'approved_centers' => $company->serviceCenters()->where('is_approved', 1)->count(),
            'pending_centers' => $company->serviceCenters()->where('is_approved', 0)->count(),
            'by_specialization' => $centersBySpecialization,
        ];
    }

    private function getClaimStats($company, $claimsByStatus, $claimsByMonth)
    {
        return [
            'total_claims' => $company->claims_count,
            'pending_claims' => $claimsByStatus->get('pending', 0),
            'approved_claims' => $claimsByStatus->get('approved', 0),
            'rejected_claims' => $claimsByStatus->get('rejected', 0),
            'under_review' => $claimsByStatus->get('under_review', 0),
            'in_progress' => $claimsByStatus->get('in_progress', 0),
            'by_status' => $claimsByStatus,
            'by_month' => $claimsByMonth,
        ];
    }

    private function getComplaintStats($company, $complaintsByStatus)
    {
        return [
            'total_complaints' => $company->complaints_count,
            'unread_complaints' => $complaintsByStatus->get('unread', 0),
            'read_complaints' => $complaintsByStatus->get('read', 0),
            'by_status' => $complaintsByStatus,
        ];
    }

    private function getFinancialStats($claims)
    {
        return [
            'total_premiums' => $this->calculateTotalPremiums($claims),
            'paid_claims' => $this->calculatePaidClaims($claims),
            'pending_amount' => $this->calculatePendingAmount($claims),
        ];
    }

    private function getPerformanceMetrics($claimsByStatus, $claims, $complaints)
    {
        return [
            'claim_approval_rate' => $this->calculateApprovalRate($claimsByStatus),
            'avg_processing_time' => $this->calculateAvgProcessingTime($claims),
            'customer_satisfaction' => $this->calculateSatisfactionRate($complaints),
        ];
    }

    private function calculateProfileCompletion($company)
    {
        $fields = [
            'legal_name',
            'phone',
            'commercial_register',
            'office_address',
            'employee_count',
            'insured_cars_count',
            'tax_number',
            'company_logo',
        ];

        $completed = 0;
        foreach ($fields as $field) {
            if (!empty($company->$field)) {
                $completed++;
            }
        }

        return round(($completed / count($fields)) * 100);
    }

    private function calculateTotalPremiums($claims)
    {
        // This is a placeholder calculation
        // You should replace this with actual premium calculation logic
        return $claims->count() * 1000; // مثال: 1000 ريال لكل مطالبة
    }

    private function calculatePaidClaims($claims)
    {
        // Placeholder calculation - replace with actual logic
        $approvedClaims = $claims->where('status', 'approved');
        return $approvedClaims->count() * 500; // مثال: 500 ريال متوسط لكل مطالبة مقبولة
    }

    private function calculatePendingAmount($claims)
    {
        // Placeholder calculation
        $pendingClaims = $claims->whereIn('status', ['pending', 'under_review']);
        return $pendingClaims->count() * 300; // مثال: 300 ريال متوسط لكل مطالبة معلقة
    }

    private function calculateApprovalRate($claimsByStatus)
    {
        $total = $claimsByStatus->sum();
        if ($total == 0) return 0;

        $approved = $claimsByStatus->get('approved', 0);
        return round(($approved / $total) * 100, 1);
    }

    private function calculateAvgProcessingTime($claims)
    {
        // Placeholder calculation - you'll need actual processing time data
        $processedClaims = $claims->whereIn('status', ['approved', 'rejected']);
        if ($processedClaims->count() == 0) return 0;

        // مثال: متوسط 5 أيام معالجة
        return 5;
    }

    private function calculateSatisfactionRate($complaints)
    {
        $total = $complaints->count();
        if ($total == 0) return 100; // إذا لم توجد شكاوى، فالرضا 100%

        $resolved = $complaints->where('status', 'read')->count();
        return round((($total - $complaints->where('status', 'unread')->count()) / $total) * 100, 1);
    }

    private function getRecentActivities($company)
    {
        // يمكنك إضافة منطق لجلب الأنشطة الحديثة
        return [
            'recent_users' => InsuranceUser::where('insurance_company_id', $company->id)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get(),
            'recent_claims' => Claim::whereHas('insuranceUser', function($query) use ($company) {
                $query->where('insurance_company_id', $company->id);
            })->orderBy('created_at', 'desc')
              ->limit(5)
              ->get(),
            'recent_centers' => ServiceCenter::where('insurance_company_id', $company->id)
                ->orderBy('created_at', 'desc')
                ->limit(3)
                ->get()
        ];
    }
}
