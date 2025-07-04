<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\TowServiceController;

class ProcessExpiredTowStages extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'tow:process-expired-stages';

    /**
     * The console command description.
     */
    protected $description = 'Process expired tow service stages and move to next stage automatically';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚚 Starting to process expired tow service stages...');
        
        try {
            $controller = app(\App\Http\Controllers\TowServiceController::class);
            $response = $controller->processExpiredStages();
            $data = $response->getData(true);
            
            if ($data['success']) {
                $processedCount = $data['processed_requests'] ?? 0;
                
                if ($processedCount > 0) {
                    $this->info("✅ Successfully processed {$processedCount} expired tow requests.");
                    $this->info("📋 Moved requests to next stage in the cycle.");
                    
                    // Log the activity
                    \Log::info("Tow Service Cron: Processed {$processedCount} expired requests", [
                        'timestamp' => now(),
                        'processed_count' => $processedCount
                    ]);
                } else {
                    $this->info("ℹ️  No expired tow requests found to process.");
                }
            } else {
                $this->error('❌ Failed to process expired stages.');
                $errorMessage = $data['error'] ?? 'Unknown error occurred';
                $this->error("Error: {$errorMessage}");
                
                // Log the error
                \Log::error("Tow Service Cron Error: {$errorMessage}", [
                    'timestamp' => now(),
                    'response_data' => $data
                ]);
            }
        } catch (\Exception $e) {
            $this->error('❌ Exception occurred: ' . $e->getMessage());
            $this->error('Stack trace: ' . $e->getTraceAsString());
            
            // Log the exception
            \Log::error('Tow Service Cron Exception', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return Command::FAILURE;
        }
        
        return Command::SUCCESS;
    }
}