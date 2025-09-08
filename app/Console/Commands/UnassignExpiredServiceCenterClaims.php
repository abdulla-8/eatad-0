<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Claim;
use Illuminate\Support\Facades\Log;

class UnassignExpiredServiceCenterClaims extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'claims:unassign-expired';

    /**
     * The console command description.
     */
    protected $description = 'Unassign service center from claims whose assignment expired and reset status to pending';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('⏱  Processing expired service center assignments for claims...');

        $processed = 0;

        Claim::whereNotNull('service_center_id')
            ->where('status', 'pending')
            ->whereNotNull('service_center_expires_at')
            ->where('service_center_expires_at', '<=', now())
            ->chunkById(200, function ($claims) use (&$processed) {
                foreach ($claims as $claim) {
                    $claim->update([
                        'service_center_id' => null,
                        'service_center_expires_at' => null,
                        'status' => 'pending',
                    ]);
                    $processed++;
                }
            });

        if ($processed > 0) {
            $this->info("✅ Unassigned {$processed} expired claim assignments.");
            Log::info('Claims cron: unassigned expired service center claims', [
                'processed' => $processed,
                'timestamp' => now(),
            ]);
        } else {
            $this->info('ℹ️  No expired claim assignments found.');
        }

        return Command::SUCCESS;
    }
} 