<?php
// Path: app/Services/ClaimService.php

namespace App\Services;

use App\Models\Claim;
use App\Models\ClaimAttachment;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ClaimService
{
    /**
     * Create a new claim with attachments
     */
    public function createClaim(array $claimData, array $files = []): Claim
    {
        return DB::transaction(function() use ($claimData, $files) {
            $claim = Claim::create($claimData);
            
            // Handle file uploads
            $this->processAttachments($claim, $files);
            
            return $claim->load('attachments');
        });
    }

    /**
     * Update an existing claim with new attachments
     */
    public function updateClaim(Claim $claim, array $claimData, array $files = []): Claim
    {
        return DB::transaction(function() use ($claim, $claimData, $files) {
            $claim->update($claimData);
            
            // Handle new file uploads
            $this->processAttachments($claim, $files);
            
            return $claim->load('attachments');
        });
    }

    /**
     * Process file attachments for a claim
     */
    public function processAttachments(Claim $claim, array $files): void
    {
        $allowedTypes = ['policy_image', 'registration_form', 'repair_receipt', 'damage_report', 'estimation_report'];
        
        foreach ($allowedTypes as $type) {
            if (isset($files[$type])) {
                $uploadedFiles = $files[$type];
                
                // Handle both single files and arrays
                if (!is_array($uploadedFiles)) {
                    $uploadedFiles = [$uploadedFiles];
                }
                
                foreach ($uploadedFiles as $file) {
                    if ($file instanceof UploadedFile && $file->isValid()) {
                        $this->storeAttachment($claim, $file, $type);
                    }
                }
            }
        }
    }

    /**
     * Store a single attachment
     */
    public function storeAttachment(Claim $claim, UploadedFile $file, string $type): ClaimAttachment
    {
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $size = $file->getSize();
        $mimeType = $file->getMimeType();
        
        // Generate unique filename
        $filename = sprintf(
            '%d_%s_%d_%s.%s',
            $claim->id,
            $type,
            time(),
            uniqid(),
            $extension
        );
        
        // Store file
        $path = $file->storeAs(
            "claims/{$claim->id}/{$type}",
            $filename,
            'public'
        );
        
        // Create attachment record
        return ClaimAttachment::create([
            'claim_id' => $claim->id,
            'type' => $type,
            'file_path' => $path,
            'file_name' => $originalName,
            'file_size' => $size,
            'mime_type' => $mimeType
        ]);
    }

    /**
     * Delete an attachment and its file
     */
    public function deleteAttachment(ClaimAttachment $attachment): bool
    {
        // Delete the file from storage
        if ($attachment->file_path && Storage::disk('public')->exists($attachment->file_path)) {
            Storage::disk('public')->delete($attachment->file_path);
        }
        
        // Delete the record
        return $attachment->delete();
    }

    /**
     * Approve a claim and assign service center
     */
    public function approveClaim(Claim $claim, int $serviceCenterId, string $notes = null): bool
    {
        if (!$claim->canBeApproved()) {
            return false;
        }

        return DB::transaction(function() use ($claim, $serviceCenterId, $notes) {
            $updateData = [
                'status' => 'approved',
                'service_center_id' => $serviceCenterId,
            ];

            // Offer tow service if vehicle is not working
            if (!$claim->is_vehicle_working) {
                $updateData['tow_service_offered'] = true;
            }

            if ($notes) {
                $updateData['notes'] = $notes;
            }

            return $claim->update($updateData);
        });
    }

    /**
     * Reject a claim with reason
     */
    public function rejectClaim(Claim $claim, string $reason): bool
    {
        if (!$claim->canBeRejected()) {
            return false;
        }

        return $claim->update([
            'status' => 'rejected',
            'rejection_reason' => $reason
        ]);
    }

    /**
     * Resubmit a rejected claim
     */
    public function resubmitClaim(Claim $claim): bool
    {
        if ($claim->status !== 'rejected') {
            return false;
        }

        return $claim->update([
            'status' => 'pending',
            'rejection_reason' => null
        ]);
    }

    /**
     * Update tow service response
     */
    public function updateTowServiceResponse(Claim $claim, bool $accepted): bool
    {
        if ($claim->status !== 'approved' || $claim->tow_service_offered !== true) {
            return false;
        }

        return $claim->update([
            'tow_service_accepted' => $accepted
        ]);
    }

    /**
     * Get claim statistics for a company
     */
    public function getCompanyStats(int $companyId): array
    {
        return [
            'total' => Claim::forCompany($companyId)->count(),
            'pending' => Claim::forCompany($companyId)->pending()->count(),
            'approved' => Claim::forCompany($companyId)->approved()->count(),
            'rejected' => Claim::forCompany($companyId)->rejected()->count(),
            'this_month' => Claim::forCompany($companyId)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'today' => Claim::forCompany($companyId)
                ->whereDate('created_at', today())
                ->count()
        ];
    }

    /**
     * Get user claim statistics
     */
    public function getUserStats(int $userId): array
    {
        return [
            'total' => Claim::forUser($userId)->count(),
            'pending' => Claim::forUser($userId)->pending()->count(),
            'approved' => Claim::forUser($userId)->approved()->count(),
            'rejected' => Claim::forUser($userId)->rejected()->count(),
        ];
    }

    /**
     * Validate file before upload
     */
    public function validateFile(UploadedFile $file, string $type): array
    {
        $errors = [];
        
        // Check file size (5MB max)
        if ($file->getSize() > 5242880) {
            $errors[] = 'File size must not exceed 5MB';
        }
        
        // Check mime type
        $allowedMimes = ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'];
        if (!in_array($file->getMimeType(), $allowedMimes)) {
            $errors[] = 'File must be an image (JPEG, PNG, JPG) or PDF';
        }
        
        // Check file extension
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf'];
        if (!in_array(strtolower($file->getClientOriginalExtension()), $allowedExtensions)) {
            $errors[] = 'Invalid file extension';
        }
        
        return $errors;
    }
}