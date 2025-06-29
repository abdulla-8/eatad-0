<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class InsuranceUser extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'insurance_company_id',
        'phone',
        'password',
        'full_name',
        'national_id',
        'policy_number',
        'is_active'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean'
    ];

    public function insuranceCompany()
    {
        return $this->belongsTo(InsuranceCompany::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    public function getFormattedPhoneAttribute()
    {
        $phone = $this->phone;
        if (strlen($phone) == 11 && substr($phone, 0, 2) == '01') {
            return substr($phone, 0, 4) . ' ' . substr($phone, 4, 3) . ' ' . substr($phone, 7);
        }
        return $phone;
    }

    public function getStatusBadgeAttribute()
    {
        if ($this->is_active) {
            return ['class' => 'bg-green-100 text-green-800', 'text' => t('admin.active')];
        }
        
        return ['class' => 'bg-red-100 text-red-800', 'text' => t('admin.inactive')];
    }

    public function getFormattedNationalIdAttribute()
    {
        $id = $this->national_id;
        if (strlen($id) == 14) {
            return substr($id, 0, 3) . ' ' . substr($id, 3, 3) . ' ' . substr($id, 6, 4) . ' ' . substr($id, 10, 3) . ' ' . substr($id, 13, 1);
        }
        return $id;
    }

    public function getAuthIdentifierName()
    {
        return 'phone';
    }

    public function claims()
{
    return $this->hasMany(Claim::class);
}


}