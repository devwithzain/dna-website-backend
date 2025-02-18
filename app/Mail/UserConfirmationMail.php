<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;

class UserConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;

    public function __construct($userDetails)
    {
        $this->name = $userDetails['name'];
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Thank You for Your Request!',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'email.user_confirmation',
            with: [
                'name' => $this->name,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
