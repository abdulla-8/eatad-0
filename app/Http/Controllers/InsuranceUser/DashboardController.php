<?php

namespace App\Http\Controllers\InsuranceUser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\InsuranceCompany;

class DashboardController extends Controller
{
    protected $company;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $companySlug = $request->route('companySlug');
            $this->company = InsuranceCompany::where('company_slug', $companySlug)
                ->where('is_active', true)
                ->where('is_approved', true)
                ->firstOrFail();
            
            view()->share('company', $this->company);
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $user = Auth::guard('insurance_user')->user();
        
        if ($user->insurance_company_id !== $this->company->id) {
            Auth::guard('insurance_user')->logout();
            return redirect()->route('insurance.user.login', $this->company->company_slug);
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
                'name' => $this->company->legal_name,
                'phone' => $this->company->formatted_phone,
                'address' => $this->company->office_address
            ]
        ];

        return view('insurance-user.dashboard.index', compact('stats', 'user'));
    }
}