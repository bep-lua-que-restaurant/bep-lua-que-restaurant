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
                                <tr<tr>
                                    <th>Trạng thái</th>
                                    <td>
                                        @if ($phieuNhapKho->trang_thai == 'cho_duyet')
                                            <span class="badge bg-warning text-dark">Chờ duyệt</span>
                                            <form action="{{ route('phieu-nhap-kho.duyet', $phieuNhapKho->id) }}"
                                                method="POST" style="display:inline;">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-success btn-sm">Duyệt</button>
                                            </form>
                                            <form action="{{ route('phieu-nhap-kho.huy', $phieuNhapKho->id) }}"
                                                method="POST" style="display:inline;">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-danger btn-sm">Hủy</button>
                                            </form>
                                        @elseif ($phieuNhapKho->trang_thai == 'da_duyet')
                                            <span class="badge bg-success">Đã duyệt</span>
                                        @elseif ($phieuNhapKho->trang_thai == 'da_huy')
                                            <span class="badge bg-danger">Đã hủy</span>
                                        @else
                                            <span class="badge bg-secondary">Không xác định</span>
                                        @endif
                                    </td>
                                    </tr>

                                    </tr>
                            </tbody>
                        </table>

                        <!-- Thông tin nguyên liệu -->
                        <h5 class="text-primary mt-4">Danh Sách Nguyên Liệu</h5>
                        <table class="table  table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Mã Nguyên Liệu</th>
                                    <th>Tên Nguyên Liệu</th>
                                    <th>Loại Nguyên Liệu</th>
                                    <th>Số Lượng nhập</th>
                                    <th>Giá Nhập</th>
                                    <th>Thành Tiền</th>
                                    <th>Hành động</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($phieuNhapKho->chiTietPhieuNhapKho as $index => $chiTiet)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $chiTiet->nguyenLieu->ma_nguyen_lieu ?? 'Không xác định' }}</td>
                                        <td>{{ $chiTiet->nguyenLieu->ten_nguyen_lieu ?? 'Không xác định' }}</td>
                                        <td>{{ $chiTiet->nguyenLieu->loaiNguyenLieu->ten_loai ?? 'Không xác định' }}</td>
                                        <td>{{ $chiTiet->so_luong }}</td>
                                        <td>{{ number_format($chiTiet->don_gia, 0, ',', '.') }} VNĐ</td>

                                        <td>{{ number_format($chiTiet->tong_tien, 0, ',', '.') }} VNĐ</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <a href="{{ route('phieu-nhap-kho.chitiet-nguyenlieu', ['phieuNhapId' => $phieuNhapKho->id, 'nguyenLieuId' => $chiTiet->nguyenLieu->id]) }}"
                                                    class="btn btn-info btn-sm p-2 m-2">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            </div>
                                        </td>
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
