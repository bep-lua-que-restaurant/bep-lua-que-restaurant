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
        Schema::table('cham_congs', function (Blueprint $table) {
            $table->unsignedBigInteger('ca_lam_id')->after('nhan_vien_id'); // Thêm cột ca_lam_id
            $table->foreign('ca_lam_id')->references('id')->on('ca_lams')->onDelete('cascade'); // Thiết lập khóa ngoại
        });
    }

    public function down()
    {
        Schema::table('cham_congs', function (Blueprint $table) {
            // $table->dropForeign(['ca_lam_id']); // Xóa khóa ngoại trước
            // $table->dropColumn('ca_lam_id'); // Xóa cột ca_lam_id
        });
    }
};
