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
        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            // สร้าง Foreign Key เชื่อมไปยังตาราง products ถ้าสินค้าถูกลบ ให้ลบรูปภาพทั้งหมดทิ้งด้วย (onDelete cascade)
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('image_url'); // เก็บพาธหรือลิงก์รูปภาพเพิ่มเติม
            $table->integer('sort_order')->default(0); // จัดลำดับการแสดงผลรูปภาพ (เช่น รูปที่ 1, 2, 3)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_images');
    }
};
