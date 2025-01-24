<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactFormRequest extends FormRequest
{
   public function rules()
   {
      return [
         'name' => 'required|string|max:255',
         'email' => 'required|email|max:255',
         'phone' => 'required|string|max:20',
         'postcode' => 'required|string|max:255',
         'tvsize' => 'required|string|max:255',
         'specialRequest' => 'required|string|max:255',
      ];
   }

   public function messages()
   {
      return [
         'name.required' => 'The name field is required.',
         'email.required' => 'The email number field is required.',
         'phone.required' => 'The phone number field is required.',
         'postcode.required' => 'The postcode field is required.',
         'tvsize.required' => 'The tvsize field is required.',
         'specialRequest.required' => 'The special request field is required.',
      ];
   }
}