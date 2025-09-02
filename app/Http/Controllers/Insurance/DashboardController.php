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

class DashboardController extends Controller
{
    public function index()
    {
        // الحصول على شركة التأمين الحالية - يمكنك تعديل هذا حسب طريقة authentication الخاصة بك
        $company = Auth::user(); // أو أي طريقة أخرى للحصول على الشركة
        
        // إذا كنت تحتاج الحصول على الشركة بطريقة مختلفة:
        // $company = InsuranceCompany::find(session('company_id'));
        // أو
        // $company = InsuranceCompany::where('id', auth()->id())->first();

        // Get insurance users for this company
        $companyUsers = InsuranceUser::where('insurance_company_id', $company->id)->get();
        
        // Get service centers created by this company
        $companyServiceCenters = ServiceCenter::where('insurance_company_id', $company->id)
            ->where('created_by_company', 1)->get();
        
        // Get all claims related to this company (through users)
        $companyClaims = Claim::whereIn('insurance_user_id', $companyUsers->pluck('id'))->get();
        
        // Get complaints for this company
        $companyComplaints = Complaint::where('complainant_type', 'insurance_company')
            ->where('complainant_id', $company->id)
            ->get();

        // Claims by status
        $claimsByStatus = $companyClaims->groupBy('status')->map(function ($claims) {
            return $claims->count();
        });

        // Complaints by status
        $complaintsByStatus = $companyComplaints->groupBy('status')->map(function ($complaints) {
            return $complaints->count();
        });

        // Claims by month (last 6 months)
        $claimsByMonth = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthName = $month->format('M Y');
            
            $claimsCount = $companyClaims->filter(function ($claim) use ($month) {
                return Carbon::parse($claim->created_at)->format('Y-m') === $month->format('Y-m');
            })->count();
            
            $claimsByMonth[] = [
                'month' => $monthName,
                'count' => $claimsCount
            ];
        }

        // Users registration by month (last 6 months)
        $usersByMonth = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthName = $month->format('M Y');
            
            $usersCount = $companyUsers->filter(function ($user) use ($month) {
                return Carbon::parse($user->created_at)->format('Y-m') === $month->format('Y-m');
            })->count();
            
            $usersByMonth[] = [
                'month' => $monthName,
                'count' => $usersCount
            ];
        }

        // Service Centers by specialization (if you have specialization data)
        $centersBySpecialization = $companyServiceCenters->groupBy('service_specialization_id')->map(function ($centers) {
            return $centers->count();
        });

        // Build comprehensive stats array
        $stats = [
            'company_info' => [
                'legal_name' => $company->legal_name ?? 'اسم الشركة',
                'phone' => $company->phone ?? 'غير متوفر',
                'commercial_register' => $company->commercial_register ?? 'غير متوفر',
                'office_address' => $company->office_address ?? 'غير متوفر',
                'employee_count' => $company->employee_count ?? 0,
                'insured_cars_count' => $company->insured_cars_count ?? 0,
                'member_since' => $company->created_at ? $company->created_at->format('Y/m/d') : date('Y/m/d'),
                'last_login' => $company->updated_at ? $company->updated_at->format('Y/m/d H:i') : date('Y/m/d H:i')
            ],
            'users_stats' => [
                'total_users' => $companyUsers->count(),
                'active_users' => $companyUsers->where('is_active', 1)->count(),
                'inactive_users' => $companyUsers->where('is_active', 0)->count(),
                'verified_users' => $companyUsers->whereNotNull('email_verified_at')->count(),
                'by_month' => $usersByMonth
            ],
            'service_centers_stats' => [
                'total_centers' => $companyServiceCenters->count(),
                'active_centers' => $companyServiceCenters->where('is_active', 1)->count(),
                'approved_centers' => $companyServiceCenters->where('is_approved', 1)->count(),
                'pending_centers' => $companyServiceCenters->where('is_approved', 0)->count(),
                'by_specialization' => $centersBySpecialization
            ],
            'claims_stats' => [
                'total_claims' => $companyClaims->count(),
                'pending_claims' => $claimsByStatus->get('pending', 0),
                'approved_claims' => $claimsByStatus->get('approved', 0),
                'rejected_claims' => $claimsByStatus->get('rejected', 0),
                'under_review' => $claimsByStatus->get('under_review', 0),
                'in_progress' => $claimsByStatus->get('in_progress', 0),
                'by_status' => $claimsByStatus,
                'by_month' => $claimsByMonth
            ],
            'complaints_stats' => [
                'total_complaints' => $companyComplaints->count(),
                'unread_complaints' => $complaintsByStatus->get('unread', 0),
                'read_complaints' => $complaintsByStatus->get('read', 0),
                'by_status' => $complaintsByStatus
            ],
            'financial_stats' => [
                'total_premiums' => $this->calculateTotalPremiums($companyClaims),
                'paid_claims' => $this->calculatePaidClaims($companyClaims),
                'pending_amount' => $this->calculatePendingAmount($companyClaims)
            ],
            'performance_metrics' => [
                'claim_approval_rate' => $this->calculateApprovalRate($claimsByStatus),
                'avg_processing_time' => $this->calculateAvgProcessingTime($companyClaims),
                'customer_satisfaction' => $this->calculateSatisfactionRate($companyComplaints)
            ],
            'profile_completion' => $this->calculateProfileCompletion($company)
        ];

        return view('insurance.dashboard.index', compact('company', 'stats'));
    }

    /**
     * Calculate profile completion percentage
     */
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
            'company_logo'
        ];

        $completed = 0;
        foreach ($fields as $field) {
            if (!empty($company->$field)) {
                $completed++;
            }
        }

        return round(($completed / count($fields)) * 100);
    }

    /**
     * Calculate total premiums (example calculation)
     */
    private function calculateTotalPremiums($claims)
    {
        // This is a placeholder calculation
        // You should replace this with actual premium calculation logic
        return $claims->count() * 1000; // مثال: 1000 ريال لكل مطالبة
    }

    /**
     * Calculate total paid claims amount
     */
    private function calculatePaidClaims($claims)
    {
        // Placeholder calculation - replace with actual logic
        $approvedClaims = $claims->where('status', 'approved');
        return $approvedClaims->count() * 500; // مثال: 500 ريال متوسط لكل مطالبة مقبولة
    }

    /**
     * Calculate pending claims amount
     */
    private function calculatePendingAmount($claims)
    {
        // Placeholder calculation
        $pendingClaims = $claims->whereIn('status', ['pending', 'under_review']);
        return $pendingClaims->count() * 300; // مثال: 300 ريال متوسط لكل مطالبة معلقة
    }

    /**
     * Calculate claim approval rate
     */
    private function calculateApprovalRate($claimsByStatus)
    {
        $total = $claimsByStatus->sum();
        if ($total == 0) return 0;
        
        $approved = $claimsByStatus->get('approved', 0);
        return round(($approved / $total) * 100, 1);
    }

    /**
     * Calculate average processing time
     */
    private function calculateAvgProcessingTime($claims)
    {
        // Placeholder calculation - you'll need actual processing time data
        $processedClaims = $claims->whereIn('status', ['approved', 'rejected']);
        if ($processedClaims->count() == 0) return 0;
        
        // مثال: متوسط 5 أيام معالجة
        return 5;
    }

    /**
     * Calculate customer satisfaction rate
     */
    private function calculateSatisfactionRate($complaints)
    {
        $total = $complaints->count();
        if ($total == 0) return 100; // إذا لم توجد شكاوى، فالرضا 100%
        
        $resolved = $complaints->where('status', 'read')->count();
        return round((($total - $complaints->where('status', 'unread')->count()) / $total) * 100, 1);
    }

    /**
     * Get recent activities (optional method)
     */
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