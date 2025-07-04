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
            return response()->json(['success' => false, 'error' => 'Tow service not accepted'], 400);
        }

        if ($claim->tow_request_id) {
            return response()->json(['success' => false, 'error' => 'Tow request already exists'], 400);
        }

        try {
            DB::beginTransaction();

            // Create tow request with tracking token
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
                'stage_expires_at' => now()->addMinutes(1),
                'tracking_url' => Str::random(32),
                'driver_tracking_token' => Str::random(32)
            ]);

            // Update claim with tow request
            $claim->update(['tow_request_id' => $towRequest->id]);

            // Send offers to service centers first
            $this->sendOffersToServiceCenters($towRequest);

            DB::commit();

            \Log::info("Tow request created", [
                'tow_request_id' => $towRequest->id,
                'claim_id' => $claim->id,
                'request_code' => $towRequest->request_code
            ]);

            return response()->json([
                'success' => true,
                'tow_request' => $towRequest->load('offers'),
                'message' => 'Tow request created and sent to service centers'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to create tow request', [
                'claim_id' => $claim->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'error' => 'Failed to create tow request: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send offers to the assigned service center only
     */
    private function sendOffersToServiceCenters(TowRequest $towRequest)
    {
        $assignedServiceCenter = $towRequest->claim->serviceCenter;

        if (!$assignedServiceCenter) {
            \Log::error("No service center assigned to claim", [
                'tow_request_id' => $towRequest->id,
                'claim_id' => $towRequest->claim->id
            ]);
            return;
        }

        \Log::info("Sending offer to assigned service center only", [
            'tow_request_id' => $towRequest->id,
            'service_center_id' => $assignedServiceCenter->id,
            'service_center_name' => $assignedServiceCenter->legal_name
        ]);

        if (
            $assignedServiceCenter->is_active &&
            $assignedServiceCenter->is_approved &&
            $assignedServiceCenter->has_tow_service
        ) {
            if (TowServiceCapacity::checkCapacity('service_center', $assignedServiceCenter->id)) {
                TowOffer::create([
                    'tow_request_id' => $towRequest->id,
                    'provider_type' => 'service_center',
                    'provider_id' => $assignedServiceCenter->id,
                    'stage' => 'service_center',
                    'offer_time' => now(),
                    'expires_at' => $towRequest->stage_expires_at,
                    'status' => 'pending'
                ]);

                TowServiceCapacity::reserveCapacity('service_center', $assignedServiceCenter->id);

                \Log::info("Offer created for assigned service center", [
                    'tow_request_id' => $towRequest->id,
                    'service_center_id' => $assignedServiceCenter->id
                ]);
            } else {
                \Log::warning("Service center has no capacity", [
                    'tow_request_id' => $towRequest->id,
                    'service_center_id' => $assignedServiceCenter->id
                ]);
                $this->moveToNextStage($towRequest);
            }
        } else {
            \Log::warning("Service center not eligible for tow service", [
                'tow_request_id' => $towRequest->id,
                'service_center_id' => $assignedServiceCenter->id,
                'is_active' => $assignedServiceCenter->is_active,
                'is_approved' => $assignedServiceCenter->is_approved,
                'has_tow_service' => $assignedServiceCenter->has_tow_service
            ]);
            $this->moveToNextStage($towRequest);
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

        \Log::info("Sending offers to tow companies", [
            'tow_request_id' => $towRequest->id,
            'companies_count' => $towCompanies->count()
        ]);

        $offersCreated = 0;
        foreach ($towCompanies as $company) {
            if (TowServiceCapacity::checkCapacity('tow_company', $company->id)) {
                TowOffer::create([
                    'tow_request_id' => $towRequest->id,
                    'provider_type' => 'tow_company',
                    'provider_id' => $company->id,
                    'stage' => 'tow_companies',
                    'offer_time' => now(),
                    'expires_at' => $towRequest->stage_expires_at,
                    'status' => 'pending'
                ]);

                TowServiceCapacity::reserveCapacity('tow_company', $company->id);
                $offersCreated++;
            }
        }

        \Log::info("Offers created for tow companies", [
            'tow_request_id' => $towRequest->id,
            'offers_created' => $offersCreated
        ]);
    }

    /**
     * Send offers to individuals only
     */
    private function sendOffersToIndividuals(TowRequest $towRequest)
    {
        $individuals = TowServiceIndividual::where('is_active', true)
            ->where('is_approved', true)
            ->get();

        \Log::info("Sending offers to individuals", [
            'tow_request_id' => $towRequest->id,
            'individuals_count' => $individuals->count()
        ]);

        $offersCreated = 0;
        foreach ($individuals as $individual) {
            if (TowServiceCapacity::checkCapacity('individual', $individual->id)) {
                TowOffer::create([
                    'tow_request_id' => $towRequest->id,
                    'provider_type' => 'individual',
                    'provider_id' => $individual->id,
                    'stage' => 'individuals',
                    'offer_time' => now(),
                    'expires_at' => $towRequest->stage_expires_at,
                    'status' => 'pending'
                ]);

                TowServiceCapacity::reserveCapacity('individual', $individual->id);
                $offersCreated++;
            }
        }

        \Log::info("Offers created for individuals", [
            'tow_request_id' => $towRequest->id,
            'offers_created' => $offersCreated
        ]);
    }

    /**
     * Accept tow offer
     */
    public function acceptOffer(Request $request, TowOffer $offer)
    {
        if ($offer->status !== 'pending') {
            return response()->json(['success' => false, 'error' => 'Offer already processed'], 400);
        }

        $request->validate([
            'estimated_pickup_time' => 'nullable|date|after:now',
            'notes' => 'nullable|string|max:500'
        ]);

        try {
            DB::beginTransaction();

            // Accept this offer
            $success = $offer->accept(
                $request->estimated_pickup_time ?: now()->addHour(),
                $request->notes
            );

            if (!$success) {
                DB::rollBack();
                return response()->json(['success' => false, 'error' => 'Failed to accept offer'], 400);
            }

            // Reject all other pending offers for this request
            $towRequest = $offer->towRequest;
            $otherOffers = TowOffer::where('tow_request_id', $towRequest->id)
                ->where('id', '!=', $offer->id)
                ->where('status', 'pending')
                ->get();

            foreach ($otherOffers as $otherOffer) {
                $otherOffer->update(['status' => 'rejected']);
                TowServiceCapacity::releaseCapacity(
                    $otherOffer->provider_type,
                    $otherOffer->provider_id
                );
            }

            // Assign the tow request with verification codes
            $towRequest->assign(
                $offer->provider_type,
                $offer->provider_id,
                $offer->estimated_pickup_time
            );

            DB::commit();

            \Log::info("Tow offer accepted", [
                'offer_id' => $offer->id,
                'tow_request_id' => $towRequest->id,
                'provider_type' => $offer->provider_type,
                'provider_id' => $offer->provider_id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Offer accepted successfully! Tow request is now assigned.',
                'tow_request' => $towRequest->fresh(),
                'driver_tracking_url' => $towRequest->driver_tracking_url,
                'customer_verification_code' => $towRequest->customer_verification_code,
                'service_center_verification_code' => $towRequest->service_center_verification_code
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to accept tow offer', [
                'offer_id' => $offer->id,
                'error' => $e->getMessage()
            ]);
            return response()->json(['success' => false, 'error' => 'Failed to accept offer: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Reject tow offer
     */
    public function rejectOffer(Request $request, TowOffer $offer)
    {
        if ($offer->status !== 'pending') {
            return response()->json(['success' => false, 'error' => 'Offer already processed'], 400);
        }

        $request->validate([
            'rejection_reason' => 'nullable|string|max:500'
        ]);

        try {
            DB::beginTransaction();

            $success = $offer->reject($request->rejection_reason);

            if (!$success) {
                DB::rollBack();
                return response()->json(['success' => false, 'error' => 'Failed to reject offer'], 400);
            }

            TowServiceCapacity::releaseCapacity(
                $offer->provider_type,
                $offer->provider_id
            );

            $towRequest = $offer->towRequest;
            $pendingOffersInStage = TowOffer::where('tow_request_id', $towRequest->id)
                ->where('stage', $towRequest->current_stage)
                ->where('status', 'pending')
                ->count();

            if ($pendingOffersInStage == 0) {
                $this->moveToNextStage($towRequest);
            }

            DB::commit();

            \Log::info("Tow offer rejected", [
                'offer_id' => $offer->id,
                'tow_request_id' => $towRequest->id,
                'reason' => $request->rejection_reason
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Offer rejected successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to reject tow offer', [
                'offer_id' => $offer->id,
                'error' => $e->getMessage()
            ]);
            return response()->json(['success' => false, 'error' => 'Failed to reject offer: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Move to next stage
     */
    private function moveToNextStage(TowRequest $towRequest)
    {
        try {
            $nextStage = match($towRequest->current_stage) {
                'service_center' => 'tow_companies',
                'tow_companies' => 'individuals', 
                'individuals' => 'service_center',
                default => 'service_center'
            };

            \Log::info("Moving tow request to next stage", [
                'tow_request_id' => $towRequest->id,
                'current_stage' => $towRequest->current_stage,
                'next_stage' => $nextStage
            ]);

            $completedCycles = $towRequest->completed_cycles ?? 0;
            
            if ($nextStage === 'service_center' && $towRequest->current_stage === 'individuals') {
                $completedCycles++;
                
                if ($completedCycles >= 3) {
                    $towRequest->update([
                        'status' => 'expired',
                        'current_stage' => 'expired',
                        'completed_cycles' => $completedCycles
                    ]);
                    
                    \Log::info("Tow request expired after 3 complete cycles", [
                        'tow_request_id' => $towRequest->id,
                        'request_code' => $towRequest->request_code,
                        'completed_cycles' => $completedCycles
                    ]);
                    return;
                }
            }

            $stageTimeout = $this->getStageTimeout($nextStage);
            $towRequest->update([
                'current_stage' => $nextStage,
                'stage_started_at' => now(),
                'stage_expires_at' => now()->addMinutes($stageTimeout),
                'completed_cycles' => $completedCycles
            ]);

            \Log::info("Tow request moved to next stage", [
                'tow_request_id' => $towRequest->id,
                'request_code' => $towRequest->request_code,
                'new_stage' => $nextStage,
                'timeout_minutes' => $stageTimeout,
                'expires_at' => $towRequest->stage_expires_at,
                'completed_cycles' => $completedCycles
            ]);

            match($nextStage) {
                'service_center' => $this->sendOffersToServiceCenters($towRequest),
                'tow_companies' => $this->sendOffersToTowCompanies($towRequest),
                'individuals' => $this->sendOffersToIndividuals($towRequest)
            };

        } catch (\Exception $e) {
            \Log::error('Failed to move tow request to next stage', [
                'tow_request_id' => $towRequest->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Process expired stages
     */
    public function processExpiredStages()
    {
        try {
            $expiredRequests = TowRequest::where('stage_expires_at', '<=', now())
                ->where('status', 'pending')
                ->whereIn('current_stage', ['service_center', 'tow_companies', 'individuals'])
                ->get();

            $processedCount = 0;

            foreach ($expiredRequests as $request) {
                DB::beginTransaction();
                
                try {
                    $expiredOffers = TowOffer::where('tow_request_id', $request->id)
                        ->where('status', 'pending')
                        ->get();

                    foreach ($expiredOffers as $expiredOffer) {
                        $expiredOffer->update(['status' => 'expired']);
                        
                        TowServiceCapacity::releaseCapacity(
                            $expiredOffer->provider_type,
                            $expiredOffer->provider_id
                        );
                    }

                    $this->moveToNextStage($request);
                    
                    $processedCount++;
                    DB::commit();
                    
                    \Log::info("Processed expired tow request", [
                        'request_id' => $request->id,
                        'request_code' => $request->request_code,
                        'previous_stage' => $request->current_stage,
                        'expired_offers_count' => $expiredOffers->count()
                    ]);
                    
                } catch (\Exception $e) {
                    DB::rollBack();
                    \Log::error("Failed to process expired tow request {$request->id}: " . $e->getMessage());
                }
            }

            return response()->json([
                'success' => true,
                'processed_requests' => $processedCount,
                'message' => "Processed {$processedCount} expired tow requests"
            ]);

        } catch (\Exception $e) {
            \Log::error('Failed to process expired tow stages: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get timeout for each stage
     */
    private function getStageTimeout($stage)
    {
        return match ($stage) {
            'service_center' => 0.1,
            'tow_companies' => 0.1,  
            'individuals' => 0.1,
            default => 0.1
        };
    }

    /**
     * Get tow request status
     */
    public function getTowRequestStatus(TowRequest $towRequest)
    {
        return response()->json([
            'tow_request' => $towRequest->load([
                'offers' => function ($query) {
                    $query->with(['serviceCenter', 'towCompany', 'individual']);
                },
                'claim.insuranceUser'
            ]),
            'current_stage_offers' => $towRequest->offers()
                ->where('stage', $towRequest->current_stage)
                ->with(['serviceCenter', 'towCompany', 'individual'])
                ->get(),
            'provider_info' => $towRequest->getProviderContactInfo(),
            'tracking_info' => [
                'driver_tracking_url' => $towRequest->driver_tracking_url,
                'customer_verification_code' => $towRequest->customer_verification_code,
                'service_center_verification_code' => $towRequest->service_center_verification_code,
                'latest_location' => \App\Models\TowTracking::getLatestLocation($towRequest->id)
            ]
        ]);
    }
}