<?php
// Path: app/Http/Controllers/Insurance/ClaimsController.php

namespace App\Http\Controllers\Insurance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Claim;
use App\Models\ServiceCenter;

class ClaimsController extends Controller
{
    public function index(Request $request)
    {
        $company = Auth::guard('insurance_company')->user();

        $query = Claim::forCompany($company->id)
            ->with(['insuranceUser', 'attachments', 'serviceCenter'])
            ->orderBy('created_at', 'desc');

        // Filters
        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('policy_number', 'like', "%{$search}%")
                    ->orWhere('vehicle_plate_number', 'like', "%{$search}%")
                    ->orWhere('chassis_number', 'like', "%{$search}%")
                    ->orWhereHas('insuranceUser', function ($userQuery) use ($search) {
                        $userQuery->where('full_name', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%");
                    });
            });
        }

        $claims = $query->paginate(15);

        // Statistics
        $stats = [
            'total' => Claim::forCompany($company->id)->count(),
            'pending' => Claim::forCompany($company->id)->pending()->count(),
            'approved' => Claim::forCompany($company->id)->approved()->count(),
            'rejected' => Claim::forCompany($company->id)->rejected()->count(),
        ];

        return view('insurance.claims.index', compact('claims', 'stats', 'company'));
    }

    public function show(Request $request, $companyRoute, $claim)
    {
        $company = Auth::guard('insurance_company')->user();

        // الحصول على الـ Claim ID من الـ route parameters
        $claimId = $request->route('claim');

        $claim = Claim::where('id', $claimId)
            ->where('insurance_company_id', $company->id)
            ->with(['insuranceUser', 'attachments', 'serviceCenter'])
            ->first();

        if (!$claim) {
            abort(404, 'Claim not found');
        }

        return view('insurance.claims.show', compact('claim', 'company'));
    }

    public function approve(Request $request, $companyRoute, $claim)
    {
        $company = Auth::guard('insurance_company')->user();

        // الحصول على الـ Claim ID من الـ route parameters
        $claimId = $request->route('claim');

        $claim = Claim::where('id', $claimId)
            ->where('insurance_company_id', $company->id)
            ->where('status', 'pending')
            ->first();

        if (!$claim) {
            abort(404, 'Claim not found or not eligible for approval');
        }

        $request->validate([
            'service_center_id' => 'required|exists:service_centers,id',
            'notes' => 'nullable|string|max:1000'
        ]);

        // Verify service center exists and is active
        $serviceCenter = ServiceCenter::where('id', $request->service_center_id)
            ->where('is_active', true)
            ->where('is_approved', true)
            ->firstOrFail();

        try {
            // بيانات الموافقة
            $approvalData = [
                'status' => 'approved',
                'service_center_id' => $serviceCenter->id
            ];

            // إذا كانت السيارة لا تعمل - اعرض خدمة السطحة
            if (!$claim->is_vehicle_working) {
                $approvalData['tow_service_offered'] = true;
            } else {
                // إذا كانت السيارة تعمل - ولّد كود التوصيل مباشرة
                $approvalData['customer_delivery_code'] = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            }

            if ($request->notes) {
                $approvalData['notes'] = $request->notes;
            }

            $claim->update($approvalData);

            $message = 'Claim approved successfully.';

            // لو السيارة تشتغل - اعلم المستخدم بكود التوصيل
            if ($claim->is_vehicle_working) {
                $message .= ' Delivery code: ' . $claim->customer_delivery_code;
            }

            return redirect()->route('insurance.claims.show', [
                'companyRoute' => $company->company_slug,
                'claim' => $claim->id
            ])->with('success', $message);
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to approve claim. Please try again.');
        }
    }


    public function reject(Request $request, $companyRoute, $claim)
    {
        $company = Auth::guard('insurance_company')->user();

        // الحصول على الـ Claim ID من الـ route parameters
        $claimId = $request->route('claim');

        $claim = Claim::where('id', $claimId)
            ->where('insurance_company_id', $company->id)
            ->where('status', 'pending')
            ->first();

        if (!$claim) {
            abort(404, 'Claim not found or not eligible for rejection');
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:1000'
        ]);

        try {
            $claim->reject($request->rejection_reason);

            return redirect()->route('insurance.claims.show', [
                'companyRoute' => $company->company_slug,
                'claim' => $claim->id
            ])->with('success', 'Claim rejected successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to reject claim. Please try again.');
        }
    }

    public function getServiceCenters(Request $request)
    {
        $serviceCenters = ServiceCenter::where('is_active', true)
            ->where('is_approved', true)
            ->with('industrialArea')
            ->orderBy('legal_name')
            ->get()
            ->map(function ($center) {
                return [
                    'id' => $center->id,
                    'name' => $center->legal_name,
                    'area' => $center->industrialArea ? $center->industrialArea->display_name : null,
                    'address' => $center->center_address,
                    'phone' => $center->formatted_phone,
                    'location' => [
                        'lat' => $center->center_location_lat,
                        'lng' => $center->center_location_lng
                    ]
                ];
            });

        return response()->json($serviceCenters);
    }

    public function stats()
    {
        $company = Auth::guard('insurance_company')->user();

        $stats = [
            'total_claims' => Claim::forCompany($company->id)->count(),
            'pending_claims' => Claim::forCompany($company->id)->pending()->count(),
            'approved_claims' => Claim::forCompany($company->id)->approved()->count(),
            'rejected_claims' => Claim::forCompany($company->id)->rejected()->count(),
            'claims_this_month' => Claim::forCompany($company->id)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'claims_today' => Claim::forCompany($company->id)
                ->whereDate('created_at', today())
                ->count()
        ];

        return response()->json($stats);
    }
}
