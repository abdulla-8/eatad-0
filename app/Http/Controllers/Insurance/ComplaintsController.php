<?php
// app/Http/Controllers/Insurance/ComplaintsController.php

namespace App\Http\Controllers\Insurance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Complaint;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ComplaintsController extends Controller
{
    public function index(Request $request)
    {
        $company = Auth::guard('insurance_company')->user();
        
        $query = Complaint::where('complainant_type', 'insurance_company')
            ->where('complainant_id', $company->id)
            ->orderBy('created_at', 'desc');

        // Apply filters
        if ($request->type) {
            $query->byType($request->type);
        }

        if ($request->status) {
            $query->byStatus($request->status);
        }

        if ($request->search) {
            $query->search($request->search);
        }

        $complaints = $query->paginate(10);

        // Statistics
        $stats = [
            'total' => Complaint::where('complainant_type', 'insurance_company')->where('complainant_id', $company->id)->count(),
            'unread' => Complaint::where('complainant_type', 'insurance_company')->where('complainant_id', $company->id)->where('is_read', false)->count(),
            'read' => Complaint::where('complainant_type', 'insurance_company')->where('complainant_id', $company->id)->where('is_read', true)->count(),
            'inquiry' => Complaint::where('complainant_type', 'insurance_company')->where('complainant_id', $company->id)->where('type', 'inquiry')->count(),
            'complaint' => Complaint::where('complainant_type', 'insurance_company')->where('complainant_id', $company->id)->where('type', 'complaint')->count(),
            'other' => Complaint::where('complainant_type', 'insurance_company')->where('complainant_id', $company->id)->where('type', 'other')->count(),
        ];

        return view('insurance.complaints.index', compact('complaints', 'stats', 'company'));
    }

    public function store(Request $request)
    {
        $company = Auth::guard('insurance_company')->user();

        $request->validate([
            'type' => 'required|in:inquiry,complaint,other',
            'subject' => 'required|string|max:500',
            'description' => 'required|string|max:2000',
            'attachment' => 'nullable|file|mimes:jpeg,png,jpg,pdf,doc,docx|max:5120'
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = time() . '_' . $file->getClientOriginalName();
            $attachmentPath = $file->storeAs('complaints', $filename, 'public');
        }

        Complaint::create([
            'complainant_type' => 'insurance_company',
            'complainant_id' => $company->id,
            'complainant_name' => $company->legal_name,
            'type' => $request->type,
            'subject' => $request->subject,
            'description' => $request->description,
            'attachment_path' => $attachmentPath,
            'is_read' => false
        ]);

        return redirect()->route('insurance.complaints.index', $company->company_slug)
            ->with('success', t($company->translation_group . '.complaint_submitted_successfully'));
    }

    public function show($companyRoute, $id)
    {
        $company = Auth::guard('insurance_company')->user();
        
        $complaint = Complaint::where('complainant_type', 'insurance_company')
            ->where('complainant_id', $company->id)
            ->where('id', $id)
            ->firstOrFail();

        return view('insurance.complaints.show', compact('complaint', 'company'));
    }
}
