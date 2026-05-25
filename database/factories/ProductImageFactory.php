<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductImageFactory extends Factory
{
    public function definition(): array
    {
        return [
            // สุ่มรูปภาพจาก picsum มาเป็นรูปประกอบย่อย
            'image_url' => 'https://picsum.photos/seed/' . Str::random(5) . '/600/600',
            'sort_order' => $this->faker->numberBetween(1, 4), // สุ่มลำดับรูปที่ 1 ถึง 4
        ];
    }
}