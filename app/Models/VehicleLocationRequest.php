<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class VehicleLocationRequest extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'claim_id',
        'insurance_user_id',
        'public_hash',
        'city',
        'district',
        'notes',
        'location_lat',
        'location_lng',
        'is_completed',
        'completed_at'
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'completed_at' => 'datetime',
        'location_lat' => 'decimal:8',
        'location_lng' => 'decimal:8'
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->public_hash)) {
                $model->public_hash = Str::random(32);
            }
        });
    }

    public function claim(): BelongsTo
    {
        return $this->belongsTo(Claim::class);
    }

    public function insuranceUser(): BelongsTo
    {
        return $this->belongsTo(InsuranceUser::class);
    }

    public function getPublicUrlAttribute(): string
    {
        return route('vehicle.location.form', $this->public_hash);
    }

    public function scopeActive($query)
    {
        return $query->where('is_completed', false);
    }

    public function scopeCompleted($query)
    {
        return $query->where('is_completed', true);
    }
} 