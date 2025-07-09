<?php

namespace App\Http\Controllers\Insurance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ClaimInspection;

class PartsQuotesController extends Controller
{
    public function index(Request $request)
    {
        $company = Auth::guard('insurance_company')->user();

        $query = ClaimInspection::whereHas('claim', function($q) use ($company) {
                $q->where('insurance_company_id', $company->id);
            })
            ->with(['claim.insuranceUser', 'claim.serviceCenter', 'serviceCenter'])
            ->where('pricing_status', '!=', 'pending')
            ->orderBy('sent_to_insurance_at', 'desc');

        // Filter by status
        if ($request->status) {
            if ($request->status === 'awaiting_response') {
                $query->where('pricing_status', 'sent_to_insurance');
            } else {
                $query->where('insurance_response', $request->status);
            }
        }

        // Search
        if ($request->search) {
            $search = $request->search;
            $query->whereHas('claim', function($q) use ($search) {
                $q->where('claim_number', 'like', "%{$search}%")
                  ->orWhere('vehicle_plate_number', 'like', "%{$search}%")
                  ->orWhere('chassis_number', 'like', "%{$search}%");
            });
        }

        $inspections = $query->paginate(15);

        // Statistics
        $stats = [
            'total' => ClaimInspection::whereHas('claim', function($q) use ($company) {
                $q->where('insurance_company_id', $company->id);
            })->where('pricing_status', '!=', 'pending')->count(),
            
            'awaiting_response' => ClaimInspection::whereHas('claim', function($q) use ($company) {
                $q->where('insurance_company_id', $company->id);
            })->where('pricing_status', 'sent_to_insurance')->count(),
            
            'approved' => ClaimInspection::whereHas('claim', function($q) use ($company) {
                $q->where('insurance_company_id', $company->id);
            })->where('insurance_response', 'approved')->count(),
            
            'rejected' => ClaimInspection::whereHas('claim', function($q) use ($company) {
                $q->where('insurance_company_id', $company->id);
            })->where('insurance_response', 'rejected')->count(),
            
            'total_approved_amount' => ClaimInspection::whereHas('claim', function($q) use ($company) {
                $q->where('insurance_company_id', $company->id);
            })->where('insurance_response', 'approved')->sum('total_amount')
        ];

        return view('insurance.parts-quotes.index', compact('inspections', 'stats', 'company'));
    }

    public function show(Request $request, $companyRoute, ClaimInspection $inspection)
    {
        $company = Auth::guard('insurance_company')->user();

        // Check if this inspection belongs to this company
        if ($inspection->claim->insurance_company_id !== $company->id) {
            abort(404, 'Parts quote not found');
        }

        // Load relationships
        $inspection->load(['claim.insuranceUser', 'claim.serviceCenter', 'serviceCenter']);

        return view('insurance.parts-quotes.show', compact('inspection', 'company'));
    }

    public function approve(Request $request, $companyRoute, ClaimInspection $inspection)
    {
        $company = Auth::guard('insurance_company')->user();

        // Validate ownership and status
        if ($inspection->claim->insurance_company_id !== $company->id) {
            abort(404, 'Parts quote not found');
        }

        if ($inspection->pricing_status !== 'sent_to_insurance') {
            return redirect()->back()
                ->with('error', 'This quote is not available for approval');
        }

        $request->validate([
            'insurance_notes' => 'nullable|string|max:1000'
        ]);

        try {
            $inspection->approveByInsurance($request->insurance_notes);

            return redirect()->back()
                ->with('success', 'Parts pricing approved successfully');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to approve pricing. Please try again.');
        }
    }

    public function reject(Request $request, $companyRoute, ClaimInspection $inspection)
    {
        $company = Auth::guard('insurance_company')->user();

        // Validate ownership and status
        if ($inspection->claim->insurance_company_id !== $company->id) {
            abort(404, 'Parts quote not found');
        }

        if ($inspection->pricing_status !== 'sent_to_insurance') {
            return redirect()->back()
                ->with('error', 'This quote is not available for rejection');
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:1000',
            'insurance_notes' => 'nullable|string|max:1000'
        ]);

        try {
            $inspection->rejectByInsurance($request->rejection_reason, $request->insurance_notes);

            return redirect()->back()
                ->with('success', 'Parts pricing rejected successfully. Admin can now update the pricing.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to reject pricing. Please try again.');
        }
    }

    public function getStats()
    {
        $company = Auth::guard('insurance_company')->user();

        $stats = [
            'total_quotes' => ClaimInspection::whereHas('claim', function($q) use ($company) {
                $q->where('insurance_company_id', $company->id);
            })->where('pricing_status', '!=', 'pending')->count(),

            'pending_review' => ClaimInspection::whereHas('claim', function($q) use ($company) {
                $q->where('insurance_company_id', $company->id);
            })->where('pricing_status', 'sent_to_insurance')->count(),

            'approved_this_month' => ClaimInspection::whereHas('claim', function($q) use ($company) {
                $q->where('insurance_company_id', $company->id);
            })->where('insurance_response', 'approved')
              ->whereMonth('insurance_responded_at', now()->month)
              ->whereYear('insurance_responded_at', now()->year)
              ->count(),

            'total_approved_amount' => ClaimInspection::whereHas('claim', function($q) use ($company) {
                $q->where('insurance_company_id', $company->id);
            })->where('insurance_response', 'approved')->sum('total_amount'),

            'average_response_time' => $this->calculateAverageResponseTime($company->id)
        ];

        return response()->json($stats);
    }

    private function calculateAverageResponseTime($companyId)
    {
        $inspections = ClaimInspection::whereHas('claim', function($q) use ($companyId) {
                $q->where('insurance_company_id', $companyId);
            })
            ->whereNotNull('sent_to_insurance_at')
            ->whereNotNull('insurance_responded_at')
            ->select('sent_to_insurance_at', 'insurance_responded_at')
            ->get();

        if ($inspections->isEmpty()) {
            return 0;
        }

        $totalMinutes = 0;
        foreach ($inspections as $inspection) {
            $totalMinutes += $inspection->sent_to_insurance_at->diffInMinutes($inspection->insurance_responded_at);
        }

        return round($totalMinutes / $inspections->count() / 60, 1); // Return in hours
    }
}