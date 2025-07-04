<?php
// Path: app/Console/Commands/ProcessExpiredTowStages.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\TowServiceController;

class ProcessExpiredTowStages extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'tow:process-expired';

    /**
     * The console command description.
     */
    protected $description = 'Process expired tow service stages and move to next stage';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Processing expired tow service stages...');
        
        $controller = app(TowServiceController::class);
        $response = $controller->processExpiredStages();
        $data = $response->getData(true);
        
        if ($data['success']) {
            $this->info("Processed {$data['processed_requests']} expired tow requests.");
        } else {
            $this->error('Failed to process expired stages.');
        }
        
        return 0;
    }
}