<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // 1. เพิ่มบรรทัดนี้เพื่อเรียกใช้งาน Trait
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory; // 2. เพิ่มคำนี้ข้างใน Class เพื่อเปิดสิทธิ์ให้สามารถเรียกใช้ factory() ได้

    // รายชื่อคอลัมน์ที่อนุญาตให้เขียนข้อมูลลงตาราง (อันเดิมที่มีอยู่แล้ว)
    protected $fillable = [
        'product_id', 
        'image_url', 
        'sort_order'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}