@extends('layouts.admin')

@section('title', 'Chi Tiết Phiếu Nhập Kho')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Chi Tiết Phiếu Nhập Kho</h4>
                    </div>
                    <div class="card-body">
                        <!-- Thông tin phiếu nhập -->
                        <h5 class="text-primary">Thông Tin Phiếu Nhập</h5>
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th>Mã Phiếu Nhập</th>
                                    <td>{{ $phieuNhapKho->ma_phieu_nhap }}</td>
                                </tr>
                                <tr>
                                    <th>Nhân Viên Nhập</th>
                                    <td>{{ $phieuNhapKho->nhanVien->ho_ten ?? 'Không xác định' }}</td>
                                </tr>
                                <tr>
                                    <th>Nhà Cung Cấp</th>
                                    <td>{{ $phieuNhapKho->nhaCungCap->ten_nha_cung_cap ?? 'Không xác định' }}</td>
                                </tr>
                                <tr>
                                    <th>Ngày Nhập</th>
                                    <td>{{ \Carbon\Carbon::parse($phieuNhapKho->ngay_nhap)->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Ghi Chú</th>
                                    <td>{{ $phieuNhapKho->ghi_chu ?? 'Không có ghi chú' }}</td>
                                </tr>
                            </tbody>
                        </table>

                        <!-- Thông tin nguyên liệu -->
                        <h5 class="text-primary mt-4">Danh Sách Nguyên Liệu</h5>
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Mã Nguyên Liệu</th>
                                    <th>Tên Nguyên Liệu</th>
                                    <th>Loại Nguyên Liệu</th>
                                    <th>Đơn Vị Tính</th>
                                    <th>Số Lượng nhập</th>
                                    <th>Giá Nhập</th>
                                    <th>Hạn Sử Dụng</th>
                                    <th>Thành Tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($phieuNhapKho->chiTietPhieuNhapKho as $index => $chiTiet)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $chiTiet->nguyenLieu->ma_nguyen_lieu ?? 'Không xác định' }}</td>
                                        <td>{{ $chiTiet->nguyenLieu->ten_nguyen_lieu ?? 'Không xác định' }}</td>
                                        <td>{{ $chiTiet->nguyenLieu->loaiNguyenLieu->ten_loai ?? 'Không xác định' }}</td>
                                        <td>{{ $chiTiet->nguyenLieu->don_vi_tinh ?? 'Không xác định' }}</td>
                                        <td>{{ $chiTiet->so_luong }}</td>
                                        <td>{{ number_format($chiTiet->don_gia, 0, ',', '.') }} VNĐ</td>
                                        <td>
                                            {{ $chiTiet->han_su_dung ? \Carbon\Carbon::parse($chiTiet->han_su_dung)->format('d/m/Y') : 'Không xác định' }}
                                        </td>
                                        <td>{{ number_format($chiTiet->tong_tien, 0, ',', '.') }} VNĐ</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <!-- Tổng cộng -->
                        <div class="mt-4">
                            <h5 class="text-danger">Tổng Tiền: 
                                {{ number_format($phieuNhapKho->chiTietPhieuNhapKho->sum('tong_tien'), 0, ',', '.') }} VNĐ
                            </h5>
                        </div>

                        <!-- Nút quay lại -->
                        <div class="mt-4">
                            <a href="{{ route('phieu-nhap-kho.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Quay Lại
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
