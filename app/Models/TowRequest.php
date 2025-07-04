<?php
// Path: app/Models/TowRequest.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

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
        'notes',
        'customer_verification_code',
        'service_center_verification_code',
        'driver_tracking_token'
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

    public function acceptedOffer(): HasMany
    {
        return $this->hasMany(TowOffer::class)->where('status', 'accepted');
    }

    public function tracking(): HasMany
    {
        return $this->hasMany(TowTracking::class);
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
            'customer_verified' => ['class' => 'bg-green-100 text-green-800', 'text' => 'Customer Verified'],
            'vehicle_loaded' => ['class' => 'bg-indigo-100 text-indigo-800', 'text' => 'Vehicle Loaded'],
            'in_transit_to_dropoff' => ['class' => 'bg-blue-100 text-blue-800', 'text' => 'In Transit'],
            'delivered' => ['class' => 'bg-green-100 text-green-800', 'text' => 'Delivered'],
            'service_center_received' => ['class' => 'bg-teal-100 text-teal-800', 'text' => 'Under Inspection'],
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

    public function getDriverTrackingUrlAttribute()
    {
        if (!$this->driver_tracking_token) {
            return null;
        }
        
        return route('driver.tracking', $this->driver_tracking_token);
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
        $nextStage = match ($this->current_stage) {
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
        return match ($stage) {
            'service_center' => 0.5, // 30 minutes
            'tow_companies' => 0.5,  // 20 minutes
            'individuals' => 0.5,    // 15 minutes
            default => 30
        };
    }

    public function assign($providerType, $providerId, $estimatedPickupTime = null)
    {
        // Generate verification codes
        $customerCode = $this->generateVerificationCode(5);
        $serviceCenterCode = $this->generateVerificationCode(6);
        $driverToken = Str::random(32);

        $this->update([
            'status' => 'assigned',
            'assigned_provider_type' => $providerType,
            'assigned_provider_id' => $providerId,
            'estimated_pickup_time' => $estimatedPickupTime ?: now()->addHour(),
            'customer_verification_code' => $customerCode,
            'service_center_verification_code' => $serviceCenterCode,
            'driver_tracking_token' => $driverToken
        ]);

        // Reject all other pending offers
        $this->offers()->where('status', 'pending')->update(['status' => 'rejected']);
    }

    public function updateStatus($status)
    {
        $this->update(['status' => $status]);
        
        // Log tracking info
        if ($this->assigned_provider_type && $this->assigned_provider_id) {
            TowTracking::create([
                'tow_request_id' => $this->id,
                'driver_lat' => 0, // Will be updated with real location later
                'driver_lng' => 0,
                'timestamp' => now(),
                'status' => $status
            ]);
        }
    }

    public function verifyCustomerCode($code)
    {
        if ($this->customer_verification_code === $code && $this->status === 'arrived_at_pickup') {
            $this->update([
                'status' => 'customer_verified',
                'actual_pickup_time' => now()
            ]);
            return true;
        }
        return false;
    }

    public function verifyServiceCenterCode($code)
    {
        if ($this->service_center_verification_code === $code && $this->status === 'delivered') {
            $this->update([
                'status' => 'service_center_received',
                'actual_delivery_time' => now()
            ]);
            return true;
        }
        return false;
    }

    private function generateVerificationCode($length)
    {
        return str_pad(random_int(0, pow(10, $length) - 1), $length, '0', STR_PAD_LEFT);
    }

    public function getAssignedProvider()
    {
        if (!$this->assigned_provider_type || !$this->assigned_provider_id) {
            return null;
        }

        return match ($this->assigned_provider_type) {
            'service_center' => ServiceCenter::find($this->assigned_provider_id),
            'tow_company' => TowServiceCompany::find($this->assigned_provider_id),
            'individual' => TowServiceIndividual::find($this->assigned_provider_id),
            default => null
        };
    }

    public function getProviderContactInfo()
    {
        $provider = $this->getAssignedProvider();
        
        if (!$provider) {
            return null;
        }

        return [
            'name' => match($this->assigned_provider_type) {
                'service_center' => $provider->legal_name,
                'tow_company' => $provider->legal_name,
                'individual' => $provider->full_name,
                default => 'Unknown'
            },
            'phone' => $provider->formatted_phone ?? $provider->phone,
            'type' => $this->assigned_provider_type,
            'location' => [
                'lat' => match($this->assigned_provider_type) {
                    'service_center' => $provider->center_location_lat,
                    'tow_company' => $provider->office_location_lat,
                    'individual' => $provider->location_lat,
                    default => null
                },
                'lng' => match($this->assigned_provider_type) {
                    'service_center' => $provider->center_location_lng,
                    'tow_company' => $provider->office_location_lng,
                    'individual' => $provider->location_lng,
                    default => null
                },
                'address' => match($this->assigned_provider_type) {
                    'service_center' => $provider->center_address,
                    'tow_company' => $provider->office_address,
                    'individual' => $provider->address,
                    default => null
                }
            ]
        ];
    }
}