<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Specialization extends Model
{
    protected $fillable = [
        'brand_name',
        'brand_name_ar',
        'image',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc');
    }

    // الحصول على اسم البراند حسب اللغة الحالية
    public function getDisplayNameAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->brand_name_ar : $this->brand_name;
    }

    // الحصول على مسار الصورة الكامل
    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return asset('images/default-brand.png');
        }
        
        return asset('storage/' . $this->image);
    }

    // التحقق من وجود الصورة
    public function hasImage()
    {
        return !empty($this->image) && file_exists(storage_path('app/public/' . $this->image));
    }
}