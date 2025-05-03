@extends('layouts.admin')

@section('title', 'Chi tiết nguyên liệu')

@section('content')
<div class="container py-4">
    <div class="card shadow border-0">
        <div class="card-header bg-success text-white">
            <h4 class="mb-0">Chi tiết nguyên liệu</h4>
        </div>
        <div class="card-body">
            <table class="table table-bordered mb-0">
                <tbody>
                    <tr>
                        <th class="w-25">Tên nguyên liệu</th>
                        <td>{{ $nguyenLieu->ten_nguyen_lieu }}</td>
                    </tr>
                    <tr>
                        <th>Loại nguyên liệu</th>
                        <td>{{ $nguyenLieu->loaiNguyenLieu->ten_loai ?? 'Đã xóa' }}</td>
                    </tr>
                    <tr>
                        <th>Đơn vị tồn</th>
                        <td>{{ $nguyenLieu->don_vi_ton }}</td>
                    </tr>
                    <tr>
                        <th>Số lượng tồn</th>
                        <td>{{ number_format($nguyenLieu->so_luong_ton, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Trạng thái</th>
                        <td>
                            @if($nguyenLieu->deleted_at)
                                <span class="badge bg-danger">Đã xóa</span>
                            @else
                                <span class="badge bg-success">Hoạt động</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Ghi chú</th>
                        <td>{{ $nguyenLieu->ghi_chu ?? 'Không có' }}</td>
                    </tr>
                </tbody>
            </table>

            {{-- Bảng chi tiết theo lô nhập --}}
            <h5 class="mt-5">Danh sách lô nhập nguyên liệu</h5>
            <table class="table table-bordered mt-3">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Số lượng nhập</th>
                        <th>Đơn vị</th>
                        <th>NSX</th>
                        <th>HSD</th>
                        <th>Nhà cung cấp</th>
                        <th>Ngày nhập</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($nguyenLieu->chiTietPhieuNhapKhos as $index => $ct)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ number_format($ct->so_luong_nhap, 2) }}</td>
                            <td>{{ $ct->don_vi_nhap }}</td>
                            <td>{{ \Carbon\Carbon::parse($ct->ngay_san_xuat)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($ct->han_su_dung)->format('d/m/Y') }}</td>
                            <td>{{ $ct->phieuNhapKho->nhaCungCap->ten_nha_cung_cap ?? 'Đã xóa' }}</td>
                            <td>{{ \Carbon\Carbon::parse($ct->phieuNhapKho->created_at)->format('d/m/Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Chưa có dữ liệu nhập kho cho nguyên liệu này.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="text-end mt-4">
                <a href="{{ route('nguyen-lieu.index') }}" class="btn btn-secondary">
                    ← Quay lại danh sách
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
