<?php

// app/Models/IndustrialArea.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IndustrialArea extends Model
{
    protected $fillable = [
        'name',
        'name_ar',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function serviceCenters()
    {
        return $this->hasMany(ServiceCenter::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc');
    }

    public function getDisplayNameAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->name_ar : $this->name;
    }
}