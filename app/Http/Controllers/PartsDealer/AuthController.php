<?php

namespace App\Http\Controllers\PartsDealer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\PartsDealer;
use App\Models\Specialization;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::guard('parts_dealer')->check()) {
            return redirect()->route('dealer.dashboard');
        }
        
        return view('dealer.auth.login');
    }

    public function showRegister()
    {
        if (Auth::guard('parts_dealer')->check()) {
            return redirect()->route('dealer.dashboard');
        }
        
        $specializations = Specialization::active()->ordered()->get();
        return view('dealer.auth.register', compact('specializations'));
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

        if (Auth::guard('parts_dealer')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('dealer.dashboard'));
        }

        return back()->withErrors([
            'phone' => t('auth.failed', 'Invalid credentials'),
        ])->onlyInput('phone');
    }

    public function register(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|max:20|unique:parts_dealers,phone',
            'password' => 'required|string|min:6|confirmed',
            'legal_name' => 'required|string|max:255',
            'commercial_register' => 'required|string|max:100|unique:parts_dealers,commercial_register',
            'tax_number' => 'nullable|string|max:100',
            'specialization_id' => 'nullable|exists:specializations,id',
            'is_scrapyard_owner' => 'boolean',
            'shop_address' => 'nullable|string',
        ]);

        try {
            $data = $request->only([
                'phone', 'legal_name', 'commercial_register', 
                'tax_number', 'specialization_id', 'shop_address'
            ]);
            
            $data['password'] = Hash::make($request->password);
            $data['is_scrapyard_owner'] = $request->has('is_scrapyard_owner');
            $data['is_active'] = true;
            $data['is_approved'] = false; // Needs admin approval

            $dealer = PartsDealer::create($data);

            Auth::guard('parts_dealer')->login($dealer);

            return redirect()->route('dealer.dashboard')->with('success', 
                t('auth.registration_success', 'Account created successfully. Awaiting admin approval.'));
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', t('auth.registration_failed', 'Registration failed. Please try again.'))
                ->withInput();
        }
    }

    public function logout(Request $request)
    {
        Auth::guard('parts_dealer')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('dealer.login');
    }
}