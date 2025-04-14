<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   
    
     public function up(): void
     {
         Schema::table('luongs', function (Blueprint $table) {
             // Thêm cột ngay_ap_dung và không cho phép NULL
             $table->date('ngay_ap_dung')->after('muc_luong')->nullable();
         });
     }
 
     public function down(): void
     {
         Schema::table('luongs', function (Blueprint $table) {
             $table->dropColumn('ngay_ap_dung');
         });
     }
   
    
};
