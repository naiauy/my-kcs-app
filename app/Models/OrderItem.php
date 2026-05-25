<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    // 💡 ประกาศเพียงครั้งเดียวและรวมฟิลด์ทั้งหมดไว้ด้วยกัน
    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'quantity',
        'price',
        'selected_options', // ฟิลด์ออปชันสินค้าที่เราเพิ่มเข้าไปล่าสุด
    ];

    // ความสัมพันธ์เชื่อมกลับไปหาตาราง Product หลัก
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}