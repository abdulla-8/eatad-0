<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class PartsDealer extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'phone',
        'password',
        'commercial_register',
        'tax_number',
        'legal_name',
        'specialization_id',
        'is_scrapyard_owner',
        'shop_location_lat',
        'shop_location_lng',
        'shop_address',
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
        'is_scrapyard_owner' => 'boolean',
        'shop_location_lat' => 'decimal:8',
        'shop_location_lng' => 'decimal:8'
    ];

    // Relations
    public function specialization()
    {
        return $this->belongsTo(Specialization::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function scopeScrapyardOwners($query)
    {
        return $query->where('is_scrapyard_owner', true);
    }

    public function scopeRegularDealers($query)
    {
        return $query->where('is_scrapyard_owner', false);
    }

    // Accessors
    public function getDisplayNameAttribute()
    {
        return $this->legal_name;
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

    public function getTypeAttribute()
    {
        return $this->is_scrapyard_owner ? t('admin.scrapyard_owner') : t('admin.parts_dealer');
    }

    public function getLocationUrlAttribute()
    {
        if ($this->shop_location_lat && $this->shop_location_lng) {
            return "https://maps.google.com/?q={$this->shop_location_lat},{$this->shop_location_lng}";
        }
        return null;
    }

    // Check if dealer has complete profile
    public function hasCompleteProfile()
    {
        return !empty($this->legal_name) && 
               !empty($this->commercial_register) && 
               !empty($this->shop_address);
    }

    // Get phone in formatted way
    public function getFormattedPhoneAttribute()
    {
        // Format Egyptian phone numbers
        $phone = $this->phone;
        if (strlen($phone) == 11 && substr($phone, 0, 2) == '01') {
            return substr($phone, 0, 4) . ' ' . substr($phone, 4, 3) . ' ' . substr($phone, 7);
        }
        return $phone;
    }
}