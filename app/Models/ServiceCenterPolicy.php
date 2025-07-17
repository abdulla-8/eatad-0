<?php

namespace App\Policies;

use App\Models\ServiceCenter;
use App\Models\InsuranceCompany;
use Illuminate\Auth\Access\HandlesAuthorization;

class ServiceCenterPolicy
{
    use HandlesAuthorization;

    /**
     * تحديد ما إذا كان بإمكان شركة التأمين عرض مركز الصيانة
     */
    public function view(InsuranceCompany $company, ServiceCenter $serviceCenter)
    {
        return $serviceCenter->insurance_company_id === $company->id && $serviceCenter->created_by_company;
    }

    /**
     * تحديد ما إذا كان بإمكان شركة التأمين تحديث مركز الصيانة
     */
    public function update(InsuranceCompany $company, ServiceCenter $serviceCenter)
    {
        return $serviceCenter->insurance_company_id === $company->id && $serviceCenter->created_by_company;
    }

    /**
     * تحديد ما إذا كان بإمكان شركة التأمين حذف مركز الصيانة
     */
    public function delete(InsuranceCompany $company, ServiceCenter $serviceCenter)
    {
        return $serviceCenter->insurance_company_id === $company->id && $serviceCenter->created_by_company;
    }

    /**
     * تحديد ما إذا كان بإمكان شركة التأمين تفعيل/إلغاء تفعيل مركز الصيانة
     */
    public function toggle(InsuranceCompany $company, ServiceCenter $serviceCenter)
    {
        return $serviceCenter->insurance_company_id === $company->id && $serviceCenter->created_by_company;
    }
}
