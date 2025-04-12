@extends('layouts.admin')

@section('title', 'Thêm mới món ăn')

@section('content')
    <div class="container mt-4">
        <div class="card shadow rounded-3">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Thêm Món Ăn</h4>
            </div>
            <div class="card-body">
                {{-- Hiển thị lỗi tổng --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('mon-an.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-2">
                        <!-- Tên món -->
                        <div class="col-md-6">
                            <label for="ten" class="form-label">Tên món ăn</label>
                            <input type="text" name="ten" id="ten" class="form-control"
                                value="{{ old('ten') }}">
                            @error('ten')
                                <small class="text-danger d-block">*{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Danh mục -->
                        <div class="col-md-6">
                            <label for="danh_muc_mon_an_id" class="form-label">Danh mục</label>
                            <select name="danh_muc_mon_an_id" id="danh_muc_mon_an_id" class="form-select">
                                <option value="">-- Chọn danh mục --</option>
                                @foreach ($danhMucs as $danhMuc)
                                    <option value="{{ $danhMuc->id }}"
                                        {{ old('danh_muc_mon_an_id') == $danhMuc->id ? 'selected' : '' }}>
                                        {{ $danhMuc->ten }}
                                    </option>
                                @endforeach
                            </select>
                            @error('danh_muc_mon_an_id')
                                <small class="text-danger d-block">*{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Giá -->
                        <div class="col-md-6">
                            <label for="gia" class="form-label">Giá (VNĐ)</label>
                            <input type="number" name="gia" id="gia" class="form-control"
                                value="{{ old('gia') }}">
                            @error('gia')
                                <small class="text-danger d-block">*{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Hình ảnh -->
                        <div class="col-md-6">
                            <label for="hinh_anh" class="form-label">Hình ảnh</label>
                            <input type="file" name="hinh_anh[]" id="hinh_anh" class="form-control" multiple
                                accept="image/*">
                            @error('hinh_anh.*')
                                <small class="text-danger d-block">*{{ $message }}</small>
                            @enderror
                        </div>



                        <!--thời gian nấu-->
                        <div class="col-6">
                            <label for="thoi_gian_nau" class="form-label">Thời gian nấu</label>
                            <input type="text" name="thoi_gian_nau" id="thoi_gian_nau" class="form-control"
                                value="{{ old('thoi_gian_nau') }}">
                            @error('thoi_gian_nau')
                                <small class="text-danger d-block">*{{ $message }}</small>
                            @enderror

                        </div>
                        
                        <!-- Mô tả -->
                        <div class="col-12">
                            <label for="mo_ta" class="form-label">Mô tả</label>
                            <textarea name="mo_ta" id="mo_ta" class="form-control" rows="2">{{ old('mo_ta') }}</textarea>
                            @error('mo_ta')
                                <small class="text-danger d-block">*{{ $message }}</small>
                            @enderror
                        </div>

                    </div>

                    <!-- Hiển thị preview ảnh -->
                    <div id="previewImages" class="mt-3 d-flex flex-wrap gap-2"></div>

                    <hr class="my-3">
                    <h5 class="mb-2">Nguyên liệu & Công thức</h5>
                    <div id="cong-thuc-wrapper">
                        @php
                            $oldCongThuc = old('cong_thuc', [
                                ['nguyen_lieu_id' => '', 'so_luong' => '', 'don_vi' => ''],
                            ]);
                        @endphp
                        @foreach ($oldCongThuc as $index => $item)
                            <div class="row align-items-end mb-2 cong-thuc-row border-bottom pb-2">
                                <div class="col-md-5">
                                    <label class="form-label">Nguyên liệu</label>
                                    <select name="cong_thuc[{{ $index }}][nguyen_lieu_id]"
                                        class="form-select nguyenlieu-select" data-index="{{ $index }}">
                                        <option value="">-- Chọn nguyên liệu --</option>
                                        @foreach ($nguyenLieus as $nl)
                                            <option value="{{ $nl->id }}"
                                                {{ $item['nguyen_lieu_id'] == $nl->id ? 'selected' : '' }}>
                                                {{ $nl->ten_nguyen_lieu }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error("cong_thuc.$index.nguyen_lieu_id")
                                        <small class="text-danger d-block">*{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Số lượng</label>
                                    <input type="number" step="0.01" name="cong_thuc[{{ $index }}][so_luong]"
                                        class="form-control" value="{{ $item['so_luong'] }}">
                                    @error("cong_thuc.$index.so_luong")
                                        <small class="text-danger d-block">*{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Đơn vị</label>
                                    <input type="text" name="cong_thuc[{{ $index }}][don_vi]"
                                        class="form-control donvi-input" value="{{ $item['don_vi'] }}" readonly>
                                    @error("cong_thuc.$index.don_vi")
                                        <small class="text-danger d-block">*{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-danger w-100 btn-remove-row mt-4">Xóa</button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <button type="button" class="btn btn-outline-primary btn-sm mb-3" id="btn-add-nguyenlieu">
                        <i class="fas fa-plus"></i> Thêm nguyên liệu
                    </button>



                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('mon-an.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-plus-circle"></i> Thêm món ăn
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#hinh_anh').on('change', function() {
                const previewContainer = $('#previewImages');
                previewContainer.empty(); // clear ảnh cũ
                const files = this.files;

                if (files.length === 0) return;

                Array.from(files).forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const imgHtml = `
                        <div class="position-relative">
                            <img src="${e.target.result}" class="img-thumbnail" style="width:100px;height:100px;object-fit:cover;">
                            <button type="button" class="btn btn-danger btn-sm remove-image" data-index="${index}" 
                                style="position:absolute;top:2px;right:2px;font-size:12px;padding:2px 6px;">×</button>
                        </div>`;
                        previewContainer.append(imgHtml);
                    };
                    reader.readAsDataURL(file);
                });
            });

            // Remove 1 ảnh đã chọn
            $(document).on('click', '.remove-image', function() {
                const indexToRemove = $(this).data('index');
                const input = $('#hinh_anh')[0];
                const dt = new DataTransfer();
                Array.from(input.files).forEach((file, i) => {
                    if (i !== indexToRemove) dt.items.add(file);
                });
                input.files = dt.files;
                $(this).closest('div').remove();
            });
        });


        $(document).on("click", ".remove-image", function() {
            let index = $(this).data("index");
            let dataTransfer = new DataTransfer();
            let files = $("#hinh_anh")[0].files;
            Array.from(files).forEach((file, i) => {
                if (i !== index) dataTransfer.items.add(file);
            });
            $("#hinh_anh")[0].files = dataTransfer.files;
            $(this).closest("div").remove();
        });

        // Lấy danh sách nguyên liệu (đã keyBy id)
        const nguyenLieus = @json($nguyenLieus->keyBy('id'));

        // Thêm dòng công thức mới
        $('#btn-add-nguyenlieu').click(function() {
            let index = $('.cong-thuc-row').length;
            let newRow = `
                <div class="row align-items-end mb-2 cong-thuc-row border-bottom pb-2">
                    <div class="col-md-5">
                        <label class="form-label">Nguyên liệu</label>
                        <select name="cong_thuc[${index}][nguyen_lieu_id]" class="form-select nguyenlieu-select" data-index="${index}">
                            <option value="">-- Chọn nguyên liệu --</option>
                            @foreach ($nguyenLieus as $nl)
                                <option value="{{ $nl->id }}">{{ $nl->ten_nguyen_lieu }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Số lượng</label>
                        <input type="number" step="0.01" name="cong_thuc[${index}][so_luong]" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Đơn vị</label>
                        <input type="text" name="cong_thuc[${index}][don_vi]" class="form-control donvi-input" readonly>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger w-100 btn-remove-row mt-4">Xóa</button>
                    </div>
                </div>
            `;
            $('#cong-thuc-wrapper').append(newRow);
        });

        function isDuplicateNguyenLieu(index, selectedId) {
            let duplicated = false;
            $('.nguyenlieu-select').each(function(i) {
                if (i !== index && $(this).val() == selectedId) {
                    duplicated = true;
                    return false; // break
                }
            });
            return duplicated;
        }

        $(document).on('change', '.nguyenlieu-select', function() {
            let index = $(this).data('index');
            let selectedId = $(this).val();

            if (isDuplicateNguyenLieu(index, selectedId)) {
                alert('Nguyên liệu này đã được chọn ở dòng khác!');
                $(this).val('');
                $(this).closest('.cong-thuc-row').find('.donvi-input').val('');
                return;
            }

            let donVi = nguyenLieus[selectedId]?.don_vi_ton || '';
            $(this).closest('.cong-thuc-row').find('.donvi-input').val(donVi);
        });


        // Xoá dòng công thức
        $(document).on('click', '.btn-remove-row', function() {
            $(this).closest('.cong-thuc-row').remove();
        });

        // Khi form được load lại (submit lỗi), cập nhật đơn vị cho các dòng cũ
        $('.nguyenlieu-select').each(function() {
            let nguyenLieuId = $(this).val();
            let donVi = nguyenLieus[nguyenLieuId]?.don_vi_ton || '';
            $(this).closest('.cong-thuc-row').find('.donvi-input').val(donVi);
        });
    </script>
    <style>
        .cong-thuc-row {
            padding-bottom: 10px;
            border-bottom: 1px solid #ddd;
            margin-bottom: 10px;
        }

        .cong-thuc-row .form-label {
            font-weight: 500;
        }

        .cong-thuc-row small.text-danger {
            margin-top: 2px;
            display: block;
        }
    </style>

@endsection
