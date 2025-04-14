<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('mon_bi_huys', function (Blueprint $table) {
            // Bỏ các cột không cần thiết
            $table->dropColumn('danh_muc_mon_an_id');
            $table->dropColumn('hinh_anh');
            $table->dropColumn('don_gia');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mon_bi_huys', function (Blueprint $table) {
            // Nếu cần khôi phục các cột, dùng phương thức này
            $table->unsignedBigInteger('danh_muc_mon_an_id')->nullable();
            $table->string('hinh_anh')->nullable();
            $table->decimal('don_gia', 10, 2)->nullable();
        });
    }
};
