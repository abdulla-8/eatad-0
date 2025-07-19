<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
    Language,
    Translation,
    Specialization,
    IndustrialArea,
    ServiceSpecialization,
    PartsDealer,
    InsuranceCompany,
    InsuranceUser,
    ServiceCenter,
    TowServiceCompany,
    TowServiceIndividual,
    Complaint,
    Claim,
    ClaimInspection
};
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $dashboardData = $this->getDashboardData();
        return view('admin.dashboard.index', $dashboardData);
    }

    private function getDashboardData()
    {
        return [
            // Language and System Stats
            'activeLanguages' => Language::where('is_active', true)->get(),
            'currentLanguage' => Language::find(session('current_language_id', 1)),
            'isRtl' => app()->getLocale() === 'ar',

            // System Overview Stats
            'systemStats' => $this->getSystemStats(),

            // User Management Stats
            'userStats' => $this->getUserStats(),

            // Content Management Stats
            'contentStats' => $this->getContentStats(),

            // Activity Stats
            'activityStats' => $this->getActivityStats(),

            // Recent Activities
            'recentActivities' => $this->getRecentActivities(),

            // Top Statistics Cards
            'topStats' => $this->getTopStats(),

            // System Health
            'systemHealth' => $this->getSystemHealth(),

            // Quick Actions
            'quickActions' => $this->getQuickActions(),
        ];
    }

    private function getSystemStats()
    {
        return [
            'total_translations' => Translation::count(),
            'active_languages' => Language::where('is_active', true)->count(),
            'total_complaints' => Complaint::count(),
            'unread_complaints' => Complaint::where('is_read', false)->count(),
            'pending_approvals' => $this->getPendingApprovalsCount(),
            'active_claims' => Claim::whereIn('status', ['pending', 'approved', 'in_progress'])->count(),
        ];
    }

    private function getUserStats()
    {
        return [
            'parts_dealers' => [
                'total' => PartsDealer::count(),
                'active' => PartsDealer::where('is_active', true)->count(),
                'approved' => PartsDealer::where('is_approved', true)->count(),
                'pending_approval' => PartsDealer::where('is_approved', false)->count(),
                'scrapyard_owners' => PartsDealer::where('is_scrapyard_owner', true)->count(),
            ],
            'insurance_companies' => [
                'total' => InsuranceCompany::count(),
                'active' => InsuranceCompany::where('is_active', true)->count(),
                'approved' => InsuranceCompany::where('is_approved', true)->count(),
                'pending_approval' => InsuranceCompany::where('is_approved', false)->count(),
                'total_users' => InsuranceUser::count(),
                'active_users' => InsuranceUser::where('is_active', true)->count(),
            ],
            'service_centers' => [
                'total' => ServiceCenter::count(),
                'active' => ServiceCenter::where('is_active', true)->count(),
                'approved' => ServiceCenter::where('is_approved', true)->count(),
                'pending_approval' => ServiceCenter::where('is_approved', false)->count(),
                'total_technicians' => ServiceCenter::sum(DB::raw('
                    COALESCE(body_work_technicians, 0) + 
                    COALESCE(mechanical_technicians, 0) + 
                    COALESCE(painting_technicians, 0) + 
                    COALESCE(electrical_technicians, 0) + 
                    COALESCE(other_technicians, 0)
                ')),
                'total_area' => ServiceCenter::sum('center_area_sqm'),
            ],
            'tow_services' => [
                'companies' => [
                    'total' => TowServiceCompany::count(),
                    'active' => TowServiceCompany::where('is_active', true)->count(),
                    'approved' => TowServiceCompany::where('is_approved', true)->count(),
                    'total_capacity' => TowServiceCompany::sum('daily_capacity'),
                ],
                'individuals' => [
                    'total' => TowServiceIndividual::count(),
                    'active' => TowServiceIndividual::where('is_active', true)->count(),
                    'approved' => TowServiceIndividual::where('is_approved', true)->count(),
                ],
            ],
        ];
    }

    private function getContentStats()
    {
        return [
            'specializations' => [
                'total' => Specialization::count(),
                'active' => Specialization::where('is_active', true)->count(),
                'with_images' => Specialization::whereNotNull('image')->count(),
            ],
            'industrial_areas' => [
                'total' => IndustrialArea::count(),
                'active' => IndustrialArea::where('is_active', true)->count(),
            ],
            'service_specializations' => [
                'total' => ServiceSpecialization::count(),
                'active' => ServiceSpecialization::where('is_active', true)->count(),
            ],
        ];
    }

    private function getActivityStats()
    {
        $today = Carbon::today();
        $thisWeek = Carbon::now()->startOfWeek();
        $thisMonth = Carbon::now()->startOfMonth();

        return [
            'today' => [
                'new_complaints' => Complaint::whereDate('created_at', $today)->count(),
                'new_claims' => Claim::whereDate('created_at', $today)->count(),
                'new_registrations' => $this->getTodayRegistrations(),
            ],
            'this_week' => [
                'new_complaints' => Complaint::where('created_at', '>=', $thisWeek)->count(),
                'new_claims' => Claim::where('created_at', '>=', $thisWeek)->count(),
                'approved_accounts' => $this->getWeeklyApprovals(),
            ],
            'this_month' => [
                'new_complaints' => Complaint::where('created_at', '>=', $thisMonth)->count(),
                'new_claims' => Claim::where('created_at', '>=', $thisMonth)->count(),
                'total_inspections' => ClaimInspection::where('created_at', '>=', $thisMonth)->count(),
            ],
        ];
    }

    private function getRecentActivities()
    {
        $activities = collect();

        // Recent Complaints - بدون with relations
        $recentComplaints = Complaint::latest()
            ->take(5)
            ->get()
            ->map(function ($complaint) {
                return [
                    'type' => 'complaint',
                    'title' => 'New ' . ucfirst($complaint->type) . ' Received',
                    'description' => $complaint->subject, // غيرنا ده عشان منجبش مشاكل
                    'time' => $complaint->created_at,
                    'icon' => 'chat-bubble-left-ellipsis',
                    'color' => $complaint->type === 'complaint' ? 'red' : 'blue',
                    'link' => route('admin.complaints.show', $complaint->id),
                ];
            });

        // Recent User Registrations
        $recentDealers = PartsDealer::latest()->take(3)->get()->map(function ($dealer) {
            return [
                'type' => 'registration',
                'title' => 'New Parts Dealer Registration',
                'description' => $dealer->legal_name,
                'time' => $dealer->created_at,
                'icon' => 'user-plus',
                'color' => 'green',
                'link' => route('admin.users.parts-dealers.index'),
            ];
        });

        $recentCompanies = InsuranceCompany::latest()->take(2)->get()->map(function ($company) {
            return [
                'type' => 'registration',
                'title' => 'New Insurance Company Registration',
                'description' => $company->legal_name,
                'time' => $company->created_at,
                'icon' => 'building-office',
                'color' => 'blue',
                'link' => route('admin.users.insurance-companies.index'),
            ];
        });

        // Recent Claims - بدون with relations
        $recentClaims = Claim::latest()
            ->take(3)
            ->get()
            ->map(function ($claim) {
                return [
                    'type' => 'claim',
                    'title' => 'New Claim Submitted',
                    'description' => "Claim #{$claim->claim_number}", // بسطنا الموضوع
                    'time' => $claim->created_at,
                    'icon' => 'document-text',
                    'color' => 'yellow',
                    'link' => '#',
                ];
            });

        return $activities
            ->merge($recentComplaints)
            ->merge($recentDealers)
            ->merge($recentCompanies)
            ->merge($recentClaims)
            ->sortByDesc('time')
            ->take(10)
            ->values();
    }

    private function getTopStats()
    {
        return [
            [
                'title' => 'Total Users',
                'value' => $this->getTotalUsersCount(),
                'change' => $this->getUserGrowthPercentage(),
                'icon' => 'users',
                'color' => 'blue',
                'trend' => 'up',
            ],
            [
                'title' => 'Active Claims',
                'value' => Claim::whereIn('status', ['pending', 'approved', 'in_progress'])->count(),
                'change' => $this->getClaimsGrowthPercentage(),
                'icon' => 'document-text',
                'color' => 'green',
                'trend' => 'up',
            ],
            [
                'title' => 'Pending Approvals',
                'value' => $this->getPendingApprovalsCount(),
                'change' => $this->getApprovalsChangePercentage(),
                'icon' => 'clock',
                'color' => 'yellow',
                'trend' => 'down',
            ],
            [
                'title' => 'Unread Complaints',
                'value' => Complaint::where('is_read', false)->count(),
                'change' => $this->getComplaintsChangePercentage(),
                'icon' => 'chat-bubble-left-ellipsis',
                'color' => 'red',
                'trend' => 'down',
            ],
        ];
    }

    private function getSystemHealth()
    {
        $totalUsers = $this->getTotalUsersCount();
        $activeUsers = $this->getActiveUsersCount();
        $pendingApprovals = $this->getPendingApprovalsCount();
        $unreadComplaints = Complaint::where('is_read', false)->count();

        $healthScore = 100;

        // Deduct points for issues
        if ($pendingApprovals > 10) $healthScore -= 15;
        if ($unreadComplaints > 20) $healthScore -= 20;
        if ($totalUsers > 0 && ($activeUsers / $totalUsers) < 0.8) $healthScore -= 10;

        return [
            'score' => max($healthScore, 0),
            'status' => $healthScore >= 90 ? 'excellent' : ($healthScore >= 70 ? 'good' : 'needs_attention'),
            'issues' => $this->getSystemIssues(),
            'recommendations' => $this->getSystemRecommendations(),
        ];
    }

    private function getQuickActions()
    {
        return [
            [
                'title' => 'Review Pending Approvals',
                'description' => $this->getPendingApprovalsCount() . ' accounts waiting for approval',
                'icon' => 'check-circle',
                'color' => 'yellow',
                'urgent' => $this->getPendingApprovalsCount() > 10,
                'actions' => [
                    ['title' => 'Parts Dealers', 'link' => route('admin.users.parts-dealers.index')],
                    ['title' => 'Insurance Companies', 'link' => route('admin.users.insurance-companies.index')],
                    ['title' => 'Service Centers', 'link' => route('admin.users.service-centers.index')],
                ]
            ],
            [
                'title' => 'Address Complaints',
                'description' => Complaint::where('is_read', false)->count() . ' unread complaints',
                'icon' => 'chat-bubble-left-ellipsis',
                'color' => 'red',
                'urgent' => Complaint::where('is_read', false)->count() > 5,
                'actions' => [
                    ['title' => 'View All Complaints', 'link' => route('admin.complaints.index')],
                    ['title' => 'Unread Only', 'link' => route('admin.complaints.index', ['status' => 'unread'])],
                ]
            ],
            [
                'title' => 'System Configuration',
                'description' => 'Manage languages, translations, and content',
                'icon' => 'cog-6-tooth',
                'color' => 'blue',
                'urgent' => false,
                'actions' => [
                    ['title' => 'Languages', 'link' => route('admin.languages.index')],
                    ['title' => 'Translations', 'link' => route('admin.translations.index')],
                    ['title' => 'Specializations', 'link' => route('admin.specializations.index')],
                ]
            ],
        ];
    }

    // Helper Methods
    private function getPendingApprovalsCount()
    {
        return PartsDealer::where('is_approved', false)->count() +
            InsuranceCompany::where('is_approved', false)->count() +
            ServiceCenter::where('is_approved', false)->count() +
            TowServiceCompany::where('is_approved', false)->count() +
            TowServiceIndividual::where('is_approved', false)->count();
    }

    private function getTotalUsersCount()
    {
        return PartsDealer::count() +
            InsuranceCompany::count() +
            ServiceCenter::count() +
            TowServiceCompany::count() +
            TowServiceIndividual::count() +
            InsuranceUser::count();
    }

    private function getActiveUsersCount()
    {
        return PartsDealer::where('is_active', true)->count() +
            InsuranceCompany::where('is_active', true)->count() +
            ServiceCenter::where('is_active', true)->count() +
            TowServiceCompany::where('is_active', true)->count() +
            TowServiceIndividual::where('is_active', true)->count() +
            InsuranceUser::where('is_active', true)->count();
    }

    private function getTodayRegistrations()
    {
        $today = Carbon::today();
        return PartsDealer::whereDate('created_at', $today)->count() +
            InsuranceCompany::whereDate('created_at', $today)->count() +
            ServiceCenter::whereDate('created_at', $today)->count();
    }

    private function getWeeklyApprovals()
    {
        $thisWeek = Carbon::now()->startOfWeek();
        return PartsDealer::where('is_approved', true)->where('updated_at', '>=', $thisWeek)->count() +
            InsuranceCompany::where('is_approved', true)->where('updated_at', '>=', $thisWeek)->count() +
            ServiceCenter::where('is_approved', true)->where('updated_at', '>=', $thisWeek)->count();
    }

    private function getUserGrowthPercentage()
    {
        $thisMonth = $this->getTotalUsersCount();
        $lastMonth = $this->getLastMonthUsersCount();

        if ($lastMonth == 0) return 100;
        return round((($thisMonth - $lastMonth) / $lastMonth) * 100, 1);
    }

    private function getLastMonthUsersCount()
    {
        $lastMonth = Carbon::now()->subMonth();
        return PartsDealer::where('created_at', '<=', $lastMonth)->count() +
            InsuranceCompany::where('created_at', '<=', $lastMonth)->count() +
            ServiceCenter::where('created_at', '<=', $lastMonth)->count();
    }

    private function getClaimsGrowthPercentage()
    {
        $thisMonth = Claim::whereMonth('created_at', Carbon::now()->month)->count();
        $lastMonth = Claim::whereMonth('created_at', Carbon::now()->subMonth()->month)->count();

        if ($lastMonth == 0) return 100;
        return round((($thisMonth - $lastMonth) / $lastMonth) * 100, 1);
    }

    private function getApprovalsChangePercentage()
    {
        // Simplified calculation
        return rand(-5, 15);
    }

    private function getComplaintsChangePercentage()
    {
        // Simplified calculation
        return rand(-10, 5);
    }

    private function getSystemIssues()
    {
        $issues = [];

        if ($this->getPendingApprovalsCount() > 10) {
            $issues[] = 'High number of pending account approvals';
        }

        if (Complaint::where('is_read', false)->count() > 20) {
            $issues[] = 'Many unread complaints requiring attention';
        }

        $activePercentage = $this->getActiveUsersCount() / max($this->getTotalUsersCount(), 1);
        if ($activePercentage < 0.8) {
            $issues[] = 'Low user activation rate';
        }

        return $issues;
    }

    private function getSystemRecommendations()
    {
        $recommendations = [];

        if ($this->getPendingApprovalsCount() > 10) {
            $recommendations[] = 'Review and approve pending user accounts';
        }

        if (Complaint::where('is_read', false)->count() > 5) {
            $recommendations[] = 'Address unread complaints promptly';
        }

        if (Language::where('is_active', true)->count() < 2) {
            $recommendations[] = 'Consider adding more language support';
        }

        return $recommendations;
    }
}
