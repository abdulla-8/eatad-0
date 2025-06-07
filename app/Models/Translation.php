<?php
// app/Models/Translation.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    protected $fillable = [
        'language_id', 'translation_key', 'translation_value'
    ];

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public function scopeForLanguage($query, $languageId)
    {
        return $query->where('language_id', $languageId);
    }

    public function scopeByKey($query, $key)
    {
        return $query->where('translation_key', $key);
    }
}