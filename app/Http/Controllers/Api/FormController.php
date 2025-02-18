<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\ContactFormRequest;
use App\Mail\ContactFormMail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class FormController extends Controller
{
   public function sendContactForm(ContactFormRequest $request)
   {
      $data = $request->validate([
         'name' => 'required|string|max:255',
         'email' => 'required|email|max:255',
         'phone' => 'nullable|string|max:20',
         'specialRequest' => 'nullable|string',
         'agreeToTerms' => 'nullable|boolean',
         'specialOffer' => 'nullable|boolean',
      ]);

      $subject = "New Appointment Request from " . $data['name'];

      try {
         Mail::to(config('mail.from.address'))->send(new ContactFormMail($subject, $data));
      } catch (\Exception $e) {
         Log::error('Failed to send contact form email: ' . $e->getMessage());
         return response()->json(['error' => 'Failed to send email. Please try again later.'], 500);
      }
      return response()->json(['success' => "Your request has been submitted successfully."], 200);
   }
}