<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Extend the enum to include new statuses
        DB::statement("ALTER TABLE `claims` MODIFY `status` ENUM(
            'pending',
            'approved',
            'rejected',
            'service_center_accepted',
            'service_center_rejected',
            'parts_approved',
            'in_progress',
            'completed',
            'location_review',
            'location_submitted'
        ) NOT NULL DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Coerce rows with the new statuses to a safe value before shrinking enum
        DB::table('claims')
            ->whereIn('status', ['location_review', 'location_submitted'])
            ->update(['status' => 'pending']);

        // Shrink the enum by removing the newly added statuses
        DB::statement("ALTER TABLE `claims` MODIFY `status` ENUM(
            'pending',
            'approved',
            'rejected',
            'service_center_accepted',
            'service_center_rejected',
            'parts_approved',
            'in_progress',
            'completed'
        ) NOT NULL DEFAULT 'pending'");
    }
}; 