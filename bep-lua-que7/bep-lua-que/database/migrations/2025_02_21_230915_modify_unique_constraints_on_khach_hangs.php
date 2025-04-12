<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('khach_hangs', function (Blueprint $table) {
            $table->dropUnique(['email']);
            $table->dropUnique(['so_dien_thoai']);
            $table->dropUnique(['can_cuoc']);
        });
    }

    public function down(): void
    {
        Schema::table('khach_hangs', function (Blueprint $table) {
            $table->unique('email');
            $table->unique('so_dien_thoai');
            $table->unique('can_cuoc');
        });
    }
};
