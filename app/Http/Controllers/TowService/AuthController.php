<?php

namespace App\Http\Controllers\TowService;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\TowServiceCompany;
use App\Models\TowServiceIndividual;
use App\Models\TowServiceCompanyPhone;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::guard('tow_service_company')->check() || Auth::guard('tow_service_individual')->check()) {
            return redirect()->route('tow-service.dashboard');
        }
        
        return view('tow-service.auth.login');
    }

    public function showRegister()
    {
        if (Auth::guard('tow_service_company')->check() || Auth::guard('tow_service_individual')->check()) {
            return redirect()->route('tow-service.dashboard');
        }
        
        return view('tow-service.auth.register');
    }

    public function login(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'password' => 'required|min:6',
        ]);

        $phone = $request->phone;
        $password = $request->password;
        $remember = $request->boolean('remember');

        // Try to authenticate as company first
        if (Auth::guard('tow_service_company')->attempt(['phone' => $phone, 'password' => $password, 'is_active' => true], $remember)) {
            $request->session()->regenerate();
            return redirect()->intended(route('tow-service.dashboard'));
        }

        // Try to authenticate as individual
        if (Auth::guard('tow_service_individual')->attempt(['phone' => $phone, 'password' => $password, 'is_active' => true], $remember)) {
            $request->session()->regenerate();
            return redirect()->intended(route('tow-service.dashboard'));
        }

        return back()->withErrors([
            'phone' => t('auth.failed', 'Invalid credentials'),
        ])->onlyInput('phone');
    }

    public function register(Request $request)
    {
        $request->validate([
            'user_type' => 'required|in:company,individual',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($request->user_type === 'company') {
            return $this->registerCompany($request);
        } else {
            return $this->registerIndividual($request);
        }
    }

    private function registerCompany(Request $request)
    {
        $request->validate([
            'phone' => 'unique:tow_service_companies,phone',
            'legal_name' => 'required|string|max:255',
            'commercial_register' => 'required|string|max:100|unique:tow_service_companies,commercial_register',
            'tax_number' => 'nullable|string|max:100',
            'daily_capacity' => 'nullable|integer|min:1',
            'delegate_number' => 'nullable|string|max:100',
            'office_address' => 'nullable|string',
            'company_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        try {
            $data = $request->only([
                'phone', 'legal_name', 'commercial_register', 
                'tax_number', 'daily_capacity', 'delegate_number', 'office_address'
            ]);
            
            $data['password'] = Hash::make($request->password);
            $data['is_active'] = true;
            $data['is_approved'] = false; // Needs admin approval

            // Handle logo upload
            if ($request->hasFile('company_logo')) {
                $logo = $request->file('company_logo');
                $filename = 'tow_company_' . time() . '.' . $logo->getClientOriginalExtension();
                $path = $logo->storeAs('tow_company_logos', $filename, 'public');
                $data['company_logo'] = $path;
            }

            $company = TowServiceCompany::create($data);

            // Create primary phone
            TowServiceCompanyPhone::create([
                'tow_service_company_id' => $company->id,
                'phone' => $company->phone,
                'label' => t('admin.primary_phone'),
                'is_primary' => true
            ]);

            Auth::guard('tow_service_company')->login($company);

            return redirect()->route('tow-service.dashboard')->with('success', 
                t('auth.registration_success', 'Account created successfully. Awaiting admin approval.'));
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', t('auth.registration_failed', 'Registration failed. Please try again.'))
                ->withInput();
        }
    }

    private function registerIndividual(Request $request)
    {
        $request->validate([
            'phone' => 'unique:tow_service_individuals,phone',
            'full_name' => 'required|string|max:255',
            'national_id' => 'required|string|size:14|unique:tow_service_individuals,national_id',
            'tow_truck_plate_number' => 'required|string|max:50|unique:tow_service_individuals,tow_truck_plate_number',
            'address' => 'nullable|string',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tow_truck_form' => 'nullable|image|mimes:jpeg,png,jpg,gif,pdf|max:5120'
        ]);

        try {
            $data = $request->only([
                'phone', 'full_name', 'national_id', 
                'tow_truck_plate_number', 'address'
            ]);
            
            $data['password'] = Hash::make($request->password);
            $data['is_active'] = true;
            $data['is_approved'] = false; // Needs admin approval

            // Handle profile image upload
            if ($request->hasFile('profile_image')) {
                $image = $request->file('profile_image');
                $filename = 'tow_individual_' . time() . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('tow_individual_profiles', $filename, 'public');
                $data['profile_image'] = $path;
            }

            // Handle tow truck form upload
            if ($request->hasFile('tow_truck_form')) {
                $form = $request->file('tow_truck_form');
                $filename = 'tow_form_' . time() . '.' . $form->getClientOriginalExtension();
                $path = $form->storeAs('tow_truck_forms', $filename, 'public');
                $data['tow_truck_form'] = $path;
            }

            $individual = TowServiceIndividual::create($data);

            Auth::guard('tow_service_individual')->login($individual);

            return redirect()->route('tow-service.dashboard')->with('success', 
                t('auth.registration_success', 'Account created successfully. Awaiting admin approval.'));
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', t('auth.registration_failed', 'Registration failed. Please try again.'))
                ->withInput();
        }
    }

    public function logout(Request $request)
    {
        if (Auth::guard('tow_service_company')->check()) {
            Auth::guard('tow_service_company')->logout();
        }
        
        if (Auth::guard('tow_service_individual')->check()) {
            Auth::guard('tow_service_individual')->logout();
        }
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('tow-service.login');
    }
}