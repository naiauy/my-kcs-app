<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // 💡 กำหนดให้สามารถทำ Mass Assignment ได้อย่างปลอดภัย
    protected $fillable = ['name', 'slug'];

    /**
     * ความสัมพันธ์: 1 หมวดหมู่มีสินค้าได้หลายชิ้น (One-to-Many)
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }
}