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
                        <label for="loai_phieu" class="form-label">Loại Phiếu</label>
                        <select name="loai_phieu" id="loai_phieu"
                            class="form-select @error('loai_phieu') is-invalid @enderror">
                            <option value="">-- Chọn --</option>
                            <option value="nhap_tu_bep" {{ old('loai_phieu') == 'nhap_tu_bep' ? 'selected' : '' }}>Nhập từ
                                bếp
                            </option>
                            <option value="nhap_tu_ncc" {{ old('loai_phieu') == 'nhap_tu_ncc' ? 'selected' : '' }}>Nhập từ
                                nhà cung
                                cấp</option>
                        </select>
                        @error('loai_phieu')
                            <div class="invalid-feedback">* {{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4" id="nha_cung_cap_wrapper">
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
                        <label class="form-label">Nhân Viên</label>
                        <input type="hidden" name="nhan_vien_id" value="{{ auth()->user()->id }}">
                        <input type="text" class="form-control" value="{{ auth()->user()->ho_ten }}" readonly>
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
                                    <th class="th-don-gia">Đơn giá</th>
                                    <th class="th-ngay-san-xuat">Ngày sản xuất</th>
                                    <th class="th-ngay-het-han">Ngày hết hạn</th>
                                    <th>Ghi chú</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
            
                            <tbody id="nguyen-lieu-body">
                                @php $rowCount = count(old('ten_nguyen_lieus', [''])); @endphp
            
                                @for ($i = 0; $i < $rowCount; $i++)
                                    <tr>
                                        <td class="row-index text-center align-middle">{{ $i + 1 }}</td>
            
                                        {{-- Tên nguyên liệu --}}
                                        <td style="min-width: 260px">
                                            <input type="text" name="ten_nguyen_lieus[]"
                                                class="form-control form-control-sm ten-nguyen-lieu-input @error("ten_nguyen_lieus.$i") is-invalid @enderror"
                                                placeholder="Tên nguyên liệu"
                                                value="{{ old("ten_nguyen_lieus.$i") }}"
                                                data-index="{{ $i }}">
                                            @error("ten_nguyen_lieus.$i")
                                                <div class="form-text text-danger small">* {{ $message }}</div>
                                            @enderror
                                        </td>
            
                                        {{-- Loại nguyên liệu --}}
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
                                                <div class="form-text text-danger small">* {{ $message }}</div>
                                            @enderror
                                        </td>
            
                                        {{-- Đơn vị nhập --}}
                                        <td>
                                            <select name="don_vi_nhaps[]"
                                                class="form-select form-select-sm @error("don_vi_nhaps.$i") is-invalid @enderror"
                                                style="min-width: 80px">
                                                <option value="">Chọn đơn vị</option>
                                                @foreach ($donViNhapOptions as $value => $label)
                                                    <option value="{{ $value }}"
                                                        {{ old("don_vi_nhaps.$i") == $value ? 'selected' : '' }}>
                                                        {{ $label }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error("don_vi_nhaps.$i")
                                                <div class="form-text text-danger small">* {{ $message }}</div>
                                            @enderror
                                        </td>
            
                                        {{-- Số lượng nhập --}}
                                        <td>
                                            <input type="number" name="so_luong_nhaps[]"
                                                value="{{ old("so_luong_nhaps.$i") }}"
                                                class="form-control form-control-sm @error("so_luong_nhaps.$i") is-invalid @enderror"
                                                placeholder="Số lượng" style="min-width: 80px">
                                            @error("so_luong_nhaps.$i")
                                                <div class="form-text text-danger small">* {{ $message }}</div>
                                            @enderror
                                        </td>
            
                                        {{-- Đơn giá --}}
                                        <td class="don-gia-wrapper">
                                            <input type="number" name="don_gias[]"
                                                value="{{ old("don_gias.$i") }}"
                                                class="form-control form-control-sm @error("don_gias.$i") is-invalid @enderror"
                                                placeholder="Đơn giá" style="min-width: 100px">
                                            @error("don_gias.$i")
                                                <div class="form-text text-danger small">* {{ $message }}</div>
                                            @enderror
                                        </td>
            
                                        {{-- Ngày sản xuất --}}
                                        <td class="ngay-san-xuat-wrapper">
                                            <input type="date" name="ngay_san_xuats[]"
                                                value="{{ old("ngay_san_xuats.$i") }}"
                                                class="form-control form-control-sm @error("ngay_san_xuats.$i") is-invalid @enderror"
                                                style="min-width: 120px">
                                            @error("ngay_san_xuats.$i")
                                                <div class="form-text text-danger small">* {{ $message }}</div>
                                            @enderror
                                        </td>
            
                                        {{-- Ngày hết hạn --}}
                                        <td class="ngay-het-han-wrapper">
                                            <input type="date" name="ngay_het_hans[]"
                                                value="{{ old("ngay_het_hans.$i") }}"
                                                class="form-control form-control-sm @error("ngay_het_hans.$i") is-invalid @enderror"
                                                style="min-width: 120px">
                                            @error("ngay_het_hans.$i")
                                                <div class="form-text text-danger small">* {{ $message }}</div>
                                            @enderror
                                        </td>
            
                                        {{-- Ghi chú --}}
                                        <td>
                                            <input type="text" name="ghi_chus[]"
                                                value="{{ old("ghi_chus.$i") }}"
                                                class="form-control form-control-sm"
                                                placeholder="Ghi chú..." style="min-width: 120px">
                                        </td>
            
                                        {{-- Xóa dòng --}}
                                        <td>
                                            <button type="button" class="btn btn-danger btn-sm remove-row">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            

            <!-- NÚT SUBMIT -->
            <div class="d-flex justify-content-end mb-5">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-1"></i> Lưu Phiếu Nhập
                </button>
            </div>
        </form>

    </div>

    <!-- JS xử lý -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const body = document.getElementById('nguyen-lieu-body');
            const addRowButton = document.getElementById('add-row');
    
            // Ẩn/hiện các cột tùy theo loại phiếu
            function toggleNhaCungCap() {
                const loaiPhieu = document.getElementById('loai_phieu').value;
                const hide = loaiPhieu === 'nhap_tu_bep';
    
                // Ẩn/hiện nhà cung cấp
                const nhaCungCapWrapper = document.getElementById('nha_cung_cap_wrapper');
                if (nhaCungCapWrapper) {
                    nhaCungCapWrapper.style.display = hide ? 'none' : '';
                }
    
                // Ẩn/hiện các trường liên quan
                const toggleFields = [
                    ['.don-gia-wrapper', '.th-don-gia'],
                    ['.ngay-san-xuat-wrapper', '.th-ngay-san-xuat'],
                    ['.ngay-het-han-wrapper', '.th-ngay-het-han']
                ];
    
                toggleFields.forEach(([tdClass, thClass]) => {
                    document.querySelectorAll(tdClass).forEach(td => td.style.display = hide ? 'none' : '');
                    const th = document.querySelector(thClass);
                    if (th) th.style.display = hide ? 'none' : '';
                });
            }
    
            // Cập nhật chỉ số dòng
            function updateRowIndex() {
                const rows = body.querySelectorAll('tr');
                rows.forEach((row, i) => {
                    const indexCell = row.querySelector('.row-index');
                    if (indexCell) indexCell.textContent = i + 1;
    
                    row.querySelectorAll('[data-index]').forEach(el => {
                        el.dataset.index = i;
                    });
                });
            }
    
            // Reset các input/select trong dòng clone
            function resetInputFields(row, newIndex) {
                row.querySelectorAll('input, select').forEach(el => {
                    if (el.type !== 'hidden') el.value = '';
                    el.classList.remove('is-invalid');
    
                    const next = el.nextElementSibling;
                    if (next && next.classList.contains('invalid-feedback')) {
                        next.remove();
                    }
    
                    if (el.dataset.index !== undefined) el.dataset.index = newIndex;
                });
            }
    
            // Thêm dòng
            if (addRowButton) {
                addRowButton.addEventListener('click', function () {
                    const rows = body.querySelectorAll('tr');
                    const lastRow = rows[rows.length - 1];
                    const newIndex = rows.length;
    
                    const newRow = lastRow.cloneNode(true);
                    resetInputFields(newRow, newIndex);
                    body.appendChild(newRow);
    
                    updateRowIndex();
                });
            }
    
            // Xóa dòng
            body.addEventListener('click', function (e) {
                if (e.target.classList.contains('remove-row')) {
                    const rows = body.querySelectorAll('tr');
                    if (rows.length > 1) {
                        e.target.closest('tr').remove();
                        updateRowIndex();
                    } else {
                        alert('Phải có ít nhất 1 dòng nguyên liệu.');
                    }
                }
            });
    
            // Bắt đầu
            toggleNhaCungCap();
            document.getElementById('loai_phieu').addEventListener('change', toggleNhaCungCap);
        });
    </script>
    
    


    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>

@endsection
