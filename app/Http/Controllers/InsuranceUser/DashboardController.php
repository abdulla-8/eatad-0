<?php

namespace App\Http\Controllers\InsuranceUser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\InsuranceCompany;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Get company from route parameter
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
            ]
        ];

        return view('insurance-user.dashboard.index', compact('stats', 'user', 'company'));
    }
}