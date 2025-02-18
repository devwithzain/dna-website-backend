<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;

class NewsletterSubscribedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subscriber;

    public function __construct($subscriber)
    {
        $this->subscriber = $subscriber;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Newsletter Subscriber',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'email.newsletter_subscribed',
            with: ['subscriber' => $this->subscriber]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}