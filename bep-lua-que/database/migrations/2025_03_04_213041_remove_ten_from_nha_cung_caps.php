<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('nha_cung_caps', function (Blueprint $table) {
            $table->dropColumn('ten');
        });
    }

    public function down()
    {
        Schema::table('nha_cung_caps', function (Blueprint $table) {
            $table->string('ten'); // Khôi phục cột nếu rollback
        });
    }
};
