<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClaimInspection extends Model
{
    protected $fillable = [
        'claim_id',
        'vehicle_brand',
        'vehicle_model', 
        'vehicle_year',
        'chassis_number',
        'registration_image_path',
        'required_parts',
        'inspection_notes',
        'inspected_by',
        'parts_pricing',
        'service_center_fees',
        'tax_percentage',
        'tax_amount',
        'total_amount',
        'pricing_status',
        'insurance_response',
        'rejection_reason',
        'admin_notes',
        'insurance_notes',
        'priced_at',
        'sent_to_insurance_at',
        'insurance_responded_at'
    ];

    protected $casts = [
        'required_parts' => 'array',
        'parts_pricing' => 'array',
        'vehicle_year' => 'integer',
        'service_center_fees' => 'decimal:2',
        'tax_percentage' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'priced_at' => 'datetime',
        'sent_to_insurance_at' => 'datetime',
        'insurance_responded_at' => 'datetime'
    ];

    public function claim(): BelongsTo
    {
        return $this->belongsTo(Claim::class);
    }

    public function serviceCenter(): BelongsTo
    {
        return $this->belongsTo(ServiceCenter::class, 'inspected_by');
    }

    // Get parts with pricing
    public function getPartsWithPricingAttribute()
    {
        if (!$this->parts_pricing || !isset($this->parts_pricing['parts'])) {
            return [];
        }
        
        return $this->parts_pricing['parts'];
    }

    // Get parts total from pricing
    public function getPartsTotalAttribute()
    {
        if (!$this->parts_pricing || !isset($this->parts_pricing['parts_total'])) {
            return 0;
        }
        
        return $this->parts_pricing['parts_total'];
    }

    // Check if inspection has pricing
    public function hasPricing(): bool
    {
        return $this->pricing_status !== 'pending' && 
               !empty($this->parts_pricing) && 
               isset($this->parts_pricing['parts']);
    }

    // Check if pricing is complete and ready to send
    public function isPricingComplete(): bool
    {
        return $this->hasPricing() && 
               $this->total_amount > 0 && 
               $this->pricing_status === 'priced';
    }

    // Check if sent to insurance
    public function isSentToInsurance(): bool
    {
        return $this->pricing_status === 'sent_to_insurance' || 
               $this->pricing_status === 'approved' || 
               $this->pricing_status === 'rejected';
    }

    // Check if insurance responded
    public function hasInsuranceResponse(): bool
    {
        return $this->insurance_response === 'approved' || 
               $this->insurance_response === 'rejected';
    }

    // Calculate totals
    public function calculateTotals()
    {
        if (!$this->parts_pricing || !isset($this->parts_pricing['parts'])) {
            return;
        }

        $partsTotal = 0;
        foreach ($this->parts_pricing['parts'] as $part) {
            $partsTotal += $part['total'] ?? 0;
        }

        $serviceFees = $this->service_center_fees ?? 0;
        $taxPercentage = $this->tax_percentage ?? 15;
        
        $subtotal = $partsTotal + $serviceFees;
        $taxAmount = ($subtotal * $taxPercentage) / 100;
        $totalAmount = $subtotal + $taxAmount;

        // Update pricing JSON
        $pricing = $this->parts_pricing;
        $pricing['parts_total'] = $partsTotal;
        $pricing['service_fees'] = $serviceFees;
        $pricing['tax_percentage'] = $taxPercentage;
        $pricing['tax_amount'] = $taxAmount;
        $pricing['total_amount'] = $totalAmount;

        $this->update([
            'parts_pricing' => $pricing,
            'tax_amount' => $taxAmount,
            'total_amount' => $totalAmount
        ]);
    }

    // Send to insurance
    public function sendToInsurance()
    {
        if (!$this->isPricingComplete()) {
            return false;
        }

        $this->update([
            'pricing_status' => 'sent_to_insurance',
            'sent_to_insurance_at' => now()
        ]);

        // Update related claim
        $this->claim->update([
            'has_pricing' => true,
            'pricing_total' => $this->total_amount
        ]);

        return true;
    }

    // Insurance approve
    public function approveByInsurance($notes = null)
    {
        $this->update([
            'pricing_status' => 'approved',
            'insurance_response' => 'approved',
            'insurance_notes' => $notes,
            'insurance_responded_at' => now()
        ]);

        // Update related claim
        $this->claim->update([
            'pricing_approved' => true
        ]);

        return true;
    }

    // Insurance reject
    public function rejectByInsurance($reason, $notes = null)
    {
        $this->update([
            'pricing_status' => 'rejected',
            'insurance_response' => 'rejected',
            'rejection_reason' => $reason,
            'insurance_notes' => $notes,
            'insurance_responded_at' => now()
        ]);

        // Update related claim
        $this->claim->update([
            'pricing_approved' => false
        ]);

        return true;
    }

    // Reset for resubmission
    public function resetForResubmission()
    {
        $this->update([
            'pricing_status' => 'priced',
            'insurance_response' => 'pending',
            'rejection_reason' => null,
            'insurance_notes' => null,
            'sent_to_insurance_at' => null,
            'insurance_responded_at' => null
        ]);

        return true;
    }

    // Status badges for UI
    public function getPricingStatusBadgeAttribute()
    {
        $badges = [
            'pending' => ['class' => 'bg-gray-100 text-gray-800', 'text' => 'Pending Pricing'],
            'priced' => ['class' => 'bg-blue-100 text-blue-800', 'text' => 'Priced'],
            'sent_to_insurance' => ['class' => 'bg-yellow-100 text-yellow-800', 'text' => 'Sent to Insurance'],
            'approved' => ['class' => 'bg-green-100 text-green-800', 'text' => 'Approved'],
            'rejected' => ['class' => 'bg-red-100 text-red-800', 'text' => 'Rejected']
        ];

        return $badges[$this->pricing_status] ?? $badges['pending'];
    }

    public function getInsuranceResponseBadgeAttribute()
    {
        $badges = [
            'pending' => ['class' => 'bg-yellow-100 text-yellow-800', 'text' => 'Awaiting Response'],
            'approved' => ['class' => 'bg-green-100 text-green-800', 'text' => 'Approved'],
            'rejected' => ['class' => 'bg-red-100 text-red-800', 'text' => 'Rejected']
        ];

        return $badges[$this->insurance_response] ?? $badges['pending'];
    }

    // Scopes
    public function scopeWithPricing($query)
    {
        return $query->whereNotNull('parts_pricing')
                    ->where('pricing_status', '!=', 'pending');
    }

    public function scopePendingPricing($query)
    {
        return $query->where('pricing_status', 'pending');
    }

    public function scopeSentToInsurance($query)
    {
        return $query->where('pricing_status', 'sent_to_insurance');
    }

    public function scopeApprovedByInsurance($query)
    {
        return $query->where('insurance_response', 'approved');
    }

    public function scopeRejectedByInsurance($query)
    {
        return $query->where('insurance_response', 'rejected');
    }
}