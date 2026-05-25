<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use App\Models\ProductImage; // อย่าลืมเรียกใช้ Model รูปภาพย่อยตรงนี้ด้วยครับ
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. สร้างผู้ใช้งานทดสอบ
        if (!User::where('email', 'test@example.com')->exists()) {
            User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);
        }

        // 2. สร้างสินค้าพร้อมรูปย่อยและวิดีโอ
        // เราจะใช้คอลแบ็ก ->has() ของ Laravel เพื่อสั่งให้ปั๊มรูปภาพย่อยผูกไปด้วยทันทีชิ้นละ 3 รูป
        Product::factory(20)
            ->has(ProductImage::factory()->count(3), 'images') 
            ->create([
                // สุ่มแปะลิงก์วิดีโอ YouTube รีวิวยอดนิยมลงไปเพื่อทดสอบหน้าจอ (ใช้รูปแบบ /embed/ เพื่อให้เล่นบนหน้าเว็บได้)
                'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ' 
            ]);
    }
}