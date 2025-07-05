<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClaimInspection;

class InspectionsController extends Controller
{
    public function index(Request $request)
    {
        $query = ClaimInspection::with(['claim.insuranceUser', 'claim.insuranceCompany', 'serviceCenter'])
            ->orderBy('created_at', 'desc');

        if ($request->search) {
            $search = $request->search;
            $query->whereHas('claim', function($q) use ($search) {
                $q->where('claim_number', 'like', "%{$search}%")
                  ->orWhere('vehicle_plate_number', 'like', "%{$search}%")
                  ->orWhere('chassis_number', 'like', "%{$search}%");
            });
        }

        $inspections = $query->paginate(15);

        return view('admin.inspections.index', compact('inspections'));
    }

    public function show(ClaimInspection $inspection)
    {
        $inspection->load(['claim.insuranceUser', 'claim.insuranceCompany', 'serviceCenter']);
        
        return view('admin.inspections.show', compact('inspection'));
    }
}