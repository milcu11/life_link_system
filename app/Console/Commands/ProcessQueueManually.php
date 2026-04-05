<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessQueueManually extends Command
{
    protected $signature = 'queue:process-manual {--limit=10}';
    protected $description = 'Manually process queued jobs (temporary debugging)';

    public function handle()
    {
        Log::info('Starting manual queue processing');
        
        $limit = $this->option('limit');
        $this->info("Processing up to {$limit} jobs...");
        
        // Use Artisan command to process jobs once
        $code = \Illuminate\Support\Facades\Artisan::call('queue:work', [
            '--max-jobs' => $limit,
            '--max-time' => 60,
            '--stop-when-empty' => true,
        ]);
        
        $remaining = DB::table('jobs')->count();
        $this->info("Queue processing completed. Remaining jobs: {$remaining}");
        
        return $code;
    }
}
