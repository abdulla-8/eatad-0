<?php
// Path: app/Http/Controllers/CustomerTrackingController.php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TowRequest;
use App\Models\TowTracking;

class CustomerTrackingController extends Controller
{
    /**
     * Show customer tracking page
     */
    public function show($requestCode)
    {
        $towRequest = TowRequest::where('request_code', $requestCode)
            ->with(['claim.insuranceUser', 'claim.serviceCenter'])
            ->first();

        if (!$towRequest) {
            abort(404, 'Tracking request not found');
        }

        // Get latest tracking info
        $latestTracking = TowTracking::getLatestLocation($towRequest->id);
        
        // Get provider info
        $providerInfo = $towRequest->getProviderContactInfo();

        return view('customer.tracking', compact('towRequest', 'latestTracking', 'providerInfo'));
    }

    /**
     * Verify customer pickup code
     */
    public function verifyPickupCode(Request $request, $requestCode)
    {
        $towRequest = TowRequest::where('request_code', $requestCode)->first();

        if (!$towRequest) {
            return response()->json(['error' => 'Invalid request code'], 404);
        }

        $request->validate([
            'verification_code' => 'required|string|size:5'
        ]);

        if ($towRequest->status !== 'arrived_at_pickup') {
            return response()->json([
                'success' => false,
                'error' => 'Driver has not arrived at pickup location yet'
            ], 400);
        }

        if ($towRequest->verifyCustomerCode($request->verification_code)) {
            return response()->json([
                'success' => true,
                'message' => 'Verification successful! Driver can now load your vehicle.',
                'new_status' => 'customer_verified'
            ]);
        }

        return response()->json([
            'success' => false,
            'error' => 'Invalid verification code'
        ], 400);
    }

    /**
     * Get current tracking data for customer
     */
    public function getCurrentData($requestCode)
    {
        $towRequest = TowRequest::where('request_code', $requestCode)
            ->with(['claim.insuranceUser', 'claim.serviceCenter'])
            ->first();

        if (!$towRequest) {
            return response()->json(['error' => 'Invalid request code'], 404);
        }

        $latestTracking = TowTracking::getLatestLocation($towRequest->id);
        $providerInfo = $towRequest->getProviderContactInfo();

        return response()->json([
            'tow_request' => [
                'id' => $towRequest->id,
                'request_code' => $towRequest->request_code,
                'status' => $towRequest->status,
                'status_badge' => $towRequest->status_badge,
                'pickup_location' => [
                    'lat' => $towRequest->pickup_location_lat,
                    'lng' => $towRequest->pickup_location_lng,
                    'address' => $towRequest->pickup_location_address
                ],
                'dropoff_location' => [
                    'lat' => $towRequest->dropoff_location_lat,
                    'lng' => $towRequest->dropoff_location_lng,
                    'address' => $towRequest->dropoff_location_address
                ],
                'customer' => [
                    'name' => $towRequest->claim->insuranceUser->full_name,
                    'phone' => $towRequest->claim->insuranceUser->formatted_phone
                ],
                'service_center' => [
                    'name' => $towRequest->claim->serviceCenter->legal_name,
                    'phone' => $towRequest->claim->serviceCenter->formatted_phone,
                    'address' => $towRequest->claim->serviceCenter->center_address
                ],
                'estimated_pickup_time' => $towRequest->estimated_pickup_time?->format('Y-m-d H:i:s'),
                'actual_pickup_time' => $towRequest->actual_pickup_time?->format('Y-m-d H:i:s'),
                'actual_delivery_time' => $towRequest->actual_delivery_time?->format('Y-m-d H:i:s'),
                'show_verification_input' => $towRequest->status === 'arrived_at_pickup',
                'customer_verification_code' => $towRequest->status === 'arrived_at_pickup' ? $towRequest->customer_verification_code : null
            ],
            'provider_info' => $providerInfo,
            'latest_tracking' => $latestTracking ? [
                '