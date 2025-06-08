<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PartsDealer;
use App\Models\InsuranceCompany;
use App\Models\InsuranceCompanyPhone;
use App\Models\Specialization;
use Illuminate\Support\Facades\Hash;

class UsersManagementController extends Controller
{
    // Parts Dealers Management
    public function partsDealersIndex()
    {
        $dealers = PartsDealer::with('specialization')->orderBy('created_at', 'desc')->get();
        return view('admin.users.parts-dealers.index', compact('dealers'));
    }

    public function partsDealersCreate()
    {
        $specializations = Specialization::active()->ordered()->get();
        return view('admin.users.parts-dealers.addedit', compact('specializations'));
    }

    public function partsDealersStore(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|max:20|unique:parts_dealers,phone',
            'password' => 'required|string|min:6',
            'commercial_register' => 'required|string|max:100|unique:parts_dealers,commercial_register',
            'tax_number' => 'nullable|string|max:100',
            'legal_name' => 'required|string|max:255',
            'specialization_id' => 'nullable|exists:specializations,id',
            'is_scrapyard_owner' => 'boolean',
            'shop_location_lat' => 'nullable|numeric|between:-90,90',
            'shop_location_lng' => 'nullable|numeric|between:-180,180',
            'shop_address' => 'nullable|string',
            'is_active' => 'boolean',
            'is_approved' => 'boolean'
        ]);

        try {
            $data = $request->all();
            $data['password'] = Hash::make($request->password);
            $data['is_scrapyard_owner'] = $request->has('is_scrapyard_owner');
            $data['is_active'] = $request->has('is_active');
            $data['is_approved'] = $request->has('is_approved');

            PartsDealer::create($data);

            return redirect()->route('admin.users.parts-dealers.index')
                ->with('success', t('admin.parts_dealer_created'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', t('admin.error_occurred'))
                ->withInput();
        }
    }

    public function partsDealersEdit(PartsDealer $partsDealer)
    {
        $specializations = Specialization::active()->ordered()->get();
        return view('admin.users.parts-dealers.addedit', compact('partsDealer', 'specializations'));
    }

    public function partsDealersUpdate(Request $request, PartsDealer $partsDealer)
    {
        $request->validate([
            'phone' => 'required|string|max:20|unique:parts_dealers,phone,' . $partsDealer->id,
            'password' => 'nullable|string|min:6',
            'commercial_register' => 'required|string|max:100|unique:parts_dealers,commercial_register,' . $partsDealer->id,
            'tax_number' => 'nullable|string|max:100',
            'legal_name' => 'required|string|max:255',
            'specialization_id' => 'nullable|exists:specializations,id',
            'is_scrapyard_owner' => 'boolean',
            'shop_location_lat' => 'nullable|numeric|between:-90,90',
            'shop_location_lng' => 'nullable|numeric|between:-180,180',
            'shop_address' => 'nullable|string',
            'is_active' => 'boolean',
            'is_approved' => 'boolean'
        ]);

        try {
            $data = $request->except(['password']);

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            $data['is_scrapyard_owner'] = $request->has('is_scrapyard_owner');
            $data['is_active'] = $request->has('is_active');
            $data['is_approved'] = $request->has('is_approved');

            $partsDealer->update($data);

            return redirect()->route('admin.users.parts-dealers.index')
                ->with('success', t('admin.parts_dealer_updated'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', t('admin.error_occurred'))
                ->withInput();
        }
    }

    public function partsDealersDestroy(PartsDealer $partsDealer)
    {
        try {
            $partsDealer->delete();
            return redirect()->route('admin.users.parts-dealers.index')
                ->with('success', t('admin.parts_dealer_deleted'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', t('admin.error_occurred'));
        }
    }

    public function partsDealersToggle(PartsDealer $partsDealer)
    {
        try {
            $partsDealer->update(['is_active' => !$partsDealer->is_active]);

            $message = $partsDealer->is_active
                ? t('admin.parts_dealer_activated')
                : t('admin.parts_dealer_deactivated');

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', t('admin.error_occurred'));
        }
    }

    public function partsDealersApprove(PartsDealer $partsDealer)
    {
        try {
            $partsDealer->update(['is_approved' => !$partsDealer->is_approved]);

            $message = $partsDealer->is_approved
                ? t('admin.parts_dealer_approved')
                : t('admin.parts_dealer_unapproved');

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', t('admin.error_occurred'));
        }
    }

    // Insurance Companies Management
    public function insuranceCompaniesIndex()
    {
        $companies = InsuranceCompany::with('additionalPhones')->orderBy('created_at', 'desc')->get();
        return view('admin.users.insurance-companies.index', compact('companies'));
    }

    public function insuranceCompaniesCreate()
    {
        return view('admin.users.insurance-companies.addedit');
    }

    public function insuranceCompaniesStore(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|max:20|unique:insurance_companies,phone',
            'password' => 'required|string|min:6',
            'commercial_register' => 'required|string|max:100|unique:insurance_companies,commercial_register',
            'tax_number' => 'nullable|string|max:100',
            'legal_name' => 'required|string|max:255',
            'employee_count' => 'nullable|integer|min:1',
            'insured_cars_count' => 'nullable|integer|min:0',
            'office_location_lat' => 'nullable|numeric|between:-90,90',
            'office_location_lng' => 'nullable|numeric|between:-180,180',
            'office_address' => 'nullable|string',
            'is_active' => 'boolean',
            'is_approved' => 'boolean',
            'additional_phones' => 'nullable|array',
            'additional_phones.*' => 'nullable|string|max:20',
            'phone_labels' => 'nullable|array',
            'phone_labels.*' => 'nullable|string|max:100'
        ]);

        try {
            $data = $request->except(['additional_phones', 'phone_labels']);
            $data['password'] = Hash::make($request->password);
            $data['is_active'] = $request->has('is_active');
            $data['is_approved'] = $request->has('is_approved');

            $company = InsuranceCompany::create($data);

            // Add primary phone
            InsuranceCompanyPhone::create([
                'insurance_company_id' => $company->id,
                'phone' => $company->phone,
                'label' => t('admin.primary_phone'),
                'is_primary' => true
            ]);

            // Add additional phones
            if ($request->additional_phones) {
                foreach ($request->additional_phones as $index => $phone) {
                    if (!empty($phone)) {
                        InsuranceCompanyPhone::create([
                            'insurance_company_id' => $company->id,
                            'phone' => $phone,
                            'label' => $request->phone_labels[$index] ?? null,
                            'is_primary' => false
                        ]);
                    }
                }
            }

            return redirect()->route('admin.users.insurance-companies.index')
                ->with('success', t('admin.insurance_company_created'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', t('admin.error_occurred'))
                ->withInput();
        }
    }

    public function insuranceCompaniesEdit(InsuranceCompany $insuranceCompany)
    {
        $insuranceCompany->load('additionalPhones');
        return view('admin.users.insurance-companies.addedit', compact('insuranceCompany'));
    }

    public function insuranceCompaniesUpdate(Request $request, InsuranceCompany $insuranceCompany)
    {
        $request->validate([
            'phone' => 'required|string|max:20|unique:insurance_companies,phone,' . $insuranceCompany->id,
            'password' => 'nullable|string|min:6',
            'commercial_register' => 'required|string|max:100|unique:insurance_companies,commercial_register,' . $insuranceCompany->id,
            'tax_number' => 'nullable|string|max:100',
            'legal_name' => 'required|string|max:255',
            'employee_count' => 'nullable|integer|min:1',
            'insured_cars_count' => 'nullable|integer|min:0',
            'office_location_lat' => 'nullable|numeric|between:-90,90',
            'office_location_lng' => 'nullable|numeric|between:-180,180',
            'office_address' => 'nullable|string',
            'is_active' => 'boolean',
            'is_approved' => 'boolean',
            'additional_phones' => 'nullable|array',
            'additional_phones.*' => 'nullable|string|max:20',
            'phone_labels' => 'nullable|array',
            'phone_labels.*' => 'nullable|string|max:100'
        ]);

        try {
            $data = $request->except(['password', 'additional_phones', 'phone_labels']);

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            $data['is_active'] = $request->has('is_active');
            $data['is_approved'] = $request->has('is_approved');

            $insuranceCompany->update($data);

            // Update primary phone
            $primaryPhone = $insuranceCompany->primaryPhone;
            if ($primaryPhone) {
                $primaryPhone->update(['phone' => $insuranceCompany->phone]);
            } else {
                InsuranceCompanyPhone::create([
                    'insurance_company_id' => $insuranceCompany->id,
                    'phone' => $insuranceCompany->phone,
                    'label' => t('admin.primary_phone'),
                    'is_primary' => true
                ]);
            }

            // Delete existing additional phones and recreate
            $insuranceCompany->additionalPhones()->where('is_primary', false)->delete();

            // Add new additional phones
            if ($request->additional_phones) {
                foreach ($request->additional_phones as $index => $phone) {
                    if (!empty($phone)) {
                        InsuranceCompanyPhone::create([
                            'insurance_company_id' => $insuranceCompany->id,
                            'phone' => $phone,
                            'label' => $request->phone_labels[$index] ?? null,
                            'is_primary' => false
                        ]);
                    }
                }
            }

            return redirect()->route('admin.users.insurance-companies.index')
                ->with('success', t('admin.insurance_company_updated'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', t('admin.error_occurred'))
                ->withInput();
        }
    }

    public function insuranceCompaniesDestroy(InsuranceCompany $insuranceCompany)
    {
        try {
            $insuranceCompany->delete();
            return redirect()->route('admin.users.insurance-companies.index')
                ->with('success', t('admin.insurance_company_deleted'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', t('admin.error_occurred'));
        }
    }

    public function insuranceCompaniesToggle(InsuranceCompany $insuranceCompany)
    {
        try {
            $insuranceCompany->update(['is_active' => !$insuranceCompany->is_active]);

            $message = $insuranceCompany->is_active
                ? t('admin.insurance_company_activated')
                : t('admin.insurance_company_deactivated');

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', t('admin.error_occurred'));
        }
    }

    public function insuranceCompaniesApprove(InsuranceCompany $insuranceCompany)
    {
        try {
            $insuranceCompany->update(['is_approved' => !$insuranceCompany->is_approved]);

            $message = $insuranceCompany->is_approved
                ? t('admin.insurance_company_approved')
                : t('admin.insurance_company_unapproved');

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', t('admin.error_occurred'));
        }
    }

    // Dashboard statistics
    public function usersStats()
    {
        $stats = [
            'parts_dealers' => [
                'total' => PartsDealer::count(),
                'active' => PartsDealer::active()->count(),
                'approved' => PartsDealer::approved()->count(),
                'scrapyard_owners' => PartsDealer::scrapyardOwners()->count(),
                'pending_approval' => PartsDealer::where('is_approved', false)->count()
            ],
            'insurance_companies' => [
                'total' => InsuranceCompany::count(),
                'active' => InsuranceCompany::active()->count(),
                'approved' => InsuranceCompany::approved()->count(),
                'pending_approval' => InsuranceCompany::where('is_approved', false)->count()
            ]
        ];

        return response()->json($stats);
    }
}
