<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        // สุ่มชื่อสินค้าขึ้นมา เช่น "Logitech MX Master 3S"
        $name = $this->faker->unique()->words(3, true); 

        return [
            'name' => ucwords($name),
            // ทำตัวพิมพ์เล็กและใส่ขีดกลางสำหรับ URL Web เช่น logitech-mx-master
            'slug' => Str::slug($name), 
            'description' => $this->faker->paragraph(3), // สุ่มคำอธิบายสินค้า 3 ย่อหน้า
            'price' => $this->faker->randomFloat(2, 100, 5000), // สุ่มราคาตั้งแต่ 100 ถึง 5,000 บาท (ทศนิยม 2 ตำแหน่ง)
            'sale_price' => $this->faker->optional(0.3)->randomFloat(2, 50, 2000), // 30% ของสินค้าจะมีราคาส่วนลด
            'stock' => $this->faker->numberBetween(0, 100), // สุ่มจำนวนสต็อก 0 ถึง 100 ชิ้น
            'image_url' => 'https://picsum.photos/seed/' . Str::random(5) . '/600/600', // ใช้บริการสุ่มรูปภาพฟรีมาใส่เป็นหน้าปกสินค้า
            'is_active' => true, // เปิดให้พร้อมขายทันที
        ];
    }
}