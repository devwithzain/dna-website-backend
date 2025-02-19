<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CheckoutDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'first_name',
        'last_name',
        'phone',
        'email',
        'country',
        'street_address',
        'town_city',
        'state',
        'zip',
        'agreed_terms',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}