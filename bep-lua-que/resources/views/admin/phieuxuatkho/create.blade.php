@extends('layouts.admin')

@section('title', 'Tạo Phiếu Xuất Kho')

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Đã xảy ra lỗi:</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('phieu-xuat-kho.store') }}" method="POST">
        @csrf

        <!-- Thông tin phiếu -->
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-primary text-white"><strong>Thông Tin Phiếu Xuất</strong></div>
            <div class="card-body row g-3">
                <div class="col-md-3">
                    <label class="form-label">Mã Phiếu</label>
                    <input type="text" name="ma_phieu" value="{{ old('ma_phieu', $nextCode) }}" readonly
                        class="form-control">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Ngày Xuất</label>
                    <input type="date" name="ngay_xuat" value="{{ old('ngay_xuat', now()->toDateString()) }}"
                        class="form-control @error('ngay_xuat') is-invalid @enderror">
                    @error('ngay_xuat')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Nhân Viên</label>
                    <select name="nhan_vien_id" class="form-select @error('nhan_vien_id') is-invalid @enderror">
                        <option value="">-- Chọn --</option>
                        @foreach ($nhanViens as $nv)
                            <option value="{{ $nv->id }}" {{ old('nhan_vien_id') == $nv->id ? 'selected' : '' }}>
                                {{ $nv->ho_ten }}</option>
                        @endforeach
                    </select>
                    @error('nhan_vien_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Loại Phiếu</label>
                    <select name="loai_phieu" id="loai_phieu" class="form-select @error('loai_phieu') is-invalid @enderror">
                        <option value="">-- Chọn --</option>
                        <option value="xuat_bep" {{ old('loai_phieu') == 'xuat_bep' ? 'selected' : '' }}>Xuất Bếp</option>
                        <option value="xuat_tra_hang" {{ old('loai_phieu') == 'xuat_tra_hang' ? 'selected' : '' }}>Trả Nhà
                            Cung Cấp</option>
                        <option value="xuat_huy" {{ old('loai_phieu') == 'xuat_huy' ? 'selected' : '' }}>Hủy Hàng</option>
                    </select>
                    @error('loai_phieu')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6" id="nguoi_nhan_group">
                    <label class="form-label">Người Nhận</label>
                    <input type="text" name="nguoi_nhan" value="{{ old('nguoi_nhan') }}"
                        class="form-control @error('nguoi_nhan') is-invalid @enderror">
                    @error('nguoi_nhan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 d-none" id="nha_cung_cap_group">
                    <label class="form-label">Nhà Cung Cấp</label>
                    <select name="nha_cung_cap_id" class="form-select @error('nha_cung_cap_id') is-invalid @enderror">
                        <option value="">-- Chọn --</option>
                        @foreach ($nhaCungCaps as $ncc)
                            <option value="{{ $ncc->id }}"
                                {{ old('nha_cung_cap_id') == $ncc->id ? 'selected' : '' }}>
                                {{ $ncc->ten_nha_cung_cap }}
                            </option>
                        @endforeach
                    </select>
                    @error('nha_cung_cap_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Ghi chú</label>
                    <textarea name="ghi_chu" class="form-control @error('ghi_chu') is-invalid @enderror">{{ old('ghi_chu') }}</textarea>
                    @error('ghi_chu')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Chi tiết nguyên liệu -->
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                <strong>Nguyên Liệu Xuất</strong>
                <button type="button" id="add-row" class="btn btn-light btn-sm">+ Thêm dòng</button>
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered text-center align-middle mb-0" id="nguyen-lieu-table">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Loại nguyên liệu</th>
                            <th>Nguyên liệu</th>
                            <th>Đơn vị xuất</th>
                            <th>Hệ số quy đổi</th>
                            <th>Số lượng xuất</th>
                            <th class="don-gia-column">Đơn giá</th>
                            <th>Ghi chú</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="nguyen-lieu-body">
                        @php $oldNguyenLieus = old('nguyen_lieu_ids', ['']); @endphp
                        @foreach ($oldNguyenLieus as $index => $value)
                            <tr>
                                <td class="row-index">{{ $loop->iteration }}</td>
                                <td>
                                    <select name="loai_nguyen_lieu_ids[]"
                                        class="form-select form-select-sm @error("loai_nguyen_lieu_ids.$index") is-invalid @enderror">
                                        <option value="">-- Chọn --</option>
                                        @foreach ($loaiNguyenLieus as $loai)
                                            <option value="{{ $loai->id }}"
                                                {{ old("loai_nguyen_lieu_ids.$index") == $loai->id ? 'selected' : '' }}>
                                                {{ $loai->ten_loai }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error("loai_nguyen_lieu_ids.$index")
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td>
                                    <select name="nguyen_lieu_ids[]"
                                        class="form-select form-select-sm nguyen-lieu-select @error("nguyen_lieu_ids.$index") is-invalid @enderror">
                                        <option value="">-- Chọn --</option>
                                        @foreach ($nguyenLieus as $nl)
                                            <option value="{{ $nl->id }}" data-loai="{{ $nl->loai_nguyen_lieu_id }}"
                                                data-don-gia="{{ $nl->don_gia }}"
                                                data-deleted="{{ $nl->deleted_at ? '1' : '0' }}"
                                                class="{{ $nl->deleted_at ? 'text-danger bg-light' : '' }}"
                                                {{ old("nguyen_lieu_ids.$index") == $nl->id ? 'selected' : '' }}>
                                                {{ $nl->ten_nguyen_lieu }}{{ $nl->deleted_at ? ' (Đã xóa)' : '' }}
                                            </option>
                                        @endforeach

                                    </select>
                                    @error("nguyen_lieu_ids.$index")
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td>
                                    <input type="text" name="don_vi_xuats[]"
                                        class="form-control form-control-sm @error("don_vi_xuats.$index") is-invalid @enderror"
                                        value="{{ old("don_vi_xuats.$index") }}">
                                    @error("don_vi_xuats.$index")
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td>
                                    <input type="text" name="he_so_quy_dois[]"
                                        class="form-control form-control-sm @error("he_so_quy_dois.$index") is-invalid @enderror"
                                        value="{{ old("he_so_quy_dois.$index") }}">
                                    @error("he_so_quy_dois.$index")
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td>
                                    <input type="number" step="0.01" min="0.01" name="so_luong_xuats[]"
                                        class="form-control form-control-sm @error("so_luong_xuats.$index") is-invalid @enderror"
                                        value="{{ old("so_luong_xuats.$index") }}">
                                    @error("so_luong_xuats.$index")
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td class="don-gia-column">
                                    <input type="number" step="0.01" min="0" name="don_gias[]"
                                        class="form-control form-control-sm don-gia-input @error("don_gias.$index") is-invalid @enderror"
                                        value="{{ old("don_gias.$index") }}">
                                    @error("don_gias.$index")
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>

                                <td>
                                    <input type="text" name="ghi_chus[]" class="form-control form-control-sm"
                                        value="{{ old("ghi_chus.$index") }}">
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-outline-danger remove-row">🗑️</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>



        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-primary">Lưu Phiếu Xuất</button>
            <a href="{{ route('phieu-xuat-kho.index') }}" class="btn btn-secondary">⬅ Quay lại</a>
        </div>
    </form>

    <script>
        $(document).ready(function() {
            function updateRowIndex() {
                $('#nguyen-lieu-body tr').each((i, row) => {
                    $(row).find('.row-index').text(i + 1);
                });
            }

            function toggleDonGiaColumn() {
                const loaiPhieu = $('#loai_phieu').val();
                if (loaiPhieu === 'xuat_tra_hang') {
                    $('.don-gia-column').show();
                } else {
                    $('.don-gia-column').hide();
                }
            }

            function toggleFieldsByLoaiPhieu() {
                const loaiPhieu = $('#loai_phieu').val();
                if (loaiPhieu === 'xuat_tra_hang') {
                    $('#nha_cung_cap_group').removeClass('d-none');
                    $('#nguoi_nhan_group').removeClass('d-none');
                } else {
                    $('#nha_cung_cap_group').addClass('d-none');
                    $('#nguoi_nhan_group').removeClass('d-none');
                }
            }

            function handleNguyenLieuDisabled() {
                const isXuatBep = $('#loai_phieu').val() === 'xuat_bep';

                $('#nguyen-lieu-body select[name="nguyen_lieu_ids[]"]').each(function() {
                    const select = $(this);
                    select.find('option').each(function() {
                        const isDeleted = $(this).data('deleted') === 1 || $(this).data(
                            'deleted') === '1';
                        $(this).prop('disabled', isXuatBep && isDeleted);
                    });
                });
            }

            function addNewRow() {
                const lastRow = $('#nguyen-lieu-body tr:last');
                const newRow = lastRow.clone();

                newRow.find('input, select').each(function() {
                    $(this).val('').removeClass('is-invalid');
                });

                newRow.find('.invalid-feedback').remove();

                $('#nguyen-lieu-body').append(newRow);
                updateRowIndex();
                handleNguyenLieuDisabled();
            }

            // Bind 1 lần duy nhất
            $('#add-row').on('click', function() {
                addNewRow();
            });

            $('#nguyen-lieu-body').on('click', '.remove-row', function() {
                if ($('#nguyen-lieu-body tr').length > 1) {
                    $(this).closest('tr').remove();
                    updateRowIndex();
                }
            });

            $('#nguyen-lieu-body').on('change', 'select[name="loai_nguyen_lieu_ids[]"]', function() {
                const selectedLoaiId = $(this).val();
                const nguyenLieuSelect = $(this).closest('tr').find('select[name="nguyen_lieu_ids[]"]');

                nguyenLieuSelect.find('option').each(function() {
                    const loaiId = $(this).data('loai');
                    $(this).toggle(!$(this).val() || loaiId == selectedLoaiId);
                });

                nguyenLieuSelect.val('');
            });

            $('#nguyen-lieu-body').on('change', 'select[name="nguyen_lieu_ids[]"]', function() {
                const selectedOption = $(this).find('option:selected');
                const selectedValue = $(this).val();
                const donGia = parseFloat(selectedOption.data('don-gia')) || 0;

                if ($('#loai_phieu').val() === 'xuat_tra_hang') {
                    const donGiaInput = $(this).closest('tr').find('input[name="don_gias[]"]');
                    donGiaInput.val(donGia.toFixed(2));
                }

                // ✅ Kiểm tra trùng nguyên liệu
                let isDuplicate = false;
                const currentRow = $(this).closest('tr');

                $('select[name="nguyen_lieu_ids[]"]').not(this).each(function() {
                    if ($(this).val() === selectedValue) {
                        isDuplicate = true;
                        return false; // break
                    }
                });

                if (isDuplicate) {
                    alert('Nguyên liệu đã được chọn ở dòng khác!');
                    $(this).val('').trigger('change'); // reset lại chọn
                }
            });


            $('#loai_phieu').on('change', function() {
                toggleDonGiaColumn();
                toggleFieldsByLoaiPhieu();
                handleNguyenLieuDisabled();
            });

            // Lúc load lần đầu
            toggleDonGiaColumn();
            toggleFieldsByLoaiPhieu();
            handleNguyenLieuDisabled();
        });
    </script>



@endsection
