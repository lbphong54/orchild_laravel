<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('restaurants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->string('name', 100);
            $table->string('address', 255);
            $table->string('phone', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->text('summary')->nullable();
            $table->text('suggestion')->nullable();
            $table->text('description')->nullable();
            $table->text('regulation')->nullable();
            $table->string('parking_info', 255)->nullable();
            $table->json('amenities')->nullable();
            $table->json('opening_hours')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('restaurants');
    }
}; 