<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // Primary Key ID สินค้า
            $table->string('name'); // ชื่อสินค้า
            $table->string('slug')->unique(); // URL สวยๆ สำหรับฝั่ง Web เช่น iphone-15-pro (ต้องห้ามซ้ำ)
            $table->text('description')->nullable(); // รายละเอียดสินค้า (ใส่ nullable คือเว้นว่างได้)
            
            // ราคา: แนะนำใช้ decimal แทน float เพื่อความแม่นยำของทศนิยมในระบบการเงิน
            $table->decimal('price', 10, 2); 
            $table->decimal('sale_price', 10, 2)->nullable(); // ราคาส่วนลด (ถ้ามี)
            
            $table->integer('stock')->default(0); // จำนวนสต็อกสินค้า (ค่าเริ่มต้นเป็น 0)
            $table->string('image_url')->nullable(); // ลิงก์รูปภาพสินค้า (สำหรับดึงไปแสดงผลบน Mobile App และ Web)
            $table->boolean('is_active')->default(true); // สถานะเปิด-ปิดการขายสินค้า
            
            $table->timestamps(); // สร้างคอลัมน์ created_at และ updated_at ให้โดยอัตโนมัติ
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};