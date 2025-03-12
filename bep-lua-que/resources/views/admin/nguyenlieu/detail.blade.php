@extends('layouts.admin')

@section('title')
    Chi tiết Nguyên Liệu
@endsection

@section('content')
<div class="container">
    <h2>Chi tiết Nguyên Liệu</h2>

    <div class="mb-3">
        <label class="form-label"><strong>Mã nguyên liệu:</strong></label>
        <p>{{ $nguyenLieu->ma_nguyen_lieu }}</p>
    </div>

    <div class="mb-3">
        <label class="form-label"><strong>Tên nguyên liệu:</strong></label>
        <p>{{ $nguyenLieu->ten_nguyen_lieu }}</p>
    </div>

    <div class="mb-3">
        <label class="form-label"><strong>Loại nguyên liệu:</strong></label>
        <p>{{ $nguyenLieu->loaiNguyenLieu->ten_loai ?? 'Chưa có loại' }}</p>
    </div>

    <div class="mb-3">
        <label class="form-label"><strong>Đơn vị tính:</strong></label>
        <p>{{ $nguyenLieu->don_vi_tinh }}</p>
    </div>

    <div class="mb-3">
        <label class="form-label"><strong>Hệ số quy đổi:</strong></label>
        <p>{{ rtrim(rtrim(number_format($nguyenLieu->he_so_quy_doi, 2, '.', ''), '0'), '.') }}</p>
    </div>

    <div class="mb-3">
        <label class="form-label"><strong>Số lượng tồn:</strong></label>
        <p>{{ rtrim(rtrim(number_format($nguyenLieu->so_luong_ton, 2, '.', ''), '0'), '.') }}</p>
    </div>

    <div class="mb-3">
        <label class="form-label"><strong>Giá nhập:</strong></label>
        <p>{{ number_format($nguyenLieu->gia_nhap, 0, ',', '.') }} VND</p>
    </div>

    <div class="mb-3">
        <label class="form-label"><strong>Mô tả:</strong></label>
        <div>{!! $nguyenLieu->mo_ta !!}</div>
    </div>

    <div class="mb-3">
        <label class="form-label"><strong>Hình ảnh:</strong></label>
        @if ($nguyenLieu->hinh_anh)
            <div>
                <img src="{{ asset('storage/' . $nguyenLieu->hinh_anh) }}" class="img-thumbnail" style="width: 150px; height: 150px; object-fit: cover;">
            </div>
        @else
            <p>Chưa có hình ảnh</p>
        @endif
    </div>

    <a href="{{ route('nguyen-lieu.index') }}" class="btn btn-secondary">Quay lại danh sách</a>
</div>
@endsection
