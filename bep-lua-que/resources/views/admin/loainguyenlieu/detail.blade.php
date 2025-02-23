@extends('layouts.admin')

@section('title', 'Chi Tiết Nguyên Liệu')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Chi Tiết Nguyên Liệu</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th>Mã Nguyên Liệu</th>
                                    <td>{{ $nguyenLieu->ma_nguyen_lieu }}</td>
                                </tr>
                                <tr>
                                    <th>Tên Nguyên Liệu</th>
                                    <td>{{ $nguyenLieu->ten_nguyen_lieu }}</td>
                                </tr>
                                <tr>
                                    <th>Loại Nguyên Liệu</th>
                                    <td>{{ $nguyenLieu->loaiNguyenLieu->ten_loai ?? 'Không xác định' }}</td>
                                </tr>
                                <tr>
                                    <th>Đơn Vị Tính</th>
                                    <td>{{ $nguyenLieu->don_vi_tinh }}</td>
                                </tr>
                                <tr>
                                    <th>Số Lượng Tồn</th>
                                    <td>{{ $nguyenLieu->so_luong_ton }}</td>
                                </tr>
                                <tr>
                                    <th>Giá Nhập</th>
                                    <td>{{ number_format($nguyenLieu->gia_nhap, 0, ',', '.') }} VNĐ</td>
                                </tr>
                                <tr>
                                    <th>Hình Ảnh</th>
                                    <td>
                                        @if($nguyenLieu->hinh_anh)
                                            <img src="{{ asset('storage/' . $nguyenLieu->hinh_anh) }}" alt="{{ $nguyenLieu->ten_nguyen_lieu }}" style="max-width: 150px; max-height: 150px;">
                                        @else
                                            <span>Không có hình ảnh</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Mô Tả</th>
                                    <td>{{ $nguyenLieu->mo_ta ?? 'Không có mô tả' }}</td>
                                </tr>
                            </tbody>
                        </table>

                        <!-- Nút quay lại -->
                        <div class="mt-4">
                            <a href="{{  }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Quay Lại
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
