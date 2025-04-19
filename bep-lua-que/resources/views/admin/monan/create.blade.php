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
