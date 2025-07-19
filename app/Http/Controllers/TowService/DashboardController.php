<?php

namespace App\Http\Controllers\TowService;

use App\Http\Controllers\Controller;
use App\Models\TowServiceIndividual;
use App\Models\TowServiceCompany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = null;
        $userType = null;

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

        $stats = $this->getBasicStats($user, $userType);

        return view('tow-service.dashboard.index', compact('user', 'userType', 'stats'));
    }

    private function getBasicStats($user, $userType)
    {
        return [
            'user_type' => $userType,
            'user_info' => $this->getUserInfo($user, $userType),
            'profile_completion' => $this->calculateProfileCompletion($user, $userType),
            'account_status' => $user->is_approved ? 'approved' : 'pending',
            'registration_date' => $user->created_at,
            'is_active' => $user->is_active,
        ];
    }

    private function getUserInfo($user, $userType)
    {
        $baseInfo = [
            'phone' => $user->phone,
            'member_since' => $user->created_at->format('Y/m/d'),
            'last_login' => $user->updated_at->format('Y/m/d H:i'),
            'address' => $user->address ?? ($userType == 'company' ? $user->office_address : null) ?? 'غير محدد'
        ];

        if ($userType === 'company') {
            return array_merge($baseInfo, [
                'display_name' => $user->legal_name,
                'commercial_register' => $user->commercial_register,
                'tax_number' => $user->tax_number,
                'daily_capacity' => $user->daily_capacity ?? 0,
                'delegate_number' => $user->delegate_number,
                'office_address' => $user->office_address,
                'office_location_lat' => $user->office_location_lat,
                'office_location_lng' => $user->office_location_lng,
                'company_logo' => $user->company_logo
            ]);
        } else {
            return array_merge($baseInfo, [
                'display_name' => $user->full_name,
                'national_id' => $user->national_id,
                'plate_number' => $user->tow_truck_plate_number,
                'tow_truck_form' => $user->tow_truck_form,
                'profile_image' => $user->profile_image,
                'location_lat' => $user->location_lat,
                'location_lng' => $user->location_lng,
                'has_truck_form' => !empty($user->tow_truck_form),
                'has_profile_image' => !empty($user->profile_image)
            ]);
        }
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
            
            if (!empty($user->company_logo)) {
                $completed += 0.5;
            }
            
            return round(($completed / (count($fields) + 0.5)) * 100);
        } else {
            $fields = ['full_name', 'phone', 'national_id', 'tow_truck_plate_number', 'address'];
            $completed = 0;
            foreach ($fields as $field) {
                if (!empty($user->$field)) {
                    $completed++;
                }
            }

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
