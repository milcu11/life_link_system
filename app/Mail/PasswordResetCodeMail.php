<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PasswordResetCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $userName;
    public $resetCode;
    public $expirationMinutes;

    /**
     * Create a new message instance.
     */
    public function __construct(string $userName, string $resetCode, int $expirationMinutes = 10)
    {
        $this->userName = $userName;
        $this->resetCode = $resetCode;
        $this->expirationMinutes = $expirationMinutes;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Password Reset Code - LifeLink Blood Donation System',
            from: new Address(
                env('MAIL_FROM_ADDRESS', 'noreply@lifelink.com'),
                env('MAIL_FROM_NAME', 'LifeLink')
            )
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.password-reset-code',
            with: [
                'userName' => $this->userName,
                'resetCode' => $this->resetCode,
                'expirationMinutes' => $this->expirationMinutes,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
