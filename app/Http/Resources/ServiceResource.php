<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
{
   public function toArray(Request $request): array
   {
      return [
         'id' => $this->id,
         'title' => $this->title,
         'description' => $this->description,
         'price' => $this->price,
         'image' => $this->image,
         'shipping' => $this->shipping,
         'options' => $this->options,
         'created_at' => $this->created_at->toDateTimeString(),
      ];
   }
}