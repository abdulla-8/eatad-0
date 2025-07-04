<?php
// Path: app/Http/Controllers/ServiceCenter/VerificationController.php

namespace App\Http\Controllers\ServiceCenter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TowRequest;

class VerificationController extends Controller
{
    /**
     * Show vehicle verification page
     */
    public function index()
    {
        $serviceCenter = Auth::guard('service_center')->user();
        
        // Get recent deliveries for this service center
        $recentDeliveries = TowRequest::whereHas('claim', function($query) use ($serviceCenter) {
                $query->where('service_center_id', $serviceCenter->id);
            })
            ->where('status', 'delivered')
            ->with(['claim.insuranceUser'])
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get();

        return view('service-center.verification.index', compact('serviceCenter', 'recentDeliveries'));
    }

    /**
     * Verify delivery code
     */
    public function verify(Request $request)
    {
        $serviceCenter = Auth::guard('service_center')->user();
        
        $request->validate([
            'verification_code' => 'required|string|size:6'
        ]);

        // Find tow request with this verification code
        $towRequest = TowRequest::where('service_center_verification_code', $request->verification_code)
            ->where('status', 'delivered')
            ->whereHas('claim', function($query) use ($serviceCenter) {
                $query->where('service_center_id', $serviceCenter->id);
            })
            ->with(['claim.insuranceUser', 'claim'])
            ->first();

        if (!$towRequest) {
            return response()->json([
                'success' => false,
                'error' => 'Invalid verification code or vehicle not ready for pickup'
            ], 400);
        }

        try {
            // Verify the code and update status
            if ($towRequest->verifyServiceCenterCode($request->verification_code)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Vehicle received successfully! Now under inspection.',
                    'tow_request' => [
                        'request_code' => $towRequest->request_code,
                        'customer_name' => $towRequest->claim->insuranceUser->full_name,
                        'claim_number' => $towRequest->claim->claim_number,
                        'vehicle_info' => $towRequest->claim->vehicle_plate_number ?: $towRequest->claim->chassis_number,
                        'delivery_time' => $towRequest->actual_delivery_time->format('M d, Y H:i'),
                        'new_status' => 'Under Inspection'
                    ]
                ]);
            }

            return response()->json([
                'success' => false,
                'error' => 'Failed to verify code'
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'An error occurred while processing verification'
            ], 500);
        }
    }

    /**
     * Get verification history
     */
    public function history()
    {
        $serviceCenter = Auth::guard('service_center')->user();
        
        $verifications = TowRequest::whereHas('claim', function($query) use ($serviceCenter) {
                $query->where('service_center_id', $serviceCenter->id);
            })
            ->whereIn('status', ['service_center_received'])
            ->with(['claim.insuranceUser'])
            ->orderBy('actual_delivery_time', 'desc')
            ->paginate(20);

        return response()->json([
            'verifications' => $verifications->map(function($towRequest) {
                return [
                    'request_code' => $towRequest->request_code,
                    'customer_name' => $towRequest->claim->insuranceUser->full_name,
                    'claim_number' => $towRequest->claim->claim_number,
                    'vehicle_info' => $towRequest->claim->vehicle_plate_number ?: $towRequest->claim->chassis_number,
                    'delivery_time' => $towRequest->actual_delivery_time?->format('M d, Y H:i'),
                    'verification_time' => $towRequest->updated_at->format('M d, Y H:i'),
                    'status' => $towRequest->status
                ];
            }),
            'pagination' => [
                'current_page' => $verifications->currentPage(),
                'last_page' => $verifications->lastPage(),
                'total' => $verifications->total()
            ]
        ]);
    }
}