<?php

namespace App\Mail;

use App\Models\DonorAppeal;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AppealReceived extends Mailable
{
    use Queueable, SerializesModels;

    public $appeal;

    public function __construct(DonorAppeal $appeal)
    {
        $this->appeal = $appeal;
    }

    public function build()
    {
        return $this->subject('We received your appeal')
                    ->view('emails.appeal_received');
    }
}
