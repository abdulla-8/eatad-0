<?php

namespace App\Http\Controllers\ServiceCenter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Claim;

class ClaimsController extends Controller
{
    /**
     * Display claims assigned to the service center
     */
    public function index(Request $request)
    {
        $serviceCenter = Auth::guard('service_center')->user();
        
        // Build query for claims assigned to this service center
        $query = Claim::with(['insuranceUser', 'insuranceCompany', 'attachments'])
            ->where('service_center_id', $serviceCenter->id)
            ->orderBy('created_at', 'desc');

        // Apply filters
        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('policy_number', 'like', "%{$search}%")
                  ->orWhere('vehicle_plate_number', 'like', "%{$search}%")
                  ->orWhere('chassis_number', 'like', "%{$search}%")
                  ->orWhereHas('insuranceUser', function($userQuery) use ($search) {
                      $userQuery->where('full_name', 'like', "%{$search}%")
                               ->orWhere('phone', 'like', "%{$search}%");
                  });
            });
        }

        $claims = $query->paginate(15);

        // Calculate statistics
        $stats = [
            'total' => Claim::where('service_center_id', $serviceCenter->id)->count(),
            'approved' => Claim::where('service_center_id', $serviceCenter->id)->where('status', 'approved')->count(),
            'in_progress' => Claim::where('service_center_id', $serviceCenter->id)->where('status', 'in_progress')->count(),
            'completed' => Claim::where('service_center_id', $serviceCenter->id)->where('status', 'completed')->count(),
        ];

        return view('service-center.claims.index', compact('claims', 'stats', 'serviceCenter'));
    }

    /**
     * Display specific claim details
     */
    public function show(Request $request, $id)
    {
        $serviceCenter = Auth::guard('service_center')->user();
        
        $claim = Claim::with(['insuranceUser', 'insuranceCompany', 'attachments'])
            ->where('service_center_id', $serviceCenter->id)
            ->where('id', $id)
            ->firstOrFail();

        return view('service-center.claims.show', compact('claim', 'serviceCenter'));
    }

    /**
     * Mark claim as in progress
     */
    public function markInProgress(Request $request, $id)
    {
        $serviceCenter = Auth::guard('service_center')->user();
        
        $claim = Claim::where('service_center_id', $serviceCenter->id)
            ->where('id', $id)
            ->where('status', 'approved')
            ->firstOrFail();

        $claim->update([
            'status' => 'in_progress'
        ]);

        return redirect()->back()->with('success', t('service_center.claim_marked_in_progress'));
    }

    /**
     * Mark claim as completed
     */
    public function markCompleted(Request $request, $id)
    {
        $serviceCenter = Auth::guard('service_center')->user();
        
        $claim = Claim::where('service_center_id', $serviceCenter->id)
            ->where('id', $id)
            ->whereIn('status', ['approved', 'in_progress'])
            ->firstOrFail();

        $request->validate([
            'completion_notes' => 'nullable|string|max:1000'
        ]);

        $claim->update([
            'status' => 'completed',
            'notes' => $request->completion_notes ?: $claim->notes
        ]);

        return redirect()->back()->with('success', t('service_center.claim_marked_completed'));
    }

    /**
     * Add notes to claim
     */
    public function addNotes(Request $request, $id)
    {
        $serviceCenter = Auth::guard('service_center')->user();
        
        $claim = Claim::where('service_center_id', $serviceCenter->id)
            ->where('id', $id)
            ->firstOrFail();

        $request->validate([
            'notes' => 'required|string|max:1000'
        ]);

        $existingNotes = $claim->notes ? $claim->notes . "\n\n" : '';
        $newNotes = $existingNotes . '[' . now()->format('Y-m-d H:i') . '] ' . $request->notes;

        $claim->update([
            'notes' => $newNotes
        ]);

        return redirect()->back()->with('success', t('service_center.notes_added_successfully'));
    }

    /**
     * Get claims statistics for dashboard
     */
    public function getStats()
    {
        $serviceCenter = Auth::guard('service_center')->user();
        
        $stats = [
            'total_claims' => Claim::where('service_center_id', $serviceCenter->id)->count(),
            'new_claims' => Claim::where('service_center_id', $serviceCenter->id)
                ->where('status', 'approved')
                ->count(),
            'in_progress_claims' => Claim::where('service_center_id', $serviceCenter->id)
                ->where('status', 'in_progress')
                ->count(),
            'completed_claims' => Claim::where('service_center_id', $serviceCenter->id)
                ->where('status', 'completed')
                ->count(),
            'claims_this_month' => Claim::where('service_center_id', $serviceCenter->id)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'claims_today' => Claim::where('service_center_id', $serviceCenter->id)
                ->whereDate('created_at', today())
                ->count()
        ];

        return response()->json($stats);
    }
}