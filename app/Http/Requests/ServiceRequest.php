<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceRequest extends FormRequest
{
   public function rules(): array
   {
      return [
         'title' => 'required|string|max:258',
         'description' => 'required|string',
         'price' => 'required|string',
         'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
         'shipping' => 'required|string',
         'options' => 'nullable|array',
         'options.*.title' => 'required|string|max:255',
      ];
   }
   public function messages()
   {
      return [
         'title.required' => 'The title field is required!',
         'description.required' => 'The description field is required!',
         'price.required' => 'The price field is required!',
         'image.required' => 'The image field is required!',
         'shipping.required' => 'The shipping field is required!',
         'options.required' => 'The options field is required!',
      ];
   }
}