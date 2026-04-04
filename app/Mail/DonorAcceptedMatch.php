<?php

namespace App\Mail;

use App\Models\Matching;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DonorAcceptedMatch extends Mailable
{
    use SerializesModels;

    public Matching $match;

    /**
     * Create a new message instance.
     */
    public function __construct(Matching $match)
    {
        $this->match = $match;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $donor = $this->match->donor;
        $request = $this->match->request;
        $hospital = $request->hospital;

        return $this->to($hospital->email)
                    ->subject("✅ Donor Accepted Your Blood Request for {$request->blood_type}")
                    ->view('emails.donor_accepted_match')
                    ->with([
                        'match' => $this->match,
                        'donor' => $donor,
                        'request' => $request,
                        'hospital' => $hospital,
                    ]);
    }
}
