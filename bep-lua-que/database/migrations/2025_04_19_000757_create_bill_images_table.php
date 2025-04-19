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
        Schema::create('bill_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hoa_don_id');
            $table->string('image_path');
            $table->timestamps();

            $table->foreign('hoa_don_id')
                ->references('id')
                ->on('hoa_dons')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bill_images');
    }
};
