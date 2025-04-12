@extends('layouts.admin')

@section('title', 'Tạo Phiếu Nhập Kho')

@section('content')
    <div class="container-fluid mt-4">
        <div class="row mb-3">
            <div class="col">
                <h4 class="fw-bold">🧾 Tạo Phiếu Nhập Kho Mới</h4>
            </div>
            <div class="col-auto">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Tạo phiếu nhập kho</li>
                    </ol>
                </nav>
            </div>
        </div>

        <form action="{{ route('phieu-nhap-kho.store') }}" method="POST">
            @csrf

            <!-- THÔNG TIN CHUNG -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <strong>Thông Tin Phiếu Nhập</strong>
                </div>
                <div class="card-body row g-3">
                    <div class="col-md-4">
                        <label for="ma_phieu" class="form-label">Mã Phiếu</label>
                        <input type="text" id="ma_phieu" name="ma_phieu" value="{{ old('ma_phieu', $nextCode) }}"
                            class="form-control @error('ma_phieu') is-invalid @enderror" readonly>
                        @error('ma_phieu')
                            <div class="invalid-feedback">* {{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="nha_cung_cap_id" class="form-label">Nhà Cung Cấp</label>
                        <select name="nha_cung_cap_id"
                            class="form-select select2 @error('nha_cung_cap_id') is-invalid @enderror">
                            <option value="">-- Chọn --</option>
                            @foreach ($nhaCungCaps as $item)
                                <option value="{{ $item->id }}"
                                    {{ old('nha_cung_cap_id') == $item->id ? 'selected' : '' }}>
                                    {{ $item->ten_nha_cung_cap }}
                                </option>
                            @endforeach
                        </select>
                        @error('nha_cung_cap_id')
                            <div class="invalid-feedback">* {{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="nhan_vien_id" class="form-label">Nhân Viên</label>
                        <select name="nhan_vien_id" class="form-select select2 @error('nhan_vien_id') is-invalid @enderror">
                            <option value="">-- Chọn --</option>
                            @foreach ($nhanViens as $item)
                                <option value="{{ $item->id }}"
                                    {{ old('nhan_vien_id') == $item->id ? 'selected' : '' }}>
                                    {{ $item->ho_ten }}
                                </option>
                            @endforeach
                        </select>
                        @error('nhan_vien_id')
                            <div class="invalid-feedback">* {{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="ghi_chu" class="form-label">Ghi chú</label>
                        <textarea name="ghi_chu" class="form-control @error('ghi_chu') is-invalid @enderror" rows="2"
                            placeholder="Thêm ghi chú nếu cần...">{{ old('ghi_chu') }}</textarea>
                        @error('ghi_chu')
                            <div class="invalid-feedback">* {{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>


            <!-- NGUYÊN LIỆU -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <strong>Nguyên Liệu Nhập</strong>
                    <button type="button" id="add-row" class="btn btn-light btn-sm">+ Thêm nguyên liệu</button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle text-center mb-0" id="nguyen-lieu-table">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Tên nguyên liệu</th>
                                    <th>Loại nguyên liệu</th>
                                    <th>Đơn vị nhập</th>
                                    <th>Số lượng nhập</th>
                                    <th>Hệ số quy đổi</th>
                                    <th>Đơn giá</th>
                                    <th>Ngày sản xuất</th>
                                    <th>Ngày hết hạn</th>
                                    <th>Ghi chú</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody id="nguyen-lieu-body">
                                @for ($i = 0; $i < count(old('ten_nguyen_lieus', [''])); $i++)
                                    <tr>
                                        <td class="row-index text-center align-middle">{{ $i + 1 }}</td>
                            
                                        <td>
                                            <input type="text" name="ten_nguyen_lieus[]"
                                                value="{{ old("ten_nguyen_lieus.$i") }}"
                                                class="form-control form-control-sm @error("ten_nguyen_lieus.$i") is-invalid @enderror"
                                                placeholder="Tên nguyên liệu" style="min-width: 160px">
                                            @error("ten_nguyen_lieus.$i")
                                                <div class="form-text text-danger small text-truncate w-100">* {{ $message }}</div>
                                            @enderror
                                        </td>
                            
                                        <td>
                                            <select name="loai_nguyen_lieu_ids[]"
                                                class="form-select form-select-sm @error("loai_nguyen_lieu_ids.$i") is-invalid @enderror"
                                                style="min-width: 130px">
                                                <option value="">-- Chọn --</option>
                                                @foreach ($loaiNguyenLieus as $loai)
                                                    <option value="{{ $loai->id }}"
                                                        {{ old("loai_nguyen_lieu_ids.$i") == $loai->id ? 'selected' : '' }}>
                                                        {{ $loai->ten_loai }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error("loai_nguyen_lieu_ids.$i")
                                                <div class="form-text text-danger small text-truncate w-100">* {{ $message }}</div>
                                            @enderror
                                        </td>
                            
                                        <td>
                                            <input type="text" name="don_vi_nhaps[]"
                                                value="{{ old("don_vi_nhaps.$i") }}"
                                                class="form-control form-control-sm @error("don_vi_nhaps.$i") is-invalid @enderror"
                                                placeholder="Đơn vị" style="min-width: 100px">
                                            @error("don_vi_nhaps.$i")
                                                <div class="form-text text-danger small text-truncate w-100">* {{ $message }}</div>
                                            @enderror
                                        </td>
                                        <td>
                                            <input type="text" name="don_vi_tons[]"
                                                value="{{ old("don_vi_tons.$i") }}"
                                                class="form-control form-control-sm @error("don_vi_tons.$i") is-invalid @enderror"
                                                placeholder="Đơn vị" style="min-width: 100px">
                                            @error("don_vi_tons.$i")
                                                <div class="form-text text-danger small text-truncate w-100">* {{ $message }}</div>
                                            @enderror
                                        </td>
                            
                                        <td>
                                            <input type="number" name="so_luong_nhaps[]"
                                                value="{{ old("so_luong_nhaps.$i") }}"
                                                class="form-control form-control-sm @error("so_luong_nhaps.$i") is-invalid @enderror"
                                                placeholder="Số lượng" style="min-width: 100px">
                                            @error("so_luong_nhaps.$i")
                                                <div class="form-text text-danger small text-truncate w-100">* {{ $message }}</div>
                                            @enderror
                                        </td>
                            
                                        <td>
                                            <input type="number" name="he_so_quy_dois[]"
                                                value="{{ old("he_so_quy_dois.$i") }}"
                                                class="form-control form-control-sm @error("he_so_quy_dois.$i") is-invalid @enderror"
                                                placeholder="Hệ số" style="min-width: 90px">
                                            @error("he_so_quy_dois.$i")
                                                <div class="form-text text-danger small text-truncate w-100">* {{ $message }}</div>
                                            @enderror
                                        </td>
                            
                                        <td>
                                            <input type="number" name="don_gias[]"
                                                value="{{ old("don_gias.$i") }}"
                                                class="form-control form-control-sm @error("don_gias.$i") is-invalid @enderror"
                                                placeholder="Đơn giá" style="min-width: 100px">
                                            @error("don_gias.$i")
                                                <div class="form-text text-danger small text-truncate w-100">* {{ $message }}</div>
                                            @enderror
                                        </td>
                            
                                        <td>
                                            <input type="date" name="ngay_san_xuats[]"
                                                value="{{ old("ngay_san_xuats.$i") }}"
                                                class="form-control form-control-sm @error("ngay_san_xuats.$i") is-invalid @enderror"
                                                style="min-width: 140px">
                                            @error("ngay_san_xuats.$i")
                                                <div class="form-text text-danger small text-truncate w-100">* {{ $message }}</div>
                                            @enderror
                                        </td>
                            
                                        <td>
                                            <input type="date" name="ngay_het_hans[]"
                                                value="{{ old("ngay_het_hans.$i") }}"
                                                class="form-control form-control-sm @error("ngay_het_hans.$i") is-invalid @enderror"
                                                style="min-width: 140px">
                                            @error("ngay_het_hans.$i")
                                                <div class="form-text text-danger small text-truncate w-100">* {{ $message }}</div>
                                            @enderror
                                        </td>
                            
                                        <td>
                                            <input type="text" name="ghi_chus[]"
                                                value="{{ old("ghi_chus.$i") }}"
                                                class="form-control form-control-sm @error("ghi_chus.$i") is-invalid @enderror"
                                                placeholder="Ghi chú" style="min-width: 140px">
                                            @error("ghi_chus.$i")
                                                <div class="form-text text-danger small text-truncate w-100">* {{ $message }}</div>
                                            @enderror
                                        </td>
                            
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-outline-danger remove-row">🗑️</button>
                                        </td>
                                    </tr>
                                @endfor
                            </tbody>
                            

                        </table>
                    </div>
                </div>
            </div>

            <!-- SUBMIT -->
            <div class="text-end mb-5">
                <button type="submit" class="btn btn-success px-4 py-2 fw-bold">💾 Lưu Phiếu Nhập</button>
            </div>
        </form>
    </div>

    <!-- JS xử lý -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const addRowButton = document.getElementById('add-row');
            const body = document.getElementById('nguyen-lieu-body');

            addRowButton.addEventListener('click', function() {
                const row = body.querySelector('tr');
                const newRow = row.cloneNode(true);

                newRow.querySelectorAll('input, select').forEach(el => {
                    el.value = '';
                });

                body.appendChild(newRow);
                updateRowIndex();
            });

            body.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-row')) {
                    const rows = body.querySelectorAll('tr');
                    if (rows.length > 1 && confirm("Bạn có chắc chắn muốn xoá dòng này?")) {
                        e.target.closest('tr').remove();
                        updateRowIndex();
                    }
                }
            });

            function updateRowIndex() {
                body.querySelectorAll('tr').forEach((tr, i) => {
                    tr.querySelector('.row-index').textContent = i + 1;
                });
            }

            $('.select2').select2({
                width: '100%'
            });
        });
    </script>
    {{-- <style>
        .table td {
    min-width: 130px; /* hoặc điều chỉnh theo cột */
}
    </style> --}}

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>

@endsection
