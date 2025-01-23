<?php

namespace App\Models;

use App\Models\Service;
use Illuminate\Database\Eloquent\Model;
class ServiceOptions extends Model
{
   protected $table = 'services_options';
   protected $fillable = ['services_id', 'title'];
   public function service()
   {
      return $this->belongsTo(Service::class);
   }
}