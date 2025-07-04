<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TowOffer extends Model
{
    protected $fillable = [
        'tow_request_id',
        'provider_type',
        'provider_id',
        'stage',
        'offer_time',
        'expires_at',
        'status',
        'response_time',
        'estimated_pickup_time',
        'rejection_reason',
        'notes'
    ];

    protected $casts = [
        'offer_time' => 'datetime',
        'expires_at' => 'datetime',
        'response_time' => 'datetime',
        'estimated_pickup_time' => 'datetime'
    ];

    // Relations
    public function towRequest(): BelongsTo
    {
        return $this->belongsTo(TowRequest::class);
    }

    // Dynamic relation to get the provider
    public function provider()
    {
        return match($this->provider_type) {
            'service_center' => $this->belongsTo(ServiceCenter::class, 'provider_id'),
            'tow_company' => $this->belongsTo(TowServiceCompany::class, 'provider_id'),
            'individual' => $this->belongsTo(TowServiceIndividual::class, 'provider_id'),
            default => null
        };
    }

    public function serviceCenter(): BelongsTo
    {
        return $this->belongsTo(ServiceCenter::class, 'provider_id');
    }

    public function towCompany(): BelongsTo
    {
        return $this->belongsTo(TowServiceCompany::class, 'provider_id');
    }

    public function individual(): BelongsTo
    {
        return $this->belongsTo(TowServiceIndividual::class, 'provider_id');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeExpired($query)
    {
        return $query->where('status', 'expired');
    }

    public function scopeForStage($query, $stage)
    {
        return $query->where('stage', $stage);
    }

    public function scopeForProvider($query, $providerType, $providerId)
    {
        return $query->where('provider_type', $providerType)
            ->where('provider_id', $providerId);
    }

    // Accessors
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => ['class' => 'bg-yellow-100 text-yellow-800', 'text' => 'Pending'],
            'accepted' => ['class' => 'bg-green-100 text-green-800', 'text' => 'Accepted'],
            'rejected' => ['class' => 'bg-red-100 text-red-800', 'text' => 'Rejected'],
            'expired' => ['class' => 'bg-gray-100 text-gray-800', 'text' => 'Expired']
        ];

        return $badges[$this->status] ?? $badges['pending'];
    }

    public function getProviderTypeDisplayAttribute()
    {
        return match($this->provider_type) {
            'service_center' => 'Service Center',
            'tow_company' => 'Tow Company',
            'individual' => 'Individual',
            default => $this->provider_type
        };
    }

    public function getStageDisplayAttribute()
    {
        return match($this->stage) {
            'service_center' => 'Service Centers Stage',
            'tow_companies' => 'Tow Companies Stage',
            'individuals' => 'Individuals Stage',
            default => $this->stage
        };
    }

    public function getIsExpiredAttribute()
    {
        return $this->expires_at && $this->expires_at->isPast() && $this->status === 'pending';
    }

    public function getResponseTimeFormattedAttribute()
    {
        if (!$this->response_time) {
            return null;
        }

        return $this->response_time->diffForHumans($this->offer_time);
    }

    // Methods
    public function accept($estimatedPickupTime = null, $notes = null)
    {
        if ($this->status !== 'pending') {
            return false;
        }

        $this->update([
            'status' => 'accepted',
            'response_time' => now(),
            'estimated_pickup_time' => $estimatedPickupTime ?: now()->addHour(),
            'notes' => $notes
        ]);

        // Update the tow request
        $this->towRequest->assign(
            $this->provider_type,
            $this->provider_id,
            $this->estimated_pickup_time
        );

        return true;
    }

    public function reject($reason = null)
    {
        if ($this->status !== 'pending') {
            return false;
        }

        $this->update([
            'status' => 'rejected',
            'response_time' => now(),
            'rejection_reason' => $reason
        ]);

        return true;
    }

    public function expire()
    {
        if ($this->status !== 'pending') {
            return false;
        }

        $this->update([
            'status' => 'expired',
            'response_time' => now()
        ]);

        return true;
    }

    public function getProviderModel()
    {
        return match($this->provider_type) {
            'service_center' => ServiceCenter::find($this->provider_id),
            'tow_company' => TowServiceCompany::find($this->provider_id),
            'individual' => TowServiceIndividual::find($this->provider_id),
            default => null
        };
    }

    public function getProviderName()
    {
        $provider = $this->getProviderModel();
        
        if (!$provider) {
            return 'Unknown Provider';
        }

        return match($this->provider_type) {
            'service_center' => $provider->legal_name,
            'tow_company' => $provider->legal_name,
            'individual' => $provider->full_name,
            default => 'Unknown'
        };
    }

    public function getProviderPhone()
    {
        $provider = $this->getProviderModel();
        
        if (!$provider) {
            return null;
        }

        return $provider->formatted_phone ?? $provider->phone;
    }
}