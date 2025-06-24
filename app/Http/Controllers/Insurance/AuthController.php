<?php

namespace App\Http\Controllers\Insurance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\InsuranceCompany;
use App\Models\InsuranceCompanyPhone;

class AuthController extends Controller
{
    public function showLogin(Request $request)
    {
        $company = session('current_company');
        
        if (Auth::guard('insurance_company')->check()) {
            return redirect()->route('insurance.dashboard', ['companyRoute' => $company->company_slug]);
        }
        
        return view('insurance.auth.login', compact('company'));
    }

    public function showRegister(Request $request)
    {
        $company = session('current_company');
        
        if (Auth::guard('insurance_company')->check()) {
            return redirect()->route('insurance.dashboard', ['companyRoute' => $company->company_slug]);
        }
        
        return view('insurance.auth.register', compact('company'));
    }

    public function login(Request $request)
    {
        $company = session('current_company');
        
        $request->validate([
            'phone' => 'required|string',
            'password' => 'required|min:6',
        ]);

        $credentials = [
            'phone' => $request->phone,
            'password' => $request->password,
            'is_active' => true,
            'id' => $company->id
        ];

        if (Auth::guard('insurance_company')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->route('insurance.dashboard', ['companyRoute' => $company->company_slug]);
        }

        return back()->withErrors([
            'phone' => t('auth.failed', 'Invalid credentials'),
        ])->onlyInput('phone');
    }

    public function register(Request $request)
    {
        $company = session('current_company');
        
        $request->validate([
            'phone' => 'required|string|max:20|unique:insurance_companies,phone',
            'password' => 'required|string|min:6|confirmed',
            'legal_name' => 'required|string|max:255',
            'commercial_register' => 'required|string|max:100|unique:insurance_companies,commercial_register',
            'tax_number' => 'nullable|string|max:100',
            'employee_count' => 'nullable|integer|min:1',
            'office_address' => 'nullable|string',
        ]);

        try {
            $data = $request->only([
                'phone', 'legal_name', 'commercial_register', 
                'tax_number', 'employee_count', 'office_address'
            ]);
            
            $data['password'] = Hash::make($request->password);
            $data['is_active'] = true;
            $data['is_approved'] = false;
            $data['company_slug'] = $company->company_slug;
            $data['translation_group'] = $company->translation_group;

            $newCompany = InsuranceCompany::create($data);

            InsuranceCompanyPhone::create([
                'insurance_company_id' => $newCompany->id,
                'phone' => $newCompany->phone,
                'label' => t('admin.primary_phone', 'Primary Phone'),
                'is_primary' => true
            ]);

            Auth::guard('insurance_company')->login($newCompany);

            return redirect()->route('insurance.dashboard', ['companyRoute' => $company->company_slug])
                ->with('success', t('auth.registration_success', 'Account created successfully. Awaiting admin approval.'));
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', t('auth.registration_failed', 'Registration failed. Please try again.'))
                ->withInput();
        }
    }

    public function logout(Request $request)
    {
        $company = session('current_company');
        
        Auth::guard('insurance_company')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('insurance.login', ['companyRoute' => $company->company_slug]);
    }
}