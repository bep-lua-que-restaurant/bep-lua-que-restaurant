@extends('layouts.admin')

@section('title', 'Chi tiết phiếu nhập kho')

@section('content')
    <div class="container mt-4">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>
                <i class="fa fa-file-alt me-2 text-primary"></i>Chi tiết phiếu nhập kho: <span
                    class="text-dark">{{ $phieuNhapKho->ma_phieu }}</span>
            </h3>
            <div>
               
                <a href="{{ route('phieu-nhap-kho.index') }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left"></i> Quay lại
                </a>
            </div>

        </div>

        <div class="card mb-4 shadow-sm border-primary">
            <div class="card-header bg-primary text-white">
                <strong>Thông tin chung</strong>
            </div>
            <div class="card-body">
                <p><strong>📦 Nhà cung cấp:</strong> {{ $phieuNhapKho->nhaCungCap->ten_nha_cung_cap ?? 'N/A' }}</p>
                <p><strong>👤 Nhân viên nhập:</strong> {{ $phieuNhapKho->nhanVien->ho_ten ?? 'N/A' }}</p>
                <p><strong>📅 Ngày nhập:</strong> {{ \Carbon\Carbon::parse($phieuNhapKho->ngay_nhap)->format('d/m/Y') }}</p>
                <p><strong>📝 Ghi chú:</strong> {{ $phieuNhapKho->ghi_chu ?? 'Không có' }}</p>
                <p><strong>📋 Loại phiếu:</strong>
                    @switch($phieuNhapKho->loai_phieu)
                        @case('nhap_tu_ncc')
                            <span class="badge bg-primary">Nhập từ NCC</span>
                        @break

                        @case('nhap_tu_bep')
                            <span class="badge bg-info text-dark">Nhập Từ bếp</span>
                        @break

                        @default
                            <span class="badge bg-secondary">Khác</span>
                    @endswitch
                <p><strong>⚙️ Trạng thái:</strong>

                    @switch($phieuNhapKho->trang_thai)
                        @case('cho_duyet')
                            <span class="badge bg-warning text-dark">Chờ duyệt</span>

                            {{-- Nút duyệt --}}
                        <form action="{{ route('phieu-nhap-kho.duyet', $phieuNhapKho->id) }}" method="POST" class="d-inline ms-2"
                            onsubmit="return confirm('Bạn có chắc muốn duyệt phiếu này không?');">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-success btn-sm">
                                <i class="fa fa-check-circle"></i> Duyệt phiếu
                            </button>
                        </form>

                        {{-- Nút hủy --}}
                        <form action="{{ route('phieu-nhap-kho.huy', $phieuNhapKho->id) }}" method="POST" class="d-inline ms-2"
                            onsubmit="return confirm('Bạn có chắc muốn hủy phiếu này không?');">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fa fa-times-circle"></i> Hủy phiếu
                            </button>
                        </form>
                    @break

                    @case('da_duyet')
                        <span class="badge bg-success">Đã duyệt</span>

                        
                    @break

                    @case('da_huy')
                        <span class="badge bg-danger">Đã hủy</span>
                    @break
                @endswitch


                </p>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-info text-white">
                <strong><i class="fa fa-list me-2"></i>Danh sách nguyên liệu nhập</strong>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Tên nguyên liệu</th>
                            <th>Loại</th>
                            <th>Đơn vị nhập</th>
                            <th>Số lượng</th>
                            <th>Đơn giá</th>
                            <th>Thành tiền</th>
                            <th>NSX</th>
                            <th>HSD</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $tongTien = 0; @endphp
                        @foreach ($chiTietPhieuNhaps as $key => $chiTiet)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $chiTiet->ten_nguyen_lieu }}</td>
                                <td>{{ $chiTiet->loaiNguyenLieu->ten_loai ?? 'Không rõ' }}</td>
                                <td>{{ $chiTiet->don_vi_nhap }}</td>
                                <td>{{ $chiTiet->so_luong_nhap }}</td>
                                <td>{{ number_format($chiTiet->don_gia, 0, ',', '.') }} đ</td>
                                <td class="text-end">{{ number_format($chiTiet->thanh_tien, 0, ',', '.') }} đ</td>
                                <td>{{ $chiTiet->ngay_san_xuat ?? '-' }}</td>
                                <td>{{ $chiTiet->han_su_dung ?? '-' }}</td>
                            </tr>
                            @php $tongTien += $chiTiet->thanh_tien; @endphp
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="table-light">
                            <th colspan="7" class="text-end">Tổng tiền:</th>
                            <th class="text-end text-danger" colspan="3">
                                {{ number_format($tongTien, 0, ',', '.') }} đ
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection
