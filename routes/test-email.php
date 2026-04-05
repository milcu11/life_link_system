<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

// Test email sending
Route::get('/test-email', function () {
    try {
        Log::info('Starting email test');
        
        // Test 1: Check SMTP config
        $config = config('mail');
        Log::info('Mail config', [
            'mailer' => $config['mailer'] ?? 'not set',
            'host' => config('mail.mailers.smtp.host') ?? 'not set',
            'port' => config('mail.mailers.smtp.port') ?? 'not set',
            'encryption' => config('mail.mailers.smtp.encryption') ?? 'not set',
        ]);
        
        // Test 2: Try sending email synchronously
        Log::info('Attempting to send test email synchronously');
        Mail::raw('This is a test email from LifeLink on ' . now(), function ($message) {
            $message->to('mickeyalaz6@gmail.com')
                    ->subject('LifeLink Test Email - Sync Send');
        });
        
        return response()->json([
            'status' => 'Email sent successfully (sync)',
            'timestamp' => now(),
            'config' => [
                'mailer' => config('mail.mailer'),
                'host' => config('mail.mailers.smtp.host'),
                'port' => config('mail.mailers.smtp.port'),
                'encryption' => config('mail.mailers.smtp.encryption'),
            ]
        ]);
    } catch (\Exception $e) {
        Log::error('Email test failed: ' . $e->getMessage(), ['exception' => $e]);
        return response()->json([
            'status' => 'Email failed',
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
});

// Check queued jobs
Route::get('/check-queue', function () {
    try {
        $jobs = DB::table('jobs')->count();
        $failed = DB::table('failed_jobs')->count();
        
        $recentJobs = DB::table('jobs')
            ->select('id', 'queue', 'payload', 'attempts', 'created_at')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        return response()->json([
            'total_jobs' => $jobs,
            'failed_jobs' => $failed,
            'recent_jobs' => $recentJobs,
            'queue_connection' => config('queue.default'),
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage()
        ], 500);
    }
});

// Test queue by queueing a test email
Route::get('/test-queue-email', function () {
    try {
        Log::info('Queuing test email');
        
        Mail::queue(new class extends Illuminate\Mail\Mailable {
            use Illuminate\Queue\SerializesModels;
            
            public function build()
            {
                return $this->to('mickeyalaz6@gmail.com')
                           ->subject('LifeLink Test Email - Queue Send')
                           ->view('emails.test')
                           ->with(['message' => 'This is a queued test email sent at ' . now()]);
            }
        });
        
        $jobCount = DB::table('jobs')->count();
        Log::info('Test email queued, total jobs: ' . $jobCount);
        
        return response()->json([
            'status' => 'Email queued successfully',
            'total_jobs_in_queue' => $jobCount,
            'queue_connection' => config('queue.default'),
        ]);
    } catch (\Exception $e) {
        Log::error('Queue test failed: ' . $e->getMessage());
        return response()->json([
            'status' => 'Queue failed',
            'error' => $e->getMessage()
        ], 500);
    }
});
