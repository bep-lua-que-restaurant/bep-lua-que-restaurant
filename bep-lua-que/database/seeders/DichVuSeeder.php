<?php

namespace Database\Seeders;

use App\Models\DichVu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DichVuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        DichVu::factory()->count(
            10
        )->create();
    }
}
