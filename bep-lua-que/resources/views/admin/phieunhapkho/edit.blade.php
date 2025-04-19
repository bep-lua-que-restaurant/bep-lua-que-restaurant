@extends('layouts.admin')

@section('title', 'Chỉnh sửa phiếu nhập kho')

@section('content')
@if ($errors->any())
<div class="alert alert-danger">
    <strong>Đã xảy ra lỗi:</strong>
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
<div class="container">
    <h3 class="mb-4">Chỉnh sửa phiếu nhập kho: {{ $phieuNhapKho->ma_phieu }}</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('phieu-nhap-kho.update', $phieuNhapKho->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row mb-3">
            <div class="col-md-4">
                <label for="ma_phieu" class="form-label">Mã phiếu</label>
                <input type="text" name="ma_phieu" class="form-control" value="{{ old('ma_phieu', $phieuNhapKho->ma_phieu) }}" readonly>
            </div>

            <div class="col-md-4">
                <label for="loai_phieu" class="form-label">Loại phiếu</label>
                <input type="text" class="form-control" 
                    value="{{ match($phieuNhapKho->loai_phieu) {
                        'nhap_tu_ncc' => 'Nhập mới',
                        'nhap_tu_bep' => 'Nhập từ bếp',
                        default => ucfirst(str_replace('_', ' ', $phieuNhapKho->loai_phieu)),
                    } }}" readonly>
                <!-- Lưu giá trị loai_phieu trong một input hidden để gửi giá trị qua request -->
                <input type="hidden" name="loai_phieu" value="{{ $phieuNhapKho->loai_phieu }}">
            </div>
            

            <div class="col-md-4">
                <label for="nhan_vien_id" class="form-label">Nhân viên nhập</label>
                <select name="nhan_vien_id" class="form-select" disabled>
                    @foreach($nhanViens as $nv)
                        <option value="{{ $nv->id }}" {{ $phieuNhapKho->nhan_vien_id == $nv->id ? 'selected' : '' }}>{{ $nv->ho_ten }}</option>
                    @endforeach
                </select>
                <input type="hidden" name="nhan_vien_id" value="{{ $phieuNhapKho->nhan_vien_id }}"> <!-- Lưu giá trị nhân viên nhập trong hidden input -->
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="nha_cung_cap_id" class="form-label">Nhà cung cấp</label>
                <select name="nha_cung_cap_id" class="form-select" disabled>
                    <option value="" disabled {{ $phieuNhapKho->nha_cung_cap_id ? '' : 'selected' }}>-- Không có --</option>
                    @foreach($nhaCungCaps as $ncc)
                        @if($phieuNhapKho->nha_cung_cap_id == $ncc->id)
                            <option value="{{ $ncc->id }}" selected>{{ $ncc->ten_nha_cung_cap }}</option>
                        @endif
                    @endforeach
                </select>
                <input type="hidden" name="nha_cung_cap_id" value="{{ $phieuNhapKho->nha_cung_cap_id }}">
            </div>
            

            <div class="col-md-6">
                <label for="ghi_chu" class="form-label">Ghi chú</label>
                <textarea name="ghi_chu" name="ghi_chu" class="form-control" >{{ old('ghi_chu', $phieuNhapKho->ghi_chu) }}</textarea>
            </div>
        </div>

        <h5 class="mt-4">Chi tiết phiếu nhập</h5>
        <div id="nguyenLieuContainer">
            @foreach($phieuNhapKho->chiTietPhieuNhaps as $index => $chiTiet)
                <div class="border rounded p-3 mb-3">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label">Tên nguyên liệu</label>
                            <input type="text" class="form-control" value="{{ $chiTiet->ten_nguyen_lieu }}" readonly>
                        </div>
        
                        <div class="col-md-3">
                            <label class="form-label">Loại nguyên liệu</label>
                            <select class="form-select" disabled>
                                @foreach($loaiNguyenLieus as $loai)
                                    <option value="{{ $loai->id }}" {{ $chiTiet->loai_nguyen_lieu_id == $loai->id ? 'selected' : '' }}>
                                        {{ $loai->ten_loai }}
                                    </option>
                                @endforeach
                            </select>
                            <!-- Hidden field to ensure value is submitted -->
                            <input type="hidden" name="loai_nguyen_lieu_ids[]" value="{{ $chiTiet->loai_nguyen_lieu_id }}">
                        </div>
        
                        <div class="col-md-2">
                            <label class="form-label">Đơn vị nhập</label>
                            <input type="text" name="don_vi_nhaps[]" class="form-control" value="{{ $chiTiet->don_vi_nhap }}">
                        </div>
        
                        <div class="col-md-2">
                            <label class="form-label">Số lượng</label>
                            <input type="number" name="so_luong_nhaps[]" class="form-control" value="{{ $chiTiet->so_luong_nhap }}">
                        </div>
        
                        <div class="col-md-2">
                            <label class="form-label">Đơn giá</label>
                            <input type="number" name="don_gias[]" class="form-control" value="{{ $chiTiet->don_gia }}">
                        </div>
        
                        <div class="col-md-3">
                            <label class="form-label">Ngày sản xuất</label>
                            <input type="date" name="ngay_san_xuats[]" class="form-control" value="{{ $chiTiet->ngay_san_xuat }}">
                        </div>
        
                        <div class="col-md-3">
                            <label class="form-label">Hạn sử dụng</label>
                            <input type="date" name="ngay_het_hans[]" class="form-control" value="{{ $chiTiet->han_su_dung }}">
                        </div>
        
                        <div class="col-md-4">
                            <label class="form-label">Ghi chú</label>
                            <input type="text" name="ghi_chus[]" class="form-control" value="{{ $chiTiet->ghi_chu }}" readonly>
                        </div>
                    </div>
        
                    <!-- ID chi tiết phiếu nhập -->
                    <input type="hidden" name="chi_tiet_ids[]" value="{{ $chiTiet->id }}">
                </div>
            @endforeach
        </div>
        

        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('phieu-nhap-kho.index') }}" class="btn btn-secondary">Quay lại</a>
            <button type="submit" class="btn btn-primary">Cập nhật</button>
        </div>
    </form>
</div>
@endsection
