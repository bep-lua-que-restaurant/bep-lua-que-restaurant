@extends('layouts.admin')

@section('title', 'Chi Tiết Nguyên Liệu')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Chi Tiết Nguyên Liệu: {{ $chiTiet->nguyenLieu->ten_nguyen_lieu }}</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th>Mã Nguyên Liệu</th>
                                    <td>{{ $chiTiet->nguyenLieu->ma_nguyen_lieu }}</td>
                                </tr>
                                <tr>
                                    <th>Tên Nguyên Liệu</th>
                                    <td>{{ $chiTiet->nguyenLieu->ten_nguyen_lieu }}</td>
                                </tr>
                                <tr>
                                    <th>Loại Nguyên Liệu</th>
                                    <td>{{ $chiTiet->nguyenLieu->loaiNguyenLieu->ten_loai ?? 'Không xác định' }}</td>
                                </tr>
                                <tr>
                                    <th>Số Lượng Nhập</th>
                                    <td>{{ $chiTiet->so_luong }}</td>
                                </tr>
                                <tr>
                                    <th>Đơn vị tính</th>
                                    <td>{{ $chiTiet->nguyenLieu->don_vi_tinh }}</td>
                                </tr>
                                <tr>
                                    <th>Giá Nhập</th>
                                    <td>{{ number_format($chiTiet->don_gia, 0, ',', '.') }} VNĐ</td>
                                </tr>
                                <tr>
                                    <th>Thành Tiền</th>
                                    <td>{{ number_format($chiTiet->tong_tien, 0, ',', '.') }} VNĐ</td>
                                </tr>
                                <tr>
                                    <th>Số Lượng Tồn</th>
                                    <td>{{ $soLuongTon }} kg</td>
                                </tr>
                                <tr>
                                    <th>Hình Ảnh Nguyên Liệu</th>
                                    <td>
                                        <img src="{{ asset('storage/' . $chiTiet->nguyenLieu->hinh_anh) }}" 
                                            alt="Hình Ảnh Nguyên Liệu" width="150">
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="mt-4">
                            <a href="{{ route('phieu-nhap-kho.show', ['phieu_nhap_kho' => $phieuNhapId]) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Quay Lại
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
