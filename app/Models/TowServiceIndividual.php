<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class TowServiceIndividual extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'phone',
        'password',
        'full_name',
        'national_id',
        'tow_truck_form',
        'tow_truck_plate_number',
        'profile_image',
        'location_lat',
        'location_lng',
        'address',
        'is_active',
        'is_approved'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
        'is_approved' => 'boolean',
        'location_lat' => 'decimal:8',
        'location_lng' => 'decimal:8'
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    // Accessors
    public function getDisplayNameAttribute()
    {
        return $this->full_name;
    }

    public function getStatusBadgeAttribute()
    {
        if (!$this->is_active) {
            return ['class' => 'bg-red-100 text-red-800', 'text' => t('admin.inactive')];
        }
        
        if (!$this->is_approved) {
            return ['class' => 'bg-yellow-100 text-yellow-800', 'text' => t('admin.pending_approval')];
        }
        
        return ['class' => 'bg-green-100 text-green-800', 'text' => t('admin.active')];
    }

    public function getFormattedPhoneAttribute()
    {
        $phone = $this->phone;
        if (strlen($phone) == 11 && substr($phone, 0, 2) == '01') {
            return substr($phone, 0, 4) . ' ' . substr($phone, 4, 3) . ' ' . substr($phone, 7);
        }
        return $phone;
    }

    public function getFormattedNationalIdAttribute()
    {
        $id = $this->national_id;
        if (strlen($id) == 14) {
            return substr($id, 0, 3) . ' ' . substr($id, 3, 3) . ' ' . substr($id, 6, 4) . ' ' . substr($id, 10, 3) . ' ' . substr($id, 13, 1);
        }
        return $id;
    }

    public function getLocationUrlAttribute()
    {
        if ($this->location_lat && $this->location_lng) {
            return "https://maps.google.com/?q={$this->location_lat},{$this->location_lng}";
        }
        return null;
    }

    public function getProfileImageUrlAttribute()
    {
        if (!$this->profile_image) {
            return asset('images/default-profile.png');
        }
        
        return asset('storage/' . $this->profile_image);
    }

    public function getTowTruckFormUrlAttribute()
    {
        if (!$this->tow_truck_form) {
            return null;
        }
        
        return asset('storage/' . $this->tow_truck_form);
    }

    public function hasCompleteProfile()
    {
        return !empty($this->full_name) && 
               !empty($this->national_id) && 
               !empty($this->tow_truck_plate_number);
    }

    // Custom method to get user type for shared auth
    public function getUserTypeAttribute()
    {
        return 'individual';
    }

    // Override the username for authentication
    public function getAuthIdentifierName()
    {
        return 'phone';
    }
}