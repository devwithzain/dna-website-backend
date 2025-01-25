<?php

namespace App\Http\Controllers\Api;

use App\Mail\ContactFormMail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\ContactFormRequest;

class FormController extends Controller
{
   public function sendContactForm(ContactFormRequest $request)
   {
      $data = $request->validated();
      $subject = $data['name'] . " request a service!";

      try {
         Mail::to($data['email'])->send(new ContactFormMail($subject, $data));
      } catch (\Exception $e) {
         Log::error('Failed to send contact form email: ' . $e->getMessage());
         return response()->json(['error' => 'Failed to send email. Please try again later.'], 500);
      }
      return response()->json(['success' => "Your request has been submitted successfully."], 200);
   }
}