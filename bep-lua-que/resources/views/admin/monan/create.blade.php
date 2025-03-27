@extends('layouts.admin')

@section('title')
    Thêm mới món ăn
@endsection

@section('content')
    <div class="container mt-4">
        <div class="card shadow rounded-3">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Thêm Món Ăn</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('mon-an.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="ten" class="form-label">Tên món ăn</label>
                        <input type="text" name="ten" id="ten" class="form-control" value="{{ old('ten') }}">
                        @error('ten')
                            <div class="text-danger mt-1">*{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="danh_muc_mon_an_id" class="form-label">Danh mục</label>
                        <select name="danh_muc_mon_an_id" id="danh_muc_mon_an_id" class="form-select">
                            <option value="">-- Chọn danh mục --</option>
                            @foreach ($danhMucs as $danhMuc)
                                <option value="{{ $danhMuc->id }}" {{ old('danh_muc_mon_an_id') == $danhMuc->id ? 'selected' : '' }}>
                                    {{ $danhMuc->ten }}
                                </option>
                            @endforeach
                        </select>
                        @error('danh_muc_mon_an_id')
                            <div class="text-danger mt-1">*{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="mo_ta" class="form-label">Mô tả</label>
                        <textarea name="mo_ta" id="mo_ta" class="form-control" rows="3" placeholder="Nhập mô tả...">{{ old('mo_ta') }}</textarea>
                        @error('mo_ta')
                            <div class="text-danger mt-1">*{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="gia" class="form-label">Giá (VNĐ)</label>
                        <input type="number" name="gia" id="gia" class="form-control" value="{{ old('gia') }}">
                        @error('gia')
                            <div class="text-danger mt-1">*{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="hinh_anh" class="form-label">Hình ảnh</label>
                        <input type="file" name="hinh_anh[]" id="hinh_anh" class="form-control" multiple accept="image/*">
                        @error('hinh_anh.*')
                            <div class="text-danger mt-1">*{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Hiển thị ảnh đã chọn -->
                    <div id="previewImages" class="mb-3 d-flex flex-wrap gap-2"></div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('mon-an.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Quay lại danh sách
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-plus-circle"></i> Thêm món ăn
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <!-- Script preview hình -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#hinh_anh").on("change", function() {
                $("#previewImages").empty();
                Array.from(this.files).forEach((file, index) => {
                    let reader = new FileReader();
                    reader.onload = function(e) {
                        let imgDiv = $(`<div class="position-relative">
                            <img src="${e.target.result}" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                            <button type="button" class="btn btn-danger btn-sm remove-image" data-index="${index}"
                                style="position: absolute; top: 5px; right: 5px; padding: 2px 6px; font-size: 12px;">×</button>
                        </div>`);
                        $("#previewImages").append(imgDiv);
                    };
                    reader.readAsDataURL(file);
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
        });
    </script>
@endsection
