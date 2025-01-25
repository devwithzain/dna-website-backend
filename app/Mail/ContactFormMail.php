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
   public $selectedOption;
   public $selectedDate;
   public $specialRequest;
   public $subject;
   public function __construct($subject, $userDetails)
   {
      $this->name = $userDetails['name'];
      $this->email = $userDetails['email'];
      $this->phone = $userDetails['phone'] ?? 'Not Provided';
      $this->specialRequest = $userDetails['specialRequest'] ?? 'No Message';
      $this->subject = $subject;
      $this->selectedOption = $userDetails['selectedOption'] ?? 'Not Selected';
      $this->selectedDate = $userDetails['selectedDate'] ?? 'Not Provided';
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
            'selectedOption' => $this->selectedOption,
            'selectedDate' => $this->selectedDate,
            'specialRequest' => $this->specialRequest,
         ],
      );
   }
   public function attachments(): array
   {
      return [];
   }
}