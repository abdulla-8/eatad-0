<?php
// app/Models/Complaint.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class Complaint extends Model
{
    protected $fillable = [
        'complainant_type',
        'complainant_id',
        'complainant_name',
        'type',
        'subject',
        'description',
        'attachment_path',
        'is_read'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'is_read' => 'boolean'
    ];

    // تعريف Morph Map في constructor
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        
        Relation::morphMap([
            'insurance_company' => \App\Models\InsuranceCompany::class,
            'service_center' => \App\Models\ServiceCenter::class,
        ]);
    }

    // Polymorphic relationship
    public function complainant()
    {
        return $this->morphTo();
    }

    // Scopes
    public function scopeByType($query, $type)
    {
        if ($type) {
            return $query->where('type', $type);
        }
        return $query;
    }

    public function scopeByStatus($query, $status)
    {
        if ($status === 'read') {
            return $query->where('is_read', true);
        } elseif ($status === 'unread') {
            return $query->where('is_read', false);
        }
        return $query;
    }

    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where(function($q) use ($search) {
                $q->where('subject', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%')
                  ->orWhere('complainant_name', 'like', '%' . $search . '%');
            });
        }
        return $query;
    }

    // Accessors
    public function getTypeBadgeAttribute()
    {
        $badges = [
            'inquiry' => ['class' => 'bg-blue-100 text-blue-800'],
            'complaint' => ['class' => 'bg-yellow-100 text-yellow-800'],
            'other' => ['class' => 'bg-gray-100 text-gray-800']
        ];
        return $badges[$this->type] ?? $badges['other'];
    }

    public function getComplainantTypeBadgeAttribute()
    {
        $badges = [
            'insurance_company' => ['class' => 'bg-purple-100 text-purple-800', 'text' => 'شركة تأمين'],
            'service_center' => ['class' => 'bg-green-100 text-green-800', 'text' => 'مركز صيانة'],
        ];
        return $badges[$this->complainant_type] ?? ['class' => 'bg-gray-100 text-gray-800', 'text' => 'غير محدد'];
    }

    public function scopeForUser($query, $userType, $userId)
{
    return $query->where('complainant_type', $userType)
                 ->where('complainant_id', $userId);
}

}
