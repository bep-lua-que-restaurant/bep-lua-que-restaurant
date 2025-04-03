<?php

namespace Database\Seeders;

use App\Models\DanhMucMonAn;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DanhMucMonAnSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DanhMucMonAn::factory()
            ->count(500)
            ->create();
    }
}
