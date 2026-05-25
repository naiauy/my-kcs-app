<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductOption extends Model
{
    use HasFactory;

    // กำหนดให้สามารถบันทึกข้อมูลแบบ Mass Assignment ได้
    protected $fillable = [
        'product_id',
        'option_type',
        'option_value'
    ];

    // 💡 ผูกความสัมพันธ์ย้อนกลับไปหาตัวสินค้าหลัก (Product)
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}