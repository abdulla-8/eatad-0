<?php

namespace App\Http\Controllers\InsuranceUser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\InsuranceCompany;
use App\Models\InsuranceUser;

class AuthController extends Controller
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

    public function showLogin(Request $request)
    {
        if (Auth::guard('insurance_user')->check()) {
            return redirect()->route('insurance.user.dashboard', $this->company->company_slug);
        }

        return view('insurance-user.auth.login', [
            'company' => $this->company
        ]);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $credentials = [
            'phone' => $request->phone,
            'password' => $request->password,
            'insurance_company_id' => $this->company->id,
            'is_active' => true
        ];

        if (Auth::guard('insurance_user')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('insurance.user.dashboard', $this->company->company_slug));
        }

        return redirect()->back()
            ->withErrors(['phone' => t('auth.failed')])
            ->withInput();
    }

    public function showRegister(Request $request)
    {
        if (Auth::guard('insurance_user')->check()) {
            return redirect()->route('insurance.user.dashboard', $this->company->company_slug);
        }

        return view('insurance-user.auth.register', [
            'company' => $this->company
        ]);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:insurance_users,phone',
            'national_id' => 'required|string|size:14|unique:insurance_users,national_id',
            'policy_number' => 'required|string|max:50',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $user = InsuranceUser::create([
                'insurance_company_id' => $this->company->id,
                'full_name' => $request->full_name,
                'phone' => $request->phone,
                'national_id' => $request->national_id,
                'policy_number' => $request->policy_number,
                'password' => Hash::make($request->password),
                'is_active' => true
            ]);

            Auth::guard('insurance_user')->login($user);

            return redirect()->route('insurance.user.dashboard', $this->company->company_slug)
                ->with('success', t('auth.registration_successful'));

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', t('auth.registration_failed'))
                ->withInput();
        }
    }

    public function logout(Request $request)
    {
        Auth::guard('insurance_user')->logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('insurance.user.login', $this->company->company_slug);
    }
}