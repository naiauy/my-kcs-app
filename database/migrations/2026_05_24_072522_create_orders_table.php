<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->text('shipping_address');
            $table->string('payment_method')->default('qr_code'); // วิธีชำระเงิน เช่น qr_code, transfer
            $table->decimal('total_amount', 10, 2);
            $table->string('status')->default('pending'); // สถานะ: pending, paid, shipped, cancelled
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
