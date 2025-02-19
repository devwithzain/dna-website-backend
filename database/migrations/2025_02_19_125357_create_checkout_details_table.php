<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('checkout_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone');
            $table->string('email');
            $table->string('country');
            $table->string('street_address');
            $table->string('town_city');
            $table->string('state');
            $table->string('zip')->nullable();
            $table->boolean('agreed_terms')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('checkout_details');
    }
};