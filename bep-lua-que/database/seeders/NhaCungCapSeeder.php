<?php

namespace Database\Seeders;

use App\Models\NhaCungCap;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NhaCungCapSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        NhaCungCap::factory()
            ->count(10)
            ->create();
    }
}
