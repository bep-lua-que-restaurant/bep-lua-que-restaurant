<?php

namespace Database\Seeders;

use App\Models\ComBo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ComBoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ComBo::factory()
            ->count(10)
            ->create();
    }
}
