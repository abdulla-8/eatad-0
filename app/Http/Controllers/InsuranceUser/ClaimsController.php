<?php
// Path: app/Http/Controllers/InsuranceUser/ClaimsController.php

namespace App\Http\Controllers\InsuranceUser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Claim;
use App\Models\ClaimAttachment;
use App\Models\InsuranceCompany;

class ClaimsController extends Controller
{
    public function index(Request $request)
    {
        $companySlug = $request->route('companySlug');
        $company = InsuranceCompany::where('company_slug', $companySlug)
            ->where('is_active', true)
            ->where('is_approved', true)
            ->firstOrFail();

        $user = Auth::guard('insurance_user')->user();

        // Check if user belongs to this company
        if ($user->insurance_company_id !== $company->id) {
            Auth::guard('insurance_user')->logout();
            return redirect()->route('insurance.user.login', $companySlug);
        }

        $claims = Claim::forUser($user->id)
            ->with(['attachments', 'serviceCenter'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('insurance-user.claims.index', compact('claims', 'user', 'company'));
    }

    public function create(Request $request)
    {
        $companySlug = $request->route('companySlug');
        $company = InsuranceCompany::where('company_slug', $companySlug)
            ->where('is_active', true)
            ->where('is_approved', true)
            ->firstOrFail();

        $user = Auth::guard('insurance_user')->user();

        // Check if user belongs to this company
        if ($user->insurance_company_id !== $company->id) {
            Auth::guard('insurance_user')->logout();
            return redirect()->route('insurance.user.login', $companySlug);
        }

        return view('insurance-user.claims.create', compact('user', 'company'));
    }

    public function store(Request $request)
    {
        $companySlug = $request->route('companySlug');
        $company = InsuranceCompany::where('company_slug', $companySlug)
            ->where('is_active', true)
            ->where('is_approved', true)
            ->firstOrFail();

        $user = Auth::guard('insurance_user')->user();

        // Check if user belongs to this company
        if ($user->insurance_company_id !== $company->id) {
            Auth::guard('insurance_user')->logout();
            return redirect()->route('insurance.user.login', $companySlug);
        }

        // Validation
        $request->validate([
            'policy_number' => 'required|string|max:100',
            'vehicle_plate_number' => 'nullable|string|max:50',
            'chassis_number' => 'nullable|string|max:100',
            'vehicle_location' => 'required|string',
            'vehicle_location_lat' => 'nullable|numeric|between:-90,90',
            'vehicle_location_lng' => 'nullable|numeric|between:-180,180',
            'is_vehicle_working' => 'required|boolean',
            'repair_receipt_ready' => 'required|boolean',
            'notes' => 'nullable|string|max:1000',
            
            // File validations
            'policy_image.*' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
            'registration_form.*' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
            'repair_receipt.*' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
            'damage_report.*' => 'required|file|mimes:jpeg,png,jpg,pdf|max:5120',
            'estimation_report.*' => 'required|file|mimes:jpeg,png,jpg,pdf|max:5120',
        ], [
            'vehicle_plate_number.required_without' => 'Either vehicle plate number or chassis number is required',
            'chassis_number.required_without' => 'Either vehicle plate number or chassis number is required',
            'damage_report.*.required' => 'Damage report is required',
            'estimation_report.*.required' => 'Estimation report is required',
        ]);

        // Check that either vehicle plate or chassis number is provided
        if (!$request->vehicle_plate_number && !$request->chassis_number) {
            return back()->withErrors(['vehicle_info' => 'Either vehicle plate number or chassis number is required'])
                ->withInput();
        }

        try {
            // Create claim
            $claimData = [
                'insurance_user_id' => $user->id,
                'insurance_company_id' => $company->id,
                'policy_number' => $request->policy_number,
                'vehicle_plate_number' => $request->vehicle_plate_number,
                'chassis_number' => $request->chassis_number,
                'vehicle_location' => $request->vehicle_location,
                'vehicle_location_lat' => $request->vehicle_location_lat,
                'vehicle_location_lng' => $request->vehicle_location_lng,
                'is_vehicle_working' => $request->is_vehicle_working,
                'repair_receipt_ready' => $request->repair_receipt_ready,
                'notes' => $request->notes,
                'status' => 'pending'
            ];

            $claim = Claim::create($claimData);

            // Handle file uploads
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

            return redirect()->route('insurance.user.claims.show', [
                'companySlug' => $companySlug,
                'claim' => $claim->id
            ])->with('success', 'Claim submitted successfully. Claim number: ' . $claim->claim_number);

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to submit claim. Please try again.')
                ->withInput();
        }
    }

    public function show(Request $request, $companySlug, $claim)
    {
        $company = InsuranceCompany::where('company_slug', $companySlug)
            ->where('is_active', true)
            ->where('is_approved', true)
            ->firstOrFail();

        $user = Auth::guard('insurance_user')->user();

        // Check if user belongs to this company
        if ($user->insurance_company_id !== $company->id) {
            Auth::guard('insurance_user')->logout();
            return redirect()->route('insurance.user.login', $companySlug);
        }

        // Get claim ID from route parameters
        $claimId = $request->route('claim');

        $claim = Claim::with(['attachments', 'serviceCenter', 'insuranceCompany'])
            ->where('insurance_user_id', $user->id)
            ->where('id', $claimId)
            ->first();

        if (!$claim) {
            abort(404, 'Claim not found');
        }

        return view('insurance-user.claims.show', compact('claim', 'user', 'company'));
    }

    public function edit(Request $request, $companySlug, $claim)
    {
        $company = InsuranceCompany::where('company_slug', $companySlug)
            ->where('is_active', true)
            ->where('is_approved', true)
            ->firstOrFail();

        $user = Auth::guard('insurance_user')->user();

        // Check if user belongs to this company
        if ($user->insurance_company_id !== $company->id) {
            Auth::guard('insurance_user')->logout();
            return redirect()->route('insurance.user.login', $companySlug);
        }

        // Get claim ID from route parameters
        $claimId = $request->route('claim');

        $claim = Claim::with('attachments')
            ->where('insurance_user_id', $user->id)
            ->where('status', 'rejected')
            ->where('id', $claimId)
            ->first();

        if (!$claim) {
            abort(404, 'Claim not found or not editable');
        }

        return view('insurance-user.claims.edit', compact('claim', 'user', 'company'));
    }

    public function update(Request $request, $companySlug, $claim)
    {
        $company = InsuranceCompany::where('company_slug', $companySlug)
            ->where('is_active', true)
            ->where('is_approved', true)
            ->firstOrFail();

        $user = Auth::guard('insurance_user')->user();

        // Check if user belongs to this company
        if ($user->insurance_company_id !== $company->id) {
            Auth::guard('insurance_user')->logout();
            return redirect()->route('insurance.user.login', $companySlug);
        }

        // Get claim ID from route parameters
        $claimId = $request->route('claim');

        $claim = Claim::where('insurance_user_id', $user->id)
            ->where('status', 'rejected')
            ->where('id', $claimId)
            ->first();

        if (!$claim) {
            abort(404, 'Claim not found or not editable');
        }

        // Same validation as store
        $request->validate([
            'policy_number' => 'required|string|max:100',
            'vehicle_plate_number' => 'nullable|string|max:50',
            'chassis_number' => 'nullable|string|max:100',
            'vehicle_location' => 'required|string',
            'vehicle_location_lat' => 'nullable|numeric|between:-90,90',
            'vehicle_location_lng' => 'nullable|numeric|between:-180,180',
            'is_vehicle_working' => 'required|boolean',
            'repair_receipt_ready' => 'required|boolean',
            'notes' => 'nullable|string|max:1000',
            
            // File validations
            'policy_image.*' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
            'registration_form.*' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
            'repair_receipt.*' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
            'damage_report.*' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
            'estimation_report.*' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
        ]);

        // Check that either vehicle plate or chassis number is provided
        if (!$request->vehicle_plate_number && !$request->chassis_number) {
            return back()->withErrors(['vehicle_info' => 'Either vehicle plate number or chassis number is required'])
                ->withInput();
        }

        try {
            // Update claim
            $claim->update([
                'policy_number' => $request->policy_number,
                'vehicle_plate_number' => $request->vehicle_plate_number,
                'chassis_number' => $request->chassis_number,
                'vehicle_location' => $request->vehicle_location,
                'vehicle_location_lat' => $request->vehicle_location_lat,
                'vehicle_location_lng' => $request->vehicle_location_lng,
                'is_vehicle_working' => $request->is_vehicle_working,
                'repair_receipt_ready' => $request->repair_receipt_ready,
                'notes' => $request->notes,
            ]);

            // Handle new file uploads
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

            // Resubmit claim
            $claim->resubmit();

            return redirect()->route('insurance.user.claims.show', [
                'companySlug' => $companySlug,
                'claim' => $claim->id
            ])->with('success', 'Claim updated and resubmitted successfully.');

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update claim. Please try again.')
                ->withInput();
        }
    }

    public function updateTowService(Request $request, $companySlug, $claim)
    {
        $company = InsuranceCompany::where('company_slug', $companySlug)
            ->where('is_active', true)
            ->where('is_approved', true)
            ->firstOrFail();

        $user = Auth::guard('insurance_user')->user();

        // Check if user belongs to this company
        if ($user->insurance_company_id !== $company->id) {
            Auth::guard('insurance_user')->logout();
            return redirect()->route('insurance.user.login', $companySlug);
        }

        // Get claim ID from route parameters
        $claimId = $request->route('claim');

        $claim = Claim::where('insurance_user_id', $user->id)
            ->where('status', 'approved')
            ->where('tow_service_offered', true)
            ->whereNull('tow_service_accepted')
            ->where('id', $claimId)
            ->first();

        if (!$claim) {
            abort(404, 'Claim not found or tow service not available');
        }

        $request->validate([
            'tow_service_accepted' => 'required|boolean'
        ]);

        $claim->update([
            'tow_service_accepted' => $request->tow_service_accepted
        ]);

        $message = $request->tow_service_accepted 
            ? 'Tow service accepted. Please wait for further instructions.'
            : 'Tow service declined. Please proceed to the service center.';

        return redirect()->route('insurance.user.claims.show', [
            'companySlug' => $companySlug,
            'claim' => $claim->id
        ])->with('success', $message);
    }

    public function deleteAttachment(Request $request, $companySlug, $claim, $attachment)
    {
        $user = Auth::guard('insurance_user')->user();
        
        // Get claim ID from route parameters
        $claimId = $request->route('claim');
        $attachmentId = $request->route('attachment');
        
        $claim = Claim::where('insurance_user_id', $user->id)
            ->where('status', 'rejected')
            ->where('id', $claimId)
            ->first();

        if (!$claim) {
            abort(404, 'Claim not found or not editable');
        }

        $attachment = ClaimAttachment::where('claim_id', $claim->id)
            ->where('id', $attachmentId)
            ->first();

        if (!$attachment) {
            abort(404, 'Attachment not found');
        }

        $attachment->delete();

        return back()->with('success', 'Attachment deleted successfully.');
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
}