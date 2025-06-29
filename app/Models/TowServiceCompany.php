<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class TowServiceCompany extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'phone',
        'password',
        'legal_name',
        'commercial_register',
        'tax_number',
        'daily_capacity',
        'delegate_number',
        'company_logo',
        'office_location_lat',
        'office_location_lng',
        'office_address',
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
        'daily_capacity' => 'integer',
        'office_location_lat' => 'decimal:8',
        'office_location_lng' => 'decimal:8'
    ];

    // Relations
    public function additionalPhones()
    {
        return $this->hasMany(TowServiceCompanyPhone::class);
    }

    public function primaryPhone()
    {
        return $this->hasOne(TowServiceCompanyPhone::class)->where('is_primary', true);
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

    public function getFormattedPhoneAttribute()
    {
        $phone = $this->phone;
        if (strlen($phone) == 11 && substr($phone, 0, 2) == '01') {
            return substr($phone, 0, 4) . ' ' . substr($phone, 4, 3) . ' ' . substr($phone, 7);
        }
        return $phone;
    }

    public function getLocationUrlAttribute()
    {
        if ($this->office_location_lat && $this->office_location_lng) {
            return "https://maps.google.com/?q={$this->office_location_lat},{$this->office_location_lng}";
        }
        return null;
    }

    public function getLogoUrlAttribute()
    {
        if (!$this->company_logo) {
            return asset('images/default-tow-company-logo.png');
        }
        
        return asset('storage/' . $this->company_logo);
    }

    public function hasCompleteProfile()
    {
        return !empty($this->legal_name) && 
               !empty($this->commercial_register) && 
               !empty($this->office_address);
    }

    // Custom method to get user type for shared auth
    public function getUserTypeAttribute()
    {
        return 'company';
    }

    // Override the username for authentication
    public function getAuthIdentifierName()
    {
        return 'phone';
    }
}