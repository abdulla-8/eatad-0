<?php

namespace App\Http\Controllers\TowService;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TowDashboardController extends Controller
{
    public function index()
    {
        $user = null;
        $userType = null;
        
        // Check which guard is authenticated
        if (Auth::guard('tow_service_company')->check()) {
            $user = Auth::guard('tow_service_company')->user();
            $userType = 'company';
        } elseif (Auth::guard('tow_service_individual')->check()) {
            $user = Auth::guard('tow_service_individual')->user();
            $userType = 'individual';
        }

        if (!$user) {
            return redirect()->route('tow-service.login');
        }

        $stats = $this->calculateStats($user, $userType);

        return view('tow-service.dashboard.index', compact('user', 'userType', 'stats'));
    }

    private function calculateStats($user, $userType)
    {
        $stats = [
            'user_type' => $userType,
            'profile_completion' => $this->calculateProfileCompletion($user, $userType),
            'account_status' => $user->is_approved ? 'approved' : 'pending',
            'registration_date' => $user->created_at,
            'is_active' => $user->is_active,
        ];

        if ($userType === 'company') {
            $stats['company_info'] = [
                'legal_name' => $user->legal_name,
                'commercial_register' => $user->commercial_register,
                'daily_capacity' => $user->daily_capacity,
                'delegate_number' => $user->delegate_number,
                'additional_phones' => $user->additionalPhones->count(),
            ];
        } else {
            $stats['individual_info'] = [
                'full_name' => $user->full_name,
                'national_id' => $user->formatted_national_id,
                'plate_number' => $user->tow_truck_plate_number,
                'has_truck_form' => !empty($user->tow_truck_form),
            ];
        }

        return $stats;
    }

    private function calculateProfileCompletion($user, $userType)
    {
        if ($userType === 'company') {
            $fields = ['legal_name', 'phone', 'commercial_register', 'office_address', 'daily_capacity'];
            $completed = 0;
            
            foreach ($fields as $field) {
                if (!empty($user->$field)) {
                    $completed++;
                }
            }
            
            return round(($completed / count($fields)) * 100);
        } else {
            $fields = ['full_name', 'phone', 'national_id', 'tow_truck_plate_number', 'address'];
            $completed = 0;
            
            foreach ($fields as $field) {
                if (!empty($user->$field)) {
                    $completed++;
                }
            }
            
            // Add bonus for uploaded documents
            if (!empty($user->tow_truck_form)) {
                $completed += 0.5;
            }
            if (!empty($user->profile_image)) {
                $completed += 0.5;
            }
            
            return round(($completed / (count($fields) + 1)) * 100);
        }
    }
}