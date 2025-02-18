<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;

class ContactFormMail extends Mailable
{
   use Queueable, SerializesModels;

   public $data;
   public $subject;

   public function __construct($subject, $data)
   {
      $this->subject = $subject;
      $this->data = $data;
   }

   public function envelope(): Envelope
   {
      return new Envelope(
         subject: $this->subject,
      );
   }

   public function content(): Content
   {
      return new Content(
         view: 'email.contact',
         with: $this->data
      );
   }

   public function attachments(): array
   {
      return [];
   }
}