<?php

namespace App\Models;

use App\Models\Cart;
use App\Models\ServiceOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Service extends Model
{
   use HasFactory;
   protected $table = 'services';
   protected $fillable = ['title', 'description', 'price', 'shipping', 'image'];
   public function options()
   {
      return $this->hasMany(ServiceOptions::class, 'service_id');
   }
   public function carts()
   {
      return $this->hasMany(Cart::class, 'service_id');
   }
   public function orderItems()
   {
      return $this->hasMany(OrderItem::class, 'service_id');
   }
}