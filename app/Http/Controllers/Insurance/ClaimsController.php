<?php
// Path: app/Http/Controllers/Insurance/ClaimsController.php

namespace App\Http\Controllers\Insurance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Claim;
use App\Models\ServiceCenter;
use App\Models\InsuranceUser;
use App\Models\ClaimAttachment;

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
            'accepted_by_center' => Claim::forCompany($company->id)
            ->where('status', 'approved')
            ->count(),
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

    public function create(Request $request)
    {
        $company = Auth::guard('insurance_company')->user();
        $users = InsuranceUser::where('insurance_company_id', $company->id)->get();
        return view('insurance.claims.create', compact('company', 'users'));
    }

    public function store(Request $request)
    {
        $company = Auth::guard('insurance_company')->user();

        $request->validate([
            'insurance_user_id' => 'required|exists:insurance_users,id',
            'policy_number' => 'required|string|max:100',
            'vehicle_plate_number' => 'nullable|string|max:50',
            'chassis_number' => 'nullable|string|max:100',
            'vehicle_brand' => 'nullable|string|max:100',
            'vehicle_type' => 'nullable|string|max:100',
            'vehicle_model' => 'nullable|string|max:100',
            'vehicle_location' => 'required_if:is_vehicle_working,0|string|nullable',
            'vehicle_location_lat' => 'nullable|numeric|between:-90,90',
            'vehicle_location_lng' => 'nullable|numeric|between:-180,180',
            'is_vehicle_working' => 'required|boolean',
            'repair_receipt_ready' => 'required|boolean',
            'notes' => 'nullable|string|max:1000',
            'policy_image.*' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
            'registration_form.*' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
            'repair_receipt.*' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
            'damage_report.*' => 'required|file|mimes:jpeg,png,jpg,pdf|max:5120',
            'estimation_report.*' => 'required|file|mimes:jpeg,png,jpg,pdf|max:5120',
        ]);

        if (!$request->vehicle_plate_number && !$request->chassis_number) {
            return back()->withErrors(['vehicle_info' => 'Either vehicle plate number or chassis number is required'])
                ->withInput();
        }

        try {
            $claimData = [
                'insurance_user_id' => $request->insurance_user_id,
                'insurance_company_id' => $company->id,
                'policy_number' => $request->policy_number,
                'vehicle_plate_number' => $request->vehicle_plate_number,
                'chassis_number' => $request->chassis_number,
                'vehicle_brand' => $request->vehicle_brand,
                'vehicle_type' => $request->vehicle_type,
                'vehicle_model' => $request->vehicle_model,
                'vehicle_location' => $request->vehicle_location,
                'vehicle_location_lat' => $request->vehicle_location_lat,
                'vehicle_location_lng' => $request->vehicle_location_lng,
                'is_vehicle_working' => $request->is_vehicle_working,
                'repair_receipt_ready' => $request->repair_receipt_ready,
                'notes' => $request->notes,
                'status' => 'pending'
            ];

            $claim = Claim::create($claimData);

            $fileTypes = ['policy_image', 'registration_form', 'repair_receipt', 'damage_report', 'estimation_report'];
            
            foreach ($fileTypes as $type) {
                if ($request->hasFile($type)) {
                    $files = $request->file($type);
                    if (!is_array($files)) {
                        $files = [$files];
                    }

                    foreach ($files as $file) {
                        $this->storeAttachment($claim, $file, $type);
                    }
                }
            }

            return redirect()->route('insurance.claims.show', [
                'companyRoute' => $company->company_slug,
                'claim' => $claim->id
            ])->with('success', 'Claim submitted successfully. Claim number: ' . $claim->claim_number);

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to submit claim. Please try again.')
                ->withInput();
        }
    }

    public function edit(Request $request, $companyRoute, $claim)
    {
        $company = Auth::guard('insurance_company')->user();
        $claim = Claim::where('id', $claim)->where('insurance_company_id', $company->id)->firstOrFail();
        $users = InsuranceUser::where('insurance_company_id', $company->id)->get();
        return view('insurance.claims.edit', compact('claim', 'company', 'users'));
    }

    public function update(Request $request, $companyRoute, $claim)
    {
        $company = Auth::guard('insurance_company')->user();
        $claim = Claim::where('id', $claim)->where('insurance_company_id', $company->id)->firstOrFail();

        $request->validate([
            'insurance_user_id' => 'required|exists:insurance_users,id',
            'policy_number' => 'required|string|max:100',
            'vehicle_plate_number' => 'nullable|string|max:50',
            'chassis_number' => 'nullable|string|max:100',
            'vehicle_brand' => 'nullable|string|max:100',
            'vehicle_type' => 'nullable|string|max:100',
            'vehicle_model' => 'nullable|string|max:100',
            'vehicle_location' => 'required_if:is_vehicle_working,0|string|nullable',
            'vehicle_location_lat' => 'nullable|numeric|between:-90,90',
            'vehicle_location_lng' => 'nullable|numeric|between:-180,180',
            'is_vehicle_working' => 'required|boolean',
            'repair_receipt_ready' => 'required|boolean',
            'notes' => 'nullable|string|max:1000',
            'policy_image.*' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
            'registration_form.*' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
            'repair_receipt.*' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
            'damage_report.*' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
            'estimation_report.*' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
        ]);

        if (!$request->vehicle_plate_number && !$request->chassis_number) {
            return back()->withErrors(['vehicle_info' => 'Either vehicle plate number or chassis number is required'])
                ->withInput();
        }

        try {
            $claim->update($request->except(['_token', '_method']));

            $fileTypes = ['policy_image', 'registration_form', 'repair_receipt', 'damage_report', 'estimation_report'];
            
            foreach ($fileTypes as $type) {
                if ($request->hasFile($type)) {
                    $files = $request->file($type);
                    if (!is_array($files)) {
                        $files = [$files];
                    }

                    foreach ($files as $file) {
                        $this->storeAttachment($claim, $file, $type);
                    }
                }
            }

            return redirect()->route('insurance.claims.show', [
                'companyRoute' => $company->company_slug,
                'claim' => $claim->id
            ])->with('success', 'Claim updated successfully.');

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update claim. Please try again.')
                ->withInput();
        }
    }
    
    private function storeAttachment(Claim $claim, $file, string $type): ClaimAttachment
    {
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $size = $file->getSize();
        $mimeType = $file->getMimeType();
        
        $filename = $claim->id . '_' . $type . '_' . time() . '_' . uniqid() . '.' . $extension;
        $path = $file->storeAs('claims/' . $claim->id . '/' . $type, $filename, 'public');

        return ClaimAttachment::create([
            'claim_id' => $claim->id,
            'type' => $type,
            'file_path' => $path,
            'file_name' => $originalName,
            'file_size' => $size,
            'mime_type' => $mimeType
        ]);
    }

    public function approve(Request $request, $companyRoute, $claim)
    {
        $company = Auth::guard('insurance_company')->user();
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

        $serviceCenter = ServiceCenter::where('id', $request->service_center_id)
            ->where('is_active', true)
            ->where('is_approved', true)
            ->firstOrFail();

        try {
            $approvalData = [
                'status' => 'pending',
                'service_center_id' => $serviceCenter->id,
                'notes' => $request->notes,
                // لا تولد كود التوصيل هنا ولا ترسل إشعار للمستخدم
            ];

            $claim->update($approvalData);

            return redirect()->route('insurance.claims.show', [
                'companyRoute' => $company->company_slug,
                'claim' => $claim->id
            ])->with('success', 'Claim assigned to service center. Awaiting service center decision.');
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
        $company = Auth::guard('insurance_company')->user();
        
        // عرض جميع مراكز الصيانة المفعلة والمعتمدة
        // بما في ذلك: المراكز المستقلة، المراكز التابعة للشركة الحالية، والمراكز التابعة لشركات أخرى
        $serviceCenters = ServiceCenter::where('is_active', true)
            ->where('is_approved', true)
            ->with(['industrialArea', 'insuranceCompany'])
            ->withCount(['claims as accepted_claims_count' => function($query) use ($company) {
                // عدّ الطلبات المقبولة من مركز الصيانة للشركة الحالية فقط
                $query->where('insurance_company_id', $company->id)
                      ->where('status', 'approved');
            }])
            ->orderBy('legal_name')
            ->get()
            ->map(function ($center) use ($company) {
                return [
                    'id' => $center->id,
                    'name' => $center->legal_name,
                    'area' => $center->industrialArea ? $center->industrialArea->display_name : null,
                    'address' => $center->center_address,
                    'phone' => $center->formatted_phone,
                    'accepted_claims_count' => $center->accepted_claims_count ?? 0,
                    'is_owned_by_current_company' => $center->created_by_company && $center->insurance_company_id == $company->id,
                    'is_independent' => !$center->created_by_company,
                    'is_owned_by_other_company' => $center->created_by_company && $center->insurance_company_id != $company->id,
                    'owner_company' => $center->created_by_company && $center->insuranceCompany ? $center->insuranceCompany->legal_name : null,
                    'center_type' => $this->getCenterType($center, $company),
                    'location' => [
                        'lat' => $center->center_location_lat,
                        'lng' => $center->center_location_lng
                    ]
                ];
            });

        return response()->json($serviceCenters);
    }

    /**
     * تحديد نوع مركز الصيانة بشكل مفصل
     */
    private function getCenterType($center, $company)
    {
        // إذا لم يتم إنشاؤه بواسطة شركة (أنشأه الأدمن)
        if (!$center->created_by_company) {
            return 'independent';
        }
        
        // إذا تم إنشاؤه بواسطة نفس الشركة
        if ($center->insurance_company_id == $company->id) {
            return 'owned_by_current_company';
        }
        
        // إذا تم إنشاؤه بواسطة شركة أخرى
        return 'owned_by_other_company';
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
