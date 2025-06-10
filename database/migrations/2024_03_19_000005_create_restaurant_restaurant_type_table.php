<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('restaurant_restaurant_type', function (Blueprint $table) {
            $table->foreignId('restaurant_id')->constrained('restaurants')->onDelete('cascade');
            $table->foreignId('restaurant_type_id')->constrained('restaurant_types')->onDelete('cascade');
            $table->primary(['restaurant_id', 'restaurant_type_id']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('restaurant_restaurant_type');
    }
}; 