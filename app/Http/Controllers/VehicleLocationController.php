<?php

namespace App\Http\Controllers;

use App\Models\VehicleLocationRequest;
use App\Models\Claim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VehicleLocationController extends Controller
{
    /**
     * Show the public vehicle location form
     */
    public function showForm($hash)
    {
        $locationRequest = VehicleLocationRequest::where('public_hash', $hash)
            ->where('is_completed', false)
            ->with('claim.insuranceCompany')
            ->firstOrFail();

        return view('vehicle-location.form', compact('locationRequest'));
    }

    /**
     * Submit the vehicle location
     */
    public function submitLocation(Request $request, $hash)
    {
        $locationRequest = VehicleLocationRequest::where('public_hash', $hash)
            ->where('is_completed', false)
            ->firstOrFail();

        $request->validate([
            'city' => 'required|string|max:100',
            'district' => 'required|string|max:100',
            'notes' => 'nullable|string|max:1000',
            'location_lat' => 'nullable|numeric|between:-90,90',
            'location_lng' => 'nullable|numeric|between:-180,180',
        ]);

        try {
            $locationRequest->update([
                'city' => $request->city,
                'district' => $request->district,
                'notes' => $request->notes,
                'location_lat' => $request->location_lat,
                'location_lng' => $request->location_lng,
                'is_completed' => true,
                'completed_at' => now(),
            ]);

            // Update the claim with location information
            $claim = $locationRequest->claim;
            $claim->update([
                'vehicle_location' => $request->city . ', ' . $request->district . ($request->notes ? ' - ' . $request->notes : ''),
                'vehicle_location_lat' => $request->location_lat,
                'vehicle_location_lng' => $request->location_lng,
                'status' => (!$claim->is_vehicle_working ? 'location_submitted' : $claim->status),
            ]);

            // Soft delete the location request after successful update
            $locationRequest->delete();

            Log::info('Vehicle location submitted', [
                'claim_id' => $claim->id,
                'hash' => $hash,
                'city' => $request->city,
                'district' => $request->district
            ]);

            return view('vehicle-location.success', compact('locationRequest'));

        } catch (\Exception $e) {
            Log::error('Failed to submit vehicle location', [
                'hash' => $hash,
                'error' => $e->getMessage()
            ]);

            return back()->withErrors(['error' => 'Failed to submit location. Please try again.']);
        }
    }
} 