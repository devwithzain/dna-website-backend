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

   public $name;
   public $email;
   public $phone;
   public $postcode;
   public $tvsize;
   public $specialRequest;
   public $subject;


   public function __construct($subject, $userDetails, )
   {
      $this->name = $userDetails['name'];
      $this->email = $userDetails['email'];
      $this->phone = $userDetails['phone'];
      $this->postcode = $userDetails['postcode'];
      $this->tvsize = $userDetails['tvsize'];
      $this->specialRequest = $userDetails['specialRequest'];
      $this->subject = $subject;
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
         with: [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'postcode' => $this->postcode,
            'tvsize' => $this->tvsize,
            'specialRequest' => $this->specialRequest,
         ],
      );
   }
   public function attachments(): array
   {
      return [];
   }
}