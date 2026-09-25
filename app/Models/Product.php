<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // 1. เติมบรรทัดนี้เพื่อเรียกใช้งาน Trait
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory; // 2. เติมคำนี้ข้างใน Class เพื่อดึงความสามารถของ Factory มาใช้งาน

    // // รายชื่อคอลัมน์ที่อนุญาตให้กรอกข้อมูล (อันเดิมที่มีอยู่แล้ว)

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'sale_price',
        'stock',
        'image_url',
        'video_url',
        'is_active',
    ];

    // สินค้า 1 ชิ้น มีรูปภาพประกอบได้หลายรูป
    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order', 'asc');
    }

    public function options()
    {
        return $this->hasMany(ProductOption::class);
    }

    // app/Models/Product.php
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
