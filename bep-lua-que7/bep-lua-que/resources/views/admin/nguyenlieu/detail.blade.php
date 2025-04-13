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

            <div class="text-end mt-4">
                <a href="{{ route('nguyen-lieu.index') }}" class="btn btn-secondary">
                    ← Quay lại danh sách
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
