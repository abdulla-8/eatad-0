<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    use HasFactory;

    protected $fillable = [
        'language_id',
        'group',
        'key',
        'value'
    ];

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public function scopeByGroup($query, $group)
    {
        return $query->where('group', $group);
    }

    public function scopeByLanguage($query, $languageId)
    {
        return $query->where('language_id', $languageId);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('key', 'like', '%' . $search . '%')
              ->orWhere('value', 'like', '%' . $search . '%');
        });
    }
}