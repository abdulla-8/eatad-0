<?php

// app/Http/Controllers/ServiceCenter/AuthController.php

namespace App\Http\Controllers\ServiceCenter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\ServiceCenter;
use App\Models\ServiceCenterPhone;
use App\Models\IndustrialArea;
use App\Models\ServiceSpecialization;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::guard('service_center')->check()) {
            return redirect()->route('service-center.dashboard');
        }
        
        return view('service-center.auth.login');
    }

    public function showRegister()
    {
        if (Auth::guard('service_center')->check()) {
            return redirect()->route('service-center.dashboard');
        }
        
        $industrialAreas = IndustrialArea::active()->ordered()->get();
        $serviceSpecializations = ServiceSpecialization::active()->ordered()->get();
        return view('service-center.auth.register', compact('industrialAreas', 'serviceSpecializations'));
    }

    public function login(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'password' => 'required|min:6',
        ]);

        $credentials = [
            'phone' => $request->phone,
            'password' => $request->password,
            'is_active' => true
        ];

        if (Auth::guard('service_center')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('service-center.dashboard'));
        }

        return back()->withErrors([
            'phone' => t('auth.failed', 'Invalid credentials'),
        ])->onlyInput('phone');
    }

    public function register(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|max:20|unique:service_centers,phone',
            'password' => 'required|string|min:6|confirmed',
            'legal_name' => 'required|string|max:255',
            'commercial_register' => 'required|string|max:100|unique:service_centers,commercial_register',
            'tax_number' => 'nullable|string|max:100',
            'industrial_area_id' => 'nullable|exists:industrial_areas,id',
            'service_specialization_id' => 'nullable|exists:service_specializations,id',
            'body_work_technicians' => 'nullable|integer|min:0',
            'mechanical_technicians' => 'nullable|integer|min:0',
            'painting_technicians' => 'nullable|integer|min:0',
            'electrical_technicians' => 'nullable|integer|min:0',
            'other_technicians' => 'nullable|integer|min:0',
            'center_area_sqm' => 'nullable|numeric|min:0',
            'center_address' => 'nullable|string',
        ]);

        try {
            $data = $request->only([
                'phone', 'legal_name', 'commercial_register', 
                'tax_number', 'industrial_area_id', 'service_specialization_id',
                'body_work_technicians', 'mechanical_technicians', 
                'painting_technicians', 'electrical_technicians', 'other_technicians',
                'center_area_sqm', 'center_address'
            ]);
            
            $data['password'] = Hash::make($request->password);
            $data['is_active'] = true;
            $data['is_approved'] = false; // Needs admin approval

            $serviceCenter = ServiceCenter::create($data);

            // Create primary phone
            ServiceCenterPhone::create([
                'service_center_id' => $serviceCenter->id,
                'phone' => $serviceCenter->phone,
                'label' => t('admin.primary_phone'),
                'is_primary' => true
            ]);

            Auth::guard('service_center')->login($serviceCenter);

            return redirect()->route('service-center.dashboard')->with('success', 
                t('auth.registration_success', 'Account created successfully. Awaiting admin approval.'));
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', t('auth.registration_failed', 'Registration failed. Please try again.'))
                ->withInput();
        }
    }

    public function logout(Request $request)
    {
        Auth::guard('service_center')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('service-center.login');
    }
}
