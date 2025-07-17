<?php

namespace App\Http\Controllers\Insurance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ServiceCenter;
use App\Models\ServiceCenterPhone;
use App\Models\IndustrialArea;
use App\Models\ServiceSpecialization;
use App\Models\Translation;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ServiceCenterController extends Controller
{
    /**
     * عرض جميع مراكز الصيانة الخاصة بالشركة
     */
    public function index()
    {
        $insuranceCompany = Auth::guard('insurance_company')->user();
        
        $serviceCenters = ServiceCenter::with(['industrialArea', 'serviceSpecialization', 'additionalPhones'])
            ->forInsuranceCompany($insuranceCompany->id)
            ->createdByCompany()
            ->orderBy('created_at', 'desc')
            ->get();

        return view('insurance.service-centers.index', compact('serviceCenters'));
    }

    /**
     * عرض نموذج إنشاء مركز صيانة جديد
     */
    public function create()
    {
        $industrialAreas = IndustrialArea::active()->ordered()->get();
        $serviceSpecializations = ServiceSpecialization::active()->ordered()->get();
        
        return view('insurance.service-centers.create', compact('industrialAreas', 'serviceSpecializations'));
    }

    /**
     * عرض تفاصيل مركز الصيانة
     */
    public function show($companyRoute, $serviceCenterId)
    {
        $insuranceCompany = Auth::guard('insurance_company')->user();
        
        $serviceCenter = ServiceCenter::with(['additionalPhones', 'industrialArea', 'serviceSpecialization'])
            ->where('id', $serviceCenterId)
            ->where('insurance_company_id', $insuranceCompany->id)
            ->where('created_by_company', true)
            ->firstOrFail();
        
        return view('insurance.service-centers.show', compact('serviceCenter'));
    }

    /**
     * عرض نموذج تعديل مركز الصيانة
     */
    public function edit($companyRoute, $serviceCenterId)
    {
        $insuranceCompany = Auth::guard('insurance_company')->user();
        
        $serviceCenter = ServiceCenter::with(['additionalPhones'])
            ->where('id', $serviceCenterId)
            ->where('insurance_company_id', $insuranceCompany->id)
            ->where('created_by_company', true)
            ->firstOrFail();
            
        $industrialAreas = IndustrialArea::active()->ordered()->get();
        $serviceSpecializations = ServiceSpecialization::active()->ordered()->get();
        
        return view('insurance.service-centers.edit', compact('serviceCenter', 'industrialAreas', 'serviceSpecializations'));
    }

    /**
     * تحديث مركز الصيانة
     */
    public function update(Request $request, $companyRoute, $serviceCenterId)
    {
        $insuranceCompany = Auth::guard('insurance_company')->user();
        
        $serviceCenter = ServiceCenter::where('id', $serviceCenterId)
            ->where('insurance_company_id', $insuranceCompany->id)
            ->where('created_by_company', true)
            ->firstOrFail();

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

            $serviceCenter->update($data);

            // تحديث الهاتف الأساسي
            $primaryPhone = $serviceCenter->primaryPhone;
            if ($primaryPhone) {
                $primaryPhone->update(['phone' => $serviceCenter->phone]);
            }

            // تحديث الهواتف الإضافية
            $serviceCenter->additionalPhones()->where('is_primary', false)->delete();

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

            return redirect()->route('insurance.service-centers.index', ['companyRoute' => $companyRoute])
                ->with('success', 'تم تحديث مركز الصيانة بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء تحديث مركز الصيانة')
                ->withInput();
        }
    }

    /**
     * حذف مركز الصيانة
     */
    public function destroy($companyRoute, $serviceCenterId)
    {
        $insuranceCompany = Auth::guard('insurance_company')->user();
        
        $serviceCenter = ServiceCenter::where('id', $serviceCenterId)
            ->where('insurance_company_id', $insuranceCompany->id)
            ->where('created_by_company', true)
            ->firstOrFail();

        try {
            $serviceCenter->delete();
            return redirect()->route('insurance.service-centers.index', ['companyRoute' => $companyRoute])
                ->with('success', 'تم حذف مركز الصيانة بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف مركز الصيانة');
        }
    }

    /**
     * تفعيل/إلغاء تفعيل مركز الصيانة
     */
    public function toggle($companyRoute, $serviceCenterId)
    {
        $insuranceCompany = Auth::guard('insurance_company')->user();
        
        $serviceCenter = ServiceCenter::where('id', $serviceCenterId)
            ->where('insurance_company_id', $insuranceCompany->id)
            ->where('created_by_company', true)
            ->firstOrFail();

        try {
            $serviceCenter->update(['is_active' => !$serviceCenter->is_active]);

            $message = $serviceCenter->is_active ? 'تم تفعيل مركز الصيانة' : 'تم إلغاء تفعيل مركز الصيانة';

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تحديث حالة مركز الصيانة');
        }
    }

    /**
     * حفظ مركز صيانة جديد
     */
    public function store(Request $request)
    {
        $insuranceCompany = Auth::guard('insurance_company')->user();

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
            $data['insurance_company_id'] = $insuranceCompany->id;
            $data['created_by_company'] = true;
            $data['is_active'] = $request->has('is_active');
            $data['is_approved'] = false;
            $data['translation_group'] = 'service_' . $data['center_slug'];

            if ($request->hasFile('center_logo')) {
                $logo = $request->file('center_logo');
                $filename = $insuranceCompany->company_slug . '_' . $data['center_slug'] . '_logo.' . $logo->getClientOriginalExtension();
                $path = $logo->storeAs('service_center_logos', $filename, 'public');
                $data['center_logo'] = $path;
            }

            $serviceCenter = ServiceCenter::create($data);

            ServiceCenterPhone::create([
                'service_center_id' => $serviceCenter->id,
                'phone' => $serviceCenter->phone,
                'label' => 'الهاتف الأساسي',
                'is_primary' => true
            ]);

            $this->createDefaultTranslations($serviceCenter, $insuranceCompany);

            return redirect()->route('insurance.service-centers.index', ['companyRoute' => $insuranceCompany->company_slug])
                ->with('success', 'تم إنشاء مركز الصيانة بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء إنشاء مركز الصيانة')
                ->withInput();
        }
    }

    /**
     * إنشاء الترجمات الافتراضية
     */
    private function createDefaultTranslations($serviceCenter, $insuranceCompany)
    {
        $defaultTranslations = [
            'dashboard' => 'لوحة التحكم',
            'welcome_back' => 'مرحباً بعودتك',
            'my_profile' => 'ملفي الشخصي',
            'services' => 'الخدمات',
            'appointments' => 'المواعيد',
            'created_by' => 'منشأ بواسطة ' . $insuranceCompany->legal_name
        ];

        foreach ($defaultTranslations as $key => $value) {
            Translation::create([
                'language_id' => 1,
                'translation_key' => $serviceCenter->translation_group . '.' . $key,
                'translation_value' => $value
            ]);
        }
    }
}
