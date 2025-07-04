<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()->constrained('customers');
            $table->foreignId('restaurant_id')->constrained('restaurants');
            $table->dateTime('reservation_time');
            $table->integer('num_adults');
            $table->integer('num_children')->default(0);
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending');
            $table->string('special_request', 255)->nullable();
            $table->boolean('is_paid')->default(false);
            $table->decimal('amount', 12, 0)->nullable(); // Số tiền đặt (decimal cho tiền tệ)
            $table->boolean('confirm_paid')->nullable(); // Xác nhận đã thanh toán
            $table->dateTime('cancelled_at')->nullable(); // Thời gian hủy
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reservations');
    }
};
