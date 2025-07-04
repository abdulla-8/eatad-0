<?php
// Path: app/Http/Controllers/TowService/TowIndividualController.php

namespace App\Http\Controllers\TowService;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TowOffer;
use App\Http\Controllers\TowServiceController;

class TowIndividualController extends Controller
{
    /**
     * Display tow offers for individual
     */
    public function offers(Request $request)
    {
        $individual = Auth::guard('tow_service_individual')->user();
        
        $query = TowOffer::with(['towRequest.claim.insuranceUser', 'towRequest.claim.insuranceCompany'])
            ->where('provider_type', 'individual')
            ->where('provider_id', $individual->id)
            ->orderBy('offer_time', 'desc');

        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $offers = $query->paginate(15);

        // Stats
        $stats = [
            'total' => TowOffer::where('provider_type', 'individual')
                ->where('provider_id', $individual->id)->count(),
            'pending' => TowOffer::where('provider_type', 'individual')
                ->where('provider_id', $individual->id)
                ->where('status', 'pending')->count(),
            'accepted' => TowOffer::where('provider_type', 'individual')
                ->where('provider_id', $individual->id)
                ->where('status', 'accepted')->count(),
            'rejected' => TowOffer::where('provider_type', 'individual')
                ->where('provider_id', $individual->id)
                ->where('status', 'rejected')->count(),
        ];

        return view('tow-service.individual.offers.index', compact('offers', 'stats', 'individual'));
    }

    /**
     * Accept tow offer
     */
    public function acceptOffer(Request $request, TowOffer $offer)
    {
        $individual = Auth::guard('tow_service_individual')->user();
        
        // Check if this offer belongs to this individual
        if ($offer->provider_type !== 'individual' || $offer->provider_id !== $individual->id) {
            abort(404);
        }

        if ($offer->status !== 'pending') {
            return response()->json(['error' => 'This offer has already been processed.'], 400);
        }

        $request->validate([
            'estimated_pickup_time' => 'required|date|after:now',
            'notes' => 'nullable|string|max:500'
        ]);

        try {
            // Make API call to accept offer
            $response = app(TowServiceController::class)->acceptOffer($request, $offer);
            return $response;

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to accept offer. Please try again.'], 500);
        }
    }

    /**
     * Reject tow offer
     */
    public function rejectOffer(Request $request, TowOffer $offer)
    {
        $individual = Auth::guard('tow_service_individual')->user();
        
        // Check if this offer belongs to this individual
        if ($offer->provider_type !== 'individual' || $offer->provider_id !== $individual->id) {
            abort(404);
        }

        if ($offer->status !== 'pending') {
            return response()->json(['error' => 'This offer has already been processed.'], 400);
        }

        $request->validate([
            'rejection_reason' => 'nullable|string|max:500'
        ]);

        try {
            // Make API call to reject offer
            $response = app(TowServiceController::class)->rejectOffer($request, $offer);
            return $response;

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to reject offer. Please try again.'], 500);
        }
    }
}