<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChiTietPhieuNhapKhosTable extends Migration
{
    public function up()
    {
        Schema::create('chi_tiet_phieu_nhap_khos', function (Blueprint $table) {
            $table->id();
        
            $table->unsignedBigInteger('phieu_nhap_kho_id');
           
            $table->unsignedBigInteger('loai_nguyen_lieu_id'); // Thêm trường này
            $table->string('ten_nguyen_lieu');
            $table->string('don_vi_nhap');
            $table->string('don_vi_ton')->nullable(); // Thêm trường này
            $table->decimal('so_luong_nhap', 10, 2)->check('so_luong_nhap > 0');
            $table->decimal('he_so_quy_doi', 10, 4)->check('he_so_quy_doi > 0');
            $table->decimal('don_gia', 15, 2)->default(0);
            $table->decimal('thanh_tien', 15, 2)->default(0);
            $table->date('ngay_san_xuat')->nullable();
            $table->date('han_su_dung')->nullable();
            $table->text('ghi_chu')->nullable();
        
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('loai_nguyen_lieu_id')->references('id')->on('loai_nguyen_lieus')->onDelete('cascade');
            $table->foreign('phieu_nhap_kho_id')->references('id')->on('phieu_nhap_khos')->onDelete('cascade');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('chi_tiet_phieu_nhap_khos');
    }
    
}
