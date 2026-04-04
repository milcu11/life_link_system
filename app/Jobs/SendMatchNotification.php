<?php

namespace App\Jobs;

use App\Mail\MatchNotification;
use App\Models\Matching;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;

class SendMatchNotification implements ShouldQueue
{
    use Queueable;

    public Matching $match;

    /**
     * Create a new job instance.
     */
    public function __construct(Matching $match)
    {
        $this->match = $match;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Send email notification to donor
            Mail::send(new MatchNotification($this->match));

            // Update the notified_at timestamp
            $this->match->update([
                'notified_at' => now(),
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to send match notification', [
                'match_id' => $this->match->id,
                'error' => $e->getMessage(),
            ]);
            
            // Rethrow to use Laravel's built-in retry mechanism
            throw $e;
        }
    }
}
