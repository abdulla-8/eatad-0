<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // إضافة هذا

class ServiceCenter extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'insurance_company_id',
        'created_by_company',
        'phone',
        'password',
        'commercial_register',
        'tax_number',
        'legal_name',
        'center_slug',
        'translation_group',
        'center_logo',
        'profile_picture',
        'primary_color',
        'secondary_color',
        'industrial_area_id',
        'service_specialization_id',
        'body_work_technicians',
        'mechanical_technicians',
        'painting_technicians',
        'electrical_technicians',
        'other_technicians',
        'has_tow_service',
        'tow_trucks_count',
        'daily_tow_capacity',
        'center_area_sqm',
        'center_location_lat',
        'center_location_lng',
        'center_address',
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
        'created_by_company' => 'boolean',
        'is_approved' => 'boolean',
        'body_work_technicians' => 'integer',
        'mechanical_technicians' => 'integer',
        'painting_technicians' => 'integer',
        'electrical_technicians' => 'integer',
        'other_technicians' => 'integer',
        'center_area_sqm' => 'decimal:2',
        'center_location_lat' => 'decimal:8',
        'center_location_lng' => 'decimal:8',
        'has_tow_service' => 'boolean',
        'tow_trucks_count' => 'integer',
        'daily_tow_capacity' => 'integer',
        'classification',
        'classification_photo', 
        'classification_rating'
    ];

    /**
     * العلاقة مع شركة التأمين
     */
    public function insuranceCompany(): BelongsTo
    {
        return $this->belongsTo(InsuranceCompany::class, 'insurance_company_id');
    }

    /**
     * المراكز المنشأة بواسطة شركات التأمين
     */
    public function scopeCreatedByCompany($query)
    {
        return $query->where('created_by_company', true);
    }

    /**
     * المراكز الخاصة بشركة تأمين محددة
     */
    public function scopeForInsuranceCompany($query, $companyId)
    {
        return $query->where('insurance_company_id', $companyId);
    }

    /**
     * تحديد Route Key للـ Model Binding
     */
    public function getRouteKeyName()
    {
        return 'id';
    }

    /**
     * تحسين Route Model Binding
     */
    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where('id', $value)
            ->where('created_by_company', true)
            ->firstOrFail();
    }

    // باقي الـ Relations
    public function industrialArea()
    {
        return $this->belongsTo(IndustrialArea::class);
    }

    public function serviceSpecialization()
    {
        return $this->belongsTo(ServiceSpecialization::class);
    }

    public function additionalPhones()
    {
        return $this->hasMany(ServiceCenterPhone::class);
    }

    public function primaryPhone()
    {
        return $this->hasOne(ServiceCenterPhone::class)->where('is_primary', true);
    }

    public function translationGroup()
    {
        return $this->hasMany(Translation::class, 'translation_group', 'translation_group');
    }

    // باقي الـ Methods...
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

    public function getCenterRouteAttribute()
    {
        return $this->center_slug ?: 'service';
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
        if ($this->center_location_lat && $this->center_location_lng) {
            return "https://maps.google.com/?q={$this->center_location_lat},{$this->center_location_lng}";
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

    public function getTotalTechniciansAttribute()
    {
        return $this->body_work_technicians +
            $this->mechanical_technicians +
            $this->painting_technicians +
            $this->electrical_technicians +
            $this->other_technicians;
    }

    public function getTechniciansBreakdownAttribute()
    {
        return [
            'body_work' => $this->body_work_technicians,
            'mechanical' => $this->mechanical_technicians,
            'painting' => $this->painting_technicians,
            'electrical' => $this->electrical_technicians,
            'other' => $this->other_technicians,
            'total' => $this->total_technicians
        ];
    }

    public function getCenterSizeCategoryAttribute()
    {
        if (!$this->center_area_sqm) return t('admin.not_specified');

        if ($this->center_area_sqm < 200) return t('admin.small_center');
        if ($this->center_area_sqm < 500) return t('admin.medium_center');
        return t('admin.large_center');
    }

    public function getLogoUrlAttribute()
    {
        if (!$this->center_logo) {
            return asset('images/default-service-center-logo.png');
        }

        return asset('storage/' . $this->center_logo);
    }

    public function getPrimaryColorAttribute($value)
    {
        return $value ?: '#10B981';
    }

    public function getSecondaryColorAttribute($value)
    {
        return $value ?: '#059669';
    }

    public function hasCompleteProfile()
    {
        return !empty($this->legal_name) &&
            !empty($this->commercial_register) &&
            !empty($this->center_address) &&
            !empty($this->industrial_area_id);
    }

    public function claims()
    {
        return $this->hasMany(Claim::class);
    }

    public function activeClaims()
    {
        return $this->hasMany(Claim::class)->whereIn('status', ['approved', 'in_progress']);
    }

    public function complaints()
    {
        return $this->morphMany(Complaint::class, 'complainant');
    }

    // Add accessor for classification status
public function getClassificationStatusAttribute()
{
    return $this->classification === 'classified' ? 'مصنف' : 'غير مصنف';
}

// Add accessor for rating stars
public function getRatingStarsAttribute()
{
    if (!$this->classification_rating) return '';
    
    $stars = '';
    for ($i = 1; $i <= 5; $i++) {
        $stars .= $i <= $this->classification_rating ? '★' : '☆';
    }
    return $stars;
}
}
