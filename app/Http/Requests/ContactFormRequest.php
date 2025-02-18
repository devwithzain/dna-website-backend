<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactFormRequest extends FormRequest
{
   public function rules()
   {
      return [
         'email' => 'required|email',
         'name' => 'required|string|max:255',
         'phone' => 'required|string|max:20',
         'specialRequest' => 'nullable|string|max:500',
      ];
   }

   public function messages()
   {
      return [
         'name.required' => 'The name field is required.',
         'email.required' => 'The email field is required.',
         'phone.required' => 'The phone number field is required.',
         'specialRequest.required' => 'The special request field is required.',
      ];
   }
}