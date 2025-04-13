@extends('layouts.admin')

@section('title')
    Chi tiết món ăn
@endsection

@section('content')
<div class="container my-4">
    <div class="card shadow rounded p-4">
        <h2 class="mb-4 text-center">🍽️ Chi Tiết Món Ăn</h2>

        <div class="row mb-3">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Tên món ăn:</label>
                <div class="border p-2 rounded bg-light">{{ $monAn->ten }}</div>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Danh mục:</label>
                <div class="border p-2 rounded bg-light">{{ $monAn->danhMuc->ten ?? 'Chưa có danh mục' }}</div>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Giá:</label>
                <div class="border p-2 rounded bg-light text-success">
                    {{ number_format($monAn->gia, 0, ',', '.') }} VNĐ
                </div>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Trạng thái:</label>
                <div class="border p-2 rounded bg-light">
                    @if ($monAn->trang_thai == 'dang_ban')
                        <span class="badge bg-success">Đang bán</span>
                    @elseif ($monAn->trang_thai == 'het_hang')
                        <span class="badge bg-warning text-dark">Hết hàng</span>
                    @else
                        <span class="badge bg-secondary">Ngừng bán</span>
                    @endif
                </div>
            </div>

            <div class="col-12 mb-3">
                <label class="form-label fw-bold">Mô tả:</label>
                <div class="border p-3 rounded bg-light">{!! $monAn->mo_ta !!}</div>
            </div>

            <div class="col-12 mb-4">
                <label class="form-label fw-bold">Hình ảnh:</label>
                <div class="d-flex flex-wrap gap-3 mt-2">
                    @forelse($monAn->hinhAnhs as $hinhAnh)
                        <img src="{{ asset('storage/' . $hinhAnh->hinh_anh) }}" class="img-thumbnail shadow-sm"
                            style="width: 140px; height: 140px; object-fit: cover; border-radius: 8px;">
                    @empty
                        <p>Chưa có hình ảnh</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="text-center">
            <a href="{{ route('mon-an.index') }}" class="btn btn-outline-primary px-4">
                <i class="fas fa-arrow-left"></i> Quay lại danh sách
            </a>
        </div>
    </div>
</div>
@endsection
