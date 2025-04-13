@extends('layouts.admin')

@section('title', 'Cập nhật phiếu xuất kho')

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
    <div class="container mt-4">
        <h3 class="mb-4">📝 Cập nhật Phiếu Xuất Kho</h3>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @elseif (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form id="editPhieuForm" method="POST" action="{{ route('phieu-xuat-kho.update', $phieuXuatKho->id) }}" novalidate>
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Mã phiếu</label>
                    <input type="text" class="form-control @error('ma_phieu') is-invalid @enderror" name="ma_phieu"
                        value="{{ old('ma_phieu', $phieuXuatKho->ma_phieu) }}">
                    @error('ma_phieu')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Nhân viên</label>
                    <select name="nhan_vien_id" class="form-select @error('nhan_vien_id') is-invalid @enderror">
                        @foreach ($nhanViens as $nv)
                            <option value="{{ $nv->id }}"
                                {{ old('nhan_vien_id', $phieuXuatKho->nhan_vien_id) == $nv->id ? 'selected' : '' }}>
                                {{ $nv->ho_ten }}
                            </option>
                        @endforeach
                    </select>
                    @error('nhan_vien_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                @if ($phieuXuatKho->loai_phieu === 'xuat_tra_hang')
                    <div class="col-md-6">
                        <label class="form-label">Nhà cung cấp</label>
                        <input type="text" class="form-control" readonly
                            value="{{ $phieuXuatKho->nhaCungCap->ten_nha_cung_cap ?? '' }}">
                        <input type="hidden" name="nha_cung_cap_id" value="{{ $phieuXuatKho->nha_cung_cap_id }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Người nhận</label>
                        <input type="text" name="nguoi_nhan"
                            class="form-control @error('nguoi_nhan') is-invalid @enderror"
                            value="{{ old('nguoi_nhan', $phieuXuatKho->nguoi_nhan) }}">
                        @error('nguoi_nhan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                @else
                    <div class="col-md-6">
                        <label class="form-label">Người nhận</label>
                        <input type="text" name="nguoi_nhan"
                            class="form-control @error('nguoi_nhan') is-invalid @enderror"
                            value="{{ old('nguoi_nhan', $phieuXuatKho->nguoi_nhan) }}">
                        @error('nguoi_nhan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                @endif
                <div class="col-md-6">
                    <label class="form-label">Loại phiếu</label>

                    @php
                        $loaiPhieu = old('loai_phieu', $phieuXuatKho->loai_phieu);
                        $label = match ($loaiPhieu) {
                            'xuat_bep' => 'Xuất bếp',
                            'xuat_huy' => 'Xuất hủy hàng',
                            'xuat_tra_hang' => 'Xuất trả hàng',
                            default => 'Không xác định',
                        };
                    @endphp

                    <input type="text" class="form-control" value="{{ $label }}" readonly>
                    <input type="hidden" name="loai_phieu" value="{{ $loaiPhieu }}">

                    @error('loai_phieu')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Ngày xuất</label>
                    <input type="date" name="ngay_xuat" class="form-control @error('ngay_xuat') is-invalid @enderror"
                        value="{{ old('ngay_xuat', \Carbon\Carbon::parse($phieuXuatKho->ngay_xuat)->format('Y-m-d')) }}">
                    @error('ngay_xuat')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <hr class="my-4">
            <h5>📦 Chi tiết phiếu xuất</h5>
            <div class="table-responsive">
                <table class="table table-bordered align-middle" id="chiTietTable">
                    <thead class="table-light">
                        <tr>
                            <th>Nguyên liệu</th>
                            <th>Đơn vị</th>
                            <th>Số lượng</th>
                            <th>Hệ số</th>
                            <th>Đơn giá</th>
                            <th>Ghi chú</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($phieuXuatKho->chiTietPhieuXuatKhos as $ct)
                            <tr>
                                <td>
                                    <input type="hidden" name="chi_tiet_ids[]" value="{{ $ct->id }}">
                                    <input type="hidden" name="loai_nguyen_lieu_ids[]"
                                        value="{{ $ct->nguyenLieu->loai_nguyen_lieu_id }}">
                                    <select name="nguyen_lieu_ids[]"
                                        class="form-select @error('nguyen_lieu_ids.*') is-invalid @enderror">
                                        @foreach ($loaiNguyenLieus as $loai)
                                            @foreach ($loai->nguyenLieus as $nl)
                                                <option value="{{ $nl->id }}"
                                                    {{ $nl->id == $ct->nguyen_lieu_id ? 'selected' : '' }}>
                                                    {{ $loai->ten_loai }} - {{ $nl->ten_nguyen_lieu }}
                                                </option>
                                            @endforeach
                                        @endforeach
                                    </select>
                                    @error('nguyen_lieu_ids.*')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>

                                <td><input type="text" name="don_vi_xuats[]" class="form-control"
                                        value="{{ $ct->don_vi_xuat }}"></td>
                                <td><input type="number" name="so_luongs[]" class="form-control"
                                        value="{{ $ct->so_luong }}"></td>
                                <td><input type="number" step="0.01" name="he_so_quy_dois[]" class="form-control"
                                        value="{{ $ct->he_so_quy_doi }}"></td>
                                <td><input type="number" name="don_gias[]" class="form-control"
                                        value="{{ $ct->don_gia }}"></td>
                                <td><input type="text" name="ghi_chus[]" class="form-control"
                                        value="{{ $ct->ghi_chu }}"></td>
                                <td><button type="button" class="btn btn-outline-danger btn-sm remove-row">Xoá</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <button type="button" id="addRow" class="btn btn-outline-primary mb-4">➕ Thêm dòng</button>

            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-success">💾 Lưu cập nhật</button>
                <a href="{{ route('phieu-xuat-kho.index') }}" class="btn btn-secondary">⬅ Quay lại</a>
            </div>
        </form>
    </div>

    <script>
        const nguyenLieuOptions = @json($nguyenLieuOptions);

        $('#addRow').click(function() {
            let options = '';
            let firstLoaiId = '';
            let firstDonGia = '';

            // Bỏ qua nguyên liệu bị xoá khi chọn mặc định
            const validNguyenLieu = nguyenLieuOptions.find(nl => nl.deleted_at === null);
            if (validNguyenLieu) {
                firstLoaiId = validNguyenLieu.loai_nguyen_lieu_id;
                firstDonGia = validNguyenLieu.don_gia;
            }

            nguyenLieuOptions.forEach(opt => {
                const isDeleted = opt.deleted_at !== null;
                const deletedLabel = isDeleted ? ' (ĐÃ XOÁ)' : '';
                const disabledAttr = isDeleted ? 'disabled style="color:red;"' : '';

                options += `<option value="${opt.id}" data-loai="${opt.loai_nguyen_lieu_id}" data-don-gia="${opt.don_gia}" ${disabledAttr}>
                        ${opt.text}${deletedLabel}
                    </option>`;
            });

            const row = `
        <tr>
            <td>
                <input type="hidden" name="chi_tiet_ids[]" value="">
                <input type="hidden" name="loai_nguyen_lieu_ids[]" class="loai-id-hidden" value="${firstLoaiId}">
                <select name="nguyen_lieu_ids[]" class="form-select">${options}</select>
            </td>
            <td><input type="text" name="don_vi_xuats[]" class="form-control"></td>
            <td><input type="number" name="so_luongs[]" class="form-control"></td>
            <td><input type="number" step="0.01" name="he_so_quy_dois[]" class="form-control"></td>
            <td><input type="number" name="don_gias[]" class="form-control" value="${firstDonGia}"></td>
            <td><input type="text" name="ghi_chus[]" class="form-control"></td>
            <td><button type="button" class="btn btn-outline-danger btn-sm remove-row">Xoá</button></td>
        </tr>`;
            $('#chiTietTable tbody').append(row);
        });



        $(document).on('click', '.remove-row', function() {
            $(this).closest('tr').remove();
        });

        // Tự động gán đơn giá khi chọn nguyên liệu
        $(document).on('change', 'select[name="nguyen_lieu_ids[]"]', function() {
            const selectedOption = $(this).find('option:selected');
            const loaiId = selectedOption.data('loai');
            const donGia = selectedOption.data('don-gia');

            $(this).closest('td').find('input.loai-id-hidden').val(loaiId);
            $(this).closest('tr').find('input[name="don_gias[]"]').val(donGia);
        });
    </script>
@endsection
