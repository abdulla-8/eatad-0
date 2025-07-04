<?php
// Path: app/Models/TowServiceCapacity.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TowServiceCapacity extends Model
{
    protected $fillable = [
        'provider_type',
        'provider_id',
        'date',
        'total_capacity',
        'used_capacity',
        'available_capacity',
        'is_available'
    ];

    protected $casts = [
        'date' => 'date',
        'total_capacity' => 'integer',
        'used_capacity' => 'integer',
        'available_capacity' => 'integer',
        'is_available' => 'boolean'
    ];

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
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true)
            ->where('available_capacity', '>', 0);
    }

    public function scopeForDate($query, $date)
    {
        return $query->where('date', $date);
    }

    public function scopeForProvider($query, $providerType, $providerId)
    {
        return $query->where('provider_type', $providerType)
            ->where('provider_id', $providerId);
    }

    public function scopeToday($query)
    {
        return $query->where('date', today());
    }

    // Accessors
    public function getCapacityPercentageAttribute()
    {
        if ($this->total_capacity == 0) {
            return 0;
        }

        return round(($this->used_capacity / $this->total_capacity) * 100, 2);
    }

    public function getStatusBadgeAttribute()
    {
        if (!$this->is_available) {
            return ['class' => 'bg-red-100 text-red-800', 'text' => 'Unavailable'];
        }

        if ($this->available_capacity == 0) {
            return ['class' => 'bg-red-100 text-red-800', 'text' => 'Full'];
        }

        if ($this->capacity_percentage >= 80) {
            return ['class' => 'bg-yellow-100 text-yellow-800', 'text' => 'Almost Full'];
        }

        return ['class' => 'bg-green-100 text-green-800', 'text' => 'Available'];
    }

    public function getProviderNameAttribute()
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

    // Methods
    public function hasCapacity($quantity = 1)
    {
        return $this->is_available && $this->available_capacity >= $quantity;
    }

    public function reserve($quantity = 1)
    {
        if (!$this->hasCapacity($quantity)) {
            return false;
        }

        $this->increment('used_capacity', $quantity);
        $this->decrement('available_capacity', $quantity);

        // Update availability status
        if ($this->available_capacity <= 0) {
            $this->update(['is_available' => false]);
        }

        return true;
    }

    public function release($quantity = 1)
    {
        $this->decrement('used_capacity', $quantity);
        $this->increment('available_capacity', $quantity);

        // Make sure we don't go below 0
        if ($this->used_capacity < 0) {
            $this->update(['used_capacity' => 0]);
        }

        if ($this->available_capacity > $this->total_capacity) {
            $this->update(['available_capacity' => $this->total_capacity]);
        }

        // Update availability status
        if ($this->available_capacity > 0) {
            $this->update(['is_available' => true]);
        }

        return true;
    }

    public function reset()
    {
        $this->update([
            'used_capacity' => 0,
            'available_capacity' => $this->total_capacity,
            'is_available' => $this->total_capacity > 0
        ]);
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

    // Static methods
    public static function getOrCreateForProvider($providerType, $providerId, $date = null)
    {
        $date = $date ?: today();

        return static::firstOrCreate([
            'provider_type' => $providerType,
            'provider_id' => $providerId,
            'date' => $date
        ], [
            'total_capacity' => static::getDefaultCapacity($providerType, $providerId),
            'used_capacity' => 0,
            'available_capacity' => static::getDefaultCapacity($providerType, $providerId),
            'is_available' => true
        ]);
    }

    protected static function getDefaultCapacity($providerType, $providerId)
    {
        $provider = match($providerType) {
            'service_center' => ServiceCenter::find($providerId),
            'tow_company' => TowServiceCompany::find($providerId),
            'individual' => TowServiceIndividual::find($providerId),
            default => null
        };

        if (!$provider) {
            return 0;
        }

        return match($providerType) {
            'service_center' => $provider->daily_tow_capacity ?? 10,
            'tow_company' => $provider->daily_capacity ?? 50,
            'individual' => 1, // Individuals usually have capacity of 1
            default => 0
        };
    }

    public static function checkCapacity($providerType, $providerId, $date = null)
    {
        $capacity = static::getOrCreateForProvider($providerType, $providerId, $date);
        return $capacity->hasCapacity();
    }

    public static function reserveCapacity($providerType, $providerId, $quantity = 1, $date = null)
    {
        $capacity = static::getOrCreateForProvider($providerType, $providerId, $date);
        return $capacity->reserve($quantity);
    }

    public static function releaseCapacity($providerType, $providerId, $quantity = 1, $date = null)
    {
        $capacity = static::getOrCreateForProvider($providerType, $providerId, $date);
        return $capacity->release($quantity);
    }
}