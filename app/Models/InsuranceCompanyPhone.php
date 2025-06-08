<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InsuranceCompanyPhone extends Model
{
    use HasFactory;

    protected $fillable = [
        'insurance_company_id',
        'phone',
        'label',
        'is_primary'
    ];

    protected $casts = [
        'is_primary' => 'boolean'
    ];

    // Relations
    public function insuranceCompany()
    {
        return $this->belongsTo(InsuranceCompany::class);
    }

    // Scopes
    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    public function scopeSecondary($query)
    {
        return $query->where('is_primary', false);
    }

    // Accessors
    public function getFormattedPhoneAttribute()
    {
        // Format Egyptian phone numbers
        $phone = $this->phone;
        if (strlen($phone) == 11 && substr($phone, 0, 2) == '01') {
            return substr($phone, 0, 4) . ' ' . substr($phone, 4, 3) . ' ' . substr($phone, 7);
        }
        return $phone;
    }

    public function getDisplayLabelAttribute()
    {
        return $this->label ?: ($this->is_primary ? t('admin.primary_phone') : t('admin.additional_phone'));
    }

    // Events
    protected static function boot()
    {
        parent::boot();

        // Ensure only one primary phone per company
        static::saving(function ($phone) {
            if ($phone->is_primary) {
                static::where('insurance_company_id', $phone->insurance_company_id)
                    ->where('id', '!=', $phone->id)
                    ->update(['is_primary' => false]);
            }
        });
    }
}