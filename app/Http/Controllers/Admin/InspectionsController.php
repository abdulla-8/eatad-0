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

        // Filter by pricing status
        if ($request->pricing_status) {
            $query->where('pricing_status', $request->pricing_status);
        }

        $inspections = $query->paginate(15);

        return view('admin.inspections.index', compact('inspections'));
    }

    public function show(ClaimInspection $inspection)
    {
        $inspection->load(['claim.insuranceUser', 'claim.insuranceCompany', 'serviceCenter']);
        
        return view('admin.inspections.show', compact('inspection'));
    }

    public function updatePricing(Request $request, ClaimInspection $inspection)
    {
        $request->validate([
            'parts' => 'required|array|min:1',
            'parts.*.name' => 'required|string|max:255',
            'parts.*.price' => 'required|numeric|min:0',
            'parts.*.quantity' => 'required|integer|min:1',
            'service_center_fees' => 'required|numeric|min:0',
            'tax_percentage' => 'required|numeric|min:0|max:100',
            'admin_notes' => 'nullable|string|max:1000'
        ]);

        try {
            // Prepare parts data
            $partsData = [];
            $partsTotal = 0;

            foreach ($request->parts as $part) {
                $total = $part['price'] * $part['quantity'];
                $partsData[] = [
                    'name' => $part['name'],
                    'price' => $part['price'],
                    'quantity' => $part['quantity'],
                    'total' => $total
                ];
                $partsTotal += $total;
            }

            // Calculate totals
            $serviceFees = $request->service_center_fees;
            $taxPercentage = $request->tax_percentage;
            $subtotal = $partsTotal + $serviceFees;
            $taxAmount = ($subtotal * $taxPercentage) / 100;
            $totalAmount = $subtotal + $taxAmount;

            // Prepare pricing JSON
            $pricingData = [
                'parts' => $partsData,
                'parts_total' => $partsTotal,
                'service_fees' => $serviceFees,
                'tax_percentage' => $taxPercentage,
                'tax_amount' => $taxAmount,
                'total_amount' => $totalAmount
            ];

            // Update inspection
            $inspection->update([
                'parts_pricing' => $pricingData,
                'service_center_fees' => $serviceFees,
                'tax_percentage' => $taxPercentage,
                'tax_amount' => $taxAmount,
                'total_amount' => $totalAmount,
                'pricing_status' => 'priced',
                'admin_notes' => $request->admin_notes,
                'priced_at' => now()
            ]);

            return redirect()->back()
                ->with('success', 'Parts pricing updated successfully');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update pricing. Please try again.')
                ->withInput();
        }
    }

    public function sendToInsurance(Request $request, ClaimInspection $inspection)
    {
        if (!$inspection->isPricingComplete()) {
            return redirect()->back()
                ->with('error', 'Pricing is not complete. Please add parts pricing first.');
        }

        try {
            $inspection->sendToInsurance();

            return redirect()->back()
                ->with('success', 'Pricing sent to insurance company successfully');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to send pricing. Please try again.');
        }
    }

    public function resetPricing(Request $request, ClaimInspection $inspection)
    {
        if (!$inspection->hasInsuranceResponse()) {
            return redirect()->back()
                ->with('error', 'Cannot reset pricing. No insurance response found.');
        }

        try {
            $inspection->resetForResubmission();

            return redirect()->back()
                ->with('success', 'Pricing reset for resubmission. You can now update the pricing.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to reset pricing. Please try again.');
        }
    }

    public function deletePricing(Request $request, ClaimInspection $inspection)
    {
        if ($inspection->isSentToInsurance()) {
            return redirect()->back()
                ->with('error', 'Cannot delete pricing that has been sent to insurance.');
        }

        try {
            $inspection->update([
                'parts_pricing' => null,
                'service_center_fees' => null,
                'tax_amount' => null,
                'total_amount' => null,
                'pricing_status' => 'pending',
                'admin_notes' => null,
                'priced_at' => null
            ]);

            return redirect()->back()
                ->with('success', 'Pricing deleted successfully');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete pricing. Please try again.');
        }
    }

    public function getInspectionPricingData(ClaimInspection $inspection)
    {
        return response()->json([
            'has_pricing' => $inspection->hasPricing(),
            'parts' => $inspection->parts_with_pricing,
            'service_center_fees' => $inspection->service_center_fees,
            'tax_percentage' => $inspection->tax_percentage,
            'tax_amount' => $inspection->tax_amount,
            'total_amount' => $inspection->total_amount,
            'pricing_status' => $inspection->pricing_status,
            'admin_notes' => $inspection->admin_notes
        ]);
    }
}