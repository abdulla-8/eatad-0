<?php
// Path: app/Models/TowRequest.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TowRequest extends Model
{
    protected $fillable = [
        'claim_id',
        'request_code',
        'pickup_location_lat',
        'pickup_location_lng',
        'pickup_location_address',
        'dropoff_location_lat',
        'dropoff_location_lng',
        'dropoff_location_address',
        'status',
        'current_stage',
        'stage_started_at',
        'stage_expires_at',
        'auto_assigned',
        'assigned_provider_type',
        'assigned_provider_id',
        'tracking_url',
        'pickup_code',
        'delivery_code',
        'estimated_pickup_time',
        'actual_pickup_time',
        'estimated_delivery_time',
        'actual_delivery_time',
        'rejection_reason',
        'notes'
    ];

    protected $casts = [
        'pickup_location_lat' => 'decimal:8',
        'pickup_location_lng' => 'decimal:8',
        'dropoff_location_lat' => 'decimal:8',
        'dropoff_location_lng' => 'decimal:8',
        'auto_assigned' => 'boolean',
        'stage_started_at' => 'datetime',
        'stage_expires_at' => 'datetime',
        'estimated_pickup_time' => 'datetime',
        'actual_pickup_time' => 'datetime',
        'estimated_delivery_time' => 'datetime',
        'actual_delivery_time' => 'datetime'
    ];

    // Relations
    public function claim(): BelongsTo
    {
        return $this->belongsTo(Claim::class);
    }

    public function offers(): HasMany
    {
        return $this->hasMany(TowOffer::class);
    }

    public function pendingOffers(): HasMany
    {
        return $this->hasMany(TowOffer::class)->where('status', 'pending');
    }

    public function acceptedOffer(): BelongsTo
    {
        return $this->belongsTo(TowOffer::class, 'id', 'tow_request_id')
            ->where('status', 'accepted');
    }



    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeAssigned($query)
    {
        return $query->where('status', 'assigned');
    }

    public function scopeInCurrentStage($query, $stage)
    {
        return $query->where('current_stage', $stage);
    }

    public function scopeExpired($query)
    {
        return $query->where('stage_expires_at', '<=', now())
            ->where('status', 'pending');
    }

    // Accessors
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => ['class' => 'bg-yellow-100 text-yellow-800', 'text' => 'Pending'],
            'assigned' => ['class' => 'bg-blue-100 text-blue-800', 'text' => 'Assigned'],
            'in_transit_to_pickup' => ['class' => 'bg-purple-100 text-purple-800', 'text' => 'Going to Pickup'],
            'arrived_at_pickup' => ['class' => 'bg-orange-100 text-orange-800', 'text' => 'At Pickup'],
            'vehicle_loaded' => ['class' => 'bg-indigo-100 text-indigo-800', 'text' => 'Vehicle Loaded'],
            'in_transit_to_dropoff' => ['class' => 'bg-blue-100 text-blue-800', 'text' => 'In Transit'],
            'delivered' => ['class' => 'bg-green-100 text-green-800', 'text' => 'Delivered'],
            'cancelled' => ['class' => 'bg-red-100 text-red-800', 'text' => 'Cancelled'],
            'rejected' => ['class' => 'bg-red-100 text-red-800', 'text' => 'Rejected'],
            'expired' => ['class' => 'bg-gray-100 text-gray-800', 'text' => 'Expired']
        ];

        return $badges[$this->status] ?? $badges['pending'];
    }

    public function getStageBadgeAttribute()
    {
        $badges = [
            'service_center' => ['class' => 'bg-green-100 text-green-800', 'text' => 'Service Centers'],
            'tow_companies' => ['class' => 'bg-blue-100 text-blue-800', 'text' => 'Tow Companies'],
            'individuals' => ['class' => 'bg-purple-100 text-purple-800', 'text' => 'Individuals'],
            'completed' => ['class' => 'bg-gray-100 text-gray-800', 'text' => 'Completed'],
            'expired' => ['class' => 'bg-red-100 text-red-800', 'text' => 'Expired']
        ];

        return $badges[$this->current_stage] ?? $badges['service_center'];
    }

    public function getTrackingUrlAttribute()
    {
        return route('tow.track', $this->tracking_url);
    }

    public function getTimeRemainingAttribute()
    {
        if (!$this->stage_expires_at || $this->status !== 'pending') {
            return null;
        }

        $remaining = $this->stage_expires_at->diffInMinutes(now(), false);
        return $remaining > 0 ? 0 : abs($remaining);
    }

    public function getIsExpiredAttribute()
    {
        return $this->stage_expires_at && $this->stage_expires_at->isPast() && $this->status === 'pending';
    }

    // Methods
    public function moveToNextStage()
    {
        // Reject all pending offers in current stage
        $this->offers()->where('status', 'pending')->update(['status' => 'expired']);

        // Determine next stage
        $nextStage = match($this->current_stage) {
            'service_center' => 'tow_companies',
            'tow_companies' => 'individuals',
            'individuals' => 'service_center', // Back to service centers
            default => 'expired'
        };

        if ($nextStage === 'expired') {
            $this->update([
                'status' => 'expired',
                'current_stage' => 'expired'
            ]);
            return false;
        }

        // Update stage
        $this->update([
            'current_stage' => $nextStage,
            'stage_started_at' => now(),
            'stage_expires_at' => now()->addMinutes($this->getStageTimeout($nextStage))
        ]);

        return true;
    }

    public function getStageTimeout($stage)
    {
        return match($stage) {
            'service_center' => 30, // 30 minutes
            'tow_companies' => 20,  // 20 minutes
            'individuals' => 15,    // 15 minutes
            default => 30
        };
    }

    public function assign($providerType, $providerId, $estimatedPickupTime = null)
    {
        $this->update([
            'status' => 'assigned',
            'assigned_provider_type' => $providerType,
            'assigned_provider_id' => $providerId,
            'estimated_pickup_time' => $estimatedPickupTime ?: now()->addHour(),
            'pickup_code' => rand(10000, 99999),
            'delivery_code' => rand(10000, 99999)
        ]);

        // Reject all other pending offers
        $this->offers()->where('status', 'pending')->update(['status' => 'rejected']);
    }

    public function getAssignedProvider()
    {
        if (!$this->assigned_provider_type || !$this->assigned_provider_id) {
            return null;
        }

        return match($this->assigned_provider_type) {
            'service_center' => ServiceCenter::find($this->assigned_provider_id),
            'tow_company' => TowServiceCompany::find($this->assigned_provider_id),
            'individual' => TowServiceIndividual::find($this->assigned_provider_id),
            default => null
        };
    }
}