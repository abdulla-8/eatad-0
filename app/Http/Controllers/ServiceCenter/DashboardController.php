<?php

namespace App\Http\Controllers\ServiceCenter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $serviceCenter = auth('service_center')->user();
        
        // Basic stats for service center dashboard
        $stats = [
            'profile_completion' => $this->calculateProfileCompletion($serviceCenter),
            'account_status' => $serviceCenter->is_approved ? 'approved' : 'pending',
            'registration_date' => $serviceCenter->created_at,
            'industrial_area' => $serviceCenter->industrialArea ? $serviceCenter->industrialArea->display_name : null,
            'specialization' => $serviceCenter->serviceSpecialization ? $serviceCenter->serviceSpecialization->display_name : null,
            'total_technicians' => $serviceCenter->total_technicians,
            'center_area' => $serviceCenter->center_area_sqm,
            'technicians_breakdown' => $serviceCenter->technicians_breakdown,
        ];

        return view('service-center.dashboard.index', compact('serviceCenter', 'stats'));
    }

    private function calculateProfileCompletion($serviceCenter)
    {
        $fields = [
            'legal_name', 
            'phone', 
            'commercial_register', 
            'center_address', 
            'industrial_area_id', 
            'service_specialization_id',
            'center_area_sqm'
        ];
        
        $completed = 0;
        
        foreach ($fields as $field) {
            if (!empty($serviceCenter->$field)) {
                $completed++;
            }
        }
        
        // إضافة نقطة إضافية إذا كان لديه فنيين
        if ($serviceCenter->total_technicians > 0) {
            $completed++;
        }
        
        return round(($completed / (count($fields) + 1)) * 100);
    }
}