<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('restaurant_amenities', function (Blueprint $table) {
            $table->foreignId('restaurant_id')->constrained('restaurants')->onDelete('cascade');
            $table->foreignId('amenity_id')->constrained('amenities')->onDelete('cascade');
            $table->boolean('value')->default(true);
            $table->primary(['restaurant_id', 'amenity_id']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('restaurant_amenities');
    }
}; 