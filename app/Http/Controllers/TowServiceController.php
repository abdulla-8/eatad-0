<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Claim;
use App\Models\TowRequest;
use App\Models\TowOffer;
use App\Models\TowServiceCapacity;
use App\Models\ServiceCenter;
use App\Models\TowServiceCompany;
use App\Models\TowServiceIndividual;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TowServiceController extends Controller
{
    /**
     * Create tow request when user accepts tow service
     */
    public function createTowRequest(Claim $claim)
    {
        if ($claim->tow_service_accepted !== true) {
            return response()->json(['error' => 'Tow service not accepted'], 400);
        }

        if ($claim->tow_request_id) {
            return response()->json(['error' => 'Tow request already exists'], 400);
        }

        try {
            DB::beginTransaction();

            // Create tow request
            $towRequest = TowRequest::create([
                'claim_id' => $claim->id,
                'request_code' => 'TOW' . rand(100000, 999999),
                'pickup_location_lat' => $claim->vehicle_location_lat,
                'pickup_location_lng' => $claim->vehicle_location_lng,
                'pickup_location_address' => $claim->vehicle_location,
                'dropoff_location_lat' => $claim->serviceCenter->center_location_lat,
                'dropoff_location_lng' => $claim->serviceCenter->center_location_lng,
                'dropoff_location_address' => $claim->serviceCenter->center_address,
                'status' => 'pending',
                'current_stage' => 'service_center',
                'stage_started_at' => now(),
                'stage_expires_at' => now()->addMinutes(30), // 30 minutes for service centers
                'tracking_url' => Str::random(32),
            ]);

            // Update claim with tow request
            $claim->update(['tow_request_id' => $towRequest->id]);

            // Send offers to service centers first
            $this->sendOffersToServiceCenters($towRequest);

            DB::commit();

            return response()->json([
                'success' => true,
                'tow_request' => $towRequest->load('offers'),
                'message' => 'Tow request created and sent to service centers'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to create tow request: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Send offers to service centers only
     */
    private function sendOffersToServiceCenters(TowRequest $towRequest)
    {
        // Get available service centers with tow service
        $serviceCenters = ServiceCenter::where('is_active', true)
            ->where('is_approved', true)
            ->where('has_tow_service', true)
            ->get();

        foreach ($serviceCenters as $center) {
            // Check if they have capacity
            if (TowServiceCapacity::checkCapacity('service_center', $center->id)) {
                
                // Create offer
                TowOffer::create([
                    'tow_request_id' => $towRequest->id,
                    'provider_type' => 'service_center',
                    'provider_id' => $center->id,
                    'stage' => 'service_center',
                    'offer_time' => now(),
                    'status' => 'pending'
                ]);

                // Reserve capacity
                TowServiceCapacity::reserveCapacity('service_center', $center->id);
            }
        }
    }

    /**
     * Send offers to tow companies only
     */
    private function sendOffersToTowCompanies(TowRequest $towRequest)
    {
        $towCompanies = TowServiceCompany::where('is_active', true)
            ->where('is_approved', true)
            ->get();

        foreach ($towCompanies as $company) {
            if (TowServiceCapacity::checkCapacity('tow_company', $company->id)) {
                
                TowOffer::create([
                    'tow_request_id' => $towRequest->id,
                    'provider_type' => 'tow_company',
                    'provider_id' => $company->id,
                    'stage' => 'tow_companies',
                    'offer_time' => now(),
                    'status' => 'pending'
                ]);

                TowServiceCapacity::reserveCapacity('tow_company', $company->id);
            }
        }
    }

    /**
     * Send offers to individuals only
     */
    private function sendOffersToIndividuals(TowRequest $towRequest)
    {
        $individuals = TowServiceIndividual::where('is_active', true)
            ->where('is_approved', true)
            ->get();

        foreach ($individuals as $individual) {
            if (TowServiceCapacity::checkCapacity('individual', $individual->id)) {
                
                TowOffer::create([
                    'tow_request_id' => $towRequest->id,
                    'provider_type' => 'individual',
                    'provider_id' => $individual->id,
                    'stage' => 'individuals',
                    'offer_time' => now(),
                    'status' => 'pending'
                ]);

                TowServiceCapacity::reserveCapacity('individual', $individual->id);
            }
        }
    }

    /**
     * Accept tow offer - هنا المقدم بيوافق
     */
    public function acceptOffer(Request $request, TowOffer $offer)
    {
        if ($offer->status !== 'pending') {
            return response()->json(['error' => 'Offer already processed'], 400);
        }

        $request->validate([
            'estimated_pickup_time' => 'nullable|date|after:now',
            'notes' => 'nullable|string|max:500'
        ]);

        try {
            DB::beginTransaction();

            // Accept this offer
            $success = $offer->accept(
                $request->estimated_pickup_time,
                $request->notes
            );

            if (!$success) {
                DB::rollBack();
                return response()->json(['error' => 'Failed to accept offer'], 400);
            }

            // Release capacity for all other rejected offers for this request
            $rejectedOffers = TowOffer::where('tow_request_id', $offer->tow_request_id)
                ->where('id', '!=', $offer->id)
                ->where('status', 'rejected')
                ->get();

            foreach ($rejectedOffers as $rejectedOffer) {
                TowServiceCapacity::releaseCapacity(
                    $rejectedOffer->provider_type,
                    $rejectedOffer->provider_id
                );
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Offer accepted successfully! Tow request is now assigned.',
                'tow_request' => $offer->towRequest->fresh()
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to accept offer: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Reject tow offer - هنا المقدم بيرفض
     */
    public function rejectOffer(Request $request, TowOffer $offer)
    {
        if ($offer->status !== 'pending') {
            return response()->json(['error' => 'Offer already processed'], 400);
        }

        $request->validate([
            'rejection_reason' => 'nullable|string|max:500'
        ]);

        try {
            // Reject the offer
            $success = $offer->reject($request->rejection_reason);

            if (!$success) {
                return response()->json(['error' => 'Failed to reject offer'], 400);
            }

            // Release capacity
            TowServiceCapacity::releaseCapacity(
                $offer->provider_type,
                $offer->provider_id
            );

            // Check if all offers in current stage are rejected
            $towRequest = $offer->towRequest;
            $pendingOffersInStage = TowOffer::where('tow_request_id', $towRequest->id)
                ->where('stage', $towRequest->current_stage)
                ->where('status', 'pending')
                ->count();

            // If no pending offers left in current stage, move to next stage
            if ($pendingOffersInStage == 0) {
                $this->moveToNextStage($towRequest);
            }

            return response()->json([
                'success' => true,
                'message' => 'Offer rejected successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to reject offer: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Move to next stage when all offers rejected
     */
    private function moveToNextStage(TowRequest $towRequest)
    {
        try {
            DB::beginTransaction();

            // Determine next stage
            $nextStage = match($towRequest->current_stage) {
                'service_center' => 'tow_companies',
                'tow_companies' => 'individuals', 
                'individuals' => 'service_center', // Back to service centers
                default => 'expired'
            };

            if ($nextStage === 'expired') {
                $towRequest->update([
                    'status' => 'expired',
                    'current_stage' => 'expired'
                ]);
                DB::commit();
                return;
            }

            // Update stage
            $towRequest->update([
                'current_stage' => $nextStage,
                'stage_started_at' => now(),
                'stage_expires_at' => now()->addMinutes($this->getStageTimeout($nextStage))
            ]);

            // Send offers to next stage
            match($nextStage) {
                'service_center' => $this->sendOffersToServiceCenters($towRequest),
                'tow_companies' => $this->sendOffersToTowCompanies($towRequest),
                'individuals' => $this->sendOffersToIndividuals($towRequest)
            };

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to move tow request to next stage: ' . $e->getMessage());
        }
    }

    /**
     * Process expired stages - هنا نشيك على الوقت لو خلص
     */
    public function processExpiredStages()
    {
        $expiredRequests = TowRequest::where('stage_expires_at', '<=', now())
            ->where('status', 'pending')
            ->whereIn('current_stage', ['service_center', 'tow_companies', 'individuals'])
            ->get();

        foreach ($expiredRequests as $request) {
            // Expire all pending offers in current stage
            TowOffer::where('tow_request_id', $request->id)
                ->where('status', 'pending')
                ->update(['status' => 'expired']);

            // Release capacities for expired offers
            $expiredOffers = TowOffer::where('tow_request_id', $request->id)
                ->where('status', 'expired')
                ->get();

            foreach ($expiredOffers as $expiredOffer) {
                TowServiceCapacity::releaseCapacity(
                    $expiredOffer->provider_type,
                    $expiredOffer->provider_id
                );
            }

            // Move to next stage
            $this->moveToNextStage($request);
        }

        return response()->json([
            'success' => true,
            'processed_requests' => $expiredRequests->count()
        ]);
    }

    /**
     * Get timeout for each stage
     */
    private function getStageTimeout($stage)
    {
        return match($stage) {
            'service_center' => 30, // 30 minutes
            'tow_companies' => 20,  // 20 minutes  
            'individuals' => 15,    // 15 minutes
            default => 30
        };
    }

    /**
     * Get tow request status
     */
    public function getTowRequestStatus(TowRequest $towRequest)
    {
        return response()->json([
            'tow_request' => $towRequest->load([
                'offers' => function($query) {
                    $query->with(['serviceCenter', 'towCompany', 'individual']);
                },
                'claim.insuranceUser'
            ]),
            'current_stage_offers' => $towRequest->offers()
                ->where('stage', $towRequest->current_stage)
                ->with(['serviceCenter', 'towCompany', 'individual'])
                ->get()
        ]);
    }
}