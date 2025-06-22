<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reservation_tables', function (Blueprint $table) {
            $table->foreignId('reservation_id')->constrained('reservations')->onDelete('cascade');
            $table->foreignId('restaurant_table_id')->constrained('restaurant_tables')->onDelete('cascade');
            $table->dateTime('from_time');
            $table->dateTime('to_time');
            $table->primary(['reservation_id', 'restaurant_table_id']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reservation_tables');
    }
}; 