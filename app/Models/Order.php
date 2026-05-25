<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // 💡 เพิ่มหรือแก้ไขตรวจสอบบรรทัดนี้ในโมเดลของคุณครับ
    protected $fillable = [
        'customer_name',
        'customer_phone',
        'shipping_address',
        'payment_method',
        'total_amount',
        'status',
    ];

    // ความสัมพันธ์กับรายการสินค้าในใบสั่งซื้อ (มีอยู่เดิมแล้ว)
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}