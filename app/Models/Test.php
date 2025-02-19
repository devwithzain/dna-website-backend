<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Test extends Model
{
   use HasFactory;
   protected $table = 'tests';
   protected $fillable = ['title', 'short_description', 'description', 'image'];
}