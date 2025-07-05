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
        'inspected_by'
    ];

    protected $casts = [
        'required_parts' => 'array',
        'vehicle_year' => 'integer'
    ];

    public function claim(): BelongsTo
    {
        return $this->belongsTo(Claim::class);
    }

    public function serviceCenter(): BelongsTo
    {
        return $this->belongsTo(ServiceCenter::class, 'inspected_by');
    }
}