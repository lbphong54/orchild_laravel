<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('restaurant_tables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->integer('min_capacity');
            $table->integer('max_capacity');
            $table->enum('status', ['available', 'occupied', 'reserved'])->default('available');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('restaurant_tables');
    }
}; 