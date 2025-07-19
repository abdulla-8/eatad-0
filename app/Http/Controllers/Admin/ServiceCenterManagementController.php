<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ServiceCenter;
use App\Models\ServiceCenterPhone;
use App\Models\IndustrialArea;
use App\Models\ServiceSpecialization;
use App\Models\Translation;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ServiceCenterManagementController extends Controller
{
    // Service Centers Management
    public function serviceCentersIndex()
    {
        $serviceCenters = ServiceCenter::with(['industrialArea', 'serviceSpecialization', 'additionalPhones'])
            ->orderBy('created_at', 'desc')->get();
        return view('admin.users.service-centers.index', compact('serviceCenters'));
    }

    public function serviceCentersCreate()
    {
        $industrialAreas = IndustrialArea::active()->ordered()->get();
        $serviceSpecializations = ServiceSpecialization::active()->ordered()->get();
        return view('admin.users.service-centers.addedit', compact('industrialAreas', 'serviceSpecializations'));
    }

    public function serviceCentersStore(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|max:20|unique:service_centers,phone',
            'password' => 'required|string|min:6',
            'commercial_register' => 'required|string|max:100|unique:service_centers,commercial_register',
            'legal_name' => 'required|string|max:255',
            'center_slug' => 'required|string|max:100|unique:service_centers,center_slug',
            'industrial_area_id' => 'nullable|exists:industrial_areas,id',
            'service_specialization_id' => 'nullable|exists:service_specializations,id',
            'body_work_technicians' => 'nullable|integer|min:0',
            'mechanical_technicians' => 'nullable|integer|min:0',
            'painting_technicians' => 'nullable|integer|min:0',
            'electrical_technicians' => 'nullable|integer|min:0',
            'other_technicians' => 'nullable|integer|min:0',
            'center_area_sqm' => 'nullable|numeric|min:0',
            'center_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        try {
            $data = $request->except(['center_logo']);
            $data['password'] = Hash::make($request->password);
            $data['is_active'] = $request->has('is_active');
            $data['is_approved'] = $request->has('is_approved');
            $data['translation_group'] = 'service_' . $data['center_slug'];

            if ($request->hasFile('center_logo')) {
                $logo = $request->file('center_logo');
                $filename = $data['center_slug'] . '_logo.' . $logo->getClientOriginalExtension();
                $path = $logo->storeAs('service_center_logos', $filename, 'public');
                $data['center_logo'] = $path;
            }

            $serviceCenter = ServiceCenter::create($data);

            // Create primary phone
            ServiceCenterPhone::create([
                'service_center_id' => $serviceCenter->id,
                'phone' => $serviceCenter->phone,
                'label' => t('admin.primary_phone'),
                'is_primary' => true
            ]);

            $this->createDefaultTranslations($serviceCenter);

            return redirect()->route('admin.users.service-centers.index')
                ->with('success', t('admin.service_center_created'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', t('admin.error_occurred'))
                ->withInput();
        }
    }

    private function createDefaultTranslations($serviceCenter)
    {
        $defaultTranslations = [
            'dashboard' => 'لوحة التحكم',
            'welcome_back' => 'مرحباً بعودتك',
            'my_profile' => 'ملفي الشخصي',
            'services' => 'الخدمات',
            'appointments' => 'المواعيد'
        ];

        foreach ($defaultTranslations as $key => $value) {
            Translation::create([
                'language_id' => 1,
                'translation_key' => $serviceCenter->translation_group . '.' . $key,
                'translation_value' => $value
            ]);
        }
    }

    public function serviceCentersEdit($id)
    {
        $serviceCenter = ServiceCenter::findOrFail($id);
        $serviceCenter->load('additionalPhones');
        $industrialAreas = IndustrialArea::active()->ordered()->get();
        $serviceSpecializations = ServiceSpecialization::active()->ordered()->get();
        return view('admin.users.service-centers.addedit', compact('serviceCenter', 'industrialAreas', 'serviceSpecializations'));
    }

    public function serviceCentersUpdate(Request $request, $id)
    {
        $serviceCenter = ServiceCenter::findOrFail($id);

        $request->validate([
            'phone' => 'required|string|max:20|unique:service_centers,phone,' . $serviceCenter->id,
            'password' => 'nullable|string|min:6',
            'commercial_register' => 'required|string|max:100|unique:service_centers,commercial_register,' . $serviceCenter->id,
            'tax_number' => 'nullable|string|max:100',
            'legal_name' => 'required|string|max:255',
            'industrial_area_id' => 'nullable|exists:industrial_areas,id',
            'service_specialization_id' => 'nullable|exists:service_specializations,id',
            'body_work_technicians' => 'nullable|integer|min:0',
            'mechanical_technicians' => 'nullable|integer|min:0',
            'painting_technicians' => 'nullable|integer|min:0',
            'electrical_technicians' => 'nullable|integer|min:0',
            'other_technicians' => 'nullable|integer|min:0',
            'center_area_sqm' => 'nullable|numeric|min:0',
            'center_location_lat' => 'nullable|numeric|between:-90,90',
            'center_location_lng' => 'nullable|numeric|between:-180,180',
            'center_address' => 'nullable|string',
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

            $serviceCenter->update($data);

            // Update primary phone
            $primaryPhone = $serviceCenter->primaryPhone;
            if ($primaryPhone) {
                $primaryPhone->update(['phone' => $serviceCenter->phone]);
            } else {
                ServiceCenterPhone::create([
                    'service_center_id' => $serviceCenter->id,
                    'phone' => $serviceCenter->phone,
                    'label' => t('admin.primary_phone'),
                    'is_primary' => true
                ]);
            }

            // Delete existing additional phones and recreate
            $serviceCenter->additionalPhones()->where('is_primary', false)->delete();

            // Add new additional phones
            if ($request->additional_phones) {
                foreach ($request->additional_phones as $index => $phone) {
                    if (!empty($phone)) {
                        ServiceCenterPhone::create([
                            'service_center_id' => $serviceCenter->id,
                            'phone' => $phone,
                            'label' => $request->phone_labels[$index] ?? null,
                            'is_primary' => false
                        ]);
                    }
                }
            }

            return redirect()->route('admin.users.service-centers.index')
                ->with('success', t('admin.service_center_updated'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', t('admin.error_occurred'))
                ->withInput();
        }
    }


    public function serviceCentersDestroy($id)
    {
        try {
            $serviceCenter = ServiceCenter::findOrFail($id);
            $serviceCenter->delete();
            return redirect()->route('admin.users.service-centers.index')
                ->with('success', t('admin.service_center_deleted'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', t('admin.error_occurred'));
        }
    }

    public function serviceCentersToggle($id)
    {
        try {
            $serviceCenter = ServiceCenter::findOrFail($id);
            $serviceCenter->update(['is_active' => !$serviceCenter->is_active]);

            $message = $serviceCenter->is_active
                ? t('admin.service_center_activated')
                : t('admin.service_center_deactivated');

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', t('admin.error_occurred'));
        }
    }

    public function serviceCentersApprove($id)
    {
        try {
            $serviceCenter = ServiceCenter::findOrFail($id);
            $serviceCenter->update(['is_approved' => !$serviceCenter->is_approved]);

            $message = $serviceCenter->is_approved
                ? t('admin.service_center_approved')
                : t('admin.service_center_unapproved');

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', t('admin.error_occurred'));
        }
    }

    // Dashboard statistics
    public function serviceCentersStats()
    {
        $stats = [
            'service_centers' => [
                'total' => ServiceCenter::count(),
                'active' => ServiceCenter::active()->count(),
                'approved' => ServiceCenter::approved()->count(),
                'pending_approval' => ServiceCenter::where('is_approved', false)->count(),
                'total_technicians' => ServiceCenter::sum('body_work_technicians') +
                    ServiceCenter::sum('mechanical_technicians') +
                    ServiceCenter::sum('painting_technicians') +
                    ServiceCenter::sum('electrical_technicians') +
                    ServiceCenter::sum('other_technicians'),
                'total_area' => ServiceCenter::sum('center_area_sqm')
            ]
        ];

        return response()->json($stats);
    }
}
