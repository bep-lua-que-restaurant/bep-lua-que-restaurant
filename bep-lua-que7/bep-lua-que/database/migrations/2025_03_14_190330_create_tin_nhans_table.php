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
    Schema::create('tin_nhans', function (Blueprint $table) {
        $table->id();
        $table->foreignId('nguoi_dung_id')->constrained('nhan_viens')->onDelete('cascade');
        $table->string('ten');
        $table->text('noi_dung');
        $table->boolean('nguon_tu_bot')->default(false);
        $table->boolean('nguon_tu_nhan_vien')->default(false);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tin_nhans');
    }
};
