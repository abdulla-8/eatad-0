<?php

namespace App\Http\Controllers\ServiceCenter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TowOffer;
use App\Models\TowServiceCapacity;

class TowController extends Controller
{
    /**
     * Display tow offers for service center
     */
    public function index(Request $request)
    {
        $serviceCenter = Auth::guard('service_center')->user();
        
        $query = TowOffer::with(['towRequest.claim.insuranceUser', 'towRequest.claim.insuranceCompany'])
            ->where('provider_type', 'service_center')
            ->where('provider_id', $serviceCenter->id)
            ->orderBy('offer_time', 'desc');

        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $offers = $query->paginate(15);

        // Stats
        $stats = [
            'total' => TowOffer::where('provider_type', 'service_center')
                ->where('provider_id', $serviceCenter->id)->count(),
            'pending' => TowOffer::where('provider_type', 'service_center')
                ->where('provider_id', $serviceCenter->id)
                ->where('status', 'pending')->count(),
            'accepted' => TowOffer::where('provider_type', 'service_center')
                ->where('provider_id', $serviceCenter->id)
                ->where('status', 'accepted')->count(),
            'rejected' => TowOffer::where('provider_type', 'service_center')
                ->where('provider_id', $serviceCenter->id)
                ->where('status', 'rejected')->count(),
        ];

        return view('service-center.tow-offers.index', compact('offers', 'stats', 'serviceCenter'));
    }

    /**
     * Show tow offer details
     */
    public function show(TowOffer $offer)
    {
        $serviceCenter = Auth::guard('service_center')->user();
        
        // Check if this offer belongs to this service center
        if ($offer->provider_type !== 'service_center' || $offer->provider_id !== $serviceCenter->id) {
            abort(404);
        }

        $offer->load(['towRequest.claim.insuranceUser', 'towRequest.claim.insuranceCompany']);

        return view('service-center.tow-offers.show', compact('offer', 'serviceCenter'));
    }

/**
 * Accept tow offer
 */
public function accept(Request $request, TowOffer $offer)
{
    $serviceCenter = Auth::guard('service_center')->user();
    
    // Check if this offer belongs to this service center
    if ($offer->provider_type !== 'service_center' || $offer->provider_id !== $serviceCenter->id) {
        return response()->json(['success' => false, 'error' => 'Unauthorized'], 403);
    }

    if ($offer->status !== 'pending') {
        return response()->json(['success' => false, 'error' => 'This offer has already been processed.'], 400);
    }

    $request->validate([
        'estimated_pickup_time' => 'required|date|after:now',
        'notes' => 'nullable|string|max:500'
    ]);

    try {
        // Make API call to accept offer
        $response = app(\App\Http\Controllers\TowServiceController::class)->acceptOffer($request, $offer);
        
        if ($response && method_exists($response, 'getData')) {
            $data = $response->getData(true);
            return response()->json($data);
        }
        
        return response()->json(['success' => false, 'error' => 'Invalid response from service']);

    } catch (\Exception $e) {
        \Log::error('Service Center Accept Offer Error', [
            'offer_id' => $offer->id,
            'service_center_id' => $serviceCenter->id,
            'error' => $e->getMessage()
        ]);
        
        return response()->json(['success' => false, 'error' => 'Failed to accept offer. Please try again.'], 500);
    }
}

/**
 * Reject tow offer
 */
public function reject(Request $request, TowOffer $offer)
{
    $serviceCenter = Auth::guard('service_center')->user();
    
    // Check if this offer belongs to this service center
    if ($offer->provider_type !== 'service_center' || $offer->provider_id !== $serviceCenter->id) {
        return response()->json(['success' => false, 'error' => 'Unauthorized'], 403);
    }

    if ($offer->status !== 'pending') {
        return response()->json(['success' => false, 'error' => 'This offer has already been processed.'], 400);
    }

    $request->validate([
        'rejection_reason' => 'nullable|string|max:500'
    ]);

    try {
        // Make API call to reject offer
        $response = app(\App\Http\Controllers\TowServiceController::class)->rejectOffer($request, $offer);
        
        if ($response && method_exists($response, 'getData')) {
            $data = $response->getData(true);
            return response()->json($data);
        }
        
        return response()->json(['success' => false, 'error' => 'Invalid response from service']);

    } catch (\Exception $e) {
        \Log::error('Service Center Reject Offer Error', [
            'offer_id' => $offer->id,
            'service_center_id' => $serviceCenter->id,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return response()->json(['success' => false, 'error' => 'Failed to reject offer. Please try again.'], 500);
    }
}
}