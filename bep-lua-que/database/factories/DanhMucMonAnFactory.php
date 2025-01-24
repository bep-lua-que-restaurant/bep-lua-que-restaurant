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
            'ten' => $this->faker->name,
            'mo_ta' => $this->faker->text,
            'hinh_anh' => $this->faker->imageUrl(),
        ];
    }
}
