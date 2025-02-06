<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\NhaCungCap;
class NhaCungCapSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Tạo ngẫu nhiên 5 nhà cung cấp
         NhaCungCap::factory()->count(5)->create();
    }
}
