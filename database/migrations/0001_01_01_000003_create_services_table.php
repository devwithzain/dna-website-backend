<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('price');
            $table->string('shipping');
            $table->string('image');
            $table->text('description');
            $table->timestamps();
        });
        Schema::create('services_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('services_id')->constrained('services')->onDelete('cascade');
            $table->string('title');
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('services');
        Schema::dropIfExists('services_options');
    }
};