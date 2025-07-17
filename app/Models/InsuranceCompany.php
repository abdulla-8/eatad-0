<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany; // إضافة هذا

class InsuranceCompany extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'phone',
        'password',
        'commercial_register',
        'tax_number',
        'legal_name',
        'employee_count',
        'insured_cars_count',
        'office_location_lat',
        'office_location_lng',
        'office_address',
        'is_active',
        'is_approved',
        'company_slug',
        'translation_group',
        'company_logo',
        'primary_color',
        'secondary_color'
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
        'employee_count' => 'integer',
        'insured_cars_count' => 'integer',
        'office_location_lat' => 'decimal:8',
        'office_location_lng' => 'decimal:8'
    ];

    // باقي الـ methods...

    /**
     * العلاقة مع مراكز الصيانة
     */
    public function serviceCenters(): HasMany
    {
        return $this->hasMany(ServiceCenter::class, 'insurance_company_id');
    }

    /**
     * المراكز النشطة
     */
    public function activeServiceCenters(): HasMany
    {
        return $this->serviceCenters()->where('is_active', true);
    }

    /**
     * المراكز المعتمدة
     */
    public function approvedServiceCenters(): HasMany
    {
        return $this->serviceCenters()->where('is_approved', true);
    }

    // باقي الـ methods الموجودة...
    public function additionalPhones()
    {
        return $this->hasMany(InsuranceCompanyPhone::class);
    }

    public function primaryPhone()
    {
        return $this->hasOne(InsuranceCompanyPhone::class)->where('is_primary', true);
    }

    public function translationGroup()
    {
        return $this->hasMany(Translation::class, 'translation_group', 'translation_group');
    }

    public function users()
    {
        return $this->hasMany(InsuranceUser::class);
    }

    public function activeUsers()
    {
        return $this->hasMany(InsuranceUser::class)->where('is_active', true);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function getDisplayNameAttribute()
    {
        return $this->legal_name;
    }

    public function getCompanyRouteAttribute()
    {
        return $this->company_slug ?: 'insurance';
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

    public function getLocationUrlAttribute()
    {
        if ($this->office_location_lat && $this->office_location_lng) {
            return "https://maps.google.com/?q={$this->office_location_lat},{$this->office_location_lng}";
        }
        return null;
    }

    public function getFormattedPhoneAttribute()
    {
        $phone = $this->phone;
        if (strlen($phone) == 11 && substr($phone, 0, 2) == '01') {
            return substr($phone, 0, 4) . ' ' . substr($phone, 4, 3) . ' ' . substr($phone, 7);
        }
        return $phone;
    }

    public function getAllPhonesAttribute()
    {
        $phones = collect([$this->phone]);
        $additional = $this->additionalPhones->pluck('phone');
        return $phones->merge($additional)->unique()->values();
    }

    public function hasCompleteProfile()
    {
        return !empty($this->legal_name) &&
            !empty($this->commercial_register) &&
            !empty($this->office_address);
    }

    public function getSizeCategoryAttribute()
    {
        if (!$this->employee_count) return t('admin.not_specified');

        if ($this->employee_count < 50) return t('admin.small_company');
        if ($this->employee_count < 200) return t('admin.medium_company');
        return t('admin.large_company');
    }

    public function getLogoUrlAttribute()
    {
        if (!$this->company_logo) {
            return asset('images/default-company-logo.png');
        }

        return asset('storage/' . $this->company_logo);
    }

    public function getPrimaryColorAttribute($value)
    {
        return $value ?: '#10B981';
    }

    public function getSecondaryColorAttribute($value)
    {
        return $value ?: '#059669';
    }

    public function getUsersCountAttribute()
    {
        return $this->users()->count();
    }

    public function getActiveUsersCountAttribute()
    {
        return $this->users()->where('is_active', true)->count();
    }

    public function getInactiveUsersCountAttribute()
    {
        return $this->users()->where('is_active', false)->count();
    }

    public function claims()
    {
        return $this->hasMany(Claim::class);
    }

    public function pendingClaims()
    {
        return $this->hasMany(Claim::class)->where('status', 'pending');
    }

    public function complaints()
    {
        return $this->morphMany(Complaint::class, 'complainant');
    }
}
