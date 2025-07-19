<?php

namespace App\Http\Controllers\InsuranceUser;

use App\Http\Controllers\Controller;
use App\Models\InsuranceCompany;
use App\Models\Claim;
use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $companySlug = $request->route('companySlug');

        $company = InsuranceCompany::where('company_slug', $companySlug)
            ->where('is_active', true)
            ->where('is_approved', true)
            ->firstOrFail();

        // Get current user
        $user = Auth::guard('insurance_user')->user();

        // Check if user belongs to this company
        if ($user->insurance_company_id !== $company->id) {
            Auth::guard('insurance_user')->logout();
            return redirect()->route('insurance.user.login', $companySlug);
        }

        // Get user claims using the correct column name
        $userClaims = Claim::where('insurance_user_id', $user->id)->get();
        
        // Get user complaints using the correct columns based on table structure
        $userComplaints = Complaint::where('complainant_type', 'insurance_user')
            ->where('complainant_id', $user->id)
            ->get();
        
        // Claims by status
        $claimsByStatus = $userClaims->groupBy('status')->map(function ($claims) {
            return $claims->count();
        });

        // Complaints by status (based on actual enum values)
        $complaintsByStatus = $userComplaints->groupBy('status')->map(function ($complaints) {
            return $complaints->count();
        });

        // Claims by month (last 6 months)
        $claimsByMonth = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthKey = $month->format('Y-m');
            $monthName = $month->format('M Y');
            
            $claimsCount = $userClaims->filter(function ($claim) use ($month) {
                return Carbon::parse($claim->created_at)->format('Y-m') === $month->format('Y-m');
            })->count();
            
            $claimsByMonth[] = [
                'month' => $monthName,
                'count' => $claimsCount
            ];
        }

        // Claims by type
        $claimsByType = $userClaims->groupBy('claim_type')->map(function ($claims) {
            return $claims->count();
        });

        $stats = [
            'user_info' => [
                'full_name' => $user->full_name,
                'phone' => $user->formatted_phone,
                'national_id' => $user->formatted_national_id,
                'policy_number' => $user->policy_number,
                'member_since' => $user->created_at->format('Y/m/d'),
                'last_login' => $user->updated_at->format('Y/m/d H:i')
            ],
            'company_info' => [
                'name' => $company->legal_name,
                'phone' => $company->formatted_phone,
                'address' => $company->office_address
            ],
            'claims_stats' => [
                'total_claims' => $userClaims->count(),
                'pending_claims' => $claimsByStatus->get('pending', 0),
                'approved_claims' => $claimsByStatus->get('approved', 0),
                'rejected_claims' => $claimsByStatus->get('rejected', 0),
                'under_review' => $claimsByStatus->get('under_review', 0),
                'by_status' => $claimsByStatus,
                'by_month' => $claimsByMonth,
                'by_type' => $claimsByType
            ],
            'complaints_stats' => [
                'total_complaints' => $userComplaints->count(),
                'unread_complaints' => $complaintsByStatus->get('unread', 0),
                'read_complaints' => $complaintsByStatus->get('read', 0),
                'by_status' => $complaintsByStatus,
                // إحصائيات حسب النوع
                'inquiries' => $userComplaints->where('type', 'inquiry')->count(),
                'complaints' => $userComplaints->where('type', 'complaint')->count(),
                'others' => $userComplaints->where('type', 'other')->count(),
            ]
        ];

        return view('insurance-user.dashboard.index', compact('stats', 'user', 'company'));
    }
}
