<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TowServiceCompany;
use App\Models\TowServiceIndividual;
use App\Models\TowServiceCompanyPhone;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class TowServiceManagementController extends Controller
{
    // ==================== COMPANIES MANAGEMENT ====================
    
    public function companiesIndex()
    {
        $companies = TowServiceCompany::with('additionalPhones')
            ->orderBy('created_at', 'desc')->get();
        return view('admin.users.tow-service-companies.index', compact('companies'));
    }

    public function companiesCreate()
    {
        return view('admin.users.tow-service-companies.addedit');
    }

    public function companiesStore(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|max:20|unique:tow_service_companies,phone',
            'password' => 'required|string|min:6',
            'legal_name' => 'required|string|max:255',
            'commercial_register' => 'required|string|max:100|unique:tow_service_companies,commercial_register',
            'tax_number' => 'nullable|string|max:100',
            'daily_capacity' => 'nullable|integer|min:1',
            'delegate_number' => 'nullable|string|max:100',
            'office_location_lat' => 'nullable|numeric|between:-90,90',
            'office_location_lng' => 'nullable|numeric|between:-180,180',
            'office_address' => 'nullable|string',
            'company_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
            'is_approved' => 'boolean'
        ]);

        try {
            $data = $request->except(['company_logo', 'additional_phones', 'phone_labels']);
            $data['password'] = Hash::make($request->password);
            $data['is_active'] = $request->has('is_active');
            $data['is_approved'] = $request->has('is_approved');

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

            return redirect()->route('admin.users.tow-service-companies.index')
                ->with('success', t('admin.tow_service_company_created'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', t('admin.error_occurred'))
                ->withInput();
        }
    }

    public function companiesEdit(TowServiceCompany $towServiceCompany)
    {
        $towServiceCompany->load('additionalPhones');
        return view('admin.users.tow-service-companies.addedit', compact('towServiceCompany'));
    }

    public function companiesUpdate(Request $request, TowServiceCompany $towServiceCompany)
    {
        $request->validate([
            'phone' => 'required|string|max:20|unique:tow_service_companies,phone,' . $towServiceCompany->id,
            'password' => 'nullable|string|min:6',
            'legal_name' => 'required|string|max:255',
            'commercial_register' => 'required|string|max:100|unique:tow_service_companies,commercial_register,' . $towServiceCompany->id,
            'tax_number' => 'nullable|string|max:100',
            'daily_capacity' => 'nullable|integer|min:1',
            'delegate_number' => 'nullable|string|max:100',
            'office_location_lat' => 'nullable|numeric|between:-90,90',
            'office_location_lng' => 'nullable|numeric|between:-180,180',
            'office_address' => 'nullable|string',
            'company_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
            'is_approved' => 'boolean',
            'additional_phones' => 'nullable|array',
            'additional_phones.*' => 'nullable|string|max:20',
            'phone_labels' => 'nullable|array',
            'phone_labels.*' => 'nullable|string|max:100'
        ]);

        try {
            $data = $request->except(['password', 'company_logo', 'additional_phones', 'phone_labels']);

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            $data['is_active'] = $request->has('is_active');
            $data['is_approved'] = $request->has('is_approved');

            // Handle logo upload
            if ($request->hasFile('company_logo')) {
                // Delete old logo if exists
                if ($towServiceCompany->company_logo && Storage::disk('public')->exists($towServiceCompany->company_logo)) {
                    Storage::disk('public')->delete($towServiceCompany->company_logo);
                }

                $logo = $request->file('company_logo');
                $filename = 'tow_company_' . time() . '.' . $logo->getClientOriginalExtension();
                $path = $logo->storeAs('tow_company_logos', $filename, 'public');
                $data['company_logo'] = $path;
            }

            $towServiceCompany->update($data);

            // Update primary phone
            $primaryPhone = $towServiceCompany->primaryPhone;
            if ($primaryPhone) {
                $primaryPhone->update(['phone' => $towServiceCompany->phone]);
            } else {
                TowServiceCompanyPhone::create([
                    'tow_service_company_id' => $towServiceCompany->id,
                    'phone' => $towServiceCompany->phone,
                    'label' => t('admin.primary_phone'),
                    'is_primary' => true
                ]);
            }

            // Delete existing additional phones and recreate
            $towServiceCompany->additionalPhones()->where('is_primary', false)->delete();

            // Add new additional phones
            if ($request->additional_phones) {
                foreach ($request->additional_phones as $index => $phone) {
                    if (!empty($phone)) {
                        TowServiceCompanyPhone::create([
                            'tow_service_company_id' => $towServiceCompany->id,
                            'phone' => $phone,
                            'label' => $request->phone_labels[$index] ?? null,
                            'is_primary' => false
                        ]);
                    }
                }
            }

            return redirect()->route('admin.users.tow-service-companies.index')
                ->with('success', t('admin.tow_service_company_updated'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', t('admin.error_occurred'))
                ->withInput();
        }
    }

    public function companiesDestroy(TowServiceCompany $towServiceCompany)
    {
        try {
            // Delete logo if exists
            if ($towServiceCompany->company_logo && Storage::disk('public')->exists($towServiceCompany->company_logo)) {
                Storage::disk('public')->delete($towServiceCompany->company_logo);
            }

            $towServiceCompany->delete();
            return redirect()->route('admin.users.tow-service-companies.index')
                ->with('success', t('admin.tow_service_company_deleted'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', t('admin.error_occurred'));
        }
    }

    public function companiesToggle(TowServiceCompany $towServiceCompany)
    {
        try {
            $towServiceCompany->update(['is_active' => !$towServiceCompany->is_active]);

            $message = $towServiceCompany->is_active
                ? t('admin.tow_service_company_activated')
                : t('admin.tow_service_company_deactivated');

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', t('admin.error_occurred'));
        }
    }

    public function companiesApprove(TowServiceCompany $towServiceCompany)
    {
        try {
            $towServiceCompany->update(['is_approved' => !$towServiceCompany->is_approved]);

            $message = $towServiceCompany->is_approved
                ? t('admin.tow_service_company_approved')
                : t('admin.tow_service_company_unapproved');

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', t('admin.error_occurred'));
        }
    }

    // ==================== INDIVIDUALS MANAGEMENT ====================

    public function individualsIndex()
    {
        $individuals = TowServiceIndividual::orderBy('created_at', 'desc')->get();
        return view('admin.users.tow-service-individuals.index', compact('individuals'));
    }

    public function individualsCreate()
    {
        return view('admin.users.tow-service-individuals.addedit');
    }

    public function individualsStore(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|max:20|unique:tow_service_individuals,phone',
            'password' => 'required|string|min:6',
            'full_name' => 'required|string|max:255',
            'national_id' => 'required|string|size:14|unique:tow_service_individuals,national_id',
            'tow_truck_plate_number' => 'required|string|max:50|unique:tow_service_individuals,tow_truck_plate_number',
            'location_lat' => 'nullable|numeric|between:-90,90',
            'location_lng' => 'nullable|numeric|between:-180,180',
            'address' => 'nullable|string',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tow_truck_form' => 'nullable|image|mimes:jpeg,png,jpg,gif,pdf|max:5120',
            'is_active' => 'boolean',
            'is_approved' => 'boolean'
        ]);

        try {
            $data = $request->except(['profile_image', 'tow_truck_form']);
            $data['password'] = Hash::make($request->password);
            $data['is_active'] = $request->has('is_active');
            $data['is_approved'] = $request->has('is_approved');

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

            TowServiceIndividual::create($data);

            return redirect()->route('admin.users.tow-service-individuals.index')
                ->with('success', t('admin.tow_service_individual_created'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', t('admin.error_occurred'))
                ->withInput();
        }
    }

    public function individualsEdit(TowServiceIndividual $towServiceIndividual)
    {
        return view('admin.users.tow-service-individuals.addedit', compact('towServiceIndividual'));
    }

    public function individualsUpdate(Request $request, TowServiceIndividual $towServiceIndividual)
    {
        $request->validate([
            'phone' => 'required|string|max:20|unique:tow_service_individuals,phone,' . $towServiceIndividual->id,
            'password' => 'nullable|string|min:6',
            'full_name' => 'required|string|max:255',
            'national_id' => 'required|string|size:14|unique:tow_service_individuals,national_id,' . $towServiceIndividual->id,
            'tow_truck_plate_number' => 'required|string|max:50|unique:tow_service_individuals,tow_truck_plate_number,' . $towServiceIndividual->id,
            'location_lat' => 'nullable|numeric|between:-90,90',
            'location_lng' => 'nullable|numeric|between:-180,180',
            'address' => 'nullable|string',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tow_truck_form' => 'nullable|image|mimes:jpeg,png,jpg,gif,pdf|max:5120',
            'is_active' => 'boolean',
            'is_approved' => 'boolean'
        ]);

        try {
            $data = $request->except(['password', 'profile_image', 'tow_truck_form']);

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            $data['is_active'] = $request->has('is_active');
            $data['is_approved'] = $request->has('is_approved');

            // Handle profile image upload
            if ($request->hasFile('profile_image')) {
                // Delete old image if exists
                if ($towServiceIndividual->profile_image && Storage::disk('public')->exists($towServiceIndividual->profile_image)) {
                    Storage::disk('public')->delete($towServiceIndividual->profile_image);
                }

                $image = $request->file('profile_image');
                $filename = 'tow_individual_' . time() . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('tow_individual_profiles', $filename, 'public');
                $data['profile_image'] = $path;
            }

            // Handle tow truck form upload
            if ($request->hasFile('tow_truck_form')) {
                // Delete old form if exists
                if ($towServiceIndividual->tow_truck_form && Storage::disk('public')->exists($towServiceIndividual->tow_truck_form)) {
                    Storage::disk('public')->delete($towServiceIndividual->tow_truck_form);
                }

                $form = $request->file('tow_truck_form');
                $filename = 'tow_form_' . time() . '.' . $form->getClientOriginalExtension();
                $path = $form->storeAs('tow_truck_forms', $filename, 'public');
                $data['tow_truck_form'] = $path;
            }

            $towServiceIndividual->update($data);

            return redirect()->route('admin.users.tow-service-individuals.index')
                ->with('success', t('admin.tow_service_individual_updated'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', t('admin.error_occurred'))
                ->withInput();
        }
    }

    public function individualsDestroy(TowServiceIndividual $towServiceIndividual)
    {
        try {
            // Delete files if exist
            if ($towServiceIndividual->profile_image && Storage::disk('public')->exists($towServiceIndividual->profile_image)) {
                Storage::disk('public')->delete($towServiceIndividual->profile_image);
            }
            if ($towServiceIndividual->tow_truck_form && Storage::disk('public')->exists($towServiceIndividual->tow_truck_form)) {
                Storage::disk('public')->delete($towServiceIndividual->tow_truck_form);
            }

            $towServiceIndividual->delete();
            return redirect()->route('admin.users.tow-service-individuals.index')
                ->with('success', t('admin.tow_service_individual_deleted'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', t('admin.error_occurred'));
        }
    }

    public function individualsToggle(TowServiceIndividual $towServiceIndividual)
    {
        try {
            $towServiceIndividual->update(['is_active' => !$towServiceIndividual->is_active]);

            $message = $towServiceIndividual->is_active
                ? t('admin.tow_service_individual_activated')
                : t('admin.tow_service_individual_deactivated');

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', t('admin.error_occurred'));
        }
    }

    public function individualsApprove(TowServiceIndividual $towServiceIndividual)
    {
        try {
            $towServiceIndividual->update(['is_approved' => !$towServiceIndividual->is_approved]);

            $message = $towServiceIndividual->is_approved
                ? t('admin.tow_service_individual_approved')
                : t('admin.tow_service_individual_unapproved');

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', t('admin.error_occurred'));
        }
    }

    // Dashboard statistics
    public function towServiceStats()
    {
        $stats = [
            'companies' => [
                'total' => TowServiceCompany::count(),
                'active' => TowServiceCompany::active()->count(),
                'approved' => TowServiceCompany::approved()->count(),
                'pending_approval' => TowServiceCompany::where('is_approved', false)->count(),
                'total_capacity' => TowServiceCompany::sum('daily_capacity')
            ],
            'individuals' => [
                'total' => TowServiceIndividual::count(),
                'active' => TowServiceIndividual::active()->count(),
                'approved' => TowServiceIndividual::approved()->count(),
                'pending_approval' => TowServiceIndividual::where('is_approved', false)->count()
            ]
        ];

        return response()->json($stats);
    }
}