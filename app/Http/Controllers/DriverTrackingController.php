<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TowRequest;
use App\Models\TowTracking;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DriverTrackingController extends Controller
{
    /**
     * Show driver tracking page
     */
    public function show($token)
    {
        try {
            $towRequest = TowRequest::where('driver_tracking_token', $token)
                ->with(['claim.insuranceUser', 'claim.serviceCenter'])
                ->first();

            if (!$towRequest) {
                abort(404, 'Tracking link not found');
            }

            // Get latest tracking info
            $latestTracking = TowTracking::getLatestLocation($towRequest->id);
            
            // Get provider info
            $providerInfo = $towRequest->getProviderContactInfo();

            return view('driver.tracking', compact('towRequest', 'latestTracking', 'providerInfo'));
        } catch (\Exception $e) {
            Log::error('Driver tracking show error: ' . $e->getMessage());
            abort(500, 'Error loading tracking page');
        }
    }

    /**
     * Update driver location
     */
    public function updateLocation(Request $request, $token)
    {
        try {
            $towRequest = TowRequest::where('driver_tracking_token', $token)->first();

            if (!$towRequest) {
                return response()->json(['error' => 'Invalid tracking token'], 404);
            }

            $validated = $request->validate([
                'lat' => 'required|numeric|between:-90,90',
                'lng' => 'required|numeric|between:-180,180',
                'speed' => 'nullable|numeric|min:0',
                'heading' => 'nullable|numeric|between:0,360'
            ]);

            TowTracking::create([
                'tow_request_id' => $towRequest->id,
                'driver_lat' => $validated['lat'],
                'driver_lng' => $validated['lng'],
                'timestamp' => now(),
                'speed' => $validated['speed'] ?? null,
                'heading' => $validated['heading'] ?? null
            ]);

            return response()->json(['success' => true]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => 'Invalid location data', 'details' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Update location error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to update location'], 500);
        }
    }

    /**
     * Update tow request status
     */
    public function updateStatus(Request $request, $token)
    {
        try {
            $towRequest = TowRequest::where('driver_tracking_token', $token)->first();

            if (!$towRequest) {
                return response()->json(['error' => 'Invalid tracking token'], 404);
            }

            $validated = $request->validate([
                'status' => 'required|in:in_transit_to_pickup,arrived_at_pickup,vehicle_loaded,in_transit_to_dropoff,delivered',
                'lat' => 'nullable|numeric|between:-90,90',
                'lng' => 'nullable|numeric|between:-180,180',
                'notes' => 'nullable|string|max:500'
            ]);

            DB::beginTransaction();

            // Update tow request status
            $towRequest->update(['status' => $validated['status']]);

            // Log tracking info with status change
            if (!empty($validated['lat']) && !empty($validated['lng'])) {
                TowTracking::create([
                    'tow_request_id' => $towRequest->id,
                    'driver_lat' => $validated['lat'],
                    'driver_lng' => $validated['lng'],
                    'timestamp' => now(),
                    'status' => $validated['status'],
                    'notes' => $validated['notes'] ?? null
                ]);
            }

            DB::commit();

            $message = match($validated['status']) {
                'in_transit_to_pickup' => 'Status updated: On the way to pickup location',
                'arrived_at_pickup' => 'Status updated: Arrived at pickup location',
                'vehicle_loaded' => 'Status updated: Vehicle loaded on tow truck',
                'in_transit_to_dropoff' => 'Status updated: On the way to service center',
                'delivered' => 'Status updated: Vehicle delivered to service center',
                default => 'Status updated successfully'
            };

            return response()->json([
                'success' => true,
                'message' => $message,
                'new_status' => $validated['status'],
                'show_customer_code' => $validated['status'] === 'arrived_at_pickup',
                'show_service_code' => $validated['status'] === 'delivered',
                'service_code' => $validated['status'] === 'delivered' ? $towRequest->service_center_verification_code : null
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => 'Invalid status data', 'details' => $e->errors()], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Update status error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to update status'], 500);
        }
    }

    /**
     * Verify customer code
     */
    public function verifyCustomerCode(Request $request, $token)
    {
        try {
            $towRequest = TowRequest::where('driver_tracking_token', $token)->first();

            if (!$towRequest) {
                return response()->json(['error' => 'Invalid tracking token'], 404);
            }

            $validated = $request->validate([
                'verification_code' => 'required|string|size:5'
            ]);

            if ($towRequest->verifyCustomerCode($validated['verification_code'])) {
                return response()->json([
                    'success' => true,
                    'message' => 'Customer verification successful! Vehicle can now be loaded.',
                    'service_center_code' => $towRequest->service_center_verification_code
                ]);
            }

            return response()->json([
                'success' => false,
                'error' => 'Invalid verification code'
            ], 400);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => 'Invalid code format', 'details' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Verify customer code error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to verify code'], 500);
        }
    }

    /**
     * Get current tow request data
     */
    public function getCurrentData($token)
    {
        try {
            $towRequest = TowRequest::where('driver_tracking_token', $token)
                ->with(['claim.insuranceUser', 'claim.serviceCenter'])
                ->first();

            if (!$towRequest) {
                return response()->json(['error' => 'Invalid tracking token'], 404);
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
                    'show_customer_verification' => $towRequest->status === 'arrived_at_pickup',
                    'show_service_center_code' => in_array($towRequest->status, ['customer_verified', 'vehicle_loaded', 'in_transit_to_dropoff', 'delivered'])
                ],
                'provider_info' => $providerInfo,
                'latest_tracking' => $latestTracking ? [
                    'lat' => $latestTracking->driver_lat,
                    'lng' => $latestTracking->driver_lng,
                    'timestamp' => $latestTracking->formatted_timestamp,
                    'speed' => $latestTracking->speed,
                    'heading' => $latestTracking->heading
                ] : null
            ]);
        } catch (\Exception $e) {
            Log::error('Get current data error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to get data'], 500);
        }
    }

    /**
     * Get tracking history
     */
    public function getTrackingHistory($token)
    {
        try {
            $towRequest = TowRequest::where('driver_tracking_token', $token)->first();

            if (!$towRequest) {
                return response()->json(['error' => 'Invalid tracking token'], 404);
            }

            $history = TowTracking::getTrackingHistory($towRequest->id);

            return response()->json([
                'history' => $history->map(function ($tracking) {
                    return [
                        'lat' => $tracking->driver_lat,
                        'lng' => $tracking->driver_lng,
                        'timestamp' => $tracking->formatted_timestamp,
                        'status' => $tracking->status,
                        'notes' => $tracking->notes,
                        'speed' => $tracking->speed,
                        'heading' => $tracking->heading
                    ];
                })
            ]);
        } catch (\Exception $e) {
            Log::error('Get tracking history error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to get history'], 500);
        }
    }
}