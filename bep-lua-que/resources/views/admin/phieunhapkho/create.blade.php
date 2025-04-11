@extends('layouts.admin')

@section('title', 'Tạo Phiếu Nhập Kho')

@section('content')
    {{-- @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Đã xảy ra lỗi:</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif --}}
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
                                    <th>Đơn vị tồn</th>
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

                                        <td style="min-width: 260px">
                                            <div class="d-flex flex-column gap-1">
                                                <!-- Dòng đầu: Checkbox + Label -->
                                                <div class="form-check form-switch mb-1">
                                                    <input class="form-check-input toggle-ten-nguyen-lieu" type="checkbox"
                                                        data-index="{{ $i }}"
                                                        id="isCustomCheck{{ $i }}"
                                                        {{ old("is_custom.$i") ? 'checked' : '' }}>
                                                    <label class="form-check-label small"
                                                        for="isCustomCheck{{ $i }}">Tự nhập</label>
                                                </div>

                                                <!-- Chọn nguyên liệu có sẵn -->
                                                <select name="nguyen_lieu_ids[]"
                                                    class="form-select form-select-sm nguyen-lieu-select {{ old("is_custom.$i") ? 'd-none' : '' }}"
                                                    data-index="{{ $i }}"
                                                    {{ old("is_custom.$i") ? 'disabled' : '' }}>
                                                    <option value="">-- Chọn nguyên liệu --</option>
                                                    @foreach ($nguyenLieus as $ng)
                                                        <option value="{{ $ng->id }}"
                                                            {{ old("nguyen_lieu_ids.$i") == $ng->id ? 'selected' : '' }}>
                                                            {{ $ng->ten_nguyen_lieu }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error("nguyen_lieu_ids.$i")
                                                    @if (!old("is_custom.$i"))
                                                        <div class="form-text text-danger small">* {{ $message }}</div>
                                                    @endif
                                                @enderror

                                                <!-- Tự nhập tên nguyên liệu -->
                                                <input type="text" name="ten_nguyen_lieus[]"
                                                    class="form-control form-control-sm ten-nguyen-lieu-input {{ old("is_custom.$i") ? '' : 'd-none' }}"
                                                    placeholder="Tên nguyên liệu tự nhập"
                                                    value="{{ old("ten_nguyen_lieus.$i") }}"
                                                    data-index="{{ $i }}"
                                                    {{ old("is_custom.$i") ? '' : 'disabled' }}>
                                                @error("ten_nguyen_lieus.$i")
                                                    @if (old("is_custom.$i"))
                                                        <div class="form-text text-danger small">* {{ $message }}</div>
                                                    @endif
                                                @enderror

                                                <!-- Hidden is_custom -->
                                                <input type="hidden" name="is_custom[]"
                                                    value="{{ old("is_custom.$i") ? 1 : 0 }}" class="is-custom-hidden"
                                                    data-index="{{ $i }}">
                                            </div>
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
                                                <div class="form-text text-danger small text-truncate w-100">*
                                                    {{ $message }}</div>
                                            @enderror
                                        </td>

                                        <td>
                                            <input type="text" name="don_vi_nhaps[]"
                                                value="{{ old("don_vi_nhaps.$i") }}"
                                                class="form-control form-control-sm @error("don_vi_nhaps.$i") is-invalid @enderror"
                                                placeholder="Đơn vị" style="min-width: 80px">
                                            @error("don_vi_nhaps.$i")
                                                <div class="form-text text-danger small text-truncate w-100">*
                                                    {{ $message }}</div>
                                            @enderror
                                        </td>
                                        <td>
                                            <input type="text" name="don_vi_tons[]"
                                                value="{{ old("don_vi_tons.$i") }}"
                                                class="form-control form-control-sm @error("don_vi_tons.$i") is-invalid @enderror"
                                                placeholder="Đơn vị" style="min-width: 80px">
                                            @error("don_vi_tons.$i")
                                                <div class="form-text text-danger small text-truncate w-100">*
                                                    {{ $message }}</div>
                                            @enderror
                                        </td>

                                        <td>
                                            <input type="number" name="so_luong_nhaps[]"
                                                value="{{ old("so_luong_nhaps.$i") }}"
                                                class="form-control form-control-sm @error("so_luong_nhaps.$i") is-invalid @enderror"
                                                placeholder="Số lượng" style="min-width: 80px">
                                            @error("so_luong_nhaps.$i")
                                                <div class="form-text text-danger small text-truncate w-100">*
                                                    {{ $message }}</div>
                                            @enderror
                                        </td>

                                        <td>
                                            <input type="number" name="he_so_quy_dois[]"
                                                value="{{ old("he_so_quy_dois.$i") }}"
                                                class="form-control form-control-sm @error("he_so_quy_dois.$i") is-invalid @enderror"
                                                placeholder="Hệ số" style="min-width: 100px">
                                            @error("he_so_quy_dois.$i")
                                                <div class="form-text text-danger small text-truncate w-100">*
                                                    {{ $message }}</div>
                                            @enderror
                                        </td>

                                        <td>
                                            <input type="number" name="don_gias[]" value="{{ old("don_gias.$i") }}"
                                                class="form-control form-control-sm @error("don_gias.$i") is-invalid @enderror"
                                                placeholder="Đơn giá" style="min-width: 100px">
                                            @error("don_gias.$i")
                                                <div class="form-text text-danger small text-truncate w-100">*
                                                    {{ $message }}</div>
                                            @enderror
                                        </td>

                                        <td>
                                            <input type="date" name="ngay_san_xuats[]"
                                                value="{{ old("ngay_san_xuats.$i") }}"
                                                class="form-control form-control-sm @error("ngay_san_xuats.$i") is-invalid @enderror"
                                                style="min-width: 120px">
                                            @error("ngay_san_xuats.$i")
                                                <div class="form-text text-danger small text-truncate w-100">*
                                                    {{ $message }}</div>
                                            @enderror
                                        </td>

                                        <td>
                                            <input type="date" name="ngay_het_hans[]"
                                                value="{{ old("ngay_het_hans.$i") }}"
                                                class="form-control form-control-sm @error("ngay_het_hans.$i") is-invalid @enderror"
                                                style="min-width: 120px">
                                            @error("ngay_het_hans.$i")
                                                <div class="form-text text-danger small text-truncate w-100">*
                                                    {{ $message }}</div>
                                            @enderror
                                        </td>

                                        <td>
                                            <input type="text" name="ghi_chus[]" value="{{ old("ghi_chus.$i") }}"
                                                class="form-control form-control-sm @error("ghi_chus.$i") is-invalid @enderror"
                                                placeholder="Ghi chú" style="min-width: 140px">
                                            @error("ghi_chus.$i")
                                                <div class="form-text text-danger small text-truncate w-100">*
                                                    {{ $message }}</div>
                                            @enderror
                                        </td>

                                        <td class="text-center">
                                            <button type="button"
                                                class="btn btn-sm btn-outline-danger remove-row">🗑️</button>
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
            const body = document.getElementById('nguyen-lieu-body');

            // Hàm toggle hiển thị ô nhập tên nguyên liệu hoặc select
            function toggleTenNguyenLieu(index, isChecked) {
                const select = document.querySelector(`select[name="nguyen_lieu_ids[]"][data-index="${index}"]`);
                const input = document.querySelector(`input[name="ten_nguyen_lieus[]"][data-index="${index}"]`);
                const hidden = document.querySelector(`input.is-custom-hidden[data-index="${index}"]`);

                if (isChecked) {
                    select.classList.add('d-none');
                    select.disabled = true;

                    input.classList.remove('d-none');
                    input.disabled = false;

                    hidden.value = 1;
                } else {
                    select.classList.remove('d-none');
                    select.disabled = false;

                    input.classList.add('d-none');
                    input.disabled = true;

                    hidden.value = 0;
                }
            }

            // Cập nhật lại chỉ số dòng
            function updateRowIndex() {
                const rows = body.querySelectorAll('tr');
                rows.forEach((row, i) => {
                    row.querySelector('.row-index').innerText = i + 1;
                    row.querySelectorAll('[data-index]').forEach(el => {
                        el.dataset.index = i;

                        // Cập nhật lại id và for cho checkbox + label
                        if (el.id?.startsWith('isCustomCheck')) {
                            const newId = `isCustomCheck${i}`;
                            el.id = newId;
                            const label = row.querySelector(`label[for^="isCustomCheck"]`);
                            if (label) label.setAttribute('for', newId);
                        }
                    });
                });
            }

            // Gắn sự kiện toggle cho tất cả checkbox đang hiển thị
            document.querySelectorAll('.toggle-ten-nguyen-lieu').forEach(checkbox => {
                const index = checkbox.dataset.index;
                toggleTenNguyenLieu(index, checkbox.checked);
            });

            // Bắt sự kiện khi thay đổi checkbox "Tự nhập"
            body.addEventListener('change', function(e) {
                if (e.target.classList.contains('toggle-ten-nguyen-lieu')) {
                    const index = e.target.dataset.index;
                    toggleTenNguyenLieu(index, e.target.checked);
                }
            });

            // Xử lý nút thêm dòng mới
            const addRowButton = document.getElementById('add-row');
            if (addRowButton) {
                addRowButton.addEventListener('click', function() {
                    const rows = body.querySelectorAll('tr');
                    const lastRow = rows[rows.length - 1];
                    const newRow = lastRow.cloneNode(true);
                    const newIndex = rows.length;

                    // Reset tất cả các input/select trong dòng mới
                    newRow.querySelectorAll('input, select').forEach(el => {
                        if (el.type === 'checkbox') {
                            el.checked = false;
                        } else {
                            el.value = '';
                        }

                        el.classList.remove('is-invalid'); // XÓA trạng thái lỗi nếu có
                        const nextSibling = el.nextElementSibling;
                        if (nextSibling && nextSibling.classList.contains('invalid-feedback')) {
                            nextSibling.remove(); // XÓA thông báo lỗi
                        }

                        if (el.classList.contains('form-check-input')) {
                            const newId = `isCustomCheck${newIndex}`;
                            el.id = newId;
                        }

                        if (el.name === 'is_custom[]') {
                            el.value = 0;
                        }

                        // xử lý toggle lại giữa input và select
                        if (el.classList.contains('ten-nguyen-lieu-input')) {
                            el.classList.add('d-none');
                            el.disabled = true;
                        }

                        if (el.classList.contains('nguyen-lieu-select')) {
                            el.classList.remove('d-none');
                            el.disabled = false;
                        }
                    });


                    // Cập nhật data-index
                    newRow.querySelectorAll('[data-index]').forEach(el => {
                        el.dataset.index = newIndex;
                    });

                    // Cập nhật lại label for của checkbox
                    const checkbox = newRow.querySelector('.toggle-ten-nguyen-lieu');
                    const label = newRow.querySelector('label.form-check-label');
                    if (checkbox && label) {
                        checkbox.id = `isCustomCheck${newIndex}`;
                        label.setAttribute('for', `isCustomCheck${newIndex}`);
                    }

                    body.appendChild(newRow);
                    updateRowIndex();
                });
            }

            // Xử lý xóa dòng
            body.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-row')) {
                    const row = e.target.closest('tr');
                    if (body.querySelectorAll('tr').length > 1) {
                        row.remove();
                        updateRowIndex();
                    } else {
                        alert("Phải có ít nhất 1 dòng nguyên liệu.");
                    }
                }
            });
        });
    </script>


    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>

@endsection
