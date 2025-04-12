<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::rename('nguyen_lieu_mon_ans', 'cong_thuc');
    }

    public function down(): void
    {
        Schema::rename('cong_thuc', 'nguyen_lieu_mon_ans');
    }
};
