<?php

namespace App\Http\Controllers\PartsDealer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $dealer = auth('parts_dealer')->user();
        
        // Basic stats for dealer dashboard
        $stats = [
            'profile_completion' => $this->calculateProfileCompletion($dealer),
            'account_status' => $dealer->is_approved ? 'approved' : 'pending',
            'registration_date' => $dealer->created_at,
            'specialization' => $dealer->specialization ? $dealer->specialization->display_name : null,
        ];

        return view('dealer.dashboard.index', compact('dealer', 'stats'));
    }

    private function calculateProfileCompletion($dealer)
    {
        $fields = ['legal_name', 'phone', 'commercial_register', 'shop_address', 'specialization_id'];
        $completed = 0;
        
        foreach ($fields as $field) {
            if (!empty($dealer->$field)) {
                $completed++;
            }
        }
        
        return round(($completed / count($fields)) * 100);
    }
}

