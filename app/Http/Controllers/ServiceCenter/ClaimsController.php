<?php

namespace App\Http\Controllers\ServiceCenter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Claim;
use App\Models\ClaimInspection;

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
            ->where('inspection_status', 'completed') // يجب أن يكون الفحص مكتمل أولاً
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

    /**
     * Mark vehicle as arrived at service center
     */
    public function markVehicleArrived(Request $request, $id)
    {
        $serviceCenter = Auth::guard('service_center')->user();
        
        $claim = Claim::where('service_center_id', $serviceCenter->id)
            ->where('id', $id)
            ->where('status', 'approved')
            ->first();

        if (!$claim) {
            return response()->json(['success' => false, 'error' => 'Claim not found']);
        }

        $claim->markVehicleArrived();
        
        // إنشاء كود للعميل إذا كانت السيارة لا تعمل وتم رفض خدمة السطحة
        if (!$claim->is_vehicle_working && !$claim->tow_service_accepted) {
            $deliveryCode = $claim->generateCustomerDeliveryCode();
            
            return response()->json([
                'success' => true,
                'message' => 'Vehicle marked as arrived',
                'delivery_code' => $deliveryCode
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Vehicle marked as arrived'
        ]);
    }

    /**
     * Verify customer delivery code
     */
    public function verifyCustomerDelivery(Request $request, $id)
    {
        $request->validate([
            'delivery_code' => 'required|string|size:6'
        ]);

        $serviceCenter = Auth::guard('service_center')->user();
        
        $claim = Claim::where('service_center_id', $serviceCenter->id)
            ->where('id', $id)
            ->where('customer_delivery_code', $request->delivery_code)
            ->first();

        if (!$claim) {
            return response()->json([
                'success' => false,
                'error' => 'Invalid delivery code'
            ]);
        }

        // تأكيد وصول السيارة
        $claim->markVehicleArrived();

        return response()->json([
            'success' => true,
            'message' => 'Vehicle delivery verified successfully'
        ]);
    }

    /**
     * Start inspection process
     */
    public function startInspection(Request $request, $id)
    {
        $serviceCenter = Auth::guard('service_center')->user();
        
        $claim = Claim::where('service_center_id', $serviceCenter->id)
            ->where('id', $id)
            ->first();

        if (!$claim || !$claim->canStartInspection()) {
            return response()->json(['success' => false, 'error' => 'Cannot start inspection']);
        }

        $claim->update(['inspection_status' => 'in_progress']);

        return response()->json([
            'success' => true,
            'message' => 'Inspection started successfully'
        ]);
    }

    /**
     * Submit inspection results
     */
    public function submitInspection(Request $request, $id)
    {
        $request->validate([
            'vehicle_brand' => 'required|string|max:100',
            'vehicle_model' => 'required|string|max:100', 
            'vehicle_year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'chassis_number' => 'required|string|max:100',
            'registration_image' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'required_parts' => 'required|array|min:1',
            'required_parts.*' => 'required|string|max:255',
            'inspection_notes' => 'nullable|string|max:1000'
        ]);

        $serviceCenter = Auth::guard('service_center')->user();
        
        $claim = Claim::where('service_center_id', $serviceCenter->id)
            ->where('id', $id)
            ->where('inspection_status', 'in_progress')
            ->first();

        if (!$claim) {
            return response()->json(['success' => false, 'error' => 'Invalid claim or inspection not started']);
        }

        try {
            // Store registration image
            $registrationImage = $request->file('registration_image');
            $filename = 'registration_' . $claim->id . '_' . time() . '.' . $registrationImage->getClientOriginalExtension();
            $path = $registrationImage->storeAs('claim_inspections', $filename, 'public');

            // Create inspection record
            ClaimInspection::create([
                'claim_id' => $claim->id,
                'vehicle_brand' => $request->vehicle_brand,
                'vehicle_model' => $request->vehicle_model,
                'vehicle_year' => $request->vehicle_year,
                'chassis_number' => $request->chassis_number,
                'registration_image_path' => $path,
                'required_parts' => $request->required_parts,
                'inspection_notes' => $request->inspection_notes,
                'inspected_by' => $serviceCenter->id
            ]);

            // تحديث حالة الفحص إلى مكتمل
            $claim->update(['inspection_status' => 'completed']);

            return response()->json([
                'success' => true,
                'message' => 'Inspection submitted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to submit inspection'
            ], 500);
        }
    }
}