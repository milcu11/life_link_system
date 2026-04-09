<?php

namespace App\Mail;

use App\Models\Donor;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DonorVerificationStatus extends Mailable
{
    use Queueable, SerializesModels;

    public Donor $donor;
    public bool $approved;

    /**
     * Create a new message instance.
     */
    public function __construct(Donor $donor, bool $approved)
    {
        $this->donor = $donor;
        $this->approved = $approved;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $subject = $this->approved
            ? 'Your donor registration has been approved'
            : 'Your donor registration has been rejected';

        return $this->to($this->donor->user->email)
                    ->subject($subject)
                    ->view('emails.donor_verification')
                    ->with([
                        'donor'            => $this->donor,
                        'approved'         => $this->approved,
                        'rejectionReason'  => $this->donor->rejection_reason ?? null,
                    ]);
    }
}
