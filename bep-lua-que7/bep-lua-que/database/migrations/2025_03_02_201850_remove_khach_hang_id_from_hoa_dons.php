<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('hoa_dons', function (Blueprint $table) {
            if (Schema::hasColumn('hoa_dons', 'khach_hang_id')) {
                $table->dropColumn('khach_hang_id');
            }
        });
    }

    public function down()
    {
        Schema::table('hoa_dons', function (Blueprint $table) {
            if (!Schema::hasColumn('hoa_dons', 'khach_hang_id')) {
                $table->unsignedBigInteger('khach_hang_id')->nullable();
            }
        });
    }
};
