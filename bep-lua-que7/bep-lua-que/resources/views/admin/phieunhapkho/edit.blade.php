@extends('layouts.admin')

@section('title', 'Chỉnh sửa phiếu nhập kho')

@section('content')
<div class="container">
    <h3 class="mb-4">Chỉnh sửa phiếu nhập kho: {{ $phieuNhapKho->ma_phieu }}</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('phieu-nhap-kho.update', $phieuNhapKho->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="ma_phieu" class="form-label">Mã phiếu</label>
            <input type="text" name="ma_phieu" class="form-control" value="{{ old('ma_phieu', $phieuNhapKho->ma_phieu) }}" readonly>
        </div>

        <div class="mb-3">
            <label for="nha_cung_cap_id" class="form-label">Nhà cung cấp</label>
            <select name="nha_cung_cap_id" class="form-select">
                @foreach($nhaCungCaps as $ncc)
                    <option value="{{ $ncc->id }}" {{ $phieuNhapKho->nha_cung_cap_id == $ncc->id ? 'selected' : '' }}>
                        {{ $ncc->ten_nha_cung_cap }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="nhan_vien_id" class="form-label">Nhân viên nhập</label>
            <select name="nhan_vien_id" class="form-select">
                @foreach($nhanViens as $nv)
                    <option value="{{ $nv->id }}" {{ $phieuNhapKho->nhan_vien_id == $nv->id ? 'selected' : '' }}>
                        {{ $nv->ho_ten }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="ghi_chu" class="form-label">Ghi chú</label>
            <textarea name="ghi_chu" class="form-control">{{ old('ghi_chu', $phieuNhapKho->ghi_chu) }}</textarea>
        </div>

        <h5 class="mt-4">Chi tiết phiếu nhập</h5>
        <div id="nguyenLieuContainer">
            @foreach($phieuNhapKho->chiTietPhieuNhaps as $index => $chiTiet)
                <div class="border rounded p-3 mb-3">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Tên nguyên liệu</label>
                            <input type="text" name="ten_nguyen_lieus[]" class="form-control" value="{{ $chiTiet->ten_nguyen_lieu }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Loại nguyên liệu</label>
                            <select name="loai_nguyen_lieu_ids[]" class="form-select">
                                @foreach($loaiNguyenLieus as $loai)
                                    <option value="{{ $loai->id }}" {{ $chiTiet->loai_nguyen_lieu_id == $loai->id ? 'selected' : '' }}>
                                        {{ $loai->ten_loai }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Đơn vị nhập</label>
                            <input type="text" name="don_vi_nhaps[]" class="form-control" value="{{ $chiTiet->don_vi_nhap }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Đơn vị tồn</label>
                            <input type="text" name="don_vi_tons[]" class="form-control" value="{{ $chiTiet->don_vi_nhap }}">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Số lượng</label>
                            <input type="number" step="0.01" name="so_luong_nhaps[]" class="form-control" value="{{ $chiTiet->so_luong_nhap }}">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Hệ số quy đổi</label>
                            <input type="number" step="0.0001" name="he_so_quy_dois[]" class="form-control" value="{{ $chiTiet->he_so_quy_doi }}">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Đơn giá</label>
                            <input type="number" step="0.01" name="don_gias[]" class="form-control" value="{{ $chiTiet->don_gia }}">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Ngày sản xuất</label>
                            <input type="date" name="ngay_san_xuat[]" class="form-control" value="{{ $chiTiet->ngay_san_xuat }}">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Hạn sử dụng</label>
                            <input type="date" name="han_su_dung[]" class="form-control" value="{{ $chiTiet->han_su_dung }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Ghi chú</label>
                            <input type="text" name="ghi_chus[]" class="form-control" value="{{ $chiTiet->ghi_chu }}">
                        </div>
                    </div>

                    <input type="hidden" name="chi_tiet_ids[]" value="{{ $chiTiet->id }}">
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-between">
            <a href="{{ route('phieu-nhap-kho.index') }}" class="btn btn-secondary">Quay lại</a>
            <button type="submit" class="btn btn-primary">Cập nhật</button>
        </div>
    </form>
</div>
@endsection
