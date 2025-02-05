@extends('layouts.admin')

@section('title')
    Chi tiết món ăn
@endsection

@section('content')
<div class="container">
    <h2>Chi tiết Món Ăn</h2>

    <div class="mb-3">
        <label class="form-label"><strong>Tên món ăn:</strong></label>
        <p>{{ $monAn->ten }}</p>
    </div>

    <div class="mb-3">
        <label class="form-label"><strong>Danh mục:</strong></label>
        <p>{{ $monAn->danhMuc->ten ?? 'Chưa có danh mục' }}</p>
    </div>

    <div class="mb-3">
        <label class="form-label"><strong>Mô tả:</strong></label>
        <div>{!!$monAn->mo_ta!!}</div>
    </div>

    <div class="mb-3">
        <label class="form-label"><strong>Giá:</strong></label>
        <p>{{ number_format($monAn->gia, 0, ',', '.') }} VND</p>
    </div>

    <div class="mb-3">
        <label class="form-label"><strong>Trạng thái:</strong></label>
        <p>
            @if ($monAn->trang_thai == 'dang_ban') Đang bán
            @elseif ($monAn->trang_thai == 'het_hang') Hết hàng
            @else Ngừng bán
            @endif
        </p>
    </div>

    <div class="mb-3">
        <label class="form-label"><strong>Hình ảnh:</strong></label>
        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            @forelse($monAn->hinhAnhs as $hinhAnh)
                <img src="{{ asset('storage/' . $hinhAnh->hinh_anh) }}" class="img-thumbnail" style="width: 150px; height: 150px; object-fit: cover;">
            @empty
                <p>Chưa có hình ảnh</p>
            @endforelse
        </div>
    </div>

    <a href="{{ route('mon-an.index') }}" class="btn btn-secondary">Quay lại danh sách</a>
</div>
@endsection
