<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\NhaCungCap>
 */
class NhaCungCapFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ten_nha_cung_cap' => $this->faker->unique()->company,
            'so_dien_thoai' => $this->faker->unique()->numerify('09########'),
            'email' => $this->faker->unique()->safeEmail,
            'dia_chi' => $this->faker->address,
            'mo_ta' => $this->faker->sentence(10),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
