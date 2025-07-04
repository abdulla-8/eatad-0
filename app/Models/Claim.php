<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Claim extends Model
{
    protected $fillable = [
        'insurance_user_id',
        'insurance_company_id',
        'policy_number',
        'vehicle_plate_number',
        'chassis_number',
        'vehicle_location',
        'vehicle_location_lat',
        'vehicle_location_lng',
        'is_vehicle_working',
        'repair_receipt_ready',
        'status',
        'rejection_reason',
        'service_center_id',
        'tow_request_id',
        'tow_service_offered',
        'tow_service_accepted',
        'notes'
    ];

    protected $casts = [
        'is_vehicle_working' => 'boolean',
        'repair_receipt_ready' => 'boolean',
        'tow_service_offered' => 'boolean',
        'tow_service_accepted' => 'boolean',
        'vehicle_location_lat' => 'decimal:8',
        'vehicle_location_lng' => 'decimal:8'
    ];

    // Relations
    public function insuranceUser(): BelongsTo
    {
        return $this->belongsTo(InsuranceUser::class);
    }

    public function insuranceCompany(): BelongsTo
    {
        return $this->belongsTo(InsuranceCompany::class);
    }

    public function serviceCenter(): BelongsTo
    {
        return $this->belongsTo(ServiceCenter::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(ClaimAttachment::class);
    }

    // Add towRequest relationship
    public function towRequest(): HasOne
    {
        return $this->hasOne(TowRequest::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeForCompany($query, $companyId)
    {
        return $query->where('insurance_company_id', $companyId);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('insurance_user_id', $userId);
    }

    // Accessors
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => ['class' => 'bg-yellow-100 text-yellow-800', 'text' => 'Pending'],
            'approved' => ['class' => 'bg-green-100 text-green-800', 'text' => 'Approved'],
            'rejected' => ['class' => 'bg-red-100 text-red-800', 'text' => 'Rejected'],
            'in_progress' => ['class' => 'bg-blue-100 text-blue-800', 'text' => 'In Progress'],
            'completed' => ['class' => 'bg-gray-100 text-gray-800', 'text' => 'Completed']
        ];

        return $badges[$this->status] ?? $badges['pending'];
    }

    public function getVehicleLocationUrlAttribute()
    {
        if ($this->vehicle_location_lat && $this->vehicle_location_lng) {
            return "https://maps.google.com/?q={$this->vehicle_location_lat},{$this->vehicle_location_lng}";
        }
        return null;
    }

    public function getClaimNumberAttribute()
    {
        return 'CLM-' . str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }

    // Methods
    public function hasRequiredAttachments(): bool
    {
        $required = ['damage_report', 'estimation_report'];
        $hasVehicleInfo = $this->vehicle_plate_number || $this->chassis_number;
        
        if (!$hasVehicleInfo) {
            $required[] = 'registration_form';
        }

        foreach ($required as $type) {
            if (!$this->attachments()->where('type', $type)->exists()) {
                return false;
            }
        }

        return true;
    }

    public function getAttachmentsByType(string $type)
    {
        return $this->attachments()->where('type', $type)->get();
    }

    public function canBeApproved(): bool
    {
        return $this->status === 'pending' && $this->hasRequiredAttachments();
    }

    public function canBeRejected(): bool
    {
        return $this->status === 'pending';
    }

    public function approve($serviceCenterId = null): bool
    {
        if (!$this->canBeApproved()) {
            return false;
        }

        $this->update([
            'status' => 'approved',
            'service_center_id' => $serviceCenterId,
            'tow_service_offered' => !$this->is_vehicle_working ? true : null
        ]);

        return true;
    }

    public function reject(string $reason): bool
    {
        if (!$this->canBeRejected()) {
            return false;
        }

        $this->update([
            'status' => 'rejected',
            'rejection_reason' => $reason
        ]);

        return true;
    }

    public function resubmit(): bool
    {
        if ($this->status !== 'rejected') {
            return false;
        }

        $this->update([
            'status' => 'pending',
            'rejection_reason' => null
        ]);

        return true;
    }
}