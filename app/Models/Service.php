<?php

namespace App\Models;

use App\Models\ServiceOptions;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
   protected $table = 'services';
   protected $fillable = ['title', 'description', 'price', 'shipping', 'image'];
   public function options()
   {
      return $this->hasMany(ServiceOptions::class, 'services_id');
   }
}