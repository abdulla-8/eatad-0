<?php

namespace App\Http\Controllers\Insurance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $company = auth('insurance_company')->user();
        $currentCompany = session('current_company');
        
        $stats = [
            'profile_completion' => $this->calculateProfileCompletion($company),
            'account_status' => $company->is_approved ? 'approved' : 'pending',
            'registration_date' => $company->created_at,
            'employee_count' => $company->employee_count,
            'insured_cars_count' => $company->insured_cars_count,
            'additional_phones' => $company->additionalPhones->count(),
        ];

        return view('insurance.dashboard.index', compact('company', 'stats'));
    }

    private function calculateProfileCompletion($company)
    {
        $fields = ['legal_name', 'phone', 'commercial_register', 'office_address', 'employee_count'];
        $completed = 0;
        
        foreach ($fields as $field) {
            if (!empty($company->$field)) {
                $completed++;
            }
        }
        
        return round(($completed / count($fields)) * 100);
    }
}