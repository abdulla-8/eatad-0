<?php

namespace App\Http\Controllers\InsuranceUser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\InsuranceCompany;
use App\Models\InsuranceUser;

class AuthController extends Controller
{
    protected $company;

    public function showLogin(Request $request)
    {
        // Check if user is already logged in
        if (Auth::guard('insurance_user')->check()) {
            $companySlug = $request->route('companySlug');
            return redirect()->route('insurance.user.dashboard', $companySlug);
        }

        // Get company from route parameter
        $companySlug = $request->route('companySlug');
        $company = InsuranceCompany::where('company_slug', $companySlug)
            ->where('is_active', true)
            ->where('is_approved', true)
            ->firstOrFail();

        return view('insurance-user.auth.login', [
            'company' => $company
        ]);
    }

    public function login(Request $request)
    {
        // Get company from route parameter
        $companySlug = $request->route('companySlug');
        $company = InsuranceCompany::where('company_slug', $companySlug)
            ->where('is_active', true)
            ->where('is_approved', true)
            ->firstOrFail();

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
            'insurance_company_id' => $company->id,
            'is_active' => true
        ];

        if (Auth::guard('insurance_user')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('insurance.user.dashboard', $companySlug));
        }

        return redirect()->back()
            ->withErrors(['phone' => t('auth.failed')])
            ->withInput();

             $credentials = [
        'phone' => $request->phone,
        'password' => $request->password,
        'insurance_company_id' => $company->id,
        'is_active' => true  // ← هذا مهم لمنع المعطلين
    ];

    if (Auth::guard('insurance_user')->attempt($credentials, $request->boolean('remember'))) {
        $request->session()->regenerate();
        return redirect()->intended(route('insurance.user.dashboard', $companySlug));
    }

    // رسالة خطأ محددة للمعطلين
    $user = InsuranceUser::where('phone', $request->phone)
        ->where('insurance_company_id', $company->id)
        ->first();

    if ($user && !$user->is_active) {
        return redirect()->back()
            ->withErrors(['phone' => 'تم تعطيل حسابك. يرجى التواصل مع شركة التأمين.'])
            ->withInput();
    }

    return redirect()->back()
        ->withErrors(['phone' => t('auth.failed')])
        ->withInput();

    }

    public function showRegister(Request $request)
    {
        // Check if user is already logged in
        if (Auth::guard('insurance_user')->check()) {
            $companySlug = $request->route('companySlug');
            return redirect()->route('insurance.user.dashboard', $companySlug);
        }

        // Get company from route parameter
        $companySlug = $request->route('companySlug');
        $company = InsuranceCompany::where('company_slug', $companySlug)
            ->where('is_active', true)
            ->where('is_approved', true)
            ->firstOrFail();

        return view('insurance-user.auth.register', [
            'company' => $company
        ]);
    }

    public function register(Request $request)
    {
        // Get company from route parameter
        $companySlug = $request->route('companySlug');
        $company = InsuranceCompany::where('company_slug', $companySlug)
            ->where('is_active', true)
            ->where('is_approved', true)
            ->firstOrFail();

        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'phone' => [
                'required',
                'string',
                'max:20',
                Rule::unique('insurance_users', 'phone')->where(function ($query) use ($company) {
                    return $query->where('insurance_company_id', $company->id);
                })
            ],
            'national_id' => [
                'required',
                'string',
                'size:14',
                Rule::unique('insurance_users', 'national_id')->where(function ($query) use ($company) {
                    return $query->where('insurance_company_id', $company->id);
                })
            ],
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
                'insurance_company_id' => $company->id,
                'full_name' => $request->full_name,
                'phone' => $request->phone,
                'national_id' => $request->national_id,
                'policy_number' => $request->policy_number,
                'password' => Hash::make($request->password),
                'is_active' => true
            ]);

            Auth::guard('insurance_user')->login($user);

            return redirect()->route('insurance.user.dashboard', $companySlug)
                ->with('success', t('auth.registration_successful'));

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', t('auth.registration_failed'))
                ->withInput();
        }
    }

    public function logout(Request $request)
    {
        $companySlug = $request->route('companySlug');
        
        Auth::guard('insurance_user')->logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('insurance.user.login', $companySlug);
    }
}