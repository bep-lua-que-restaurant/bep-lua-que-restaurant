<?php
 
 use Illuminate\Database\Migrations\Migration;
 use Illuminate\Database\Schema\Blueprint;
 use Illuminate\Support\Facades\Schema;
 
 return new class extends Migration {
     public function up()
     {
         Schema::create('ma_giam_gias', function (Blueprint $table) {
             $table->id();
             $table->string('code', 20)->unique();
             $table->enum('type', ['percentage', 'fixed']);
             $table->decimal('value', 10, 2)->unsigned();
             $table->decimal('min_order_value', 10, 2)->nullable()->unsigned();
             $table->date('start_date');
             $table->date('end_date');
             $table->integer('usage_limit')->nullable()->unsigned();
             $table->timestamps();
         });
     }
 
     public function down()
     {
         Schema::dropIfExists('ma_giam_gias');
     }
 };