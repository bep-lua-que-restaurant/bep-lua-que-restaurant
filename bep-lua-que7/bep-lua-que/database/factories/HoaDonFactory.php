<?php

namespace Database\Factories;

use App\Models\KhachHang;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HoaDon>
 */
class HoaDonFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
           'ma_hoa_don' => 'HD' . str_pad($this->faker->unique()->numberBetween(0, 99999), 5, '0', STR_PAD_LEFT),

            'khach_hang_id' => KhachHang::inRandomOrder()->first()?->id ?? 1, // Lấy random khách hàng (nếu có)
            'tong_tien' => $this->faker->numberBetween(50000, 5000000),
            'phuong_thuc_thanh_toan' => $this->faker->randomElement(['tien_mat', 'chuyen_khoan', 'vi_dien_tu']),
            'mo_ta' => $this->faker->sentence(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
