<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('nha_cung_caps', function (Blueprint $table) {
            $table->text('dia_chi')->nullable()->after('ten_nha_cung_cap');
        });
    }

    public function down(): void
    {
        Schema::table('nha_cung_caps', function (Blueprint $table) {
            $table->dropColumn('dia_chi');
        });
    }
};
