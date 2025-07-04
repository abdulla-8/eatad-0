<?php
// Path: app/Http/Controllers/TowService/TowCompanyController.php

namespace App\Http\Controllers\TowService;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TowOffer;
use App\Http\Controllers\TowServiceController;

class TowCompanyController extends Controller
{
    /**
     * Display tow offers for company
     */
    public function offers(Request $request)
    {
        $company = Auth::guard('tow_service_company')->user();
        
        $query = TowOffer::with(['towRequest.claim.insuranceUser', 'towRequest.claim.insuranceCompany'])
            ->where('provider_type', 'tow_company')
            ->where('provider_id', $company->id)
            ->orderBy('offer_time', 'desc');

        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $offers = $query->paginate(15);

        // Stats
        $stats = [
            'total' => TowOffer::where('provider_type', 'tow_company')
                ->where('provider_id', $company->id)->count(),
            'pending' => TowOffer::where('provider_type', 'tow_company')
                ->where('provider_id', $company->id)
                ->where('status', 'pending')->count(),
            'accepted' => TowOffer::where('provider_type', 'tow_company')
                ->where('provider_id', $company->id)
                ->where('status', 'accepted')->count(),
            'rejected' => TowOffer::where('provider_type', 'tow_company')
                ->where('provider_id', $company->id)
                ->where('status', 'rejected')->count(),
        ];

        return view('tow-service.company.offers.index', compact('offers', 'stats', 'company'));
    }

    /**
     * Accept tow offer
     */
    public function acceptOffer(Request $request, TowOffer $offer)
    {
        $company = Auth::guard('tow_service_company')->user();
        
        // Check if this offer belongs to this company
        if ($offer->provider_type !== 'tow_company' || $offer->provider_id !== $company->id) {
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
        $company = Auth::guard('tow_service_company')->user();
        
        // Check if this offer belongs to this company
        if ($offer->provider_type !== 'tow_company' || $offer->provider_id !== $company->id) {
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