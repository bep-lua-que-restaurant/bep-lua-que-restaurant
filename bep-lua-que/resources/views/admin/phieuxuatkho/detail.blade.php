@extends('layouts.admin')

@section('title', 'Chi tiết Phiếu Xuất Kho')

@section('content')
<style>
    .table-primary th {
        color: #0000 !important; /* Màu chữ trắng */
        font-weight: bold; /* Làm đậm chữ */
        text-transform: uppercase; /* Chuyển chữ thành in hoa (tùy chọn) */
    }
</style>
<div class="container mt-4">
    <div class="card shadow-lg rounded-4 border-0">
        <div class="card-header bg-gradient bg-dark text-white">
            <h4 class="mb-0">📄 Chi tiết Phiếu Xuất Kho</h4>
        </div>
        <div class="card-body bg-light-subtle">
            <div class="row mb-3">
                <div class="col-md-4"><strong>Mã phiếu:</strong> <span class="text-primary">{{ $phieuXuatKho->ma_phieu }}</span></div>
                <div class="col-md-4"><strong>Ngày xuất:</strong> <span class="text-primary">{{ \Carbon\Carbon::parse($phieuXuatKho->ngay_xuat)->format('d/m/Y') }}</span></div>
                <div class="col-md-4">
                    <strong>Loại phiếu:</strong> 
                    <span class="badge bg-info-subtle text-dark text-uppercase">
                        {{ str_replace('_', ' ', $phieuXuatKho->loai_phieu) }}
                    </span>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4"><strong>Nhân viên:</strong> <span class="text-primary">{{ $phieuXuatKho->nhanVien->ho_ten ?? '---' }}</span></div>
                @if($phieuXuatKho->loai_phieu === 'xuat_tra_hang')
                    <div class="col-md-4"><strong>Nhà cung cấp:</strong> <span class="text-primary">{{ $phieuXuatKho->nhaCungCap->ten_nha_cung_cap ?? '---' }}</span></div>
                @else
                    <div class="col-md-4"><strong>Người nhận:</strong> <span class="text-primary">{{ $phieuXuatKho->nguoi_nhan ?? '---' }}</span></div>
                @endif
                <div class="col-md-4"><strong>Ghi chú:</strong> <span class="text-secondary fst-italic">{{ $phieuXuatKho->ghi_chu ?? '---' }}</span></div>
            </div>
            <div class="col-md-4 mb-3">
                <strong>Trạng thái:</strong>
                @if($phieuXuatKho->trang_thai === 'cho_duyet')
                    <span class="badge bg-warning-subtle text-dark">🕓 Chờ duyệt</span>
                @elseif($phieuXuatKho->trang_thai === 'da_duyet')
                    <span class="badge bg-success-subtle text-success">✔️ Đã duyệt</span>
                @elseif($phieuXuatKho->trang_thai === 'da_huy')
                    <span class="badge bg-danger-subtle text-danger">❌ Đã hủy</span>
                @endif
            </div>

            <hr class="border-dark-subtle">

            <h5 class="mb-3">📦 Danh sách nguyên liệu</h5>
            <div class="table-responsive">
                <table class="table table-bordered align-middle table-hover shadow-sm rounded">
                    <thead class="table-primary ">
                        <tr>
                            <th>#</th>
                            <th>Nguyên liệu</th>
                            <th>Loại</th>
                            <th>Đơn vị xuất</th>
                            <th>Số lượng</th>
                            <th>Hệ số QĐ</th>
                            <th>Đơn giá</th>
                            <th>Thành tiền</th>
                            <th>Ghi chú</th>
                        </tr>
                    </thead>
                    <tbody class="table-light">
                        @foreach($chiTietPhieuXuatKhos as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td class="fw-semibold text-dark">{{ $item->nguyenLieu->ten_nguyen_lieu ?? '---' }}</td>
                                <td class="text-secondary">{{ $item->nguyenLieu->loaiNguyenLieu->ten_loai ?? '---' }}</td>
                                <td>{{ $item->don_vi_xuat }}</td>
                                <td>{{ $item->so_luong }}</td>
                                <td>{{ $item->he_so_quy_doi }}</td>
                                <td>{{ number_format($item->don_gia, 0, ',', '.') }} đ</td>
                                <td class="text-end text-danger fw-bold">
                                    {{ number_format((float) $item->so_luong * (float) str_replace('.', '', $item->don_gia), 0, ',', '.') }} đ
                                </td>
                                <td class="fst-italic">{{ $item->ghi_chu ?? '' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="table-secondary fw-bold">
                            <td colspan="7" class="text-end">Tổng tiền:</td>
                            <td class="text-end text-primary">
                                {{ number_format($chiTietPhieuXuatKhos->sum(fn($i) => $i->so_luong * $i->don_gia), 0, ',', '.') }} đ
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>

                @if ($phieuXuatKho->trang_thai === 'cho_duyet')
                <div class="d-flex justify-content-end gap-2 mt-4">
                    {{-- Nút Duyệt phiếu --}}
                    <form action="{{ route('phieu-xuat-kho.duyet', $phieuXuatKho->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn DUYỆT phiếu này không?')">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-outline-success px-4">
                            ✅ Duyệt Phiếu
                        </button>
                    </form>
            
                    {{-- Nút Hủy phiếu --}}
                    <form action="{{ route('phieu-xuat-kho.huy', $phieuXuatKho->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn HỦY phiếu này không?')">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-outline-danger px-4">
                            ❌ Hủy Phiếu
                        </button>
                    </form>
                </div>
            @endif
            
            </div>

            <div class="mt-4">
                <a href="{{ route('phieu-xuat-kho.index') }}" class="btn btn-dark">
                    ← Quay lại danh sách
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
