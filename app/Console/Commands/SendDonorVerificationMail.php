<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Donor;
use App\Mail\DonorVerificationStatus;
use Illuminate\Support\Facades\Mail;

class SendDonorVerificationMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:send-donor-verification {email} {--approve=1}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a donor verification email to a specified address for testing';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $approved = (bool) $this->option('approve');

        $donor = Donor::first();

        if (!$donor) {
            $this->error('No donors found in the database. Please create a donor first.');
            return 1;
        }

        Mail::to($email)->send(new DonorVerificationStatus($donor, $approved));

        $this->info("Donor verification email sent to {$email} (approved={$approved}).");

        return 0;
    }
}
