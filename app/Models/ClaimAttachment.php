<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ClaimAttachment extends Model
{
    protected $fillable = [
        'claim_id',
        'type',
        'file_path',
        'file_name',
        'file_size',
        'mime_type'
    ];

    // Relations
    public function claim(): BelongsTo
    {
        return $this->belongsTo(Claim::class);
    }

    // Accessors
    public function getFileUrlAttribute()
    {
        return Storage::disk('public')->url($this->file_path);
    }

    public function getFileSizeFormattedAttribute()
    {
        if (!$this->file_size) {
            return 'Unknown';
        }

        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function getTypeDisplayNameAttribute()
    {
        $types = [
            'policy_image' => 'Policy Image',
            'registration_form' => 'Registration Form/Chassis Number',
            'repair_receipt' => 'Repair Receipt',
            'damage_report' => 'Damage Report (Najm)',
            'estimation_report' => 'Estimation Report'
        ];

        return $types[$this->type] ?? $this->type;
    }

    public function isImage(): bool
    {
        return str_starts_with($this->mime_type, 'image/');
    }

    public function isPdf(): bool
    {
        return $this->mime_type === 'application/pdf';
    }

    public function getIconAttribute()
    {
        if ($this->isImage()) {
            return 'photo';
        }
        
        if ($this->isPdf()) {
            return 'document-text';
        }
        
        return 'document';
    }

    // Methods
    public function deleteFile(): bool
    {
        if ($this->file_path && Storage::disk('public')->exists($this->file_path)) {
            return Storage::disk('public')->delete($this->file_path);
        }
        
        return true;
    }

    // Events
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($attachment) {
            $attachment->deleteFile();
        });
    }

    // في موديل ClaimAttachment


}