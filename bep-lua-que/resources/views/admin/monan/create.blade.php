@extends('layouts.admin')

@section('title')
    Thêm mới món ăn
@endsection

@section('content')
    <div class="container">
        <h2>Thêm Món Ăn</h2>
        <form action="{{ route('mon-an.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="ten" class="form-label">Tên món ăn</label>
                <input type="text" name="ten" id="ten" class="form-control" value="{{ old('ten') }}">
                @error('ten')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="danh_muc_mon_an_id" class="form-label">Danh mục</label>
                <select name="danh_muc_mon_an_id" id="danh_muc_mon_an_id" class="form-control">
                    @foreach ($danhMucs as $danhMuc)
                        <option value="{{ $danhMuc->id }}">{{ $danhMuc->ten }}</option>
                    @endforeach
                </select>
                @error('danh_muc_mon_an_id')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="description">Mô tả</label>
                <textarea id="description" name="mo_ta" class="form-control" placeholder="Nhập mô tả">{{ old('mo_ta') }}</textarea>
                @error('mo_ta')
                    <small class="text-danger">*{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label for="gia" class="form-label">Giá</label>
                <input type="number" name="gia" id="gia" class="form-control" value="{{ old('gia') }}">
                @error('gia')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            

            <div class="mb-3">
                <label for="hinh_anh" class="form-label">Hình ảnh</label>
                <input type="file" name="hinh_anh[]" id="hinh_anh" class="form-control" multiple accept="image/*">
                @error('hinh_anh.*')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <!-- Hiển thị ảnh đã chọn -->
            <div id="previewImages" class="mb-3" style="display: flex; gap: 10px; flex-wrap: wrap;"></div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('mon-an.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại danh sách
                </a>
                <button type="submit" class="btn btn-primary">Thêm</button>
            </div>
        </form>
    </div>

    <!-- Thêm jQuery để hiển thị và xóa ảnh trước khi upload -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            let selectedFiles = [];

            $("#hinh_anh").on("change", function() {
                $("#previewImages").empty(); // Xóa hình cũ trước khi chọn hình mới
                selectedFiles = Array.from(this.files); // Lưu danh sách file đã chọn

                selectedFiles.forEach((file, index) => {
                    let reader = new FileReader();
                    reader.onload = function(e) {
                        let imgDiv = $(`
                            <div class="image-preview" style="position: relative; display: inline-block;">
                                <img src="${e.target.result}" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover; border-radius: 5px;">
                                <button type="button" class="btn btn-danger btn-sm remove-image" data-index="${index}" 
                                    style="position: absolute; top: 5px; right: 5px; width: 18px; height: 18px; line-height: 10px; padding: 0; font-size: 12px; border-radius: 50%;">
                                    ×
                                </button>
                            </div>
                        `);
                        $("#previewImages").append(imgDiv);
                    };
                    reader.readAsDataURL(file);
                });
            });

            // Xóa ảnh đã chọn
            $(document).on("click", ".remove-image", function() {
                let index = $(this).data("index");
                selectedFiles.splice(index, 1); // Xóa ảnh khỏi danh sách

                // Cập nhật input file bằng cách tạo một DataTransfer object
                let dataTransfer = new DataTransfer();
                selectedFiles.forEach(file => dataTransfer.items.add(file));
                document.getElementById("hinh_anh").files = dataTransfer.files;

                // Xóa hình khỏi giao diện
                $(this).closest(".image-preview").remove();
            });
        });
    </script>
@endsection
