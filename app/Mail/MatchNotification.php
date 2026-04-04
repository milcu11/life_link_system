<?php

namespace App\Mail;

use App\Models\Matching;
use App\Models\BloodRequest;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MatchNotification extends Mailable
{
    use SerializesModels;

    public Matching $match;
    public BloodRequest $request;

    /**
     * Create a new message instance.
     */
    public function __construct(Matching $match)
    {
        $this->match = $match;
        $this->request = $match->request;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $donor = $this->match->donor;
        $hospital = $this->request->hospital;

        return $this->to($donor->user->email)
                    ->subject("Urgent: Blood Donation Request for {$this->request->blood_type}")
                    ->view('emails.match_notification')
                    ->with([
                        'match' => $this->match,
                        'request' => $this->request,
                        'donor' => $donor,
                        'hospital' => $hospital,
                    ]);
    }
}
