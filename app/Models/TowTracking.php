<?php
// Path: app/Models/TowTracking.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TowTracking extends Model
{
    protected $fillable = [
        'tow_request_id',
        'driver_lat',
        'driver_lng',
        'timestamp',
        'speed',
        'heading',
        'status',
        'notes'
    ];

    protected $casts = [
        'driver_lat' => 'decimal:8',
        'driver_lng' => 'decimal:8',
        'timestamp' => 'datetime',
        'speed' => 'decimal:2',
        'heading' => 'decimal:2'
    ];

    // Relations
    public function towRequest(): BelongsTo
    {
        return $this->belongsTo(TowRequest::class);
    }

    // Scopes
    public function scopeRecent($query, $hours = 24)
    {
        return $query->where('timestamp', '>=', now()->subHours($hours));
    }

    public function scopeForRequest($query, $requestId)
    {
        return $query->where('tow_request_id', $requestId);
    }

    // Accessors
    public function getFormattedTimestampAttribute()
    {
        return $this->timestamp->format('M d, Y H:i:s');
    }

    public function getLocationUrlAttribute()
    {
        if ($this->driver_lat && $this->driver_lng) {
            return "https://maps.google.com/?q={$this->driver_lat},{$this->driver_lng}";
        }
        return null;
    }

    // Methods
    public static function logDriverLocation($towRequestId, $lat, $lng, $status = null, $notes = null)
    {
        return static::create([
            'tow_request_id' => $towRequestId,
            'driver_lat' => $lat,
            'driver_lng' => $lng,
            'timestamp' => now(),
            'status' => $status,
            'notes' => $notes
        ]);
    }

    public static function getLatestLocation($towRequestId)
    {
        return static::where('tow_request_id', $towRequestId)
            ->orderBy('timestamp', 'desc')
            ->first();
    }

    public static function getTrackingHistory($towRequestId, $limit = 50)
    {
        return static::where('tow_request_id', $towRequestId)
            ->orderBy('timestamp', 'desc')
            ->limit($limit)
            ->get();
    }
}