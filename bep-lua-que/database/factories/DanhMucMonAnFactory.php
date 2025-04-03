<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DanhMucMonAn>
 */
class DanhMucMonAnFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ten' => 'Danh mục ' . strtoupper($this->faker->unique()->lexify('????') . $this->faker->numerify('###')), // 4 chữ cái + 3 số
            'mo_ta' => $this->faker->sentence, // Mô tả ngắn gọn
            'hinh_anh' => null, // Không cần ảnh
        ];
    }
}
