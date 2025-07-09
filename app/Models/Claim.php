<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Claim extends Model
{
    protected $fillable = [
        'insurance_user_id',
        'insurance_company_id',
        'policy_number',
        'vehicle_plate_number',
        'chassis_number',
        'vehicle_brand',
        'vehicle_type',
        'vehicle_model',
        'vehicle_location',
        'vehicle_location_lat',
        'vehicle_location_lng',
        'is_vehicle_working',
        'repair_receipt_ready',
        'status',
        'rejection_reason',
        'service_center_id',
        'tow_request_id',
        'tow_service_offered',
        'tow_service_accepted',
        'notes',
        'customer_delivery_code',
        'vehicle_arrived_at_center',
        'inspection_status',
        'service_center_note'
    ];

    protected $casts = [
        'is_vehicle_working' => 'boolean',
        'repair_receipt_ready' => 'boolean',
        'tow_service_offered' => 'boolean',
        'tow_service_accepted' => 'boolean',
        'vehicle_location_lat' => 'decimal:8',
        'vehicle_location_lng' => 'decimal:8',
        'vehicle_arrived_at_center' => 'datetime'
    ];

    // Relations
    public function insuranceUser(): BelongsTo
    {
        return $this->belongsTo(InsuranceUser::class);
    }

    public function insuranceCompany(): BelongsTo
    {
        return $this->belongsTo(InsuranceCompany::class);
    }

    public function serviceCenter(): BelongsTo
    {
        return $this->belongsTo(ServiceCenter::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(ClaimAttachment::class);
    }

    public function towRequest(): HasOne
    {
        return $this->hasOne(TowRequest::class);
    }

    public function inspection(): HasOne
    {
        return $this->hasOne(ClaimInspection::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeForCompany($query, $companyId)
    {
        return $query->where('insurance_company_id', $companyId);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('insurance_user_id', $userId);
    }

    // Accessors
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => ['class' => 'bg-yellow-100 text-yellow-800', 'text' => 'Pending'],
            'approved' => ['class' => 'bg-green-100 text-green-800', 'text' => 'Approved'],
            'rejected' => ['class' => 'bg-red-100 text-red-800', 'text' => 'Rejected'],
            'service_center_accepted' => ['class' => 'bg-blue-100 text-blue-800', 'text' => 'Accepted by Service Center'],
            'service_center_rejected' => ['class' => 'bg-red-100 text-red-800', 'text' => 'Rejected by Service Center'],
            'in_progress' => ['class' => 'bg-blue-100 text-blue-800', 'text' => 'In Progress'],
            'completed' => ['class' => 'bg-gray-100 text-gray-800', 'text' => 'Completed']
        ];

        return $badges[$this->status] ?? $badges['pending'];
    }

    /**
     * حالة تُعرَض للمستخدم التأميني فقط
     */
    public function getUserStatusAttribute(): string
    {
        // لو مركز الصيانة لم يقبل بعد (الطلب معتمد من التأمين لكن لم يوافق المركز)
        if ($this->status === 'approved') {
            return 'pending';
        }

        // في باقي الحالات نعيد الـ status الحقيقي
        return $this->status;
    }

    /**
     * بادج اللون للمستخدم التأميني
     */
    public function getUserStatusBadgeAttribute(): array
    {
        $status = $this->user_status;   // accessor السابق
        
        $badges = [
            'pending' => ['class' => 'bg-yellow-100 text-yellow-800', 'text' => 'Pending'],
            'service_center_accepted' => ['class' => 'bg-green-100 text-green-800', 'text' => 'Accepted'],
            'service_center_rejected' => ['class' => 'bg-red-100 text-red-800', 'text' => 'Rejected'],
            'rejected' => ['class' => 'bg-red-100 text-red-800', 'text' => 'Rejected'],
            'in_progress' => ['class' => 'bg-blue-100 text-blue-800', 'text' => 'In Progress'],
            'completed' => ['class' => 'bg-gray-100 text-gray-800', 'text' => 'Completed']
        ];

        return $badges[$status] ?? $badges['pending'];
    }

    public function getVehicleLocationUrlAttribute()
    {
        if ($this->vehicle_location_lat && $this->vehicle_location_lng) {
            return "https://maps.google.com/?q={$this->vehicle_location_lat},{$this->vehicle_location_lng}";
        }
        return null;
    }

    public function getClaimNumberAttribute()
    {
        return 'CLM-' . str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }

    // Methods
    public function hasRequiredAttachments(): bool
    {
        $required = ['damage_report', 'estimation_report'];
        $hasVehicleInfo = $this->vehicle_plate_number || $this->chassis_number;
        
        if (!$hasVehicleInfo) {
            $required[] = 'registration_form';
        }

        foreach ($required as $type) {
            if (!$this->attachments()->where('type', $type)->exists()) {
                return false;
            }
        }

        return true;
    }

    public function getAttachmentsByType(string $type)
    {
        return $this->attachments()->where('type', $type)->get();
    }

    public function canBeApproved(): bool
    {
        return $this->status === 'pending' && $this->hasRequiredAttachments();
    }

    public function canBeRejected(): bool
    {
        return $this->status === 'pending';
    }

    public function approve($serviceCenterId = null): bool
    {
        if (!$this->canBeApproved()) {
            return false;
        }

        $this->update([
            'status' => 'approved',
            'service_center_id' => $serviceCenterId,
            'tow_service_offered' => !$this->is_vehicle_working ? true : null,
            'inspection_status' => 'pending' // إضافة حالة الفحص
        ]);

        return true;
    }

    public function reject(string $reason): bool
    {
        if (!$this->canBeRejected()) {
            return false;
        }

        $this->update([
            'status' => 'rejected',
            'rejection_reason' => $reason
        ]);

        return true;
    }

    public function resubmit(): bool
    {
        if ($this->status !== 'rejected') {
            return false;
        }

        $this->update([
            'status' => 'pending',
            'rejection_reason' => null
        ]);

        return true;
    }

    /**
     * إنتاج كود التسليم للعميل
     */
    public function generateCustomerDeliveryCode()
    {
        $this->customer_delivery_code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $this->save();
        return $this->customer_delivery_code;
    }

    /**
     * تأكيد وصول السيارة لمركز الصيانة
     */
    public function markVehicleArrived()
    {
        $this->vehicle_arrived_at_center = now();
        $this->save();
        return true;
    }

    /**
     * فحص إمكانية بدء الفحص
     */
    public function canStartInspection()
    {
        return $this->vehicle_arrived_at_center !== null && 
               $this->status === 'approved' && 
               $this->inspection_status === 'pending';
    }

    /**
     * فحص إمكانية بدء العمل
     */
    public function canStartWork()
    {
        return $this->status === 'approved' && 
               $this->inspection_status === 'completed';
    }

    /**
     * فحص إذا كان العميل يحتاج لإظهار كود التسليم
     */
    public function shouldShowCustomerDeliveryCode()
    {
        return $this->status === 'approved' && 
               $this->tow_service_accepted === false && 
               $this->customer_delivery_code;
    }

    /**
     * فحص إذا كان مركز الصيانة يحتاج لزر التحقق من التسليم
     */
    public function shouldShowDeliveryVerificationButton()
    {
        return $this->status === 'approved' && 
               !$this->vehicle_arrived_at_center && 
               (
                   // السيارة لا تعمل والعميل رفض السطحة
                   (!$this->is_vehicle_working && $this->tow_service_accepted === false) ||
                   // السيارة تعمل وظهر للعميل كود التسليم
                   ($this->is_vehicle_working && $this->customer_delivery_code)
               );
    }

    /**
     * فحص إذا كان مركز الصيانة يحتاج لزر تأكيد الوصول العادي
     */
    public function shouldShowMarkArrivedButton()
    {
        return $this->status === 'approved' && 
               !$this->vehicle_arrived_at_center && 
               (
                   // السيارة تعمل ولم يتم إنتاج كود تسليم
                   ($this->is_vehicle_working && !$this->customer_delivery_code) ||
                   // تم قبول خدمة السطحة
                   ($this->tow_service_accepted === true)
               );
    }
}
