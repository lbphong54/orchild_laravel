<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('restaurants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->string('name', 100);
            $table->json('avatar')->nullable();
            $table->string('address', 255);
            $table->string('phone', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('price_range')->nullable();
            $table->text('summary')->nullable();
            $table->text('suggestion')->nullable();
            $table->text('description')->nullable();
            $table->text('regulation')->nullable();
            $table->string('parking_info', 255)->nullable();
            $table->json('amenities')->nullable();
            $table->json('opening_hours')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('inactive');
            $table->json('images')->nullable();
            $table->json('menu_images')->nullable();
            $table->float('rating')->default(5);

            // Thông tin ngân hàng
            $table->string('bank_code', 50)->nullable();
            $table->string('bank_account_number', 50)->nullable();
            $table->unsignedInteger('deposit_adult')->nullable();
            $table->unsignedInteger('deposit_child')->nullable();
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('restaurants');
    }
};